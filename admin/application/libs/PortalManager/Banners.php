<?php
namespace PortalManager;

use Interfaces\InstallModules;

class Banners implements InstallModules
{

  const DBTABLE = 'Banners';
  const DBLOG = 'Banners_Log';
  const DBCLICK = 'Banners_Click';
  const MODULTITLE = 'Bannerek';

  private $db = null;
  public $settings = array();
  public $sizegroups = array(
    '1P1' => '1:1 arányú banner',
    '2P1' => '2:1 arányú banner',
    'BILLBOARD' => '10:2 arányú Billboard széles banner'
  );

  function __construct( $arg = array() )
  {
    $this->db = $arg['db'];
    $this->settings = $arg['db']->settings;

    if( !$this->checkInstalled() && strpos($_SERVER['REQUEST_URI'], '/install') !== 0) {
      \Helper::reload('/install?module='.__CLASS__);;
    }

    return $this;
  }

  public function checkCapability( $format, $min = 1 )
  {
    if ($format == '') {
      return false;
    }

    $c = (int)$this->db->squery("SELECT count(ID) FROM ".self::DBTABLE." WHERE active = 1 and sizegroup = :format", array('format' => $format))->fetchColumn();

    if ( $c >= $min) {
      return true;
    } else {
      return false;
    }
  }

  public function clearURL( $url )
  {
    $x = explode("?", $url);
    $url = $x[0];
    $url = rtrim($url, '/');
    return $url;
  }

  public function render( $format, $banners = array() )
  {
    switch ($format) {
      case '1P1':
       $groupslug = '1p1';
      break;
      case '2P1':
       $groupslug = '2p1';
      break;
      case 'BILLBOARD':
       $groupslug = 'billboard';
      break;
      default:
        $groupslug = 'unsetted';
      break;
    }
    if (empty($banners)) {
      return false;
    }
    $r = '<div class="banners"><div class="groups group-of-'.$groupslug.'">';
      foreach ((array)$banners as $banner) {
        $this->logShow($banner);
        $r .= '<div class="banner"><div class="wrapper by-width autocorrett-height-by-width" data-image-ratio="'.$this->getRatio($format).'">';
        if ($banner['target_url']) {
          $r .= '<a target="_blank" href="'.$this->getBannerURL($banner).'"><img src="'.$banner['creative'].'" alt="'.$banner['comment'].'"/></a>';
        } else {
          $r .= '<img src="'.$banner['creative'].'" alt="'.$banner['comment'].'"/>';
        }
        if ($banner['target_url']) {
          $r .= '<div class="targeturl"><i class="fa fa-external-link"></i> '.$banner['target_url'].'</div>';
        }
        $r .= '</div></div>';
      }
    $r .= '</div></div>';

    return $r;
  }

  public function logShow( $banner )
  {
    $dategroup = date('Y-m-d');
    $ip = $_SERVER['REMOTE_ADDR'];

    $ch = $this->db->squery("SELECT ID, showed FROM ".self::DBLOG." WHERE banner_id = :id and dategroup = :date and ip = :ip;", array(
      'id' => $banner['ID'],
      'date' => $dategroup,
      'ip' => $ip
    ));

    if ($ch->rowCount() != 0) {
      $chd = $ch->fetch(\PDO::FETCH_ASSOC);

      $this->db->update(
        self::DBLOG,
        array(
          'showed' => (int)$chd['showed'] + 1
        ),
        sprintf("ID = %d", (int)$chd['ID'])
      );
    } else {
      $this->db->insert(
        self::DBLOG,
        array(
          'banner_id' => $banner['ID'],
          'showed' => 1,
          'ip' => $ip,
          'dategroup' => $dategroup
        )
      );
    }
  }

  public function logClick( $banner_id )
  {
    $dategroup = date('Y-m-d');
		$ip = $_SERVER['REMOTE_ADDR'];

    $cc = $this->db->squery("SELECT ID,clicked FROM ".self::DBCLICK." WHERE banner_id = :banner and dategroup = :dategroup and ip = :ip;", array(
      'banner' => $banner_id,
      'dategroup' => $dategroup,
      'ip' => $ip
    ));

    if ($cc->rowCount() == 0) {
      $this->db->insert(
        self::DBCLICK,
        array(
          'banner_id' => $banner_id,
          'ip' => $ip,
          'clicked' => 1,
          'dategroup' => $dategroup
        )
      );
    } else {
      $bann = $cc->fetch(\PDO::FETCH_ASSOC);
      $this->db->update(
        self::DBCLICK,
        array(
          'clicked' => (int)$bann['clicked'] + 1
        ),
        sprintf("ID = %d", (int)$bann['ID'])
      );
    }
  }

