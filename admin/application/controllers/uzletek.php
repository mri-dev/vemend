<?
use PortalManager\CasadaShops;
use PortalManager\CasadaShop;

class uzletek extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Üzletek';

	    $this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$shops = new CasadaShops(array(
				'db' => $this->db
			));

			$shoplist = $shops->getList(array( 'admin' => true ));
			$this->out( 'shops', $shoplist );
			$this->out( 'days', 	 	$this->User->days);
			$this->out( 'daynames', 	$this->User->day_names);


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

		function permission()
		{
			$shopID 	= (int)$_GET['shopID'];
			$card	 	= $_GET['card'];

			$shop = new CasadaShop( $shopID, array(
				'db' => $this->db
			));

			switch ($card) {
				case 'allow':
					$shop->allowAccess();
					Helper::reload('/uzletek');
				break;
				case 'disallow':
					$shop->disallowAccess();
					Helper::reload('/uzletek');
				break;
			}
		}

		function distributor()
		{

			$shopID 	= (int)$this->gets[2];

			$shop = new CasadaShop( $shopID, array(
				'db' => $this->db
			));

			// Tanácsadó kapcsolat eltávolítás
			if ($_GET['remove'] == '1')
			{
				$shop->removeDistributor( $_GET['uid'] );
				Helper::reload('/uzletek/distributor/'.$shop->getID());
			}

			// Tanácsadó kapcsolat eltávolítás
			if ($_GET['detuserdefault'] == '1')
			{
				$shop->setDistributorDefault( $_GET['uid'] );
				Helper::reload('/uzletek/distributor/'.$shop->getID());
			}

			if (Post::on('addDistributor'))
			{
				try {
					$shop->addDistributor($_POST['distributor']);
					Helper::reload('/uzletek/distributor/'.$shop->getID());
				} catch (\Exception $e) {
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			$qry = "
			SELECT 		u.ID, u.nev, u.email
			FROM 		".\PortalManager\Users::TABLE_NAME." as u
			WHERE 		1=1 and u.engedelyezve = 1 and u.user_group NOT IN ('user','partnre') and (SELECT 1 FROM ".\PortalManager\CasadaShop::DB_XREF." WHERE user_id = u.ID and shop_id = ".$shop->getID().") IS NULL
			;";

			$dist_users = $this->db->query($qry)->fetchAll(\PDO::FETCH_ASSOC);


			$this->out( 'shop', 		$shop );
			$this->out( 'dist_users', 	$dist_users );
		}

		public function add()
		{
			$shop 		= new CasadaShop( false, array(
				'db' => $this->db
			));

			if (Post::on('create'))
			{
				try {
					$shop->create(NULL, $_POST);
					Helper::reload('/uzletek/');
				} catch (\Exception $e) {
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			$times = \PortalManager\Formater::clockTimes(true,'zárva');

			$this->out( 'shop', $shop );
			$this->out( 'times', 	$times );
			$this->out( 'days', 	$this->User->days);
			$this->out( 'daynames', $this->User->day_names);
		}

		public function edit()
		{
			$shopID 	= (int)$_GET['shopID'];
			$shop 		= new CasadaShop( $shopID, array(
				'db' => $this->db
			));

			if (Post::on('editShop'))
			{
				try {
					$shop->adminEdit($_POST);
					Helper::reload('/uzletek/edit/?shopID='.$shop->getID());
				} catch (\Exception $e) {
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			$times = \PortalManager\Formater::clockTimes(true,'zárva');

			$this->out( 'shop', $shop );
			$this->out( 'times', 	$times );
			$this->out( 'days', 	$this->User->days);
			$this->out( 'daynames', $this->User->day_names);
		}

		public function delete()
		{
			$shopID 	= (int)$_GET['shopID'];
			$shop 		= new CasadaShop( $shopID, array(
				'db' => $this->db
			));

			// üzlet törlése
			if (Post::on('deleteShop'))
			{
				$shop->delete( $shop->getID() );
				Helper::reload('/uzletek');
			}

			$this->out( 'shop', $shop );
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
