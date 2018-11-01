<?php
namespace SzallasManager;

use SzallasManager\SzallasSzobak;
use SzallasManager\SzallasSzolgaltatasok;

class SzallasList extends SzallasFramework
{
  function __construct( $arg = array() )
  {
    parent::__construct( $arg );
		return $this;
  }

  public function getList( $arg = array() )
  {
    $back = array();
    $darg = array();
    $arg['loadfulldata'] = false;
    $config_filters = array();
    $filters = $arg['filters'];
    $admin = (isset($arg['admin']) && $arg['admin'] === false) ? false : true;

    // Config filter prepare
    if (isset($filters['erkezes'])) {
      $config_filters['datefrom'] = $filters['erkezes'];
    }
    if (isset($filters['tavozas'])) {
      $config_filters['dateto'] = $filters['tavozas'];
    }

    if (isset($filters['adults'])) {
      $config_filters['adults'] = $filters['adults'];
    }

    if (isset($filters['children'])) {
      $config_filters['children'] = $filters['children'];
    }

    if (isset($filters['ellatas'])) {
      $config_filters['ellatas'] = $filters['ellatas'];
    }

    $q = "SELECT
      sz.*
    FROM ".parent::DBSZALLASOK." as sz ";

    // Joins
    if (isset($config_filters['adults'])) {
      $q .= " LEFT OUTER JOIN ".parent::DBSZOBAK." as szo ON szo.szallas_id = sz.ID";
    }

    $q .= " WHERE 1=1 ";

    // Adott szállás lekérése
    if (isset($arg['getid']) && !empty($arg['getid'])) {
      $arg['loadfulldata'] = true;
      $q .= " and sz.ID = :id ";
      $darg['id'] = (int)$arg['getid'];
    }

    if (isset($config_filters['adults'])) {
      $q .= " and szo.elerheto = 1 and szo.felnott_db >= ".$config_filters['adults'];
    }

    if (isset($config_filters['children'])) {
      $q .= " and szo.gyermek_db >= ".$config_filters['children'];
    }

    if (isset($config_filters['ellatas']) && $config_filters['ellatas'] != 0) {
      $q .= " and :ellatas IN (SELECT sza.ellatas_id FROM ".parent::DBSZOBAAR." as sza WHERE sza.szoba_id = szo.ID)";
      $darg['ellatas'] = $config_filters['ellatas'];
    }

    // Joins GROUP
    if (isset($config_filters['adults'])) {
      $q .= " GROUP BY sz.ID ";
    }

    if (!isset($arg['order'])) {
      $q .= " ORDER BY sz.kiemelt DESC, sz.title ASC ";
    }


    // Run query

    //echo $q;
    $qry = $this->db->squery( $q, $darg );

    if ($qry->rowCount() == 0) {
      return $back;
    }

    $qry = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$qry as $d ) {
      $d['kep'] = (empty($d['profilkep']) || is_null($d['profilkep'])) ? false : true;
      $d['profilkep'] = empty($d['profilkep']) ? IMG.'no-image.png' : STOREDOMAIN.$d['profilkep'];
      $d['author_data'] = $this->getAuthor($d['author']);
      $d['kisallat_dij'] = (float)$d['kisallat_dij'];
      $d['ifa'] = (float)$d['ifa'];
      $d['kisallat'] = ($d['kisallat'] == 1) ? true : false;
      $d['aktiv'] = ($d['aktiv'] == 1) ? true : false;
      $d['url'] = $this->szallasURL($d);
      $d['ellatasok'] = $this->getSzallasEllatasIDS($d['ID']);
      $d['bejelentkezes_data'] = $this->calcNyitvaTartasData($d['bejelentkezes']);
      $d['kijelentkezes_data'] = $this->calcNyitvaTartasData($d['kijelentkezes']);
      $d['prices'] = $this->getSzallasPriceInfo( $d, $config_filters, $admin );

      // Összes vonatkozó adat betöltése
      if (isset($arg['loadfulldata']) && !empty($arg['loadfulldata'])) {
        $d['rooms'] = (new SzallasSzobak((int)$d['ID'], $this->arg))->getRooms();
        $d['services'] = (new SzallasSzolgaltatasok((int)$d['ID'], $this->arg))->getServices();
      }

      $back['list'][] = $d;
    }

    return $back;
  }

  public function loadSzallas( $id, $load_full = false )
  {
    $back = array();

    $list = $this->getList(array('getid' => $id));
    $fulldata = $list['list'][0];

    $back['szallas_id'] = (int)$fulldata['ID'];
    $back['datas'] = array();
    if ($load_full) {
      $back['datas'] = $fulldata;
    }
    $back['datas']['rooms'] = $fulldata['rooms'];
    $back['datas']['pictures'] = $fulldata['pictures'];
    $back['datas']['services'] = $fulldata['services'];

    return $back;
  }

  public function __destruct()
	{
		parent::__destruct();
	}
}
?>
