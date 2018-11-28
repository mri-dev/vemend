<?
use MailManager\MailTemplates;

class emails extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'E-mailek';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'emails', true);

			$mailtemplates = new MailTemplates(array('db'=>$this->db));
			$this->out('mails', $mailtemplates->getList());


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

		public function edit()
		{
			$mailtemplate = (new MailTemplates(array('db'=>$this->db)))->load($this->gets[2]);
			$this->out( 'mail', $mailtemplate->getData() );

			if (Post::on('saveEmail') )
			{
				try {
					$mailtemplate->save( $this->gets[2], $_POST['data'] );
					Helper::reload( );
				} catch (\Exception $e) {
					$this->view->msg = Helper::makeAlertMsg( 'pError', $e->getMessage() );
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
