<?
use PortalManager\Admin;

class uzenetek extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Üzenetek / Adminisztráció';


 			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$this->Admin = new Admin( false, array( 'db' => $this->db, 'view' => $this->view ) );
			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'belsouzenetek', true);

			if(Post::on('actionSaving')){
				try{
					$arg = array();
					$re = $this->AdminUser->doMessageAction($_POST[selectAction], 'action_', $arg);
					$this->view->rmsg	= Helper::makeAlertMsg('pSuccess', $re);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->rmsg= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if(Post::on('filterList')){
				$filtered = false;

				if($_POST[ID] != ''){
					setcookie('filter_ID',$_POST[ID],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST[uzenet_targy] != ''){
					setcookie('filter_uzenet_targy',$_POST[uzenet_targy],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_uzenet_targy','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST[termeknev] != ''){
					setcookie('filter_termeknev',$_POST[termeknev],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_termeknev','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST[fvalaszolva] != ''){
					setcookie('filter_fvalaszolva',$_POST[fvalaszolva],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_fvalaszolva','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST[farchivalt] != ''){
					setcookie('filter_farchivalt',$_POST[farchivalt],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_farchivalt','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST[contact] != ''){
					setcookie('filter_contact',$_POST[contact],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_contact','',time()-100,'/'.$this->view->gets[0]);
				}

				if($filtered){
					setcookie('filtered','1',time()+60*24*7,'/'.$this->view->gets[0]);
				}else{
					setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
				}
				Helper::reload();
			}

			// Üzenetek lista
			$arg = array();
			$arg[limit] = 50;
			$filters = Helper::getCookieFilter('filter',array('filtered'));
			$arg[filters] = $filters;
			$this->view->uzenetek 	= $this->AdminUser->getUzenetek($arg);

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

		function clearfilters(){
			setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_uzenet_targy','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_termeknev','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_fvalaszolva','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_farchivalt','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_contact','',time()-100,'/'.$this->view->gets[0]);

			setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
			Helper::reload('/'.__CLASS__.'/');
		}

		function msg(){
			$msgID = $this->view->gets[2];

			$this->view->msg = $this->AdminUser->getMessageData($msgID);

			if(Post::on('sendReplyMsg')){
				try{
					$this->Admin->replyToMessage($this->view->msg, $_POST);
					Helper::reload('/'.$this->view->gets[0].'/msg/'.$this->view->gets[2].'/?msgkey=msg&msg=Sikeresen válaszolt az üzenetre!');
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
