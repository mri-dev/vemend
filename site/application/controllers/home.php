<?
use PortalManager\News;
use PortalManager\Programs;
use PortalManager\EtlapAPI;
use ProductManager\Products;

class home extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = '';

			$this->out('homepage', true);
			$this->out('bodyclass', 'homepage');

			// Hírek
			$news = new News( false, array( 'db' => $this->db ) );
			$hirek = array();
			$arg = array(
				'limit' => 2,
				'page' 	=> 1
			);
			$news->getTree( $arg );

			if ( $news->has_news() ) {
				while ( $news->walk() ) {
					$hir = $news->the_news();
					$hirek[] = (new News(false, array( 'db' => $this->db )))->get($hir[ID]);
				}
			}
			$this->out( 'news', $hirek );
			unset($news);
			unset($hirek);

			// Miserend
			$news = new News( false, array( 'db' => $this->db ) );
			$cats = $news->categoryList();
			$miserend = array();
			$arg = array(
				'limit' => 4,
				'page' 	=> 1,
				'in_cat' => $cats['miserend']['ID']
			);
			$news->getTree( $arg );

			if ( $news->has_news() ) {
				while ( $news->walk() ) {
					$hir = $news->the_news();
					$miserend[] = (new News(false, array( 'db' => $this->db )))->get($hir[ID]);
				}
			}
			$this->out( 'miserend_news', $miserend );
			unset($news);
			unset($miserend);

			// Program
			$programs = new Programs( false, array( 'db' => $this->db ) );
			$program = array();
			$arg = array(
				'limit' => 1,
				'page' 	=> 1,
				'date' => array(
					'min' => date('Y-m-d 00:00:00'),
				)
			);
			$programs->getTree( $arg );

			if ( $programs->has_news() ) {
				while ( $programs->walk() ) {
					$prog = $programs->the_news();
					$program = (new Programs(false, array( 'db' => $this->db )))->get($prog[ID]);
				}
			}
			$this->out( 'program', $program );
			unset($programs);
			unset($program);

			// Étlap
			$etlap = new EtlapAPI( array( 'db' => $this->db ) );
			$this->out( 'etlap', $etlap );

			//
			/*
			// Újdonságok
			$arg = array(
				'limit' 	=> 3,
				'ujdonsag' => true,
				'order' => array(
					'by' => 'rand()'
				)
			);
			$ujdonsag_products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->prepareList( $arg );
			$this->out( 'ujdonsag_products', $ujdonsag_products );
			$this->out( 'ujdonsag_products_list', $ujdonsag_products->getList() );

			// Kiemelt termékek
			$arg = array(
				'limit' 	=> 6,
				'kiemelt' => true,
				'order' => array(
					'by' => 'rand()'
				)
			);
			$kiemelt_products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->prepareList( $arg );
			$this->out( 'kiemelt_products', $kiemelt_products );
			$this->out( 'kiemelt_products_list', $kiemelt_products->getList() );
			*/

			$this->out( 'head_img_title', 'Üdvözöljük Véménden!' );
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
