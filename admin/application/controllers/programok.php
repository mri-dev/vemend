<?
use PortalManager\Programs;
use PortalManager\Pagination;
use ShopManager\Categories;
use ShopManager\Category;

class programok extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Programok / Adminisztráció';


			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'program_kategoriak' );
			$news = new Programs( $this->view->gets[2],  array( 'db' => $this->db )  );

			// Hír fa betöltés
			$arg = array(
				'limit' => 25,
				'page' 	=> Helper::currentPageNum()
			);
			$page_tree 	= $news->getTree( $arg );
			// Hírek
			$this->out( 'news_list', $page_tree );
			$this->out( 'navigator', (new Pagination(array(
				'class' 	=> 'pagination pagination-sm center',
				'current' 	=> $news->getCurrentPage(),
				'max' 		=> $news->getMaxPage(),
				'root' 		=> '/'.__CLASS__,
				'item_limit'=> 28
			)))->render() );

			// LOAD
			////////////////////////////////////////////////////////////////////////////////////////
			$cat_tree 	= $categories->getTree();
			// Kategoriák
			$this->out( 'categories', $cat_tree );

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

		public function creator()
		{

			$news = new Programs( $this->view->gets[3],  array( 'db' => $this->db )  );

			if (isset($_GET['rmsg'])) {
				$xrmsg = explode('::', $_GET['rmsg']);
				$this->out('msg', \Helper::makeAlertMsg('p'.ucfirst($xrmsg[0]), $xrmsg[1]));
			}

			if(Post::on('add')){
				try{
					$id = $news->add($_POST);
					Helper::reload('/cikkek/creator/szerkeszt/'.$id.'?rmsg=success::Új cikk sikeresen létrehozva.');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			switch($this->view->gets[2]){
				case 'szerkeszt':
					if(Post::on('save')){
						try{
							$news->save($_POST);
							Helper::reload();
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'news', $news->get( $this->view->gets[3]) );
				break;
				case 'torles':
					if(Post::on('delId')){
						try{
							$news->delete($this->view->gets[3]);
							Helper::reload('/cikkek/');
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'news', $news->get( $this->view->gets[3]) );
				break;
			}
		}

		public function kategoriak()
		{
			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'program_kategoriak' );

			// Új kategória
			if( Post::on('addCategory') )
			{
				try {
					$categories->add( $_POST );
					Helper::reload();
				} catch ( Exception $e ) {
					$this->view->err	= true;
					$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			// Szerkesztés
			if ( $this->view->gets[2] == 'szerkeszt') {
				// Kategória adatok
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'program_kategoriak' )->get();
				$this->out( 'category', $cat_data );

				// Változások mentése
				if( Post::on('saveCategory') )
				{
					try {
						$categories->edit( $cat_data, $_POST );
						Helper::reload();
					} catch ( Exception $e ) {
						$this->view->err	= true;
						$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
					}
				}
			}

			// Törlés
			if ( $this->view->gets[2] == 'torles') {
				// Kategória adatok
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'program_kategoriak' )->get();
				$this->out( 'category_d', $cat_data );

				// Kategória törlése
				if( Post::on('delCategory') )
				{
					try {
						$categories->delete( $cat_data );
						Helper::reload( '/'.$this->gets[0].'/'.$this->gets[1] );
					} catch ( Exception $e ) {
						$this->view->err	= true;
						$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
					}
				}
			}

			// LOAD
			////////////////////////////////////////////////////////////////////////////////////////
			$cat_tree 	= $categories->getTree();
			// Kategoriák
			$this->out( 'categories', $cat_tree );
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
