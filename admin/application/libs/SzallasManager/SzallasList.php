<?php
namespace SzallasManager;


class SzallasList extends SzallasFramework
{
  private $db = null;
	public $settings = array();

  function __construct( $arg = array() )
  {
    parent::__construct( $arg );

    $this->db = $arg[db];
		$this->settings = $arg['db']->settings;

		return $this;
  }
  public function __destruct()
	{
		$this->db = null;
	  $this->settings = array();
	}
}
?>
