<?php
namespace SzallasManager;

use MailManager\Mailer;
use PortalManager\Template;

class SzallasFramework
{
  const DBSZALLASOK = 'Szallasok';
  const DBKEPEK = 'Szallas_Kepek';
  const DBPARAMETEREK = 'Szallas_Parameterek';
  const DBPARAMXREF = 'Szallas_xref_szallas_parameter';
  const DBTERMS = 'Szallas_Terms';
  const DBSZALLASXREFELLATAS = 'Szallas_xref_Ellatas';
  const DBSZOBAK = 'Szallasok_Szobak';
  const DBSZOBAAR = 'Szallasok_Szoba_ar';
  const DBORDERS = 'Szallas_Orders';

  protected $arg = null;
  protected $db = null;
	protected $settings = array();
  public $terms = ['ellatas'];

  function __construct( $arg = array() )
  {
    $this->arg = $arg;
    $this->db = $arg[db];
		$this->settings = $arg['db']->settings;

		return $this;
  }

  public function getSzallas( $id )
  {
    $back = array();
    $qparam = array();

    if (!$id) {
      return false;
    }

    $q = "SELECT
      sz.*
    FROM ".self::DBSZALLASOK." as sz
    WHERE 1=1 and sz.ID = :id";

    $qparam['id'] = $id;

    $data = $this->db->squery( $q, $qparam );

    if( $data->rowCount() == 0 ) {
      return $back;
    }

    $data = $data->fetch(\PDO::FETCH_ASSOC);
    $back = $data;
    $back['url'] = $this->szallasURL($back);

    return $back;
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

  public function registerImage( $szallasid, $imagedata )
  {
    $this->db->insert(
      self::DBKEPEK,
      array(
        'szallas_id' => $szallasid,
        'filepath' => $imagedata['filepath'],
        'imagename' => $imagedata['imagename'],
        'kiterjesztes' => $imagedata['kiterjesztes'],
        'filemeret' => $imagedata['filemeret'],
        'profilkep' => $imagedata['profilkep'],
      )
    );

    return $this->db->lastInsertId();
  }

  public function getImages( $szallasid )
  {
    $images = array();

    $q = "SELECT
      i.ID,
      i.cim,
      i.filemeret,
      i.kiterjesztes,
      i.filepath,
      i.imagename,
      i.profilkep
    FROM ".self::DBKEPEK." as i
    WHERE 1=1 and i.szallas_id = :szid
    ORDER BY i.sorrend ASC
    ";

    $qry = $this->db->squery($q, array('szid' => $szallasid));

    if ($qry->rowCount() == 0) {
      return $images;
    }

    $data = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$data as $d) {
      $images[] = $d;
    }

    $images = array_map(function($v)
    {
      $v['ID'] = (int)$v['ID'];
      $v['filemeret'] = (float)$v['filemeret'];
      $v['profilkep'] = ($v['profilkep'] == '1') ? true : false;
      return $v;
    }, $images);

    return $images;
  }

  public function rebuildSzallasEllatas( $szallasid, $ids = array() )
  {
    if ( empty($ids) ) {
      return false;
    }

    // Előzőek törlése
    $this->db->squery("DELETE FROM ".self::DBSZALLASXREFELLATAS." WHERE szallas_id = :szid", array('szid' => $szallasid));

    foreach ( (array)$ids as $id ) {
      $this->db->insert(
        self::DBSZALLASXREFELLATAS,
        array(
          'szallas_id' => $szallasid,
          'ellatas_id' => $id
        )
      );
    }
  }

  public function getSzallasEllatasIDS( $id )
  {
    if(empty($id)) return array();

    $ids = array();
    $data = $this->db->squery("SELECT ellatas_id FROM ".self::DBSZALLASXREFELLATAS." WHERE szallas_id = :szid", array('szid' => $id));
    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    foreach ( (array)$data as $key => $d ) {
      $ids[] = (int)$d['ellatas_id'];
    }

    return $ids;
  }

  public function getTermValues( $group )
  {
    $q = "SELECT t.ID, t.name FROM ".self::DBTERMS." as t WHERE 1=1 and t.groupkey = :group ORDER BY t.sort ASC";
    $data = $this->db->squery( $q, array(
      'group' => $group
    ));

    if ($data->rowCount() == 0) {
      return false;
    }

    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    $data = array_map(function($t){
      $t['ID'] = (int)$t['ID'];
      return $t;
    }, $data);

    return $data;
  }

