<?php
use FileManager\FileLister;

class docs extends Controller
{
		function __construct()
    {
			parent::__construct();

			parent::$pageTitle = 'Dokumentumok, letöltések';

      $files = new FileLister('../admin/src/uploaded_files');

			$doc_filter = array('showOffline' => false, 'showHided' => false, 'stacked' => true);
			if (isset($_GET['cat'])) {
				$doc_filter['in_cat'] = $_GET['cat'];
			}
      if (isset($_GET['src'])) {
				$doc_filter['search'] = $_GET['src'];
			}
			$this->out( 'files', $this->shop->checkDocuments(false, $files, $doc_filter));
			$this->out( 'doc_groupes',	$this->shop->getDocumentGroupes());
			$this->out( 'doc_colors', $this->shop->getDocumentGroupeColors());


			$this->out( 'head_img_title', 'Dokumentumok, letöltések.' );
			$this->out( 'head_img', IMGDOMAIN.'/src/uploads/covers/cover-'.__CLASS__.'.jpg' );

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', 'Letölthető dokumentumok és fontosabb letöltések.');
			$SEO .= $this->view->addMeta('keywords','dokumentum, letöltés, kiadványok, szerződések');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('title', 'Dokumentumok, letöltések.');
			$SEO .= $this->view->addOG('description', 'Letölthető dokumentumok és fontosabb letöltések.');
			$SEO .= $this->view->addOG('type','article');
			$SEO .= $this->view->addOG('url', CURRENT_URI );
			$SEO .= $this->view->addOG('image', $this->view->settings['domain'].'/admin'.$this->view->settings['logo']);
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);

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
