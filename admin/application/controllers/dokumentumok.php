<?
use FileManager\FileLister;
use ShopManager\Categories;
use ShopManager\Category;

class dokumentumok extends Controller {
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Dokumentumok';

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			if( Post::on('regFile') )
			{
				try
				{
					$this->shop->registerFileDocument( $_POST );
					Helper::reload( '/dokumentumok' );
				} catch( \Exception $e )
				{
					$this->view->msg = Helper::makeAlertMsg( 'pError', $e->getMessage() );
				}
			}

			$files = new FileLister('src/uploaded_files');

			$doc_filter = array('showOffline' => true, 'showHided' => true);
			if (isset($_GET['cat'])) {
				$doc_filter['in_cat'] = $_GET['cat'];
			}
			$this->out( 'files', 		$this->shop->checkDocuments(false, $files, $doc_filter));
			$this->out( 'doc_groupes', 	$this->shop->getDocumentGroupes());
			$this->out( 'user_groupes', $this->User->getUserGroupes());
			$this->out( 'user_containers', $this->User->getContainers());
			$this->out( 'doc_colors', 	$this->shop->getDocumentGroupeColors());

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

		public function kategoriak()
		{
			$categories = new Categories( array( 'db' => $this->db ) );
			$categories->setTable( 'shop_documents_kategoriak' );

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
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'shop_documents_kategoriak' )->get();
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
				$cat_data = (new Category( $this->view->gets[3],  array( 'db' => $this->db )  ))->setTable( 'shop_documents_kategoriak' )->get();
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

		public function edit()
		{
			/**
			 * Fájl mentése
			 * */
			if( Post::on('saveFile') )
			{
				if ($_POST['data']['user_group']) {
					$_POST['data']['user_group_in'] = implode(",",$_POST['data']['user_group']);
					unset($_POST['data']['user_group']);
				} else {
					$_POST['data']['user_group_in'] = NULL;
				}

				if ($_POST['data']['user_container']) {
					$_POST['data']['user_container_in'] = implode(",",$_POST['data']['user_container']);
					unset($_POST['data']['user_container']);
				} else {
					$_POST['data']['user_container_in'] = NULL;
				}

				$this->db->update(
					'shop_documents',
					$_POST['data'],
					"ID = ".$_POST['id']
				);

				// Kategória
				$this->db->squery("DELETE FROM shop_documents_group_xref WHERE doc_id = :did", array('did' => $_POST['id'] ));
				if (isset($_POST['data']['kategoriak'])) {
					foreach ((array)$_POST['data']['kategoriak'] as $cid) {
						if($cid == '') continue;
						$this->db->insert(
							'shop_documents_group_xref',
							array(
								'doc_id' => $_POST['id'],
								'cat_id' => $cid
							)
						);
					}
				}

				Helper::reload( '/dokumentumok' );
			}

			$fq = $this->db->query("SELECT * FROM shop_documents WHERE ID = ".$this->gets[2]);
			$f 	= $fq->fetch(\PDO::FETCH_ASSOC);
			$f['in_cat'] = $this->shop->documentFileGroups($f['ID']);
			$this->out( 'file', $f);
		}

		public function del()
		{
			$fq = $this->db->query("SELECT * FROM shop_documents WHERE ID = ".$this->gets[2]);

			if ( $fq->rowCount() == 0 ) {
				Helper::reload( '/dokumentumok' );
			}

			$f 	= $fq->fetch(\PDO::FETCH_ASSOC);
			$this->out( 'file', $f);

			/**
			 * Fájl törlése
			 * */
			if( Post::on('deleteFile') )
			{
				$removed = false;

				if ($f['tipus'] == 'local')
				{
					$removed = Helper::removeFile( $_POST['file'] );
				} else
				{
					$removed = true;
				}


				if( $removed )
				{
					$this->db->query("DELETE FROM shop_documents WHERE ID = ".$_POST['id']);
				}

				Helper::reload( '/dokumentumok' );
			}


		}

		public function upload()
		{
			/**
			 * Fájl feltöltése
			 * */
			if( Post::on('uploadFile') )
			{
				$filename 	= $_FILES['file']['name'];
				$path 		= 'src/uploaded_files/'.$filename;
				$is_upload 	= false;
				$sorrend 	= (isset($_POST[data][sorrend])) ? $_POST[data][sorrend] : 0;

				$_POST['data']['user_group_in'] = implode(",",$_POST['data']['user_group']);
				unset($_POST['data']['user_group']);

				if ( count($_POST['data']['user_container']) > 0 )
				{
					$_POST['data']['user_container_in'] = implode(",",$_POST['data']['user_container']);
					unset($_POST['data']['user_container']);
				}
				else
				{
					$_POST['data']['user_container_in'] = NULL;
				}



				if ($_POST['source'] == 'upload')
				{
					$is_upload = true;
				}

				if( $is_upload && empty($filename) ) 			$error = 'Kérjük, hogy válassza ki a feltöltendő fájlt!';
				if( !$error && empty($_POST['data']['cim']) ) 	$error = 'Kérjük, hogy adja meg a feltöltendő fájl elnevezését!';
				if( !$is_upload && empty($_POST['data']['filepath']) ) 	$error = 'Kérjük, hogy adja meg a dokumentum elérési útját (URL)!';

				if( !$error )
				{
					// Fájlfeltöltés
					if ($is_upload)
					{
						$uploaded = move_uploaded_file( $_FILES['file']['tmp_name'], $path );
						if( $uploaded )
						{
							$this->db->insert(
								'shop_documents',
								array(
									'hashname' 				=> md5($filename),
									'filepath' 				=> $path,
									'cim' 					=> addslashes($_POST['data']['cim']),
									'lathato' 				=> (isset($_POST['data']['lathato'])) ? 1 : 0,
									'szaktanacsado_only' 	=> (isset($_POST['data']['szaktanacsado_only'])) ? 1 : 0,
									'sorrend' 				=> $sorrend,
									'groupkey' 				=> $_POST['data']['groupkey'],
									'tipus' 				=> 'local',
									'user_group_in' 		=> $_POST['data']['user_group_in'],
									'user_container_in' 	=> $_POST['data']['user_container_in']
								)
							);

						} else
						{
							$this->view->msg = Helper::makeAlertMsg( 'pError', 'Fájlfeltöltés sikertelen volt.' );
						}
					}
					else
					// Link feltöltés
					{
						$path = trim($_POST['data']['filepath']);

						$this->db->insert(
							'shop_documents',
							array(
								'hashname' 				=> md5($_POST['data']['cim'].$path),
								'filepath' 				=> $path,
								'cim' 					=> addslashes($_POST['data']['cim']),
								'lathato' 				=> (isset($_POST['data']['lathato'])) ? 1 : 0,
								'szaktanacsado_only' 	=> (isset($_POST['data']['szaktanacsado_only'])) ? 1 : 0,
								'sorrend' 				=> $sorrend,
								'groupkey' 				=> $_POST['data']['groupkey'],
								'tipus' 				=> 'external',
								'user_group_in' 		=> $_POST['data']['user_group_in']
							)
						);
					}

					Helper::reload( '/dokumentumok' );
				}
				else
				{
					$this->view->msg = Helper::makeAlertMsg( 'pError', $error );
				}

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