  public function getGroupedList()
  {
    $banners = array();
    $total_banners = 0;

    $q = "SELECT
    b.*
    FROM ".self::DBTABLE." as b
    WHERE 1=1
    ORDER BY b.acc_id ASC, b.sizegroup ASC, b.active DESC
    ";

    $qry = $this->db->squery($q);

    if ($qry->rowCount() == 0) {
      return $banners;
    }

    foreach ((array)$qry->fetchAll(\PDO::FETCH_ASSOC) as $b) {
      $total_banners++;
      if (!isset($banners['list'][$b['acc_id']]['author'])) {
        $author = $this->getAuthor($b['acc_id']);
        $banners['list'][$b['acc_id']]['author'] = $author;
        $banners['list'][$b['acc_id']]['author_nev'] = $author['nev'];
        $banners['list'][$b['acc_id']]['author_email'] = $author['email'];
      }
      if (!isset($banners['list'][$b['acc_id']]['banner_active'])) {
        $banners['list'][$b['acc_id']]['banner_active'] = 0;
      }
      if (!isset($banners['list'][$b['acc_id']]['banner_inactive'])) {
        $banners['list'][$b['acc_id']]['banner_inactive'] = 0;
      }

      if ($b['active'] == '1') {
        $banners['list'][$b['acc_id']]['banner_active'] +=1;
      }else{
        $banners['list'][$b['acc_id']]['banner_inactive'] +=1;
      }

      $b['stat'] = $this->getBannerStat( $b['ID'] );
      $banners['list'][$b['acc_id']]['banner_nums'] +=1;

      $banners['list'][$b['acc_id']]['banners'][] = $b;
    }

    $banners['total_banners'] = $total_banners;

    return $banners;
  }

  public function getBannerStat( $banner_id, $arg = array() )
  {
    $current_month = date('Y-m');

    $q = "SELECT
    (SELECT sum(clicked) FROM ".self::DBCLICK." WHERE banner_id = :banner) as all_click,
    (SELECT sum(showed) FROM ".self::DBLOG." WHERE banner_id = :banner) as all_show,
    (SELECT sum(clicked) FROM ".self::DBCLICK." WHERE banner_id = :banner and dategroup LIKE :month) as month_click,
    (SELECT sum(showed) FROM ".self::DBLOG." WHERE banner_id = :banner and dategroup LIKE :month) as month_show
    ";

    $qry = $this->db->squery($q, array('banner' => $banner_id, 'month' => $current_month.'%'));
    $stats = $qry->fetch(\PDO::FETCH_ASSOC);

    $stat = array(
      'total' => array(
        'all_click' => (int)$stats['all_click'],
        'all_show' => (int)$stats['all_show'],
        'unique_click' => (int)$this->db->squery("SELECT count(ID) FROM ".self::DBCLICK." WHERE banner_id = :banner GROUP BY ip", array('banner' => $banner_id))->rowCount(),
        'unique_show' => (int)$this->db->squery("SELECT count(ID) FROM ".self::DBLOG." WHERE banner_id = :banner GROUP BY ip", array('banner' => $banner_id))->rowCount()
      ),
      'month' => array(
        'all_click' => (int)$stats['month_click'],
        'all_show' => (int)$stats['month_show'],
        'unique_click' => (int)$this->db->squery("SELECT count(ID) FROM ".self::DBCLICK." WHERE banner_id = :banner and dategroup LIKE :month GROUP BY ip", array('banner' => $banner_id, 'month' => $current_month.'%'))->rowCount(),
        'unique_show' => (int)$this->db->squery("SELECT count(ID) FROM ".self::DBLOG." WHERE banner_id = :banner and dategroup LIKE :month GROUP BY ip", array('banner' => $banner_id, 'month' => $current_month.'%'))->rowCount()
      )
    );

    return $stat;
  }

