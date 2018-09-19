<? 
use PortalManager\Admin;
use PortalManager\Portal;

class beallitasok extends Controller {
		function __construct(){	
			parent::__construct();
			parent::$pageTitle = 'Beállítások / Adminisztráció';
						
			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();
			
			// Szállítási módok
			$this->out( 'szallitas', $this->AdminUser->getSzallitasiModok() );
			// Fizetési módok
			$this->out( 'fizetes', $this->AdminUser->getFizetesiModok() );
			// Megrendelés állapotok
			$this->out( 'orderstatus', $this->AdminUser->getMegrendelesAllapotok() );
			
			// Load Admin
			$admin_id = false;
			if ($this->view->gets[1] == 'admin_torles' || $this->view->gets[1] == 'admin_szerkesztes') {
				$admin_id = $this->view->gets[2];
			}

			$admin = new Admin($admin_id, array( 'db' => $this->db ));
			$admins = $admin->getAdminList();
			$this->out( 'admins', $admins );
			$this->out( 'admin', $admin );
			$this->out( 'api_log', $admin->getAPILog() );

			// Termék állapotok listája
			$this->out( 'termek_allapotok', $admin->getTermekAllapotok() );
			// Termék márkák
			$this->out( 'markak', $admin->getMarkak() );
			// Termék szállítási idők listája
			$this->out( 'szallitasi_idok', $admin->getSzallitasiIdok() );

			if ( ( Post::on('addAdmin') || Post::on('saveAdmin') || Post::on('delAdmin') ) && $this->AdminUser->admin_jog != \PortalManager\Admin::SUPER_ADMIN_PRIV_INDEX ) {
				$this->view->err			= true;
				$this->view->bmsg['admin'] 	= Helper::makeAlertMsg('pError', 'Nincs jogosultsága a művelet végrehajtására! Csak <strong>Szuper Adminisztrátor</strong> joggal rendelkező fiókkal módosíthatja a beállításokat!'); 
			} else {
				// Admin létrehozása
				if (Post::on('addAdmin')) {
					try {
						$admin->add( $_POST );
						Helper::reload();
					} catch ( Exception $e ) {
						$this->view->err			= true;
						$this->view->bmsg['admin'] 	= Helper::makeAlertMsg('pError', $e->getMessage()); 
					}
				}

				// Admin szerkesztése
				if (Post::on('saveAdmin')) {
					try {
						$admin->save( $_POST );
						Helper::reload();
					} catch ( Exception $e ) {
						$this->view->err			= true;
						$this->view->bmsg['admin'] 	= Helper::makeAlertMsg('pError', $e->getMessage()); 
					}
				}

				// Admin törlése
				if (Post::on('delAdmin')) {
					try {
						$admin->delete();
						Helper::reload( '/beallitasok/#admins' );
					} catch ( Exception $e ) {
						$this->view->err			= true;
						$this->view->bmsg['admin'] 	= Helper::makeAlertMsg('pError', $e->getMessage()); 
					}
				}
			}

			// Változók beállítása
			if ( ( Post::on('saveBasics') ) && $this->AdminUser->admin_jog != \PortalManager\Admin::SUPER_ADMIN_PRIV_INDEX ) {
				$this->view->err			= true;
				$this->view->bmsg['basics'] = Helper::makeAlertMsg('pError', 'Nincs jogosultsága a művelet végrehajtására! Csak <strong>Szuper Adminisztrátor</strong> joggal rendelkező fiókkal módosíthatja a beállításokat!'); 
			} else {
				if (Post::on('saveBasics')) {
					unset($_POST['saveBasics']);
					$admin->saveSettings($_POST);
					Helper::reload();					
					
				}
			}
			

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description','');
			$SEO .= $this->view->addMeta('keywords','');
			$SEO .= $this->view->addMeta('revisit-after','3 days');
			
			// FB info
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url','');
			$SEO .= $this->view->addOG('image','');
			$SEO .= $this->view->addOG('site_name','');
			
			$this->view->SEOSERVICE = $SEO;
		}
		
		public function clearimages()
		{
			$portal = new Portal( array( 'db' => $this->db ) );	

			// Nem használt termék képek
			$this->out( 'unused_images', $portal->checkUnusedProductImage() );

			if( count( $this->view->unused_images) == 0 ) {
				Helper::reload( '/beallitasok' );
			}

			if ( Post::on('del_img') ) {
				foreach ( $_POST['del_img'] as $img ) {
					unlink( $img );
				}
				Helper::reload( '/' );			
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