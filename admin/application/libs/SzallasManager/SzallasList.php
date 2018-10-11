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

    $qry = $this->db->squery( $q, $darg );

    if ($qry->rowCount() == 0) {
      return $back;
    }

    $qry = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$qry as $d ) {
      $d['author_data'] = $this->getAuthor($d['author']);
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
