<? class slideshow extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Slideshow / Adminisztráció';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();


			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'slideshow', true);

			if(Post::on('add')){
				try{
					$this->AdminUser->addSlideShow($_POST);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if(Post::on('save')){
				try{
					$this->AdminUser->saveSlideShow($_POST);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if(Post::on('delete')){
				try{
					$this->AdminUser->delSlideShow($_POST);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			$arg = array();
			$arg['group'] = $_GET['g'];

			$this->view->ss = $this->AdminUser->getSlideShow( $arg );

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