  public function szallasURL( $data )
  {
    return '/szallas/'.$data['ID'].'/'.\Helper::makeSafeUrl($data['title'],'');
  }

  public function sendOrder( $szallas_id, $config )
  {
    $insert = array();

    $insert['szallas_id'] = $szallas_id;
    $insert['contact_name'] = addslashes($config['order_contacts']['name']);
    $insert['contact_email'] = addslashes($config['order_contacts']['email']);
    $insert['contact_phone'] = addslashes($config['order_contacts']['phone']);
    $insert['contact_comment'] = ($config['order_contacts']['comment'] != '') ? addslashes($config['order_contacts']['comment']) : NULL;
    $insert['datefrom'] = ($config['datefrom']);
    $insert['dateto'] = ($config['dateto']);
    $insert['nights'] = (int)($config['nights']);
    $insert['total_prices'] = (float)($config['total_price']);
    $insert['ifa_total'] = (float)($config['ifa_price']);
    $insert['ellatas_id'] = (int)($config['room']['priceconfig']['ellatas_id']);
    $insert['room_id'] = (int)($config['room']['room']['ID']);
    $insert['price_id'] = (int)($config['room']['priceconfig']['ID']);
    $insert['adults'] = (int)($config['adults']);
    $insert['children'] = (int)($config['children']);
    $insert['children_ages'] = (!isset($config['children_age'])) ? NULL : json_encode((array)$config['children_age']);
    $insert['kisallatot_hoz'] = ($config['kisallatot_hoz'] == 'true') ? 1 : 0;
    $insert['kisallat_dij'] = (float)($config['kisallat_dij']);
    $insert['configraw'] = json_encode($config['room'], \JSON_UNESCAPED_UNICODE);

    $this->db->insert(
      self::DBORDERS,
      $insert
    );

    // Referencia ID
    $refid = $this->db->lastInsertId();

    // Szállás adatok
    $config['szallas'] = $this->getSzallas( $szallas_id );
    $config['rfid'] = $refid;

    // Értesítés az adminnak
    $this->sendOrderAlert( 'Szállásadó', $config['szallas']['contact_email'], 'Új szállás ajánlatkérés - RFID#'.$refid, $config, true );

    // Értesítés az ajánlatkérőnek
    $this->sendOrderAlert( $config['order_contacts']['name'], $config['order_contacts']['email'], 'Szállás ajánlatkérés elküldve - RFID#'.$refid, $config, false );

    return $refid;
  }

  public function sendOrderAlert( $to_name, $to_email, $subject, $config, $to_owner = true )
  {
    if ($to_owner) {
      $from = $config['order_contacts']['name'];
    } else {
      $from = $config['szallas']['title'] . ' - '.$this->settings['page_title'];
    }

    $mail = new Mailer(
      $from,
      SMTP_USER,
      "smtp"
    );

    $mail->add( $to_email );

    if ($to_owner) {
      $mail->setReplyTo( $config['order_contacts']['name'], $config['order_contacts']['email'] );
    } else {
      $mail->setReplyTo( $config['szallas']['title'] . ' - '.$this->settings['page_title'], $this->settings['email_noreply_address'] );
    }


    $arg = array(
      'settings' => $this->settings,
      'to_owner' => $to_owner,
      'config' => $config,
      'rfid' => $config['rfid'],
      'szallas' => $config['szallas'],
      'contact_name' => $to_name,
      'name' => $to_name
    );

    $mail->setSubject( $subject );
    $msg = (new Template( VIEW . 'templates/mail/' ))->get( 'szallas_alert', $arg );
    $mail->setMsg( $msg );

    if ( true ) {
      $re = $mail->sendMail();
      return $re;
    }
  }

