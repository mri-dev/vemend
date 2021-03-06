<div ng-app="Szallasok" ng-controller="Szallas" ng-init="init(<?=($this->adm->user['user_group'] != 'admin')?$this->adm->user['ID']:'0'?>, '<?=http_build_query($_GET)?>')">
	<div style="float:right;">
		<a href="javascript:void(0);" ng-click="creatingSwitch()" ng-hide="creating && !editing" class="btn btn-info"><i class="fa fa-home"></i> új szállás rögzítése</a>
	</div>
	<h1>Szállások</h1>
	<div class="" ng-show="baseMsg.msg">
		<div class="alert alert-{{baseMsg.type}}" ng-bind-html="baseMsg.msg|html">

		</div>
	</div>
	<div class="row-neg">
		<div class="row szallas-modul">
		  <div class="col-md-7" ng-show="creating">
		    <div class="con" ng-class="(create.id!=0)?'con-edit':''">
					<h3 ng-show="(create.id==0)">Új szállás rögzítése</h3>
					<h3 ng-hide="(create.id==0)">"{{create.title}}" szállás szerkesztése</h3>
					<div class="tab-header" ng-show="(create.id!=0)">
						<ul>
							<li ng-repeat="tab in tabs" ng-click="switchTab(tab.name)" ng-class="(editing_page==tab.name)?'active':''">{{tab.title}}</li>
						</ul>
					</div>
					<div class="tab-block" ng-show="(editing_page=='general')">
						<div class="alert alert-warning" ng-show="savingszallas">
							Szállás adatainak mentése <i class="fa fa-spin fa-spinner"></i>
						</div>
						<div class="alert alert-warning" ng-show="uploadingimages">
							<i class="fa fa-photo"></i> Szállás képének feltöltése folyamatban <i class="fa fa-spin fa-spinner"></i>
						</div>
						<div class="row-neg" ng-hide="savingszallas">
							<div class="row">
								<div class="col-md-12">
									<label>Profilkép</label>
									<div class="clr"></div>
									<div class="uploaded-profilkep" ng-show="create.kep">
										<h4>Aktuális profilkép:</h4>
										<img ng-src="{{currentProfilkep.filepath}}" alt="">
									</div>
									<div class="uploader" ng-hide="create.kep">
										<input type="file" class="lab-selector" id="profil" file-model="profil">
										<label for="profil">
											<div class="img" ng-show="profilpreview">
												<img ng-src="{{profilpreview}}" alt="{{selectedprofilimg.name}}" class="preview">
											</div>
											<strong>Profilkép feltöltéséhez kattintson ide.</strong>
											<div class="allows">Engedélyezett méret: max. 2 MB. Fájlformátumok: jpg, jpeg, png.</div>
											<div class="selected-image-data" ng-show="selectedprofilimg.size">
												<h4>Kiválaszott kép adatai:</h4>
												Fájlformátum: <strong ng-class="(!selectedprofilimg.typecorrect)?'uncorrect':''">{{selectedprofilimg.type}}</strong><br>
												Fájlméret: <strong ng-class="(!selectedprofilimg.sizecorrect)?'uncorrect':''">{{selectedprofilimg.size|number}} KB</strong>
											</div>
											<div class="clr"></div>
										</label>
										<div class="" ng-show="profilpreview && !selectedprofilimg.typecorrect">
											<div class="alert alert-danger">
												A kép formátuma nem megfelelő! Csak az engedélyezett fájlformátumú képek tölthetőek fel!
											</div>
										</div>
										<div class="" ng-show="profilpreview && !selectedprofilimg.sizecorrect">
											<div class="alert alert-danger">
												A kép fájmérete nem megfelelő! Túl nagy fájlt szeretne feltölteni.
											</div>
										</div>
									</div>
									<div class="clr"></div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-10">
									<label for="title">Szállás elnevezése *</label>
									<input type="text" id="title" ng-model="create.title" class="form-control">
								</div>
								<div class="col-md-2">
									<label for="aktiv">Aktív szállás</label>
									<input type="checkbox" id="aktiv" ng-model="create.aktiv" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="cim">Szállás címe * (Irányítószám Város, utca házszám)</label>
									<input type="text" id="cim" ng-model="create.cim" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="cim_sub">Cím kiegészítő megjegyzés - listázásban jelenik meg (pl: 1 km-re a központtól, 50 méterre a fürdőtől, stb.)</label>
									<input type="text" maxlength="60" id="cim_sub" ng-model="create.cim_sub" class="form-control">
									<small>60 / {{60-create.cim_sub.length}}</small>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="kiemelt_szoveg">Kiemelt kiegészítő megjegyzés - listázásban jelenik meg (pl: a szállás SZÉP kártyát is elfogad.)</label>
									<input type="text" maxlength="60" id="kiemelt_szoveg" ng-model="create.kiemelt_szoveg" class="form-control">
									<small>60 / {{60-create.kiemelt_szoveg.length}}</small>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<label for="contact_email">Kapcsolat e-mail cím</label>
									<input type="text" id="contact_email" ng-model="create.contact_email" class="form-control">
								</div>
								<div class="col-md-6">
									<label for="contact_phone">Kapcsolat telefonszám</label>
									<input type="text" id="contact_phone" ng-model="create.contact_phone" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<label for="bejelentkezes">Bejelentkezés (pl.: 13:00-17:00)</label>
									<input type="text" id="bejelentkezes" ng-model="create.bejelentkezes" class="form-control">
								</div>
								<div class="col-md-6">
									<label for="kijelentkezes">Kijelentkezés xx:xx-ig (pl.: 10:00)</label>
									<input type="text" id="kijelentkezes" ng-model="create.kijelentkezes" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<label for="ifa">Idegenforgalmi adó mértéke (Ft / fő / éj)</label>
									<input type="number" id="ifa" ng-model="create.ifa" class="form-control">
								</div>
								<div class="col-md-2">
									<label for="kisallat">Kisállat hozható</label>
									<input type="checkbox" id="kisallat" ng-model="create.kisallat" class="form-control">
								</div>
								<div class="col-md-4">
									<label for="kisallat_dij">Kisállat pótdíj</label>
									<input type="number" id="kisallat_dij" ng-model="create.kisallat_dij" class="form-control">
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-6">
									<label for="ifa">Választható ellátások</label>
									<md-select ng-model="create.ellatasok" placeholder="Elérhető ellátások" multiple="true">
								    <md-option ng-value="ellatas.ID" ng-model="create.ellatasok" ng-repeat="ellatas in terms.ellatas">{{ellatas.name}}</md-option>
								  </md-select>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="leiras">Leírás</label>
									<textarea ui-tinymce="tinymceOptions" ng-model="create.leiras" class="no-editor" id="leiras" ></textarea>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="lemondas">Lemondási feltételek, tájékoztató</label>
									<textarea ui-tinymce="tinymceOptions" ng-model="create.lemondas" class="no-editor" id="lemondas" ></textarea>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="fizetes">Fizetési feltételek, tájékoztató</label>
									<textarea ui-tinymce="tinymceOptions" ng-model="create.fizetes" class="no-editor" id="fizetes" ></textarea>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="elorefizetes">Előrefizetési tájékoztató</label>
									<textarea ui-tinymce="tinymceOptions" ng-model="create.elorefizetes" class="no-editor" id="elorefizetes" ></textarea>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<label for="gyerek_potagy">Kisgyerek és pótágyazási tájékoztató</label>
									<textarea ui-tinymce="tinymceOptions" ng-model="create.gyerek_potagy" class="no-editor" id="gyerek_potagy" ></textarea>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12 right">
									<div ng-show="!cansavenow">
										<div class="alert alert-danger">
											A szállás jelenleg nem menthető. Az adatlap kitöltése során valahol hiba áll fent, vagy nem megfelelően lett kitöltve!
										</div>
									</div>
									<button type="button" class="btn btn-default" ng-click="resetSzallas()"> Mégse </button>
			            <button type="button" ng-show="cansavenow &&create.id==0 && (create.title && create.cim)" class="btn btn-primary" ng-click="saveSzallas()"> Szállás hozzáadása <i class="fa fa-plus-circle"></i> </button>
			            <button type="button" ng-show="cansavenow && create.id!=0 && (create.title && create.cim)" class="btn btn-success" ng-click="saveSzallas()"> Szállás módosítása <i class="fa fa-save"></i> </button>
			          </div>
							</div>
						</div>
					</div>

					<div class="tab-block" ng-show="(editing_page=='rooms')">
						<div class="rnav">
							<span ng-show="!roomediting" style="color: #8ab160; cursor: pointer;" ng-click="toggleVar('roomediting', true)">szerkesztő mód bekapcsolása <i class="fa fa-pencil"></i></span>
							<span ng-hide="!roomediting" style="color: #e69a9a; cursor: pointer;" ng-click="toggleVar('roomediting', false)">szerkesztő mód kikapcsolása <i class="fa fa-ban"></i></span>
						</div>
						<h3>Szobák</h3>
						<div class="clr"></div>
						<div ng-show="(create.rooms.length==0)">
							Jelenleg nincs létrehozott szoba.
						</div>
						<div class="rooms" ng-show="create.rooms">
							<div class="room" ng-repeat="room in create.rooms" ng-class="(!room.ID)?'isnew':''">
								<div class="wrapper">
									<div class="data">
										<div class="title">{{room.name}}</div>
										<div class="desc">{{room.leiras}}</div>
									</div>
									<div class="adult-cap">
										max. {{room.felnott_db}} felnőtt
									</div>
									<div class="child-cap">
										<span ng-show="(room.gyermek_db>0)">max.</span> {{room.gyermek_db}} gyermek
									</div>
									<div class="status">
										<span ng-show="room.elerheto" title="Jelenleg aktív szoba."><i class="fa fa-check"></i></span>
										<span ng-show="!room.elerheto" title="Jelenleg inaktív szoba."><i class="fa fa-ban"></i></span>
									</div>
								</div>
								<div class="editor-row" ng-show="!room.ID || roomediting">
									<div class="head">
										Szoba adatainak szerkesztése
									</div>
									<div class="wrapper">
										<div class="data">
											<div class="title"><input type="text" ng-model="room.name" placeholder="Szoba elnevezése"></div>
											<div class="desc"><input type="text" ng-model="room.leiras" placeholder="Szoba rövid leírása"></div>
										</div>
										<div class="adult-cap">
											<input type="number" ng-model="room.felnott_db" min="0" step="1">
											Felnőtt férőhely
										</div>
										<div class="child-cap">
											<input type="number" ng-model="room.gyermek_db" min="0" step="1">
											Gyermek férőhely
										</div>
										<div class="status">
											Elérhető? <br>
											<input type="checkbox" ng-model="room.elerheto">
										</div>
									</div>
								</div>
								<div class="prices">
									<div class="head">
										Szoba árképzési díjak ellátások szerint
									</div>
									<div class="ellatas-price" ng-repeat="el in create.ellatasok">
										<div class="wrapper">
											<div class="ellatas">
												{{findTermByID('ellatas', el, 'name')}}
											</div>
											<div class="prices">
												<div class="price-set">
													<div class="adult">
														Felnőtt ár:
														<div class="price" ng-class="(room.arak[el].adult)?'setted':''">
															{{room.arak[el].adult}} <span ng-show="(room.arak[el].adult)">Ft / fő</span> <span ng-hide="(room.arak[el].adult)">N/A</span>
														</div>
														<div class="edit-field" ng-show="!room.ID || roomediting">
															<input type="number" ng-model="room.arak[el].adult">
														</div>
													</div>
													<div class="child">
														Gyermek ár:
														<div class="price" ng-class="(room.arak[el].children)?'setted':''">
															{{room.arak[el].children}} <span ng-show="(room.arak[el].children)">Ft / fő</span> <span ng-hide="(room.arak[el].children)">N/A</span>
														</div>
														<div class="edit-field" ng-show="!room.ID || roomediting">
															<input type="number" ng-model="room.arak[el].children">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="" ng-show="roomsaving">
							<div class="alert alert-warning">
								Szobák konfigurációjának mentése folyamatban <i class="fa fa-spin fa-spinner"></i>
							</div>
						</div>
						<div class="row-neg" ng-hide="roomsaving">
							<div class="row">
								<div class="col-md-6 left">
									<button type="button" class="btn btn-default" ng-click="addRooms()"><i class="fa fa-plus"></i> új szoba</button>
								</div>
								<div class="col-md-6 right">
									<button type="button" class="btn btn-success" ng-click="saveRooms(create.ID, create.rooms)">Szobák adatainak mentése <i class="fa fa-save"></i> </button>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-block" ng-show="(editing_page=='pictures')">
						<?php if (true): ?>
						<div class="uploader">
							<input type="file" class="lab-selector" id="imagesuploader" image-uploader="profil" multiple="multiple">
							<label for="imagesuploader">
								<strong>Képek feltöltéséhez kattintson ide.</strong>
								<div class="allows">Engedélyezett méret: max. 2024 KB. Fájlformátumok: jpg, jpeg, png.</div>
								<div class="clr"></div>
							</label>
							<div class="uploading-image-dataset" ng-show="selectedUploadingImages.length!=0">
								<h3>Újonnan feltöltendő képek ({{selectedUploadingImages.length}}):</h3>
								<div class="picture" ng-class="(img.correct_extension && img.correct_size)?'':'unable-to-upload'" ng-repeat="img in selectedUploadingImages" >
									<div class="wrapper">
										<div class="image">
											<img src="{{img.preview}}" alt="">
										</div>
										<div class="data">
											<div class="title">
												<strong>{{img.name}}</strong>
											</div>
											<div class="size" ng-hide="img.uploaded" ng-class="(img.correct_size)?'correct-val':'incorrect-val'">
												Méret: <strong>{{img.size|number}} KB</strong>
											</div>
											<div class="size" ng-hide="img.uploaded" ng-class="(img.correct_extension)?'correct-val':'incorrect-val'">
												Kiterjesztés: <strong>{{img.type}}</strong>
											</div>
											<div class="uploaded-msg"  ng-show="img.uploaded===true">
												<i class="fa fa-check-circle-o"></i> Kép feltöltve.
											</div>
											<div class="uploaded-msg msg-error"  ng-show="img.uploaded!==true&&img.uploaded!==false">
												<i class="fa fa-times"></i> {{img.uploaded}}
											</div>
										</div>
									</div>
								</div>
								<div class="alert alert-warning" ng-show="uploadingimages">
									<i class="fa fa-spin fa-spinner"></i> Képek feltöltése folyamatban.
								</div>
								<div class="upload-button" ng-show="selectedUploadingImages.length!=0" ng-hide="uploadingimages">
									<button type="button" ng-click="uploadImages(create.id)" class="btn btn-default" name="button">Képek feltöltése <i class="fa fa-upload"></i></button>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="clr"></div>
						<div class="uploaded-images">
							<div class="rnav">
								<div style="color: #8ab160; cursor: pointer;"  ng-show="!imageediting" ng-click="toggleVar('imageediting', true)">
									Képszerkesztő bekapcsolása <i class="fa fa-pencil"></i>
								</div>
								<div style="color: #e69a9a; cursor: pointer;" ng-hide="!imageediting" ng-click="toggleVar('imageediting', false)">
									Képszerkesztő kikapcsolása <i class="fa fa-ban"></i>
								</div>
							</div>
							<h3>Feltöltött képek</h3>
							<div class="" ng-show="create.pictures.length==0">
								Jelenleg nincsenek feltöltött képek!
							</div>
							<div class="wrapper">
								<div class="image" ng-repeat="img in create.pictures">
									<div class="wrapper">
										<div class="img-wrapper">
											<div class="profilkep" ng-show="img.profilkep">
												<i class="fa fa-check-circle-o"></i> Profilkép
											</div>
											<img src="{{img.filepath}}" alt="{{img.cim}}">
										</div>
										<div class="info" ng-show="!imageediting">
											<div class="ext">
												{{img.kiterjesztes}}
											</div>
											<div class="size">
												{{img.filemeret}} KB
											</div>
										</div>
										<div class="info editor" ng-hide="!imageediting">
											<div class="profilkep">
												<input type="radio" name="profimg" id="cb_prof_i{{img.ID}}" ng-checked="(img.ID == currentProfilkep.ID)" ng-click="toggleVar('currentProfilkep', img)"> <label for="cb_prof_i{{img.ID}}">profilkép</label>
											</div>
											<div class="deleting">
												<label for="cb_del_i{{img.ID}}">törlés</label> <input class="deletingImageCb" type="checkbox" value="{{img.ID}}" id="cb_del_i{{img.ID}}">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div ng-show="imageediting">
								<br>
								<button type="button" ng-click="imageEditing()" class="btn btn-success">Képek mentése <i ng-hide="!imageeditprogress" class="fa fa-spin fa-spinner"></i> <i ng-show="!imageeditprogress" class="fa fa-save"></i></button>
							</div>
						</div>
					</div>

					<div class="tab-block" ng-show="(editing_page=='services')">
						<div class="rnav">
							<span ng-show="!serviceediting" style="color: #8ab160; cursor: pointer;" ng-click="toggleVar('serviceediting', true)">szerkesztő mód bekapcsolása <i class="fa fa-pencil"></i></span>
							<span ng-hide="!serviceediting" style="color: #e69a9a; cursor: pointer;" ng-click="toggleVar('serviceediting', false)">szerkesztő mód kikapcsolása <i class="fa fa-ban"></i></span>
						</div>
						<h3>Szolgáltatások</h3>
						<div class="services">
							<div class="service-group" ng-repeat="(group, services) in create.services">
								<h4>{{group}}</h4>
								<div class="service-list-group">
									<div class="" ng-repeat="service in services | filter:{szallas_id: create.ID}">
										<div class="value" ng-hide="serviceediting">
											{{service.title}}
										</div>
										<div class="edit-field" ng-show="serviceediting">
											<input type="text" ng-model="service.title">
											<input class="ico-label-cb" id="delserv{{service.ID}}" type="checkbox" ng-model="service.delete"> <label title="Érték törtlése" for="delserv{{service.ID}}"><i class="fa fa-trash"></i></label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="service-adder" ng-repeat="newservice in servicecreator">
							<div class="title">+ új szolgáltatás rögzítése</div>
							<div class="wrapper">
								<div class="row-neg">
									<div class="row">
										<div class="col-md-6">
											<label for="">Kategória</label>
											<md-autocomplete
							          md-items="item in serviceQuerySearch(serviceKategoriaSearch)"
												md-selected-item="newservice.kategoria"
												md-search-text-change="newservice.kategoria=serviceKategoriaSearch"
							          md-item-text="item"
												md-search-text="serviceKategoriaSearch"
							          md-min-length="0"
	          						placeholder="Szolgáltatás kategóriája">
												<md-item-template>
								          <span md-highlight-text="serviceKategoriaSearch" md-highlight-flags="^i">{{item}}</span>
								        </md-item-template>
										</div>
										<div class="col-md-6">
											<label for="">Elnevezés</label>
											<input type="text" ng-model="newservice.title">
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="" ng-show="servicessaving">
							<div class="alert alert-warning">
								Szolgáltatások mentése folyamatban <i class="fa fa-spin fa-spinner"></i>
							</div>
						</div>
						<div class="row-neg" ng-hide="servicessaving">
							<div class="row">
								<div class="col-md-6 left">
									<button type="button" class="btn btn-default" ng-click="addService()"><i class="fa fa-plus"></i> új szolgáltatás</button>
								</div>
								<div class="col-md-6 right">
									<button type="button" class="btn btn-success" ng-click="saveService(create.ID, servicecreator)">Szolgáltatások adatainak mentése <i class="fa fa-save"></i> </button>
								</div>
							</div>
						</div>
					</div>

		    </div>
		  </div>
		  <div class="" ng-class="(creating)?'col-md-5':'col-md-12'">
		    <div class="">
					<div class="table-filter">
						<div class="row-neg">
							<div class="row">
								<div class="col-md-9"></div>
								<div class="col-md-3 right">
									<input type="text" ng-model="filter.name" class="form-control" placeholder="Gyors keresés...">
								</div>
							</div>
						</div>
					</div>
		      <table class="table szallas-list">
		        <thead>
		          <tr>
		            <th ng-show="!creating" width="80" class="center">Kép</th>
		            <th>Szállás</th>
								<?php if ($this->adm->user['user_group'] == 'admin'): ?>
								<th>
									Felhasználó
								</th>
								<?php endif; ?>
		            <th ng-show="!creating"  width="80" class="center">Aktív</th>
		            <th ng-show="!creating||(creating && editing)" width="80" class="center"></th>
		          </tr>
		        </thead>
						<tbody>
							<tr ng-repeat="szallas in szallasok | szallassearcher: filter" ng-class="(create.id==szallas.ID)?'selected':''">
								<td ng-show="!creating" >
									<div class="image">
										<img ng-src="{{szallas.profilkep}}" alt="{{szallas.title}}">
									</div>
								</td>
								<td class="details">
									<div class="name">
										{{szallas.title}}
									</div>
									<div class="address">
										{{szallas.cim}}
									</div>
									<div class="contacts">
										<span class="email"><i class="fa fa-envelope-o"></i> {{szallas.contact_email}}</span>
										<span class="phone"><i class="fa fa-phone"></i> {{szallas.contact_phone}}</span>
									</div>
								</td>
								<?php if ($this->adm->user['user_group'] == 'admin'): ?>
								<td>
									<div class="author-data">
										<div class=""><strong><a href="#">{{szallas.author_data.nev}}</a></strong> (#{{szallas.author_data.ID}})</div>
										<div class="">
											<span class="email"><i class="fa fa-envelope-o"></i> {{szallas.author_data.email}}</span>
											<span class="phone"><i class="fa fa-phone"></i> {{szallas.author_data.szallitas_phone}}</span>
										</div>
									</div>
								</td>
								<?php endif; ?>
								<td ng-show="!creating"  class="center">
									<span ng-show="(szallas.aktiv=='1')"><i class="fa fa-check"></i></span>
									<span ng-hide="(szallas.aktiv=='1')"><i class="fa fa-times"></i></span>
								</td>
								<td ng-show="!creating||(creating && editing)" class="actions center">
									<i class="fa fa-pencil" ng-click="pickSzallas(szallas)" title="Szerkesztés"></i>
								</td>
							</tr>
							<tr ng-show="(loadinglist)">
								<td colspan="10" class="center">
									<div class="loader">
										<i class="fa fa-spin fa-spinner"></i> <br>
										Szállások betöltése folyamatban...
									</div>
								</td>
							</tr>
							<tr ng-show="(szallasok.length==0 && !loadinglist && listloaded)">
								<td colspan="10" class="center">
									<div class="no-data">
										<i class="fa fa-times-circle"></i> <br>
										Nincs találat.
									</div>
								</td>
							</tr>
						</tbody>
		      </table>
		    </div>
		  </div>
		</div>
	</div>
</div>
