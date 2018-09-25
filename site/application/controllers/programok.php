<?
use PortalManager\Programs;
use PortalManager\Template;
use PortalManager\Pagination;

class programok extends Controller
{
	function __construct(){
		parent::$user_opt = $user_option;
		parent::__construct();

		$this->out( 'bodyclass', 'article' );

		$url = DOMAIN.__CLASS__;
		$image = \PortalManager\Formater::sourceImg($this->view->settings['logo']);
		$title = 'Programjaink';
		$description = $this->view->settings['page_title'].' friss bejegyzései. Kövesd oldalunkat és tájékozódj az újdonságokról!';

		$news = new Programs( false, array( 'db' => $this->db ) );
		$temp = new Template( VIEW . __CLASS__.'/template/' );
		$this->out( 'template', $temp );
		$this->out( 'programcats', $news->categoryList());

		if ( isset($_GET['cikk']) )
		{
			// Cikk oldal
			$this->out( 'news', $news->get( trim($_GET['cikk']) ) );
			$news->log_view($this->view->news->getId());

			$arg = array(
				'limit' => 4,
				'page' 	=> 1,
				'in_cat' => (isset($_GET['cat']) && $_GET['cat'] != '' && $_GET['cat'] != 'olvas') ? $this->view->programcats[$_GET['cat']][ID] : false,
				'order' => array(
					'by' => 'rand()'
				),
				"except_id" => $this->view->news->getId()
			);
			$this->out( 'related', $news->getTree( $arg ) );

			$url = $this->view->news->getUrl();
			if ( $this->view->news->getImage() ) {
				$image = \PortalManager\Formater::sourceImg($this->view->news->getImage());
			}
			$title = $this->view->news->getTitle() . ' | Cikkek';
			$description = substr(strip_tags($this->view->news->getDescription()), 0 , 350);

		} else {
			// Lista oldal
			$cat_slug =  trim($_GET['cat']);

			if (isset($_GET['c']) && !empty($_GET['c'])) {
				$g = $_GET;
				unset($g['tag']);
				unset($g['list']);
				unset($g['page']);
				unset($g['c']);
				$nquery = http_build_query($g);
				\Helper::reload('/programok/'.$_GET['c'].'/?'.$nquery);
			}

			if ($cat_slug == '') {
				$this->out( 'head_img_title', 'Programjaink' );
				$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
			} else {
				$this->out( 'head_img_title', $this->view->programcats[$cat_slug]['neve'] );
				$this->out( 'head_img', IMGDOMAIN.$this->view->settings['homepage_coverimg'] );
			}

			$arg = array(
				'limit' => 12,
				'in_cat' => (int)$this->view->programcats[$cat_slug]['ID'],
				'page' => (isset($_GET['page'])) ? (int)$_GET['page'] : 1,
			);

			if ( isset($_GET['from']) && !empty($_GET['from']) ) {
				$arg['date']['min'] = $_GET['from'];
			}

			if ( isset($_GET['to']) && !empty($_GET['to']) ) {
				$arg['date']['max'] = $_GET['to'];
			}

			$this->out( 'list', $news->getTree( $arg ) );
			$this->out( 'navigator', (new Pagination(array(
					'class' 	=> 'pagination pagination-sm center',
					'current' 	=> $news->getCurrentPage(),
					'max' 		=> $news->getMaxPage(),
					'root' 		=> '/'.__CLASS__. (isset($_GET['cat']) ? '/'.$_GET['cat']: ''),
					'item_limit'=> 12
				)))->render() );
		}

		unset($news);

		// History
		$histnews = new Programs( false, array( 'db' => $this->db ) );
		$history_list = $histnews->historyList();
		$this->out( 'history', $history_list );
		unset($histnews);

		// SEO Információk
		$SEO = null;
		// Site info
		$SEO .= $this->view->addMeta('description', $description);
		$SEO .= $this->view->addMeta('keywords', $keywords);
		$SEO .= $this->view->addMeta('revisit-after','3 days');

		// FB info
		$SEO .= $this->view->addOG('title',$title.' | '.$this->view->settings['page_title']);
		$SEO .= $this->view->addOG('type','website');
		$SEO .= $this->view->addOG('url',$url);
		$SEO .= $this->view->addOG('image',$image);
		$SEO .= $this->view->addOG('site_name',$title.' | '.$this->view->settings['page_title']);

		$this->view->SEOSERVICE = $SEO;

		parent::$pageTitle = $title;
	}

	function __destruct(){
		// RENDER OUTPUT
			parent::bodyHead();					# HEADER
			$this->view->render(__CLASS__);		# CONTENT
			parent::__destruct();				# FOOTER
	}
}
?>
