<?php
namespace SzallasManager;

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

  public function saveSzallas( $szallas )
  {
    if ((int)$szallas['ID'] != 0)
    {
      // MENTÉS

      $update = array(
        'title' => $szallas['title'],
        'leiras' => $szallas['leiras'],
        'cim' => $szallas['cim'],
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

  public function getRoomsConfig( $szallasid, $config = array() )
  {
    $back = array();
    $qparam = array();

    $q = "SELECT
      r.ID,
      r.name,
      r.leiras,
      r.felnott_db,
      r.gyermek_db
    FROM ".self::DBSZOBAK." as r
    WHERE 1=1 and r.szallas_id = :szallas";
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
      r.ID, r.ellatas_id, r.felnott_ar, r.gyerek_ar,
      t.name as ellatas_name
    FROM ".self::DBSZOBAAR." as r
    LEFT OUTER JOIN ".self::DBTERMS." as t ON t.ID = r.ellatas_id
    WHERE 1=1 and r.szoba_id = :roomid
    ORDER BY t.sort ASC ";

    $qparam['roomid'] = $room_id;

    $data = $this->db->squery( $q, $qparam );

    if( $data->rowCount() == 0 ) {
      return $back;
    }

    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($data as $d) {
      $d['ID'] = (int)$d['ID'];
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
