<?php
namespace SzallasManager;


class SzallasSzolgaltatasok extends SzallasFramework
{
  public $szallas_id = 0;
  function __construct( $szallas_id, $arg = array() )
  {
    parent::__construct( $arg );

    $this->szallas_id = $szallas_id;

    return $this;
  }

  public function getServices( $arg = array() )
  {
    $back = array();
    $darg = array();

    $szallas_author_id = (int)$this->db->squery("SELECT author FROM ".parent::DBSZALLASOK." WHERE ID = :id", array('id' => $this->szallas_id ))->fetchColumn();

    $q = "SELECT
      sz.ID,
      sz.szallas_id,
      sz.kategoria,
      sz.title,
      sz.kiemelt
    FROM ".parent::DBPARAMETEREK." as sz
    WHERE 1=1 ";

    $q .= " and sz.createdby = :author ";
    $darg['author'] = $szallas_author_id;

    if (!isset($arg['order'])) {
      $q .= " ORDER BY sz.kategoria ASC, sz.title ASC ";
    }

    $qry = $this->db->squery( $q, $darg );

    if ($qry->rowCount() == 0) {
      return $back;
    }

    $qry = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$qry as $d )
    {
      $d['ID'] = (int)$d['ID'];
      $back[$d['kategoria']][] = array(
        'ID' => $d['ID'],
        'szallas_id' => (int)$d['szallas_id'],
        'title' => $d['title'],
        'kiemelt' => ($d['kiemelt'] == 1) ? true : false
      );
    }

    return $back;
  }

  public function updateServices( $szallasid, $services )
  {
    if(empty($szallasid) || $szallasid == '') return false;
    if(empty($services) || !$services) return false;

    foreach ((array)$services as $group => $serv ) {
      foreach ((array)$serv as $s) {
        $isdelete = (isset($s['delete'])) ? true : false;

        if ($isdelete) {
          // Delete
          $this->db->squery("DELETE FROM ".parent::DBPARAMETEREK." WHERE ID = :id", array('id' => $s['ID']));
        } else {
          if($s['title'] == '') continue;
          $this->db->update(
            parent::DBPARAMETEREK,
            array(
              'title' => addslashes($s['title'])
            ),
            sprintf("ID = %d", (int)$s['ID'])
          );
        }
      }
    }
  }

  public function addServices( $szallasid, $services )
  {
    if(empty($szallasid) || $szallasid == '') return false;
    if(empty($services) || !$services) return false;

    $szallas_author_id = (int)$this->db->squery("SELECT author FROM ".parent::DBSZALLASOK." WHERE ID = :id", array('id' => $szallasid ))->fetchColumn();

    foreach ( (array)$services as $service )
    {
      if ( $service['title'] == '' ) {
        continue;
      }

      $kategoria = ($service['kategoria'] == '' || !$service['kategoria']) ? 'Egyéb': $service['kategoria'];

      $this->db->insert(
        parent::DBPARAMETEREK,
        array(
          'title' => addslashes($service['title']),
          'kategoria' => addslashes($kategoria),
          'createdby' => $szallas_author_id,
          'szallas_id' => $szallasid
        )
      );
    }
  }

  public function __destruct()
	{
		parent::__destruct();
	}
}
?>
