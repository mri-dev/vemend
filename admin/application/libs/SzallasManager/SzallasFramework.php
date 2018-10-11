<?php
namespace SzallasManager;

class SzallasFramework
{
  const DBSZALLASOK = 'Szallasok';
  const DBKEPEK = 'Szallas_Kepek';
  const DBPARAMETEREK = 'Szallas_Paremeterek';
  const DBPARAMXREF = 'Szallas_xref_szallas_parameter';

  protected $db = null;
	protected $settings = array();

  function __construct( $arg = array() )
  {
    $this->db = $arg[db];
		$this->settings = $arg['db']->settings;

		return $this;
  }

  public function getAuthor( $id )
  {
    if($id == '') return false;
		$q = "SELECT * FROM ".\PortalManager\Users::TABLE_NAME." WHERE `ID` = '$id'";

		extract($this->db->q($q));

		// Felhasználó adatok
		$detailslist = array();

		if ( !$data['ID'] ) {
			return false;
		}

		$details = $this->db->query($q = "SELECT nev, ertek FROM ".\PortalManager\Users::TABLE_DETAILS_NAME." WHERE fiok_id = ".$data['ID'].";");

		if ( $details->rowCount() != 0 ) {
			foreach ($details->fetchAll(\PDO::FETCH_ASSOC) as $det) {
				if ($det['nev'] == 'permissions' && $det['ertek'] != '') {
					$det['ertek'] = json_decode($det['ertek'], \JSON_UNESCAPED_UNICODE);
				}
				$detailslist[$det['nev']] = $det['ertek'];
			}
		}

		$data = array_merge($data, $detailslist);

		return $data;
  }

  public function __destruct()
	{
		$this->db = null;
	  $this->settings = array();
	}
}
?>
