<?php
namespace PortalManager;

use Interfaces\InstallModules;

class EtlapAPI implements InstallModules
{
  const DBTABLE = 'Etlap';
  CONST DBETELEK = 'Etlap_Etelek';
  const MODULTITLE = 'Étlap';

  private $db = null;
  protected $kajakat = array('etel_fo', 'etel_leves', 'etel_va', 'etel_vb');
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

  public function checkMenuDateUsage( $date )
  {
    $q = "SELECT
      e.ID
    FROM ".self::DBTABLE." as e
    WHERE 1=1 and e.daydate = :date";

    $data = $this->db->squery($q, array(
      'date' => $date
    ));

    $id = $data->fetchColumn();

    return (int)$id;
  }

  public function usedDates()
  {
    $dates = array();
    $q = "SELECT daydate FROM ".self::DBTABLE." WHERE daydate >= now() GROUP BY daydate ORDER BY daydate ASC";
    $data = $this->db->query($q);

    if ($data->rowCount() == 0) {
      return array();
    } else {
      $data = $data->fetchAll(\PDO::FETCH_ASSOC);

      foreach ((array)$data as $d) {
        $dates[] =  str_replace('-','. ',$d['daydate']).'.';
      }

      return $dates;
    }
  }

  public function addMenu( $menu = array() )
  {
    $this->db->insert(
      self::DBTABLE,
      $menu
    );

    return $this->db->lastInsertId();
  }

  public function getMenu( $date = false )
  {
    $date = (empty($date)) ? date('Y-m-d') : $date;

    $arg = array();
    $arg['date'] = $date;
    $etel_qry = "";
    $etel_join_qry = "";

    $kf = 0;
    foreach ($this->kajakat as $kaja ) {
      $kf++;
      $etel_qry .= "
      , e".$kf.".ID as ".$kaja."_ID
      , e".$kf.".neve as ".$kaja."_neve
      , e".$kf.".kep as ".$kaja."_kep
      , e".$kf.".kategoria as ".$kaja."_kategoria
      , e".$kf.".kaloria as ".$kaja."_kaloria
      , e".$kf.".feherje as ".$kaja."_feherje
      , e".$kf.".ch as ".$kaja."_ch
      , e".$kf.".zsir as ".$kaja."_zsir
      , e".$kf.".cukor as ".$kaja."_cukor
      , e".$kf.".so as ".$kaja."_so
      , e".$kf.".allergenek as ".$kaja."_allergenek";
      $etel_join_qry .= " LEFT OUTER JOIN ".self::DBETELEK." as e".$kf." ON e".$kf.".ID = e.".$kaja;
    }

    $q = "SELECT
    e.daydate ";
    $q .= $etel_qry;
    $q .= " FROM ".self::DBTABLE." as e ";
    $q .= $etel_join_qry;
    $q .=" WHERE 1=1
    and e.daydate = :date";

    $back = array();
    $data = $this->db->squery($q, $arg);
    $data = $data->fetch(\PDO::FETCH_ASSOC);

    $ertekek = array();
    $allergenek = array();
    foreach ($this->kajakat as $kaja )
    {
      $back[$kaja] = ($data[$kaja.'_neve']) ? array(
        'ID' => (int)$data[$kaja.'_ID'],
        'neve' => $data[$kaja.'_neve'],
        'kep' => (!empty($data[$kaja.'_kep'])) ? UPLOADS.str_replace('/src/uploads/','',$data[$kaja.'_kep']) : false,
        'kategoria' => $data[$kaja.'_kategoria'],
        'kaloria' => (float)$data[$kaja.'_kaloria'],
        'feherje' => (float)$data[$kaja.'_feherje'],
        'ch' => (float)$data[$kaja.'_ch'],
        'zsir' => (float)$data[$kaja.'_zsir'],
        'cukor' => (float)$data[$kaja.'_cukor'],
        'so' => (float)$data[$kaja.'_so'],
        'allergenek' => $data[$kaja.'_allergenek'],
      ) : false;
      $allerg = explode(",", $data[$kaja.'_allergenek']);
      foreach ((array)$allerg as $al) {
        $al = trim($al);
        if (!in_array($al, $allergenek)) {
          if($al != '') {
            $allergenek[] = $al;
          }
        }
      }

      $ertekek['kaloria'] += (float)$data[$kaja.'_kaloria'];
      $ertekek['feherje'] += (float)$data[$kaja.'_feherje'];
      $ertekek['ch'] += (float)$data[$kaja.'_ch'];
      $ertekek['zsir'] += (float)$data[$kaja.'_zsir'];
      $ertekek['cukor'] += (float)$data[$kaja.'_cukor'];
      $ertekek['so'] += (float)$data[$kaja.'_so'];
    }
    $ertekek['allergenek'] = $allergenek;
    $back['ertekek'] = $ertekek;

    unset($allergenek);
    unset($ertekek);

    return $back;
  }

