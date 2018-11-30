<?
use PortalManager\Redirectors;

class atiranyitas extends Controller
{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Átirányítás kezelő / Adminisztráció';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$perm = $this->User->hasPermission($this->view->adm->user, array('admin'), 'redirects', true);

			$this->redirectors = new Redirectors(array( 'db' => $this->db ));
			$this->out( 'redirectors', $this->redirectors->getList() );
		}

		function create()
		{
			if (Post::on('createRedirect'))
			{
				try{
					unset($_POST['createRedirect']);
					$this->redirectors->create($_POST);
					Helper::reload('/atiranyitas/');
				}
				catch(Exception $e)
				{
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
		}

		function del()
		{
			$this->out( 'redirect', $this->redirectors->get($this->gets[2]) );

			if (Post::on('delRedirect'))
			{
				try{
					$this->redirectors->delete($this->gets[2]);
					Helper::reload('/atiranyitas/');
				}
				catch(Exception $e)
				{
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
		}

		function edit()
		{
			$this->out( 'redirect', $this->redirectors->get($this->gets[2]) );

			if (Post::on('editRedirect'))
			{
				try{
					unset($_POST['editRedirect']);
					$this->redirectors->edit($this->gets[2], $_POST);
					Helper::reload();
				}
				catch(Exception $e)
				{
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
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
