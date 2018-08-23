<?php
namespace SzallasManager;

class SzallasList
{
  private $db = null;
	public $settings = array();

  function __construct( $arg = array() )
  {
    $this->db = $arg[db];
		$this->settings = $arg['db']['settings'];

		return $this;
  }
  public function __destruct()
	{
		$this->db = null;
	  $this->settings = array();
	}
}
?>
