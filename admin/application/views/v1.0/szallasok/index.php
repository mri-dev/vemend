<div  ng-app="Szallasok" ng-controller="Szallas" ng-init="init(<?=($this->adm->user['user_group'] != 'admin')?$this->adm->user['ID']:'0'?>)">
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
					<div class="alert alert-warning" ng-show="savingszallas">
						Szállás adatainak mentése <i class="fa fa-spin fa-spinner"></i>
					</div>
					<div class="row-neg" ng-hide="savingszallas">
						<div class="row">
							<div class="col-md-12">
								<input type="file" id="profil" file-model="profil">
								{{profilpreview}}
								<img ng-src="{{profilpreview}}" alt="">

								<label for="title">Szállás elnevezése *</label>
								<input type="text" id="title" ng-model="create.title" class="form-control">
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
								<button type="button" class="btn btn-default" ng-click="resetSzallas()"> Mégse </button>
		            <button type="button" ng-show="create.id==0 && (create.title && create.cim)" class="btn btn-primary" ng-click="saveSzallas()"> Szállás hozzáadása <i class="fa fa-plus-circle"></i> </button>
		            <button type="button" ng-show="create.id!=0 && (create.title && create.cim)" class="btn btn-success" ng-click="saveSzallas()"> Szállás módosítása <i class="fa fa-save"></i> </button>
		          </div>
						</div>
					</div>
		    </div>
		  </div>
		  <div class="" ng-class="(creating)?'col-md-5':'col-md-12'">
		    <div class="con">
		      <table class="table szallas-list">
		        <thead>
		          <tr>
		            <th ng-show="!creating" width="120" class="center">Kép</th>
		            <th>Szállás</th>
		            <th ng-show="!creating"  width="80" class="center">Aktív</th>
		            <th ng-show="!creating||(creating && editing)" width="80" class="center"></th>
		          </tr>
		        </thead>
						<tbody>
							<tr ng-repeat="szallas in szallasok" ng-class="(create.id==szallas.ID)?'selected':''">
								<td ng-show="!creating" ></td>
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
								<td ng-show="!creating"  class="center">
									<span ng-show="(szallas.aktiv=='1')"><i class="fa fa-check"></i></span>
									<span ng-hide="(szallas.aktiv=='1')"><i class="fa fa-times"></i></span>
								</td>
								<td ng-show="!creating||(creating && editing)" class="actions center">
									<i class="fa fa-pencil" ng-click="pickSzallas(szallas)" title="Szerkesztés"></i>
								</td>
							</tr>
						</tbody>
		      </table>
		    </div>
		  </div>
		</div>
	</div>
</div>
