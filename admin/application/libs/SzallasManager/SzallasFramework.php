<?php
namespace SzallasManager;

class SzallasFramework
{
  const DBSZALLASOK = 'Szallasok';
  const DBKEPEK = 'Szallas_Kepek';
  const DBPARAMETEREK = 'Szallas_Paremeterek';
  const DBPARAMXREF = 'Szallas_xref_szallas_parameter';
  const DBTERMS = 'Szallas_Terms';
  const DBSZALLASXREFELLATAS = 'Szallas_xref_Ellatas';

  protected $db = null;
	protected $settings = array();
  public $terms = ['ellatas'];

  function __construct( $arg = array() )
  {
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
	  $this->settings = array();
	}
}
?>
