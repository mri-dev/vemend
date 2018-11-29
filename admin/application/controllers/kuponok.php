<? 
use PortalManager\Portal;
use PortalManager\Coupons;
use PortalManager\Coupon;
use ProductManager\Products;

class kuponok extends Controller{
		function __construct(){	
			parent::__construct();
			parent::$pageTitle = 'Kuponok / Adminisztráció';
			
			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$coupons = new Coupons(array( 'db' => $this->db ));
			$coupons->getTree(array(
				'admin' => true
			));
			$this->out( 'coupons', $coupons );

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

		function create()
		{
			if (Post::on('createCoupon')) 
			{
				$coupon = new Coupon(array('db'=>$this->db));

				try{
					$coupon->create($_POST);
					Helper::reload('/kuponok/');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg= Helper::makeAlertMsg('pError', $e->getMessage()); 
				}
			}
		}

		function edit()
		{
			// Kupon adatok
			$coupon = (new Coupon(array('db'=>$this->db)))->get($this->gets[2]);
			$this->out('coupon', $coupon);

			if (Post::on('saveCoupon')) 
			{
				try{
					$coupon->save($_POST);
					Helper::reload();
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg= Helper::makeAlertMsg('pError', $e->getMessage()); 
				}
			}
		}

		function del()
		{
			// Kupon adatok
			$coupon = (new Coupon(array('db'=>$this->db)))->get($this->gets[2]);
			$this->out('coupon', $coupon);

			if (Post::on('delCoupon')) 
			{
				try{
					$coupon->delete();
					Helper::reload();
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg= Helper::makeAlertMsg('pError', $e->getMessage()); 
				}
			}
		}
		

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>