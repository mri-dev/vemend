<?
use PortalManager\Admin;

class szallasok extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Szállások / Adminisztráció';

      $this->Admin = new Admin( false, array( 'db' => $this->db, 'view' => $this->view ) );

			$perm = $this->User->hasPermission($this->view->adm->user, array('admin','adminuser'), 'szallasok', true);

  			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description','');
			$SEO .= $this->view->addMeta('keywords','');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url',DOMAIN);
			$SEO .= $this->view->addOG('image',DOMAIN.substr(IMG,1).'noimg.jpg');
			$SEO .= $this->view->addOG('site_name',TITLE);

			$this->view->SEOSERVICE = $SEO;
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
