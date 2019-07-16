<?
namespace PortalManager;

/**
* class Pagination
* @package PortalManager
* @version v1.0
*/
class Pagination
{
	private $class = 'pagination';
	private $current_item = 1;
	private $max_item = 1;
	private $root = '';
	private $after = '';
	private $page_limit = 10;

	function __construct( $arg = array() )
	{
		if ( $arg['class'] ) {
			$this->class = $arg['class'];
		}

		if ( $arg['current'] ) {
			$this->current_item = $arg['current'];
		}

		if ( $arg['max'] ) {
			$this->max_item = $arg['max'];
		}

		if ( $arg['after'] ) {
			$this->after = $arg['after'];
		}

		if ( $arg['root'] ) {
			$this->root = $arg['root'];
		}
		if ( $arg['item_limit'] ) {
			$this->page_limit = $arg['item_limit'];
		}

		return $this;
	}

	public function render()
	{
		$do_start_much = false;
		$do_end_much = false;
		$r = '<div class="nav-container">';
		$r .= '<ul class="'.$this->class.'">';
		  $r .= '<li><a href="'.$this->root.'/1'.$this->after.'"><i class="fa fa-angle-left"></i></a></li>';
		  if( $this->current_item-1 > ($this->page_limit/2) &&  $this->max_item > $this->page_limit )  {
		  	$r .= '<li ><a href="'.$this->root.'/1'.$this->after.'">1</a></li>';
		  }
		  for($p = 1; $p <= $this->max_item; $p++):
		  	if( $p < ($this->current_item - ($this->page_limit/2))) {
		  		if( !$do_start_much ) {
		  			$r .= '<li ><a href="">...</a></li>';
		  			$do_start_much = true;
		  		}
		  		continue;
	  		} else if($p > ($this->current_item + ($this->page_limit/2))) {
	  			if( !$do_end_much ) {
		  			$r .= '<li ><a href="">...</a></li>';
		  			$do_end_much = true;
		  		}
		  		continue;
	  		}
		  	$r .= '<li class="'.( $this->current_item == $p  ? 'active' : '' ).'"><a href="'.$this->root.'/'.$p.$this->after.'">'.$p.'</a></li>';
		  endfor;
		  if( ($this->current_item < $this->max_item - ($this->page_limit/2) ) &&  $this->max_item > $this->page_limit )  {
		  	$r .= '<li ><a href="'.$this->root.'/'.$this->max_item.$this->after.'">'.$this->max_item.'</a></li>';
		  }
		  $r .= '<li><a href="'.$this->root.'/'.$this->max_item.$this->after.'"><i class="fa fa-angle-right"></i></a></li>';
		$r .= '</ul>';
		$r .= '</div>';

		return $r;
	}
}
?>
