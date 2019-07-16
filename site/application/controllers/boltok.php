<?
use ShopManager\Category;
use ShopManager\Categories;
use ProductManager\Products;
use PortalManager\Template;
use PortalManager\Pagination;

class boltok extends Controller {
		function __construct(){
			parent::__construct();
			$title = 'Boltok';

			/****
			* TOP TERMÉKEK
			*****/
			$arg = array(
				'limit' 	=> 5,
				'collectby' => 'top'
			);
			$top_products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->prepareList( $arg );
			$this->out( 'top_products', $top_products );
			$this->out( 'top_products_list', $top_products->getList() );

			/****
			* MEGNÉZETT TERMÉKEK
			*****/
			$arg = array();
			$viewed_products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->getLastviewedList( \Helper::getMachineID(), 5, $arg );
			$this->out( 'viewed_products_list', $viewed_products );

			/****
			* Live TERMÉKEK
			*****/
			$arg = array();
			$live_products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->getLiveviewedList( \Helper::getMachineID(), 5, $arg );
			$this->out( 'live_products_list', $live_products );


			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', '');
			$SEO .= $this->view->addMeta('keywords', '');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('type','product.group');
			$SEO .= $this->view->addOG('url',substr(DOMAIN,0,-1).$_SERVER['REQUEST_URI']);
			$SEO .= $this->view->addOG('image', '');
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);

			$this->view->SEOSERVICE = $SEO;

			parent::$pageTitle = $title;
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
