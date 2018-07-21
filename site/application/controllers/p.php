<?
use PortalManager\Pages;

class p extends Controller{
		function __construct(){
			parent::__construct();

			//parent::$title = 'ASd';

			$page = new Pages( false, array( 'db' => $this->db ) );

			if ( $this->view->gets[1] != '' ) {
				$this->out( 'page', $page->get($this->view->gets[1]) );
				$parent = new Pages( false, array( 'db' => $this->db ) );
				$top_id = $page->getTopParentId( $this->view->page->getId() );
				$this->out( 'parent', $parent->get( $top_id ) );
				//$this->out( 'menu', $page->getTree( $this->view->page->getParentId() ) );
				$this->out( 'menu', $page->getTree( $top_id ) );

			} else {
				Helper::reload('/');
			}

			$bodyclass = 'singlepage';

			if ($this->gets[0] == 'p' && $this->gets[1] == 'kapcsolat') {
				$bodyclass .= ' nofootertopmargin';
			}

			$this->out( 'bodyclass', $bodyclass);

			$this->out( 'head_img_title', $page->getTitle() );
			$this->out( 'head_img', \PortalManager\Formater::sourceImg($page->getCoverImg()) );

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description',$page->getMeta('desc'));
			$SEO .= $this->view->addMeta('keywords','');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('title',$page->getMeta('title'));
			$SEO .= $this->view->addOG('description',$page->getMeta('desc'));
			$SEO .= $this->view->addOG('type','article');
			$SEO .= $this->view->addOG('url', DOMAIN.ltrim($_SERVER['REQUEST_URI'],'/'));
			$SEO .= $this->view->addOG('image', $page->getMeta('image'));
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);

			$this->view->SEOSERVICE = $SEO;

			parent::$pageTitle = $page->getTitle();
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
