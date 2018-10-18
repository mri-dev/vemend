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

        // Szoba árak módosítása, mentése
        if ( !empty($room['arak']) && $room['arak'])
        {
          foreach ( (array)$room['arak'] as $ellid => $ar ) {
            if($ellid == 0) continue;
            $rid = (int)$ar['ID'];
            $price_adult = ($ar['adult'] != '' && $ar['adult'] != 0) ? (float)$ar['adult'] : NULL;
            $price_children = ($ar['children'] != '' && $ar['children'] != 0) ? (float)$ar['children'] : NULL;

            if($rid != 0) {
              // Szoba ár módosítás
              $this->db->update(
                parent::DBSZOBAAR,
                array(
                  'felnott_ar' => $price_adult,
                  'gyerek_ar' => $price_children
                ),
                sprintf("ID = %d", $rid)
              );
            } else {
              // Szoba ár regisztrálás
              $this->db->insert(
                parent::DBSZOBAAR,
                array(
                  'szoba_id' => (int)$room['ID'],
                  'ellatas_id' => $ellid,
                  'felnott_ar' => $price_adult,
                  'gyerek_ar' => $price_children
                )
              );
            }
          }
        }
      } else {
        // Létrehozás / szoba hozzáadása
        $uid = uniqid();

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
          $ins_id = $this->db->lastInsertId();

          // Szoba árak módosítása, mentése
          if ( !empty($room['arak']) && $room['arak'])
          {
            foreach ( (array)$room['arak'] as $ellid => $ar ) {
              if($ellid == 0) continue;
              $rid = (int)$ar['ID'];
              $price_adult = ($ar['adult'] != '' && $ar['adult'] != 0) ? (float)$ar['adult'] : NULL;
              $price_children = ($ar['children'] != '' && $ar['children'] != 0) ? (float)$ar['children'] : NULL;

              if($rid != 0) {
                // Szoba ár módosítás
                $this->db->update(
                  parent::DBSZOBAAR,
                  array(
                    'felnott_ar' => $price_adult,
                    'gyerek_ar' => $price_children
                  ),
                  sprintf("ID = %d", $rid)
                );
              } else {
                // Szoba ár regisztrálás
                $this->db->insert(
                  parent::DBSZOBAAR,
                  array(
                    'szoba_id' => (int)$ins_id,
                    'ellatas_id' => $ellid,
                    'felnott_ar' => $price_adult,
                    'gyerek_ar' => $price_children
                  )
                );
              }
            }
          }

          $added_room_ids[] = $ins_id;
        }
      }
    }

    $back['error'] = (empty($correct)) ? false : true;
    $back['correct'] = $correct;
    $back['inserted_ids'] = $added_room_ids;

    return $back;
  }

  public function roomPrices( $szobaid = 0 )
  {
    $back = array();

    $darg = array();

    $q = "SELECT
      sz.ID,
      sz.ellatas_id,
      sz.felnott_ar,
      sz.gyerek_ar
    FROM ".parent::DBSZOBAAR." as sz
    WHERE 1=1 ";

    $q .= " and sz.szoba_id = :id ";
    $darg['id'] = $szobaid;

    if (!isset($arg['order'])) {
      //$q .= " ORDER BY sz.felnott_db ASC, sz.gyermek_db ASC, sz.name ASC ";
    }

    $qry = $this->db->squery( $q, $darg );

    if ($qry->rowCount() == 0) {
      return $back;
    }

    $qry = $qry->fetchAll(\PDO::FETCH_ASSOC);

    foreach ((array)$qry as $d ) {

      $d['ID'] = (int)$d['ID'];
      $d['ellatas_id'] = (int)$d['ellatas_id'];
      $d['felnott_ar'] = ($d['felnott_ar']) ? (float)$d['felnott_ar'] : NULL;
      $d['gyerek_ar'] = ($d['gyerek_ar']) ? (float)$d['gyerek_ar']: NULL;

      $back[$d['ellatas_id']]['ID'] = $d['ID'];
      $back[$d['ellatas_id']]['adult'] = $d['felnott_ar'];
      $back[$d['ellatas_id']]['children'] = $d['gyerek_ar'];
    }

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
      $d['arak'] = $this->roomPrices((int)$d['ID']);

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
