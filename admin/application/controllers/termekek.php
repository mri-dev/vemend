<?
use ShopManager\Categories;
use ProductManager\Products;
use ProductManager\Product;
use PortalManager\Pagination;
use PortalManager\Template;
use PortalManager\Admin;
use FileManager\FileLister;
use Applications\XMLParser;

class termekek extends Controller
{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Termékek / Adminisztráció';

			$this->Admin = new Admin( false, array( 'db' => $this->db, 'view' => $this->view ) );

			$perm = $this->User->hasPermission($this->view->adm->user, array('adminuser','admin'), 'webshop', true);

			if( $_GET['backmsg'] )
			{
				$this->view->bmsg= Helper::makeAlertMsg('pSuccess', $_GET['msg']);
			}

			// Kategóriák
			$arg = array( 'db' => $this->db, 'authorid' => $this->view->adm->user['ID'] );
			if ($this->view->adm->user['user_group'] != 'admin') {
				$arg['onlyauthor'] = true;
			}
			$cats = new Categories( $arg );
			$this->out( 'categories', $cats->getTree() );

			if(Post::on('actionSaving')){
				try{
					$arg = array();
					$re = $this->AdminUser->doAction($_POST[selectAction], 'action_', $arg);
					/*echo '<pre>';
					print_r($_POST);
					echo '</pre>';*/
					$this->view->rmsg	= Helper::makeAlertMsg('pSuccess', $re);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->rmsg= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if(Post::on('filterList')){
				$filtered = false;

				if($_POST['ID'] != ''){
					setcookie('filter_ID',$_POST['ID'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['cikkszam'] != ''){
					setcookie('filter_cikkszam',$_POST['cikkszam'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_cikkszam','',time()-100,'/'.$this->view->gets[0]);
				}


				if($_POST['szin'] != ''){
					setcookie('filter_szin',$_POST['szin'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_szin','',time()-100,'/'.$this->view->gets[0]);
				}


				if($_POST['meret'] != ''){
					setcookie('filter_meret',$_POST['meret'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_meret','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['nev'] != ''){
					setcookie('filter_nev',$_POST['nev'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['marka'] != ''){
					setcookie('filter_marka',$_POST['marka'],time()+60*24,'/'.$this->view->gets[0]);
						$filtered = true;
				}else{
					setcookie('filter_marka','',time()-100,'/'.$this->view->gets[0]);
				}
				if($_POST['szallitasID'] != ''){
					setcookie('filter_szallitasID',$_POST['szallitasID'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_szallitasID','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['keszletID'] != ''){
					setcookie('filter_keszletID',$_POST['keszletID'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_keszletID','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['lathato'] != ''){
					setcookie('filter_lathato',$_POST['lathato'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_lathato','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['fotermek'] == 'on'){
					setcookie('filter_fotermek',1,time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_fotermek','',time()-100,'/'.$this->view->gets[0]);
				}

				if($filtered){
					setcookie('filtered','1',time()+60*24*7,'/'.$this->view->gets[0]);
				}else{
					setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
				}
				Helper::reload('/termekek/1');
			}

			// Termék lista
			$prodarg = array(
				'db' => $this->db,
				'authorid' => $this->view->adm->user['ID']
			);
			if ($this->view->adm->user['user_group'] != 'admin') {
				$prodarg['onlyauthor'] = true;
			}
			$products = new Products( $prodarg );
			$price_groups = $products->priceGroupList();

			$filters = Helper::getCookieFilter('filter',array('filtered'));

			// GETS
			if (isset($_GET['article'])) {
				$filters['raktar_articleid'] = $_GET['article'];
			}
			$arg = array(
				'admin' => true,
				'filters' => $filters,
				'limit' => 50,
				'page' => Helper::currentPageNum(),
				'order' => array(
					'by' => 'p.ID',
					'how' => 'DESC'
				)
			);
			$products_list = $products->prepareList( $arg )->getList();
			$this->out( 'products', $products );
			$this->out( 'termekek', $products_list );
			$this->out( 'navigator', (new Pagination(array(
				'class' => 'pagination pagination-sm center',
				'current' => $products->getCurrentPage(),
				'max' => $products->getMaxPage(),
				'root' => '/'.__CLASS__,
				'item_limit' => 28
			)))->render() );

			// Márkák
			$this->view->markak 	= $this->AdminUser->getMarkak( (int)$this->view->adm->user['ID'] );
			// Kategóriák
			$this->view->kategoria  = $this->AdminUser->getTermekKategoriak( (int)$this->view->adm->user['ID'] );

			// Készlet lista
			$this->view->keszlet 	= $this->AdminUser->getKeszletLista( (int)$this->view->adm->user['ID'] );
			// Szállításimód lista
			$this->view->szallitasMod 	= $this->AdminUser->getSzallitasModLista( (int)$this->view->adm->user['ID'] );
			// Szállításimód lista
			$fizmod = $this->AdminUser->getFizetesiModok( (int)$this->view->adm->user['ID'] );

			$nfizmod = array();
			foreach ( $fizmod as $fm ) {
				$nfizmod[$fm['ID']] = $fm;
			}
			$this->view->fizetesiMod = $nfizmod;
			// Szállítási idő lista
			$this->view->szallitas 	= $this->AdminUser->getSzallitasIdoLista( (int)$this->view->adm->user['ID'] );
			// Ár csoportok
			$this->view->price_groups = $price_groups;


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

		function clearfilters(){
			setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_cikkszam','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_szin','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_meret','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_lathato','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_fotermek','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_marka','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_szallitasID','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_keszletID','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
			Helper::reload('/termekek/');
		}

		function t(){

			$prodarg = array(
				'db' => $this->db,
				'authorid' => $this->view->adm->user['ID']
			);
			if ($this->view->adm->user['user_group'] != 'admin') {
				$prodarg['onlyauthor'] = true;
			}
			$products = new Products( $prodarg );

			switch($this->view->gets[2]){
				case 'del':
					if(Post::on('delTermId')){
						$this->AdminUser->delTermek($this->view->gets[3]);
						Helper::reload('/termekek/-/1');
					}
				break;
				case 'edit': case 'newedit':

					// Termék másolása

					if(Post::on('copyTermek')){
						try{
							$re = $this->AdminUser->copyTermek($_POST[tid],$_POST[copyNum]);
							$this->view->copyMsg	= Helper::makeAlertMsg('pSuccess', $re);
						}catch(Exception $e){
							$this->view->err 	= true;
							$this->view->copyMsg= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}

					// Termék alapadatok szerkesztése
					if(Post::on('saveTermek')){
						try{
							// Termék adatok mentése
							$save = $products->save( (new Product( array( 'db' => $this->db ) ))
								->setId( $this->view->gets[3] )
								->setData( array(
								'cikkszam' => $_POST['cikkszam'],
								'nev' => $_POST['nev'],
								'marka' => $_POST['marka'],
								'rovid_leiras' => $_POST['rovid_leiras'],
								'marketing_leiras' => $_POST['marketing_leiras'],
								'leiras' => $_POST['leiras'],
								'bankihitel_leiras' => $_POST['bankihitel_leiras'],
								'letoltesek' => $_POST['letoltesek'],
								'szallitas' => $_POST['szallitasID'],
								'allapot' => $_POST['keszletID'],
								'akcios' => ($_POST['akcios'] ? 1 : 0),
								'ujdonsag' => ($_POST['ujdonsag'] ? 1 : 0),
								'arukereso' => ($_POST['argep'] ? 1 : 0 ),
								'argep' => ($_POST['argep'] ? 1 : 0 ),
								'pickpackpont' => ($_POST['pickpackszallitas'] ? 1 : 0 ),
								'no_cetelem' => ($_POST['no_cetelem'] ? 1 : 0 ),
								'garancia' => ($_POST['garancia'] ?: 0),
								'cat' => $_POST['cat'],
								'szin' => $_POST['szin'],
								'meret' => $_POST['meret'],
								'fotermek' => ($_POST['fotermek'] == 'on' ? 1 : 0),
								'kiemelt' => ($_POST['kiemelt'] == 'on' ? 1 : 0),
								'ajanlorendszer_kiemelt' =>  ($_POST['ajanlorendszer_kiemelt'] == 'on' ? 1 : 0),
								'lathato' => ($_POST['lathato'] == 'on' ? 1 : 0),
								'ar_netto' => $_POST['netto_ar'],
								'ar_brutto' => $_POST['brutto_ar'],
								'ar_akcios_netto' => $_POST['akcios_netto_ar'],
								'ar_akcios_brutto' => $_POST['akcios_brutto_ar'],
								'raktar_keszlet' => $_POST['raktar_keszlet'],
								'linkek' => array(
									'nev' => $_POST['linkNev'],
									'url' => $_POST['linkUrl'],
								),
								'ar_by' => $_POST['ar_by'],
								'akcios_ar_by' => $_POST['akcios_ar_by'],
								'kulcsszavak' => $_POST['kulcsszavak'],
								'raktar_articleid' => $_POST['raktar_articleid'],
								'raktar_variantid' => $_POST['raktar_variantid'],
								'raktar_supplierid' => $_POST['raktar_supplierid'],
								'raktar_number' => $_POST['raktar_number'],
								'alapertelmezett_kategoria' => $_POST['alapertelmezett_kategoria'],
								'csoport_kategoria' => $_POST['csoport_kategoria'],
								'ajandek' => $_POST['ajandek'],
								'termek_site_url' => $_POST['termek_site_url'],
								'tudastar_url' => $_POST['tudastar_url'],
								'referer_price_discount' => $_POST['referer_price_discount'],
								'show_stock' => $_POST['show_stock'],
								'sorrend' =>  (isset($_POST['sorrend']) ? $_POST['sorrend'] : 100),
								'meta_title' => $_POST['meta_title'],
								'meta_desc' => $_POST['meta_desc']
							) ) );
							Helper::reload( '/termekek/t/edit/'.$this->view->gets[3].'/?backmsg=success&msg='.$save);
						} catch (Exception $e){
							$this->view->err 	= true;
							$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
						}
					}

					// Termék adatainak lekérése
					$this->out( 'products', $products );
					$this->out( 'termek', $products->get( $this->view->gets[3] ) );
					if ( $this->view->termek['related_products_ids'] ) {
						$termek_kapcsolatok = new Products( array( 'db' => $this->db ) );
						$termek_kapcsolatok = $termek_kapcsolatok->prepareList( array(
							'limit' => -1,
							'admin' => true,
							'filters' => array(
								'ID' => $this->view->termek['related_products_ids']
							)
						) )->getList();
						$kapcsolat_template = new Template( VIEW  . 'templates/' );
						$this->out(
							'kapcsolatok',
							$kapcsolat_template->get(
								'products_relatives',
								array(
									'list' 	=> $termek_kapcsolatok,
									'mode' 	=> 'remove',
									'id' 	=> $this->view->termek['ID']
								)
							)
						);
					}

					// Termék képek feltöltése
					if(Post::on('uploadImg')){
						$folder 	= '';
						$firstImg 	= false;
						$tid 		= $_POST[tid];

						$folder 	= ( $_POST['dir'] ) ?: 'src/products/p'.$this->view->gets[3];
						$firstImg 	= true;

						if(!file_exists($folder)){
							mkdir($folder,0777,true);
						}

						$tdata = $this->view->termek;

						try{
							$mt = explode(" ",str_replace(".","",microtime()));
							$imgName = Helper::makeSafeUrl($products->getManufacturName($tdata[marka]).'-'.$tdata[nev], '__'.date('YmdHis').$mt[0]);

							$rei = Images::upload(array(
								'src' 		=> 'img',
								'noRoot' 	=> true,
								'upDir' 	=> $folder,
								'fileName' 	=> $imgName,
								'makeThumbImg' => true,
								'makeWaterMark' => true,
								'maxFileSize' => 5150
							));
						}catch(Exception $e){
							echo $e->getMessage();
						}

						$upDir 		= $rei['dir'];
						$upProfil 	= $rei['file'];

						if ( count($rei['allUploadedFiles']) > 0 ) {
							$img = $this->db->query("SELECT profil_kep FROM shop_termekek WHERE ID = $tid;")->fetch(PDO::FETCH_ASSOC);

							if( $img['profil_kep'] == '' || !$img['profil_kep'] )
								if( $firstImg ) {
									$this->db->query("UPDATE shop_termekek SET kep_mappa = '{$upDir}', profil_kep = '$upProfil' WHERE ID = $tid;");
								}
						}

						if ( count($rei['allUploadedFiles']) > 0 ) {
							foreach($rei['allUploadedFiles'] as $kep){
								$products->addImageToProduct( $tid, $kep );
							}
						}

						Helper::reload();
					}

					// Termék paraméterek mentése
					if( Post::on('saveTermekParams') )
					{
						foreach($_POST['param'] as $pid => $pd){
							$v = $pd;

							if(is_null($v) || $v == ''){
								$this->db->query("DELETE FROM shop_termek_parameter WHERE termekID = {$_POST[tid]} and parameterID = $pid");
							}else{
								if($this->db->query("SELECT id FROM shop_termek_parameter WHERE termekID = {$_POST[tid]} and parameterID = $pid")->rowCount() > 0){
									$this->db->update('shop_termek_parameter',
									array(
										'ertek' => $v
									),
									"termekID = {$_POST[tid]} and parameterID = $pid and katID = {$_POST[kid]}");
								}else{
									$this->db->insert('shop_termek_parameter',
									array(
										'termekID' 		=> $_POST[tid],
										'katID' 		=> $_POST[kid],
										'parameterID' 	=> $pid,
										'ertek' 		=> addslashes($v)
									));
								}
							}

						}

						Helper::reload();
					}

					// Kategóriák
					$arg = array( 'db' => $this->db, 'authorid' => $this->view->adm->user['ID'] );
					if ($this->view->adm->user['user_group'] != 'admin') {
						$arg['onlyauthor'] = true;
					}
					$cats = new Categories( $arg );
					$this->out( 'categories', $cats->getTree() );
					// Márka lista
					$this->view->markak 	= $this->AdminUser->getMarkak((int)$this->view->adm->user['ID']);
					// Készlet lista
					$this->view->keszlet 	= $this->AdminUser->getKeszletLista((int)$this->view->adm->user['ID']);
					// Szállítási idő lista
					$this->view->szallitas 	= $this->AdminUser->getSzallitasIdoLista((int)$this->view->adm->user['ID']);

					$this->view->parameterek = $this->AdminUser->getParameterOnTermekKategoria($this->view->termek['alapertelmezett_kategoria']);

				break;
				case 'delListingFromKat':
					if(Post::on('delKatItemID')){
						$this->db->query("DELETE FROM shop_termek_in_kategoria WHERE ID = {$_POST[delKatItemID]}");
						Helper::reload('/termekek/t/edit/'.$this->view->gets[4]);
					}
				break;
			}
		}

		function upload_image()
		{

			$prodarg = array(
				'db' => $this->db,
				'authorid' => $this->view->adm->user['ID']
			);
			if ($this->view->adm->user['user_group'] != 'admin') {
				$prodarg['onlyauthor'] = true;
			}
			$products = new Products( $prodarg );

			$ids = explode("|", $this->view->gets[2]);
			$this->out( 'ids', $ids );


			// Termék képek feltöltése
			if(Post::on('upload')){
				$folder 	= '';


				$folder 	= ( $_POST['dir'] ) ?: 'src/products/upload_'.date('Ymd');
				$firstImg 	= true;

				if(!file_exists($folder)){
					mkdir($folder,0777,true);
				}

				$tdata = $this->view->termek;

				try{
					$mt = explode(" ",str_replace(".","",microtime()));
					$imgName = 'productimg__'.date('YmdHis').$mt[0];

					$rei = Images::upload(array(
						'src' 		=> 'img',
						'noRoot' 	=> true,
						'upDir' 	=> $folder,
						'fileName' 	=> $imgName,
						'maxFileSize' => 5150
					));
				}catch(Exception $e){
					echo $e->getMessage();
				}

				$upDir 		= $rei['dir'];
				$upProfil 	= $rei['file'];


				if ( count($rei['allUploadedFiles']) > 0 ) {
					foreach ( $ids as $sid ) {
						$firstImg 	= true;

				  		$products->removeImageFromProduct( $sid );
				  		foreach( $rei['allUploadedFiles'] as $kep ){
				  			if ( $firstImg ) {
				  				$products->setProfilImageToProduct( $sid, $kep );
				  				$firstImg = false;
				  			}
							$products->addImageToProduct( $sid, $kep );
						}
				  	}
				}

				Helper::reload('/termekek/upload_image/?msgkey=msg&msg=Képfeltöltés sikeres. A kiválasztott termékek képei frissítve lettek!');
			}

		}

		function szallitasi_mod(){

			if(Post::on('add')){
				try{
					$this->AdminUser->addSzallitasMod((int)$this->view->adm->user['ID'], $_POST);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('save')){
				try{
					$this->AdminUser->saveSzallitasMod($_POST, (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/szallitasi_mod/?msgkey=msg&msg=Változások sikeresen mentve lettek!');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->emsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('delId')){
				try{
					$this->AdminUser->delSzallitasMod($this->view->gets[3], (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/szallitasi_mod');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->emsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if($this->view->gets[2] == 'szerkeszt'){
				$this->view->sm = $this->AdminUser->getSzallitasModData($this->view->gets[3]);
			}


			$this->view->n = $this->AdminUser->getSzallitasiModok((int)$this->view->adm->user['ID']);
		}

		function fizetesi_mod(){

			if(Post::on('add')){
				try{
					$this->AdminUser->addFizetesiMod((int)$this->view->adm->user['ID'], $_POST);
					Helper::reload();
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('save')){
				try{
					$this->AdminUser->saveFizetesiMod((int)$this->view->adm->user['ID'], $_POST);
					Helper::reload('/termekek/fizetesi_mod');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('delId')){
				try{
					$this->AdminUser->delFizetesiMod($this->view->gets[3]);
					Helper::reload('/termekek/fizetesi_mod');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if($this->view->gets[2] == 'szerkeszt'){
				$this->view->sm = $this->AdminUser->getFizetesiModData($this->view->gets[3]);
			}


			$this->view->n = $this->AdminUser->getFizetesiModok((int)$this->view->adm->user['ID']);
		}

		function termek_allapotok(){

			if(Post::on('add')){
				try{
					$this->AdminUser->addTermekAllapot((int)$this->view->adm->user['ID'], $_POST);
					Helper::reload();
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('save')){
				try{
					$this->AdminUser->saveTermekAllapot($_POST, (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/termek_allapotok');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('delId')){
				try{
					$this->AdminUser->delTermekAllapot($this->view->gets[3], (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/termek_allapotok');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->msg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if($this->view->gets[2] == 'szerkeszt'){
				$this->view->sm = $this->AdminUser->getTermekAllapotData($this->view->gets[3]);
			}


			$this->view->n = $this->AdminUser->getFizetesiModok((int)$this->view->adm->user['ID']);
		}

		function szallitasi_ido(){
			if(Post::on('add')){
				try{
					$this->AdminUser->addSzallitasIdo((int)$this->view->adm->user['ID'], $_POST);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->bmsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('save')){
				try{
					$this->AdminUser->saveSzallitasIdo($_POST, (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/szallitasi_ido');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->emsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
			if(Post::on('delId')){
				try{
					$this->AdminUser->delSzallitasIdo($this->view->gets[3], (int)$this->view->adm->user['ID']);
					Helper::reload('/termekek/szallitasi_ido');
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->emsg 	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if($this->view->gets[2] == 'szerkeszt'){
				$this->view->sm = $this->AdminUser->getSzallitasIdoData($this->view->gets[3]);
			}

			$this->view->n = $this->AdminUser->getSzallitasIdoList((int)$this->view->adm->user['ID']);
		}

		function uj(){
			// Termék márkák
			$this->view->markak = $this->AdminUser->getMarkak((int)$this->view->adm->user['ID']);
			// Termékek
			$prodarg = array(
				'db' => $this->db,
				'authorid' => $this->view->adm->user['ID']
			);
			if ($this->view->adm->user['user_group'] != 'admin') {
				$prodarg['onlyauthor'] = true;
			}
			$products = new Products( $prodarg );

			// Kategória lista
			/*$categories = new Categories( false, array( 'db' => $this->db ) );
			$cat_tree 	= $categories->getTree();
			$this->out( 'categories', $cat_tree );*/

			// ÚJ termék
			if(Post::on('ujTermek')){
				try{
					//$re = $this->AdminUser->addTermek($_POST);
					$re = $products->create( (new Product( array( 'db' => $this->db ) ))->setData( array(
						'cikkszam' => $_POST['nagyker_kod'],
						'nev' => $_POST['nev'],
						'marka' => $_POST['marka'],
						'rovid_leiras' => $_POST['rovid_leiras'],
						'leiras' => $_POST['leiras'],
						'bankihitel_leiras' => $_POST['bankihitel_leiras'],
						'marketing_leiras' => $_POST['marketing_leiras'],
						'letoltesek' => $_POST['letoltesek'],
						'szallitas' => $_POST['szallitasID'],
						'allapot' => $_POST['keszletID'],
						'akcios' => ($_POST['akcios'] ? 1 : 0),
						'kiemelt' => ($_POST['kiemelt'] == 'on' ? 1 : 0),
						'ujdonsag' => ($_POST['ujdonsag'] ? 1 : 0),
						'arukereso' => ($_POST['argep'] ? 1 : 0 ),
						'argep' => ($_POST['argep'] ? 1 : 0 ),
						'pickpackpont' => ($_POST['pickpackszallitas'] ? 1 : 0 ),
						'no_cetelem' => ($_POST['no_cetelem'] ? 1 : 0 ),
						'garancia' => ($_POST['garancia_honap'] ? $_POST['garancia_honap'] : 0),
						'cat' => $_POST['cat'],
						'ar' => $_POST['ar'],
						'ar_netto' => $_POST['netto_ar'],
						'ar_brutto' => $_POST['brutto_ar'],
						'raktar_keszlet' => $_POST['raktar_keszlet'],
						'ar_akcios' => $_POST['akcios_ar'],
						'ar_akcios_netto' => $_POST['akcios_netto_ar'],
						'ar_akcios_brutto' => $_POST['akcios_brutto_ar'],
						'szin' => $_POST['szin'],
						'meret' => $_POST['meret'],
						'fotermek' => ($_POST['fotermek'] == 1 ? 1 : 0),
						'kulcsszavak' => $_POST['kulcsszavak'],
						'raktar_articleid' => $_POST['raktar_articleid'],
						'raktar_variantid' => $_POST['raktar_variantid'],
						'linkek' => array(
							'nev' => $_POST['linkNev'],
							'url' => $_POST['linkUrl'],
						),
						'connects' 			=> $_POST['productConnects'],
						'csoport_kategoria' => $_POST['csoport_kategoria'],
						'ajandek' 			=> $_POST['ajandek'],
						'termek_site_url' 	=> $_POST['termek_site_url'],
						'show_stock' 		=> $_POST['show_stock'],
						'referer_price_discount' => $_POST['referer_price_discount'],
						'sorrend' 			=>  (isset($_POST['sorrend']) ? $_POST['sorrend'] : 100)
					) ) );

					Helper::reload( '/termekek/uj/?backmsg=success&msg='.$re);
				}catch(Exception $e){
					$this->view->err 	= true;
					$this->view->bmsg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

		}

		function kepek()
		{
			$this->Admin = new Admin( false, array( 'db' => $this->db )  );

			if( Post::on('connectImagase') ){
				try {
					$this->Admin->autoProductImageConnecter();
					Helper::reload( '/termekek/kepek/?backmsg=success&msg=Képek frissítése megtörtént' );
				} catch (Exception $e) {
					$this->view->err 	= true;
					$this->view->msg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}
		}

		function import()
		{
			$this->Admin 			= new Admin( false, array( 'db' => $this->db )  );
			$this->uploaded_files 	= new FileLister( 'src/uploaded_files/' );

			// Online terméklista xml linkjének mentése
			if( Post::on('save_online_xml_url') ){
				try {
					$re = $this->Admin->save_product_xml_url( $_POST['import_online_xml'] );
					Helper::reload();
				} catch (Exception $e) {
					$this->view->err 	= true;
					$this->view->msg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if( Post::on('upload_xml') ) {
				if ( $_FILES['xml']['error'] == UPLOAD_ERR_OK ) {
					move_uploaded_file( $_FILES['xml']['tmp_name'], 'src/uploaded_files/'.$_FILES['xml']['name']);
					Helper::reload();
				}
			}

			if ( $_GET['checkurl']) {
				$header = @get_headers( $_GET['checkurl'], 1 );
				$header['header_code'] = substr( $header[0], 9, 3 );

				$this->out( 'url_result',  $header );
			}

			if ( $this->view->gets[2] == 'preview' ) {
				// Lista Típusa
				$ext = pathinfo( $_GET['file'], PATHINFO_EXTENSION );

				switch( $ext ) {
					case 'json':
						$json_result = json_decode( file_get_contents( $_GET['file'] ) );

						$articles = $json_result->parameters;

						/**
						 * JSON ELŐKÉSZÍTÉS
						 **/
						$repaired_articles = array();
						foreach ( $articles as $article ) {
							foreach ( $article->variants as $variant ) {
								$in = array();

								$in[articleid] = $article->article->articleid;
								$in[name] = $article->article->name;
								$in[number] = $article->article->number;
								$in[supplier_articlenumber] = $article->article->supplier_articlenumber;
								$in[description] = $article->article->description;
								$in[variantid] = $variant->variantid;
								$in[color_number] = $variant->color_number;
								$in[color_name] = $variant->color_name;
								$in[size_name] = $variant->size;
								$in[netprice] = $variant->netprice;
								$in[grossprice] = $variant->grossprice;

								$cats = array();
								if( count( $article->categories ) > 0){
									foreach ( $article->categories as $cat ) {
										$cats[] = $cat->category;
									}
								}
								$in[categories] = $cats;

								$repaired_articles[] = $in;
							}
						}

						$this->out( 'xml_result',  $repaired_articles );
						$this->out( 'xml_import_check', $this->Admin->checkImportProducts( $this->view->xml_result ) );

					break;
					case 'xml':
						$this->XMList = new XMLParser( $_GET['file'] );
						$this->out( 'xml_result',  $this->XMList->getResult() );
						$this->out( 'xml_import_check', $this->Admin->checkImportProducts( $this->view->xml_result ) );
					break;

				}
			}

			$this->out( 'uploaded_files', $this->uploaded_files );

			// Import művelet
			if ( Post::on('action_do') ) {
				try {
					// Új termékek importálása
					if ( Post::on('create_new_products') ) {
						$this->Admin->importProducts( $this->view->xml_import_check, array( 'mode' => 'create' ) );
					}
					// Frissítendő termékek frissítése
					if ( Post::on('only_update_products') ) {
						$this->Admin->importProducts( $this->view->xml_import_check, array( 'mode' => 'update' ) );
					}

					//Helper::reload('/termekek/import/?msgkey=msg&msg=Importálás műveletek sikeresen befejeződtek!');

				} catch ( Exception $e ) {
					$this->view->err 	= true;
					$this->view->msg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}

			}
		}

		function nagyker_arlista(){

			if( Post::on('uploadList') ){
				try {
					$re = $this->AdminUser->uploadNagykerLista();
					Helper::reload();
				} catch (Exception $e) {
					$this->view->err 	= true;
					$this->view->bmsg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			if( Post::on('addNagyker') ){
				try {
					$re = $this->AdminUser->addNagyker($_POST);
					Helper::reload();
				} catch (Exception $e) {
					$this->view->err 	= true;
					$this->view->bmsg	= Helper::makeAlertMsg('pError', $e->getMessage());
				}
			}

			// Nagyker árlista
			$this->view->arlista = $this->AdminUser->getNagykerArlista();

			if( $_GET[path] != '' )
			$this->view->csv = CSVParser::GET( $_GET[path] );
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
