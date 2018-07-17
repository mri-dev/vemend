<? 
use PortalManager\Menus;
use PortalManager\Pages;
use ShopManager\Categories;
use ShopManager\Category;

class menu extends Controller{
		function __construct(){	
			parent::__construct();
			parent::$pageTitle = 'Menü / Adminisztráció';
			
			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();


			if(Post::on('flag')){
				$flag = $_POST[$_POST['flag']];
				
				if( $flag )
					setcookie($_POST['flag'], $_POST[$_POST['flag']], time()+3600*24, '/menu');
				else
					setcookie($_POST['flag'], false , time()-1, '/menu');

				Helper::reload();
			}

			$menus = new Menus( $this->view->gets[2], array( 'db' => $this->db ) ); 
			if(isset($_COOKIE['flag_menu_type_filter'])) {
				$menus->addFilter( 'menu_type', $_COOKIE['flag_menu_type_filter'] );
			}
			$categories = new Categories(  array( 'db' => $this->db )  );
			$pages = new Pages( false, array( 'db' => $this->db )  );

			if(Post::on('add')){
				try{
					$menus->add($_POST);
					Helper::reload();	
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			switch($this->view->gets[1]){
				case 'szerkeszt':
					if(Post::on('save')){
						try{
							$menus->save($_POST);	
							Helper::reload();
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'menu', $menus->get($this->view->gets[2]) );
				break;
				case 'torles':
					if(Post::on('delId')){
						try{
							$menus->delete();	
							Helper::reload('/menu');
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}
					$this->out( 'menu', $menus->get($this->view->gets[2]) );
				break;
			}

			// Menü pozíciók
			$menu_positions = $menus->getPositionList();
			$this->out( 'menu_positions', $menu_positions );

			// Menü típusok
			$menu_types = $menus->getTypes();
			$this->out( 'menu_types', $menu_types );

			// Menü fa
			$this->out( 'menus', $menus->getTree( false, array(
				'admin' => 1
			)) );

			// Kategória fa betöltés
			$cat_tree 	= $categories->getTree();
			// Kategoriák
			$this->out( 'categories', $cat_tree );

			// Oldal fa betöltés
			$page_tree 	= $pages->getTree();
			// Oldalak
			$this->out( 'pages', $page_tree );
			

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
		
		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>