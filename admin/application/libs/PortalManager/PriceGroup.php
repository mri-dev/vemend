<?php
namespace PortalManager;

/*************************
* class PriceGroup
* @package PortalManager
* @version 1.0
**************************/
class PriceGroup
{
	private $db = null;
	private $id = false;
	private $item_data = false;
	public $authorid = 0;

	function __construct( $elem_id, $arg = array() )
	{
		$this->db = $arg[db];
		$this->id = $elem_id;

		if (isset($arg['authorid'])) {
			$this->authorid = $arg['authorid'];
		}

		$this->get();

		return $this;
	}

	/**
	 * Kategória adatainak lekérése
	 * @return void
	 */
	private function get()
	{
		$qarg = array();
		if ($this->authorid && $this->authorid != 0) {
			$authorid = $this->authorid;
			$where = " and ID = :id and author = :author ";
			$qarg['id'] = $this->id;
			$qarg['author'] = $authorid;
		} else {
			$where = " and ID = :id ";
			$qarg['id'] = $this->id;
		}

		$q = "SELECT * FROM ".\PortalManager\PriceGroups::DBTABLE." WHERE 1=1 ".$where;

		$cat_qry 	= $this->db->squery( $q, $qarg );
		$item_data = $cat_qry->fetch(\PDO::FETCH_ASSOC);
		$this->item_data = $item_data;
	}

	/**
	 * Aktuális kategória adatainak szerkesztése / mentése
	 * @param  array $db_fields új kategória adatok
	 * @return void
	 */
	public function edit( $db_fields )
	{
		if ($this->authorid && $this->authorid != 0) {
      $authorid = $this->authorid;
			$where = sprintf("ID = %d and author = %d", $this->id, $authorid);
    } else {
			$where = sprintf("ID = %d", $this->id);
		}

		$this->db->update(
			\PortalManager\PriceGroups::DBTABLE,
			$db_fields,
			$where
		);
	}

	/**
	 * Aktuális kategória törlése
	 * @return void
	 */
	public function delete()
	{
		$arg = array();
		$q = "DELETE FROM ".\PortalManager\PriceGroups::DBTABLE." WHERE 1=1 and ID = :id";
		$arg['id'] = $this->id;
		if ($this->authorid && $this->authorid != 0) {
			$q .= " and author = :author ";
			$authorid = $this->authorid;
			$arg['author'] = $authorid;
		}

		$this->db->squery($q, $arg);
	}

	/*===============================
	=            GETTERS            =
	===============================*/
	public function getTitle()
	{
		return $this->item_data['title'];
	}
	public function getKey()
	{
		return $this->item_data['groupkey'];
	}
	public function getId()
	{
		return $this->item_data['ID'];
	}
	/*-----  End of GETTERS  ------*/

	public function __destruct()
	{
		$this->db = null;
		$this->item_data = false;
	}

}

?>
