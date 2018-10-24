<?php
use PortalManager\Template;
use SzallasManager\SzallasList;

class szallasok extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Szállások';
			$this->out( 'bodyclass', 'szallasok' );

			$temp = new Template( VIEW . __CLASS__.'/template/' );
			$this->out( 'template', $temp );

			// Szállások
			$szallaslista = new SzallasList( array( 'db' => $this->db ) );
			$szallasok = $szallaslista->getList();
			$this->out('szallasok', $szallasok);

			if ( $_GET['adatlap'] == 1 )
			{
				// Adatlap
				$id = (int)$_GET['ID'];
				$szallas = $szallaslista->loadSzallas( $id, true );
				$kiemelt_services = $szallaslista->collectKiemeltServices($szallas['datas']['services'], 'kiemelt', 1);
				$this->out('szallas', $szallas);
				$this->out('kiemelt_services', $kiemelt_services);
			}
			else
			{
				// Lista
				$this->out( 'head_img_title', 'Szállások' );
				$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
			}

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