  public function getAuthor( $id )
  {
    if($id == '') return false;
		$q = "SELECT * FROM ".\PortalManager\Users::TABLE_NAME." WHERE `ID` = '$id'";

		extract($this->db->q($q));

		// Felhasználó adatok
		$detailslist = array();

		if ( !$data['ID'] ) {
			return false;
		}

		$details = $this->db->query($q = "SELECT nev, ertek FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = ".$data['ID'].";");

		if ( $details->rowCount() != 0 ) {
			foreach ($details->fetchAll(\PDO::FETCH_ASSOC) as $det) {
				if ($det['nev'] == 'permissions' && $det['ertek'] != '') {
					$det['ertek'] = json_decode($det['ertek'], \JSON_UNESCAPED_UNICODE);
				}
				$detailslist[$det['nev']] = $det['ertek'];
			}
		}

		$data = array_merge($data, $detailslist);

		return $data;
  }

  public function getBannerURL( $banner )
  {
    return '/app/ad/'.$banner['ID'];
  }

  public function getRatio( $format )
  {
    switch ($format) {
      case '1P1':
       return '1:1';
      break;
      case '2P1':
       return '2:1';
      break;
      case 'BILLBOARD':
       return '10:2';
      break;
      default:
        return '16:9';
      break;
    }
  }

  public function pick( $format, $count = 1 )
  {
    $banners = array();

    $qq = array();
    $q = "SELECT
      b.ID,
      b.acc_id,
      b.content as creative,
      b.target_url,
      b.comment
    FROM ".self::DBTABLE." as b
    WHERE 1=1 and b.active = 1";
    // Format
    $q .= " and b.sizegroup = :format";
    $qq['format'] = $format;
    // ORDER
    $q .= " ORDER BY rand()";
    // Limit
    $q .= " LIMIT 0,".(int)$count;

    $query = $this->db->squery($q, $qq);

    if ($query->rowCount() != $count) {
      return false;
    }

    $query = $query->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$query as $b) {
      $b['target_url'] = $this->clearURL( $b['target_url'] );
      $b['creative'] = IMGDOMAIN. $b['creative'];
      $banners[] = $b;
    }
    return $banners;
  }

  public function getBannerData( $id )
  {
    return $this->db->squery("SELECT * FROM ".self::DBTABLE." WHERE ID = :id;", array('id'=> $id))->fetch(\PDO::FETCH_ASSOC);
  }

  public function getBanner( $id )
  {
    $data = $this->getBannerData( $id );
    $data['ID'] = (int)$data['ID'];
    $data['acc_id'] = (int)$data['acc_id'];
    $data['active'] = ($data['active'] == '1') ? true : false;

    return $data;
  }

  public function __destruct()
  {
    $this->db = null;
    $this->settings = array();
  }

    /*******************************
    * Installer
    ********************************/
    public function checkInstalled()
    {
      $check_installed = $this->db->query("SHOW TABLES LIKE '".self::DBTABLE."'")->fetchColumn();

      if ( $check_installed === false ) {
        $cn = addslashes(__CLASS__);
        $this->db->query("DELETE FROM modules WHERE classname = '$cn'");
      }

      return ($check_installed === false) ? false : true;
    }

    public function installer( \PortalManager\Installer $installer )
    {
      $installed = false;


      if (false) {
        /**
        * Vehicles
        **/
        $installer->setTable( self::DBTABLE );
        // Tábla létrehozás
        $table_create =
        "(
          `ID` mediumint(9) NOT NULL,
          `title` varchar(150) NOT NULL,
          `slug` varchar(150) NOT NULL,
          `logo` text,
          `parent_id` mediumint(9) DEFAULT NULL,
          `deep` smallint(6) NOT NULL DEFAULT '0'
        )";
        $installer->createTable( $table_create );

        // Indexek
        $index_create =
        "ADD PRIMARY KEY (`ID`),
        ADD KEY `title` (`title`),
        ADD KEY `parent_id` (`parent_id`)";
        $installer->addIndexes( $index_create );

        // Increment
        $inc_create =
        "MODIFY `ID` mediumint(9) NOT NULL AUTO_INCREMENT";
        $installer->addIncrements( $inc_create );
      }

      // Modul instalállás mentése
      $installed = $installer->setModulInstalled( __CLASS__, self::MODULTITLE, 'etlapok' , 'cutlery' );

      return $installed;
    }
}
?>
