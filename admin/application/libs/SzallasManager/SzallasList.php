<?php
namespace SzallasManager;


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

    $q = "SELECT
      sz.*
    FROM ".parent::DBSZALLASOK." as sz
    WHERE 1=1 ";

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
      $back['list'][] = $d;
    }

    return $back;
  }

  public function __destruct()
	{
		parent::__destruct();
	}
}
?>
