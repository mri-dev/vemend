<?
use PortalManager\EtlapAPI;
use PortalManager\Template;

class etlap extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Étlapunk';

			$temp = new Template( VIEW .'/templates/' );
			$this->out( 'template', $temp );

			// Étlap
			$etlap = new EtlapAPI( array( 'db' => $this->db ) );
			$from = (isset($_GET['from']) && !empty($_GET['from'])) ? $_GET['from'] : false;
			$to = (isset($_GET['to']) && !empty($_GET['to'])) ? $_GET['to'] : false;
			$this->out( 'menu', $etlap->aktualisMenu() );
			$this->out( 'set', $etlap->menuSet( $from, $to) );
			$this->out( 'mondayfriday', $etlap->aktualisHet());

			// Hónap utolsó hete
			$month_last_day = date("Y-m-t", strtotime(NOW));
			$payday = date('Y-m-d', strtotime($month_last_day.' - 7 days'));
			$this->out( 'payday', $payday);


			$this->out('homepage', true);
			$this->out('bodyclass', 'homepage');

			$this->out( 'head_img_title', 'Jó étvágyat!' );
			$this->out( 'head_img', IMGDOMAIN.'/src/uploads/covers/cover-'.__CLASS__.'.jpg' );

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', $this->view->settings['about_us']);
			$SEO .= $this->view->addMeta('keywords',$this->view->settings['page_keywords']);
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('title', $this->view->settings['page_title'] . ' - '.$this->view->settings['page_description']);
			$SEO .= $this->view->addOG('description', $this->view->settings['about_us']);
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url', CURRENT_URI );
			$SEO .= $this->view->addOG('image', $this->view->settings['domain'].'/admin'.$this->view->settings['logo']);
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);
			$this->view->SEOSERVICE = $SEO;
		}


		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				$this->view->news = null;
				parent::__destruct();				# FOOTER
		}
	}

?>
