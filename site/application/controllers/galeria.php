<?php
use PortalManager\Gallery;

class galeria extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Galéria';

      $galleries = new Gallery(array('db' => $this->db));
      $galleries_list = $galleries->loadGalleries();
      $this->out('galleries', $galleries_list);

      // Lista
      $this->out( 'head_img_title', 'Galéria' );
      $this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );

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
