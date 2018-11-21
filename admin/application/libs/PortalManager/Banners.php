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

    $ch = $this->db->squery("SELECT ID, showed FROM ".self::DBLOG." WHERE banner_id = :id and dategroup = :date", array(
      'id' => $banner['ID'],
      'date' => $dategroup
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
          'dategroup' => $dategroup
        )
      );
    }
  }

  public function getBannerURL( $banner )
  {
    return $banner['target_url'];
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
      $b['creative'] = IMGDOMAIN. $b['creative'];
      $banners[] = $b;
    }
    return $banners;
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
