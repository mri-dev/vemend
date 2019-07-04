<?
use PortalManager\Portal;
use PortalManager\Traffic;

class home extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Adminisztráció';

      if(Post::on('login')){
          try{
              $this->AdminUser->login($_POST);
              Helper::reload($_GET['return']);
          }catch(Exception $e){
              $this->view->err    = true;
              $this->view->bmsg   = Helper::makeAlertMsg('pError', $e->getMessage());
          }
      }

			if($this->gets[1] == 'exit'){
				$this->AdminUser->logout();
			}

			$portal = new Portal( array( 'db' => $this->db ) );
			// Nem használt termék képek
			$this->out( 'unused_images', $portal->checkUnusedProductImage() );

			//print_r($this->view->adm);

			// STATISZTIKÁK
			/////////////////////////////////////////////////////////
			// Általános statisztikák
			$this->view->stats 		= $this->AdminUser->getStats($this->view->adm->user);
			// Forgalom statisztikák
			$this->traffic     		= new Traffic( array( 'db' => $this->db ));
			$this->view->tafficInfo = $this->traffic->calcTrafficInfo();

			$arg 		= array();
			$arg[limit] = 10;
			$filters = Helper::getCookieFilter('filter',array('filtered'));
			$filters['user_group'] 	= array('sales','reseller');

			$arg['onlyreferersale'] = true;
			$arg['order'] 			= "totalReferredOrderPrices DESC";

			$arg['referertime'] = array(
				'from' 	=> date('Y-m-d', strtotime('-30 days'))
			);
			$arg[filters] = $filters;

			$this->view->refusers = $this->User->getUserList($arg);

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
