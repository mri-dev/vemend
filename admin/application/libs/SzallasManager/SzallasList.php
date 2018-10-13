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
      $d['kisallat_dij'] = (float)$d['kisallat_dij'];
      $d['ifa'] = (float)$d['ifa'];
      $d['kisallat'] = ($d['kisallat'] == 1) ? true : false;
      $back['list'][] = $d;
    }

    return $back;
  }

  public function saveSzallas( $szallas )
  {
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
    );

    $this->db->update(
      parent::DBSZALLASOK,
      $update,
      sprintf("ID = %d", (int)$szallas['ID'] )
    );
  }

  public function __destruct()
	{
		parent::__destruct();
	}
}
?>
