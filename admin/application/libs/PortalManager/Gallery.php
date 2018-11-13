<?php
namespace PortalManager;

use Interfaces\InstallModules;

class Gallery implements InstallModules
{
  const DBGROUP = 'Galeria_Group';
  CONST DBTABLE = 'Galeria_Items';
  const MODULTITLE = 'Galéria';

  private $db = null;
  public $settings = array();

  function __construct( $arg = array() )
  {
    $this->db = $arg['db'];
    $this->settings = $arg['db']->settings;

    if( !$this->checkInstalled() && strpos($_SERVER['REQUEST_URI'], '/install') !== 0) {
      \Helper::reload('/install?module='.__CLASS__);;
    }

    return $this;
  }

  public function loadGalleries()
  {
    $list = array();

    $groupqry = "SELECT
      g.ID,
      g.neve,
      g.slug,
      g.szulo_id,
      g.kep,
      (SELECT count(i.ID) FROM ".self::DBTABLE." as i WHERE i.gallery_id = g.ID) as imagesnum
    FROM ".self::DBGROUP." as g
    WHERE 1=1
    ORDER BY g.sorrend ASC
    ";

    $groupqry = $this->db->query( $groupqry );

    if ($groupqry->rowCount() == 0) {
      return $list;
    }

    foreach ($groupqry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['ID'] = (int)$d['ID'];
      $d['imagesnum'] = (int)$d['imagesnum'];
      $d['has_kep'] = ($d['kep'] == '') ? false : true;
      $d['kep'] = (!$d['has_kep']) ? IMGDOMAIN . 'src/images/no-image.png' : UPLOADS . str_replace("/src/uploads/","",$d['kep']);
      $d['images'] = $this->getImages( $d['ID'] );
      $list[$d['slug']] = $d;
    }

    return $list;
  }

  public function registerImage( $gallery_id, $imagedata )
  {
    $this->db->insert(
      self::DBTABLE,
      array(
        'gallery_id' => $gallery_id,
        'filepath' => $imagedata['filepath'],
        'origin_name' => $imagedata['origin_name'],
        'kiterjesztes' => $imagedata['kiterjesztes'],
        'filemeret' => $imagedata['filemeret']
      )
    );

    return $this->db->lastInsertId();
  }

  function getImages( $gallery_id = 0 )
  {
    $list = array();
    $qryparam = array();

    $qry = "SELECT
      i.*
    FROM ".self::DBTABLE." as i
    WHERE 1=1 ";

    if ($gallery_id != 1) {
      $qry .= " and i.gallery_id = :gallery";
      $qryparam['gallery'] = (int)$gallery_id;
    }

    $qry .=" ORDER BY i.sorrend ASC, i.uploaded DESC";

    $qry = $this->db->squery( $qry, $qryparam );

    if ($qry->rowCount() == 0) {
      return $list;
    }

    foreach ($qry->fetchAll(\PDO::FETCH_ASSOC) as $d)
    {
      $d['filepath'] = str_replace("/src/images/","", IMG) . '/' . $d['filepath'];
      $list[] = $d;
    }

    return $list;
  }

  public function __destruct()
  {
    $this->db = null;
    $this->settings = array();
  }

    /*******************************
    * Installer
    ********************************/
    public function checkInstalled()
    {
      $check_installed = $this->db->query("SHOW TABLES LIKE '".self::DBTABLE."'")->fetchColumn();

      if ( $check_installed === false ) {
        $cn = addslashes(__CLASS__);
        $this->db->query("DELETE FROM modules WHERE classname = '$cn'");
      }

      return ($check_installed === false) ? false : true;
    }

    public function installer( \PortalManager\Installer $installer )
    {
      $installed = false;


      if (false) {
        /**
        * Vehicles
        **/
        $installer->setTable( self::DBTABLE );
        // Tábla létrehozás
        $table_create =
        "(
          `ID` mediumint(9) NOT NULL,
          `title` varchar(150) NOT NULL,
          `slug` varchar(150) NOT NULL,
          `logo` text,
          `parent_id` mediumint(9) DEFAULT NULL,
          `deep` smallint(6) NOT NULL DEFAULT '0'
        )";
        $installer->createTable( $table_create );

        // Indexek
        $index_create =
        "ADD PRIMARY KEY (`ID`),
        ADD KEY `title` (`title`),
        ADD KEY `parent_id` (`parent_id`)";
        $installer->addIndexes( $index_create );

        // Increment
        $inc_create =
        "MODIFY `ID` mediumint(9) NOT NULL AUTO_INCREMENT";
        $installer->addIncrements( $inc_create );
      }

      // Modul instalállás mentése
      $installed = $installer->setModulInstalled( __CLASS__, self::MODULTITLE, 'etlapok' , 'cutlery' );

      return $installed;
    }
}
?>