  public function saveSzallas( $szallas )
  {
    if ((int)$szallas['ID'] != 0)
    {
      // MENTÉS
      $update = array(
        'title' => $szallas['title'],
        'leiras' => $szallas['leiras'],
        'cim' => $szallas['cim'],
        'cim_sub' => $szallas['cim_sub'],
        'kiemelt_szoveg' => $szallas['kiemelt_szoveg'],
        'contact_email' => $szallas['contact_email'],
        'contact_phone' => $szallas['contact_phone'],
        'bejelentkezes' => $szallas['bejelentkezes'],
        'kijelentkezes' => $szallas['kijelentkezes'],
        'lemondas' => $szallas['lemondas'],
        'elorefizetes' => $szallas['elorefizetes'],
        'gyerek_potagy' => $szallas['gyerek_potagy'],
        'fizetes' => $szallas['fizetes'],
        'ifa' => (float)$szallas['ifa'],
        'kisallat_dij' => (float)$szallas['kisallat_dijkisallat_dij'],
        'kisallat' => ( ($szallas['kisallat'] == 'true') ? 1 : 0 ),
        'aktiv' => ( ($szallas['aktiv'] == 'true') ? 1 : 0 ),
      );

      if ( !empty($szallas['ellatasok']) ) {
        $this->rebuildSzallasEllatas( $szallas['ID'], (array)$szallas['ellatasok'] );
      }

      $this->db->update(
        self::DBSZALLASOK,
        $update,
        sprintf("ID = %d", (int)$szallas['ID'] )
      );

      return (int)$szallas['ID'];
    }
     else
    {
      // LÉTREHOZÁS
      $insert = array(
        'title' => $szallas['title'],
        'leiras' => $szallas['leiras'],
        'cim' => $szallas['cim'],
        'cim_sub' => $szallas['cim_sub'],
        'kiemelt_szoveg' => $szallas['kiemelt_szoveg'],
        'contact_email' => $szallas['contact_email'],
        'contact_phone' => $szallas['contact_phone'],
        'bejelentkezes' => $szallas['bejelentkezes'],
        'kijelentkezes' => $szallas['kijelentkezes'],
        'lemondas' => $szallas['lemondas'],
        'elorefizetes' => $szallas['elorefizetes'],
        'gyerek_potagy' => $szallas['gyerek_potagy'],
        'fizetes' => $szallas['fizetes'],
        'ifa' => (float)$szallas['ifa'],
        'kisallat_dij' => (float)$szallas['kisallat_dijkisallat_dij'],
        'kisallat' => ( ($szallas['kisallat'] == 'true') ? 1 : 0 ),
      );

      $this->db->insert(
        parent::DBSZALLASOK,
        $update
      );

      return (int) $this->db->lastInsertId();
    }
  }

  public function getSzallasPriceInfo( $data, $config_filters, $admin = true )
  {
    $back = array(
      'old' => false,
      'current' => 0,
      'discount' => false,
      'datas' => array(),
      'adults' => 1,
      'children' => 0,
      'total_person' => 0,
      'nights' => 1
    );

    if (isset($config_filters['dateto']) && isset($config_filters['datefrom'])) {
      $back['nights'] = $this->getDateDayDiff($config_filters['dateto'], $config_filters['datefrom']);
    }

    if (isset($config_filters['adults']) && $config_filters['adults'] != 0) {
      $back['adults'] = $config_filters['adults'];
    }
    if (isset($config_filters['children']) && $config_filters['children'] != 0) {
      $back['children'] = $config_filters['children'];
    }


    $back['total_person'] = $back['adults'] + $back['children'];

    $actual_min_price = 9999999999999;

    $rooms = $this->getRoomsConfig( $data['ID'], $config_filters, $admin );

    if ($rooms) {
      foreach ((array) $rooms as $room) {
        //$back['set'][] = $room;
        if (empty($room['prices'])) {
          continue;
        }

        foreach ( (array)$room['prices'] as $rp ) {
          if (isset($config_filters['ellatas']) && $config_filters['ellatas'] != 0 && $rp['ellatas_id'] != $config_filters['ellatas']) {
            continue;
          }
          if($rp['felnott_ar'] != 0 && $rp['felnott_ar'] < $actual_min_price ) {
            $actual_min_price = $rp['felnott_ar'];
            $back['datas']['room'] = $room;
            $back['datas']['roomprice'] = $rp;
          }
        }
      }

      $actual_min_price =
        ($back['nights'] * ($back['adults'] * $back['datas']['roomprice']['felnott_ar'])) +
        ($back['nights'] * ($back['children'] * $back['datas']['roomprice']['gyerek_ar']));


      $back['current'] = $actual_min_price;
    }

    return $back;
  }

  public function getDateDayDiff( $date1, $date2 )
  {
    $now = strtotime($date1); // or your date as well
    $your_date = strtotime($date2);
    $datediff = $now - $your_date;

    return round($datediff / (60 * 60 * 24));
  }

