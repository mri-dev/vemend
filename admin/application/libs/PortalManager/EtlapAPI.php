<?php
namespace PortalManager;

use Interfaces\InstallModules;

class EtlapAPI implements InstallModules
{
  const DBTABLE = 'Etlap';
  CONST DBETELEK = 'Etlap_Etelek';
  const MODULTITLE = 'Étlap';

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

  public function EtelLista()
  {
    $q = "SELECT
      e.*
    FROM ".self::DBETELEK." as e
    WHERE 1=1 ORDER BY e.neve ASC";

    $data = $this->db->query($q);

    if ($data->rowCount() != 0) {
      $list = array();
      $data = $data->fetchAll(\PDO::FETCH_ASSOC);

      foreach ( (array)$data as $d ) {
        $list[] = $d;
      }
      return $list;
    } else return array();
  }

  public function aktualisMenu()
  {
    $data = array();

    $data['ma'] = $this->aktualisNap();
    $data['hetvege_van'] = $this->isWeekend( $data['nap'] );
    $data['kovetkezo_hetfo'] = $this->nextMondayDate();
    $data['nap'] = ($data['hetvege_van']) ? $data['kovetkezo_hetfo'] : $data['ma'];
    $data['hetvege'] = $this->yearWeekend($data['nap']);

    return $data;
  }

  public function aktualisNap( $format = 'Y-m-d' )
  {
    return date($format, time());
  }

  private function isWeekend( $date ) {
    return (date('N', strtotime($date)) >= 6);
  }

  private function yearWeekend( $pdate = false )
  {
    $cdate = ($pdate) ? $pdate : date('Y-m-d');
    $duedt = explode("-", $cdate);
    $date  = mktime(0, 0, 0, $duedt[1], $duedt[2], $duedt[0]);
    $week  = (int)date('W', $date);
    return $week;
  }

  private function nextMondayDate( $format = 'Y-m-d' ) {
    $date = new \DateTime();
    $date->modify('next monday');
    return $date->format($format);
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