  public function aktualisMenu()
  {
    $data = array();

    $data['ma'] = $this->aktualisNap();
    $data['hetvege_van'] = $this->isWeekend( $data['nap'] );
    $data['kovetkezo_hetfo'] = $this->nextMondayDate();
    $data['nap'] = ($data['hetvege_van']) ? $data['kovetkezo_hetfo'] : $data['ma'];

    $weekdayname = (new \DateTime($data['nap']))->format('D');
    $weekdayname = $this->replaceWeekdayName($weekdayname);
    $data['nap_nev'] = $weekdayname;

    $data['hetvege'] = $this->yearWeekend($data['nap']);

    $data['menu'] = $this->getMenu( $data['nap'] );

    return $data;
  }

  public function aktualisHet()
  {
    $het = array();

    $het[0] = date('Y-m-d', strtotime('monday this week'));
    $het[1] = date('Y-m-d', strtotime('friday this week'));

    return $het;
  }

  public function menuSet( $from = false, $to = false )
  {
    $set = array();
    $arg = array();

    $ezahet = $this->aktualisHet();


    $q = "SELECT daydate FROM ".self::DBTABLE." WHERE 1=1 ";

    $q .= " and (daydate >= :monday and daydate <= :friday)";
    $arg['monday'] = $ezahet[0];
    $arg['friday'] = $ezahet[1];

    if ($from && $to) {
      $q .= " or (daydate >= :from and daydate <= :to)";
      $arg['from'] = $from;
      $arg['to'] = $to;
    }

    $q.= "GROUP BY daydate ORDER BY daydate ASC";
    $data = $this->db->squery($q, $arg);

    if ($data->rowCount() == 0) return $set;
    $data = $data->fetchAll(\PDO::FETCH_ASSOC);

    foreach ( $data as $d ) {
      $weeknum = $this->yearWeekend($d['daydate']);

      if (!isset($set['weeks'][$weeknum]['dateranges'])) {
        $datestartend = $this->getStartAndEndDate($weeknum, date('Y', strtotime($d['daydate'])));
        $set['weeks'][$weeknum]['dateranges'] = array(
          'start' => $datestartend['start'],
          'end' => $datestartend['end'],
          'range' => date('Y.m.d.', strtotime($datestartend['start'])) . ' - ' . date('Y.m.d.', strtotime($datestartend['end']))
        );
      }
      $weekdayname = (new \DateTime($d['daydate']))->format('D');
      $set['weeks'][$weeknum]['days'][$d['daydate']] = array(
        'day' => $d['daydate'],
        'weekday' => $this->replaceWeekdayName($weekdayname),
        'menu' => $this->getMenu($d['daydate'])
      );
    }

    return $set;
  }

  public function replaceWeekdayName( $weekdayname )
  {
    $replace = array(
      'Mon' => 'Hétfő',
      'Tue' => 'Kedd',
      'Wed' => 'Szerda',
      'Thu' => 'Csütörtök',
      'Fri' => 'Péntek'
    );

    return ($replace[$weekdayname]) ? $replace[$weekdayname] : $weekdayname;
  }

  public function aktualisNap( $format = 'Y-m-d' )
  {
    return date($format, time());
  }

  private function isWeekend( $date ) {
    return (date('N', strtotime($date)) >= 6);
  }

  private function getStartAndEndDate($week, $year)
  {
    $dto = new \DateTime();
    $dto->setISODate($year, $week);
    $ret['start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['end'] = $dto->format('Y-m-d');
    return $ret;
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