  public function getRoomsConfig( $szallasid, $config = array(), $admin = true )
  {
    $back = array();
    $qparam = array();

    $q = "SELECT
      r.ID,
      r.name,
      r.leiras,
      r.felnott_db,
      r.gyermek_db,
      r.elerheto
    FROM ".self::DBSZOBAK." as r
    WHERE 1=1";

    if (!$admin) {
      $q .= " and r.elerheto = :io";
      $qparam['io'] = 1;
    }

    $q .= " and r.szallas_id = :szallas";
    $qparam['szallas'] = $szallasid;

    $q .= " and r.felnott_db >= :adultdb";
    $qparam['adultdb'] = (int)$config['adults'];

    if (isset($config['children']) && $config['children'] != 0) {
      $q .= " and r.gyermek_db >= :gyermekdb";
      $qparam['gyermekdb'] = (int)$config['children'];
    }

    $q .= " ORDER BY r.felnott_db ASC, r.gyermek_db ASC";

    $data = $this->db->squery( $q, $qparam );

    if ($data->rowCount() == 0) {
      return $back;
    }

    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($data as $d) {
      $d['ID'] = (int)$d['ID'];
      $d['felnott_db'] = (int)$d['felnott_db'];
      $d['gyermek_db'] = (int)$d['gyermek_db'];
      $d['prices'] = $this->getRoomPrices($d['ID']);
      $ellatas_ids = array();
      if ($d['prices']) {
        foreach ( (array)$d['prices'] as $pr ) {
          $ellatas_ids[] = $pr['ellatas_id'];
        }
      }
      $d['ellatas_ids'] = $ellatas_ids;
      $back[] = $d;
    }

    return $back;
  }

  public function getRoomPrices( $room_id )
  {
    $back = array();
    $qparam = array();

    if (!$room_id) {
      return false;
    }

    $q = "SELECT
      r.ID,
      r.ellatas_id,
      r.felnott_ar,
      r.gyerek_ar,
      t.name as ellatas_name
    FROM ".self::DBSZOBAAR." as r
    LEFT OUTER JOIN ".self::DBTERMS." as t ON t.ID = r.ellatas_id
    LEFT OUTER JOIN ".self::DBSZOBAK." as sz ON sz.ID = r.szoba_id
    WHERE 1=1 and
    r.szoba_id = :roomid and
    r.ellatas_id IN (SELECT el.ellatas_id FROM ".self::DBSZALLASXREFELLATAS." as el WHERE el.szallas_id = sz.szallas_id)
    ORDER BY t.sort ASC ";

    $qparam['roomid'] = $room_id;

    $data = $this->db->squery( $q, $qparam );

    if( $data->rowCount() == 0 ) {
      return $back;
    }

    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($data as $d) {
      $d['ID'] = (int)$d['ID'];
      $d['ellatas_id'] = (int)$d['ellatas_id'];
      $d['felnott_ar'] = (float)$d['felnott_ar'];
      $d['gyerek_ar'] = (float)$d['gyerek_ar'];
      $back[] = $d;
    }


    return $back;
  }

  public function calcNyitvaTartasData( $opens )
  {
    $data = array();
    if (strpos($opens, '-') !== false) {
      $xopens = explode("-", $opens);
      $opens = $xopens;
      if (isset($xopens[0])) {
        $from = explode(":", $xopens[0]);
        $data['from'] = array(
          'ora' => $from[0],
          'perc' => $from[1]
        );
      }
      if (isset($xopens[1])) {
        $to = explode(":", $xopens[1]);
        $data['to'] = array(
          'ora' => $to[0],
          'perc' => $to[1]
        );
      }
    } else {
      $from = explode(":", $opens);
      $data['from'] = array(
        'ora' => $from[0],
        'perc' => $from[1]
      );
      $data['to'] = array(
        'ora' => 24,
        'perc' => '00'
      );
    }

    return $data;
  }

  public function collectKiemeltServices( $services = array(), $by = 'kiemelt', $what = '' )
  {
    $set = array();

    foreach ( (array)$services as $sg => $ss ) {
      foreach ((array)$ss as $s) {
        if ($s[$by] == $what ) {
          $set[] = $s;
        }
      }
    }

    return $set;
  }

  public function updateProfilPath( $szallas_id, $path )
  {
    if (empty($szallas_id)) {
      return false;
    }

    $this->db->update(
      self::DBSZALLASOK,
      array(
        'profilkep' => $path
      ),
      sprintf("ID = %d", $szallas_id)
    );
  }

  public function __destruct()
	{
		$this->db = null;
    $this->arg = null;
	  $this->settings = array();
	}
}
?>
