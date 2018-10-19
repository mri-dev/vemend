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

    $q = "SELECT
      sz.*
    FROM ".parent::DBSZALLASOK." as sz
    WHERE 1=1 ";

    // Adott szállás lekérése
    if (isset($arg['getid']) && !empty($arg['getid'])) {
      $arg['loadfulldata'] = true;
      $q .= " and sz.ID = :id ";
      $darg['id'] = (int)$arg['getid'];
    }

    if (!isset($arg['order'])) {
      $q .= " ORDER BY sz.kiemelt DESC, sz.title ASC ";
    }

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

      // Összes vonatkozó adat betöltése
      if (isset($arg['loadfulldata']) && !empty($arg['loadfulldata'])) {
        $d['rooms'] = (new SzallasSzobak((int)$d['ID'], $this->arg))->getRooms();
        $d['services'] = (new SzallasSzolgaltatasok((int)$d['ID'], $this->arg))->getServices();
      }

      $back['list'][] = $d;
    }

    return $back;
  }

  public function loadSzallas( $id )
  {
    $back = array();

    $list = $this->getList(array('getid' => $id));
    $fulldata = $list['list'][0];

    $back['szallas_id'] = (int)$fulldata['ID'];
    $back['datas'] = array();
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
