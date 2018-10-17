<?php
namespace SzallasManager;


class SzallasSzobak extends SzallasFramework
{
  public $szallas_id = 0;
  function __construct( $szallas_id, $arg = array() )
  {
    parent::__construct( $arg );

    $this->szallas_id = $szallas_id;

    return $this;
  }

  public function saveSzobak( $rooms )
  {
    $back = array();

    if (empty($rooms)) {
      return false;
    }
    $added_room_ids = array();
    $correct = array();

    foreach ( (array)$rooms as $room ) {
      if ($room['ID'] != '')
      {
        // Változások mentése
        $this->db->update(
          parent::DBSZOBAK,
          array(
            'name' => addslashes($room['name']),
            'leiras' => addslashes($room['leiras']),
            'elerheto' => ($room['elerheto'] == 'true') ? 1 : 0,
            'felnott_db' => (int)$room['felnott_db'],
            'gyermek_db' => (int)$room['gyermek_db']
          ),
          sprintf("ID = %d", (int)$room['ID'])
        );
      } else {
        // Létrehozás / szoba hozzáadása
        $uid = uniqid(); usleep(400);

        if ( empty($room['name']) ) {
          $correct[$uid][] = "Szoba létrehozásánál kötelező megadni a szoba elnevezét.";
        }

        if ( empty($room['felnott_db']) || $room['felnott_db'] == 0 ) {
          $correct[$uid][] = "Szoba létrehozásánál kötelező meghatározni legalább 1 felnőtt férőhelyet.";
        }

        if ( empty($correct) ) {
          $this->db->insert(
            parent::DBSZOBAK,
            array(
              'szallas_id' => $this->szallas_id,
              'name' => addslashes($room['name']),
              'leiras' => addslashes($room['leiras']),
              'elerheto' => ($room['elerheto'] == 'true') ? 1 : 0,
              'felnott_db' => (int)$room['felnott_db'],
              'gyermek_db' => (int)$room['gyermek_db']
            )
          );
          $added_room_ids[] = $this->db->lastInsertId();
        }
      }
    }

    $back['error'] = (empty($correct)) ? false : true;
    $back['correct'] = $correct;
    $back['inserted_ids'] = $added_room_ids;

    return $back;
  }

  public function getRooms( $arg = array() )
  {
    $back = array();
    $darg = array();

    $q = "SELECT
      sz.ID,
      sz.name,
      sz.leiras,
      sz.felnott_db,
      sz.gyermek_db,
      sz.elerheto
    FROM ".parent::DBSZOBAK." as sz
    WHERE 1=1 ";

    $q .= " and sz.szallas_id = :id ";
    $darg['id'] = $this->szallas_id;

    if (!isset($arg['order'])) {
      $q .= " ORDER BY sz.felnott_db ASC, sz.gyermek_db ASC, sz.name ASC ";
    }

    $qry = $this->db->squery( $q, $darg );

    if ($qry->rowCount() == 0) {
      return $back;
    }

    $qry = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$qry as $d ) {

      $d['ID'] = (int)$d['ID'];
      $d['felnott_db'] = (int)$d['felnott_db'];
      $d['gyermek_db'] = (int)$d['gyermek_db'];
      $d['elerheto'] = ($d['elerheto'] == 1) ? true : false;

      $back[] = $d;
    }

    return $back;
  }

  public function __destruct()
	{
		parent::__destruct();
	}
}
?>
