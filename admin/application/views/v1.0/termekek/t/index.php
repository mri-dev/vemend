<? if($this->gets[2] == 'del'): ?>
<form action="" method="post">
	<input type="hidden" name="delTermId" value="<?=$this->gets[3]?>" />
	<div class="row">
		<div class="col-md-12">
	    	<div class="panel panel-danger">
	        	<div class="panel-heading">
	            <h2><i class="fa fa-times"></i> Termék törlése</h2>
	            </div>
	        	<div class="panel-body">
	            	<div style="float:right;">
	                	<a href="/termekek/-/1" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
	                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
	                </div>
	            	<strong>Biztos, hogy törli a terméket?</strong>
	            </div>
	        </div>
	    </div>
	</div>
</form>
<? elseif($this->gets[2] == 'delListingFromKat'):?>
<form action="" method="post">
	<input type="hidden" name="delKatItemID" value="<?=$this->gets[3]?>" />
	<div class="row">
		<div class="col-md-12">
	    	<div class="panel panel-danger">
	        	<div class="panel-heading">
	            <h2><i class="fa fa-times"></i> Termék listázás törlése</h2>
	            </div>
	        	<div class="panel-body">
	            	<div style="float:right;">
	                	<a href="/termekek/t/edit/<?=$this->gets[4]?>" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
	                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
	                </div>
	            	<strong>Biztos, hogy törli a termék kategória listázását?</strong>
	            </div>
	        </div>
	    </div>
	</div>
</form>

<? elseif($this->gets[2] == 'edit'): ?>
<div style="float:right;">
	<a href="/termekek/-/1" class="btn btn-default btn-2x"><i class="fa fa-arrow-left"></i> mégse</a>
	<a href="/termekek/uj" class="btn btn-info"><i class="fa fa-plus"></i> új termék</a>
</div>
<h1>Termék szerkesztés</h1>
<?=$this->bmsg?>
<div class="clr"></div>
<div class="editForm create-product">
	<div class="row">
		<form action="" method="post" role="form">

		<div class="col-md-12 np">
			<div class="con">
				<div class="row np">
					<div class="col-md-12" align="right">
						<button class="btn btn-success btn-2x" name="saveTermek">Változások mentése <i class="fa fa-save"></i></button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-8" style="padding-left:0;">
				<input type="hidden" name="tid" value="<?=$this->termek[ID]?>">
				<div class="con">
					<div class="checkins">
						<div class="">
							<input type="checkbox" id="lathato" name="lathato" <?=($this->termek['lathato'] == 1)?'checked':''?>/> <label for="lathato">Aktív / Látható</label>
						</div>
						<div class="">
							<input type="checkbox" name="akcios" id="akciosTgl" onclick="javascript:if($(this).is(':checked')){$('#vakcios').show(0);}else{$('#vakcios').hide(0);}" <?=($this->termek['akcios'] == 1)?'checked':''?>  /> <label for="akciosTgl">Akciós</label>
						</div>
						<div class="">
							 <input type="checkbox" name="ujdonsag" id="ujdonsag"  <?=($this->termek['ujdonsag'] == 1)?'checked':''?>/> <label for="ujdonsag">Újdonság</label>
						</div>
						<?php if (false): ?>
						<div class="">
							<input type="checkbox" name="argep" id="argep" <?=($this->termek['argep'] == 1)?'checked':''?>/> <label for="argep">ÁRGÉP listába</label>
						</div>
						<div class="">
							<input type="checkbox" name="arukereso" id="arukereso" <?=($this->termek['arukereso'] == 1)?'checked':''?>/> <label for="arukereso">ÁRUKERESŐ listába</label>
						</div>
						<?php endif; ?>
						<div class="">
							<input type="checkbox" <?=($this->termek['pickpackszallitas'] == 1)?'checked':''?> name="pickpackszallitas" id="pickpackszallitas" /> <label for="pickpackszallitas">Pick Pack Pont-ra szállítható</label>
						</div>
						<?php if (false): ?>
						<div class="" style="display: none;">
							<input type="checkbox" <?=($this->termek['no_cetelem'] == 1)?'checked':''?> name="no_cetelem" id="no_cetelem" /> <label for="no_cetelem">Cetelem hitel alól KIZÁRVA</label>
						</div>
						<?php endif; ?>
						<div class="">
							<input type="checkbox" <?=($this->termek['kiemelt'] == 1)?'checked':''?> name="kiemelt" id="kiemelt" /> <label for="kiemelt">Kiemelt termék</label>
						</div>
						<div class="">
							<input type="checkbox" <?=($this->termek['show_stock'] == 1)?'checked':''?> name="show_stock" id="show_stock" /> <label for="show_stock">Készletmegjelenítés</label>
						</div>
					</div>
				</div>

				<div class="con">
					<h3>Termék törzsadatok</h3>
					<div class="row">
						<div class="col-md-6">
							<label for="raktar_articleid">Típus azonosító törzskód</label>
							<input type="text" name="raktar_articleid" id="raktar_articleid" value="<?=$this->termek[raktar_articleid]?>" class="form-control">
						</div>
						<div class="col-md-6">
							<label for="raktar_variantid">Variáció azonosító</label>
							<input type="text" name="raktar_variantid" id="raktar_variantid" value="<?=$this->termek[raktar_variantid]?>" class="form-control">
						</div>
					</div>
				</div>

				<?php if (false): ?>
				<div class="con">
					<div class="row">
						<h3>Termék ár</h3>
						<div class="col-md-6">
							<div>
								<label for="ar_by">Eredeti ár</label>
								<select name="ar_by" id="ar_by" class="form-control">
									<option value="">-- válasszon: módosítás mint --</option>
									<option value="netto">Nettó ár</option>
									<option value="brutto">Bruttó ár</option>
								</select>
								<br />
								<div class="input-group col-md-12">
									<input type="text" name="netto_ar" value="<?=$this->termek[netto_ar]?>" class="form-control">
									<span class="input-group-addon">nettó ár</span>
								</div>
								<br />
								<div class="input-group col-md-12">
									<input type="text" name="brutto_ar" value="<?=$this->termek[brutto_ar]?>" class="form-control">
									<span class="input-group-addon">bruttó ár</span>
									<span class="input-group-addon">
									= <strong title="Fogyasztói ár"><?=Helper::cashFormat($this->termek[ar])?> Ft</strong>
									(<?=number_format(($this->termek[ar] - $this->termek[brutto_ar]) / ($this->termek[ar] / 100),2,"."," ")?>%)
									</span>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div>
								<label for="akcios_ar_by">Akciós ár</label>
								<select name="akcios_ar_by" class="form-control">
									<option value="">-- válasszon: módosítás mint --</option>
									<option value="netto">Nettó ár</option>
									<option value="brutto">Bruttó ár</option>
								</select>
								<br />
								<div class="input-group col-md-12">
									<input type="text" name="akcios_netto_ar" value="<?=$this->termek[akcios_netto_ar]?>" class="form-control">
									<span class="input-group-addon">nettó ár</span>
								</div>
								<br />
								<div class="input-group col-md-12">
									<input type="text" name="akcios_brutto_ar" value="<?=$this->termek[akcios_brutto_ar]?>" class="form-control">
									<span class="input-group-addon">bruttó ár</span>
									<span class="input-group-addon">
									= <strong title="Fogyasztói ár"><?=Helper::cashFormat($this->termek[akcios_ar])?> Ft</strong>
									(<?=number_format(($this->termek[akcios_ar] - $this->termek[akcios_brutto_ar]) / ($this->termek[akcios_ar] / 100),2,"."," ")?>%)
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<div class="con">
					<h3>Termék adatok</h3>
					<div class="row">
						<div class="form-group col-md-2">
							<label for="cikkszam">Nagyker kód / Cikkszám</label>
							<input class="form-control" id="cikkszam" type="text" value="<?=$this->termek['cikkszam']?>"  name="cikkszam">
						</div>
						<div class="form-group col-md-4">
							<label for="nev">Termék neve*</label>
							<input type="text" class="form-control" name="nev" id="nev" value="<?=$this->termek[nev]?>">
						</div>
						<div class="form-group col-md-3">
							<label for="csoport_kategoria">Termék alcíme</label>
							<input type="text" class="form-control" name="csoport_kategoria" id="csoport_kategoria" value="<?=$this->termek[csoport_kategoria]?>">
						</div>
						<div class="form-group col-md-3">
							<label for="nev">Termék márka*</label>
							<select name="marka" id="marka" class="form-control">
								<option value="">-- termék márka kiválasztása --</option>
								<option value="" disabled></option>
								<? foreach($this->markak as $d): ?>
								<option value="<?=$d[ID]?>" <?=($this->termek[marka] == $d[ID])?'selected':''?> nb="<?=$d[brutto]?>"><?=$d[neve]?> (<?=($d[brutto] == '1')?'Bruttó':'Nettó'?>)</option>
								<? endforeach; ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-3">
							<label for="szin">Típus variácó <?=\PortalManager\Formater::tooltip('Több variáció esetén variációk hozhatóak létre, melyet ennél az értéknél lehet megadni. Azonos típus azonosító törzskód és eltérő variácó meghatározásánál a rendszer automatikusan összekapcsolja a termékeket és átjárást biztosít a termék adatlapokon.<br>Pl.: zöld, piros, 16mm, 10 fm, 2x4x10mm, stb...')?></label>
							<input type="text" class="form-control" name="szin" id="szin" value="<?=$this->termek['szin']?>">
						</div>
						<? if(true): ?>
						<div class="form-group col-md-3">
							<label for="meret">Kiszerelés <?=\PortalManager\Formater::tooltip('Termékenkét termékkapcsolat hozható létre, amennyiben több fajta kiszerelés van egy-egy azonos termék esetében. Adjuk meg a kiszerelést és ez alapján a vásárló válogathat.<br>Pl.: 1 liter, 1 vödör, 1 zsák, 25 kg, 100 db / csomag, stb...')?></label>
							<input type="text" class="form-control" name="meret" id="meret" placeholder="1 darab, 25 liter, 100 db / csomag, stb..." value="<?=$this->termek['meret']?>">
						</div>
						<? endif; ?>
						<div class="form-group col-md-3">
							<label for="raktar_keszlet">Raktárkészlet (mennyiség)</label>
							<input type="number" class="form-control" name="raktar_keszlet" value="<?=$this->termek['raktar_keszlet']?>" id="raktar_keszlet">
						</div>
						<div class="form-group col-md-2">
							<label for="sorrend">Sorrend</label>
							<input type="number" class="form-control" name="sorrend" id="sorrend" value="<?=$this->termek['sorrend']?>">
						</div>
						<div class="form-group col-md-1">
							<label for="fotermek">Főtermék <?=\PortalManager\Formater::tooltip('Több szín és méret esetén kijelölhetjük, hogy melyik legyen az alapértelmezett, ami megjelenjen a terméklistázásban. A Főtermék-nek NEM jelölt termékek nem fognak megjelenni a listában, hanem csak mint variáció a kapcsolódó terméklapon!')?></label>
							<input type="checkbox" class="form-control" name="fotermek" id="fotermek" <?=($this->termek && $this->termek['fotermek'] == 1)?'checked="checked"':''?>>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 form-group">
							<label for="alapertelmezett_kategoria">Alapértelmezett kategória <?=\PortalManager\Formater::tooltip('A termék elsődleges, alapértelmezett kategóriája.<br>Mindenképp legyen kiválasztva egy alapértelmezett kategória. Egyes beállítások ettől az értéktől függnek vagy válnak elérhetővé. Pl.: paraméterek.')?></label>
							<select class="form-control" name="alapertelmezett_kategoria" id="alapertelmezett_kategoria">
								<option value="">-- válasszon --</option>
								<option value="" disabled="disabled"></option>
								<? if( count($this->termek['in_cat_ids']) > 0 ): foreach ( $this->termek['in_cat_ids'] as $key => $kids ) { ?>
									<option value="<?=$kids?>" <?=($kids == $this->termek['alapertelmezett_kategoria']) ? 'selected="selected"': ''?>><?=$this->termek['in_cat_names'][$key]?></option>
								<? } endif;  ?>
							</select>
						</div>
						<div class="form-group col-md-9">
							<label for="kulcsszavak">Kulcsszavak <?=\PortalManager\Formater::tooltip('A kulcsszavak meghatározása fontos dolog, mivel ezek alapján tud pontosabb keresési találatot kapni a felhasználó. <br> <strong>A kulcsszavakat szóközzel elválasztva adja meg. Pl.: fekete úszó rövidnadrág</strong>')?></label>
							<input type="text" class="form-control" name="kulcsszavak" id="kulcsszavak" value="<?=$this->termek['kulcsszavak']?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="tudastar_url">Tudástár URL <?=\PortalManager\Formater::tooltip('A termék adatlapon megjelenő tudástár hivatkozásra kattintva erre az URL-re fog érkezni a látogató. Ez lehet egy előzetesen leszűrt (kulcsszó vagy konkrét cikk alapján) tudástár hivatkozás, mely a termékkel kapcsolatos cikk(ek)et listázza ki.')?></label>
							<input type="text" class="form-control" name="tudastar_url" id="tudastar_url" value="<?=$this->termek['tudastar_url']?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="rovid_leiras">Termék rövid leírása</label>
							<textarea name="rovid_leiras" class="form-control" id="rovid_leiras"><?=$this->termek['rovid_leiras']?></textarea>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="leiras">Termék leírása</label>
							<textarea name="leiras" class="form-control" id="leiras"><?=$this->termek['leiras']?></textarea>
						</div>
					</div>

				</div>
				<div class="con">
					<h3>Tulajdonságok</h3>
					<div class="row np">
						<div class="col-md-12">
							<div class="form-group col-md-4">
								<label for="szall">Szállítási idő*</label>
								<select name="szallitasID" id="szall" class="form-control">
									<option value="">-- válasszon --</option>
									<option value="" disabled="disabled"></option>
									<? foreach($this->szallitas as $sz): ?>
									<option value="<?=$sz[ID]?>" <?=($this->termek['szallitasID'] == $sz[ID])?'selected':''?>><?=$sz['elnevezes']?></option>
									<? endforeach; ?>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="keszlet">Állapot*</label>
								<select name="keszletID" id="keszlet" class="form-control">
									<option value="">-- válasszon --</option>
									<option value="" disabled="disabled"></option>
									<? foreach($this->keszlet as $k): ?>
									<option value="<?=$k['ID']?>" <?=($this->termek['keszletID'] == $k[ID])?'selected':''?>><?=$k['elnevezes']?></option>
									<? endforeach; ?>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="garancia">Garancia (hónap; -1 = élettartam)</label>
								<input class="form-control" type="number" id="garancia" value="<?=$this->termek['garancia_honap']?>" min="-1" name="garancia">
							</div>
						</div>
					</div>

				</div>
				<div class="con" ng-app="Documents" ng-controller="List" ng-init="init(<?=$this->termek['ID']?>)">
					<h3>Csatolt dokumentumok, hivatkozások<em class="info">Csatolja hozzá ehhez a termékhez azokat a dokumentumokat, amelyek érdekesek vagy szükségesek lehetnek a vásárló részére.</em></h3>
					<div class="row">
						<div class="col-md-12">
							<md-autocomplete
				          md-selected-item="selectedItem"
				          md-search-text-change="searchTextChange(searcher)"
									md-selected-item-change="selectedItemChange(d)"
				          md-search-text="searcher"
				          md-selected-item-change=""
				          md-items="d in findSearchDocs(searcher)"
				          md-item-text="d.cim"
				          md-min-length="0"
									md-menu-class="docsautocomplist"
				          placeholder="Dokumentum keresése...">
				        <md-item-template>
				          <div class="item">
										<i class="fa fa-link" title="Hivatkozás" ng-show="(d.tipus == 'external')"></i><i title="Feltöltött dokumentum" class="fa fa-file-o" ng-show="(d.tipus == 'local')"></i> <strong>{{d.cim}}</strong> <span class="ext" ng-show="(d.tipus == 'local')" title="Fájlkiterjesztés">({{d.ext}})</span> <span class="keywords"><em>{{d.keywords}}</em></span>
				          </div>
				        </md-item-template>
				      </md-autocomplete>
						</div>
					</div>
					<h3>Csatolt dokumentumok listája</h3>

					<div class="row" ng-show="docs_in_sync">
						<div class="col-md-12">
							<div class="alert alert-success">
								Dokumentumok szinkronizálása folyamatban...<i class="fa fa-spin fa-spinner"></i>
							</div>
						</div>
					</div>

					<div class="docs-list">
						<div class="loading-text" ng-show="loading">
							<div class="alert alert-warning">
								Becsatolt dokumentumok listájának betöltése folyamatban... <i class="fa fa-spin fa-spinner"></i>
							</div>
						</div>
						<div class="empty-list-text" ng-show="!error && docs.length===0 && !loading">
							Nincs csatolt dokumentum ehhez a termékhez.
						</div>
						<div class="alert alert-danger" ng-show="error">
							{{error}}
						</div>
						<div class="docsautocomplist docs-inserted" ng-repeat="doc in docs" ng-show="!loading">
							<div class="item">
								<div class="del" title="Dokumentum eltávolítása">
									<i class="fa fa-times" ng-click="removeDocument(doc.doc_id)"></i>
								</div>
								<i class="fa fa-link" title="Hivatkozás" ng-show="(doc.tipus == 'external')"></i><i title="Feltöltött dokumentum" class="fa fa-file-o" ng-show="(doc.tipus == 'local')"></i> <strong>{{doc.cim}}</strong> <span class="ext" ng-show="(doc.tipus == 'local')" title="Fájlkiterjesztés">({{doc.ext}})</span> <span class="keywords"><em>{{doc.keywords}}</em></span>
							</div>
						</div>
					</div>
				</div>
				<div class="con">
					<h3>Meta beállítások<em class="info">Közösségi oldalakon és keresőoptimalizálákor megjelenő adatok. Ha üresen hagyjuk, akkor az alapértelmezett adatok jelennek meg.</em></h3>
					<div class="row">
						<div class="col-md-12">
							<label for="meta_title">Meta főcím / termékcím:</label>
							<input type="text" class="form-control" name="meta_title" maxlength="70" id="meta_title" value="<?=$this->termek['meta_title']?>">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12">
							<label for="meta_title">Meta leírás:</label>
							<textarea name="meta_desc" class="form-control no-editor" maxlength="150" id="meta_desc"><?=$this->termek['meta_desc']?></textarea>
						</div>
					</div>
				</div>
	    </div>

	    <div class="col-md-4"  style="padding-right:0;">
				<div class="con">
					<h3>Termék ár adatok</h3>
					<div class="">
						<?php foreach ((array)$this->price_groups as $pg): ?>
						<div class="row">
							<div class="col-md-12">
								<h4><?=$pg['title']?> [<?=$pg['groupkey']?>]</h4>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label for="price_<?=$pg['groupkey']?>_netto">Nettó ár</label>
								<input type="number" id="price_<?=$pg['groupkey']?>_netto" min="0" step="0.1" class="form-control" name="prices[<?=$pg['groupkey']?>]" value="<?=$this->termek[$pg['groupkey']]?>">
							</div>
						</div>
						<br>
						<?php endforeach; ?>
					</div>
				</div>
	   		<? if( true ): ?>
	      <div class="con" style="display:block;">
				<h3>Kategória, amibe megjelenjen (<?=count($this->termek['in_cat_ids'])?>) <em class="info"><a href="/kategoriak" target="_blank"><i class="fa fa-gear"></i> kategóriák szerkesztése</a></em></h3>
				<div style="padding:0 0 15px 15px;">
					<div class="tree overflowed">
						<? while( $this->categories->walk() ): $item = $this->categories->the_cat(); ?>
						<div class="item deep<?=$item['deep']?>">
							<label><input name="cat[]" value="<?=$item['ID']?>" type="checkbox" <?=(in_array($item['ID'], $this->termek['in_cat_ids']))?'checked="checked"':''?>><?=$item['neve']?></label>
						</div>
						<? endwhile; ?>
					</div>
				</div>
			</div>
	    <? endif; ?>

			<? if( $this->termek['alapertelmezett_kategoria'] ): ?>
	     	<div class="con con-extra">
	     		<div style="float: right;"><a href="/kategoriak/parameterek">Paraméter beállítások <i class="fa fa-gear"></i></a></div>
            	<h3>Paraméterek</h3>
                <div style="">
                <? if(count($this->parameterek) == 0): ?> <span class="subt"><i class="fa fa-info-circle"></i> nincsennek paraméterek meghatározva</span><? endif; ?>
                <form action="" method="post">
                	<input type="hidden" name="tid" value="<?=$this->termek[ID]?>">
                 	<input type="hidden" name="kid" value="<?=$this->termek[alapertelmezett_kategoria]?>">
                <? foreach($this->parameterek as $d): ?>
                <div class="row">
                	<div class="col-sm-12"><strong><?=$d[parameter]?></strong></div>
               	</div>
				<div class="row">
					<div class="col-sm-12">
				    	<div class="input-group">
				    	<input type="text" name="param[<?=$d[ID]?>]" value="<?=$this->termek['parameters'][$d[ID]][ertek]?>" class="form-control">
				        <span class="input-group-addon"><?=$d[mertekegyseg]?></span>
				        </div>
				    </div>
				</div>
				<br>
				<? endforeach; ?>
                <? if(count($this->parameterek) > 0): ?>
                <div class="" align="right">
                    <button name="saveTermekParams" class="btn btn-success btn-2x">Mentés <i class="fa fa-check"></i></button>
                </div>
                <? endif; ?>
                </form>
                </div>
            </div>



            <? endif; ?>


	</form>

            <div class="con con-extra">
				<h3>
					<i class="fa fa-upload hbtn" title="új kép feltöltése" key="upImg"></i>
					Képek (<?=count($this->termek[images])?>)
					<em class="info">A képre kattintva beállíthatja az alapértelmezett profilképet.</em>
				</h3>
                <div class="row">
                	<div class="col-md-12 upImg" style="display:none;">
                		<div class="newWire">
							<form action="" method="post" enctype="multipart/form-data">
								<input type="hidden" name="dir" value="<?=$this->termek['kep_mappa']?>">
								<input type="hidden" name="tid" value="<?=$this->termek['ID']?>">
								<button style="float:right;" name="uploadImg">feltöltés</button>
								<input type="file" name="img[]" multiple />
								<div class="clr"></div>
							</form>
                		</div>
                	</div>
                </div>

				<div class="row">
					<div class="col-md-12">
		                <div class="images">
		                	<? foreach($this->termek['images'] as $i): ?>
		                    	<div del="0" class="item <?=( \PortalManager\Formater::productImage($i) == $this->termek['profil_kep'])?'main':''?>"><img isrc="<?=$i?>" src="<?=\PortalManager\Formater::productImage($i)?>" alt=""></div>
		                    <? endforeach; ?>
		                    <div class="clr"></div>
		                </div>
					</div>
				</div>

                <div class="row">
                	<div class="col-md-12 right">
                		<span class="delimgmode label label-danger" title="Bepipálva a képkre kattintva törölhető a termék kép!">képtörlő mód <input type="checkbox" id="imgDelMode" /></span>
                	</div>
                </div>
				<br>
	        </div>

			<? if( true ): ?>
	        <div class="con con-extra">
            	<h3>Termék másolat <?=\PortalManager\Formater::tooltip('Javasolt termék variációhoz, ahol a termék adatai nagy részében megegyeznek.')?> <em class="info">Lemásolhatja tetszőletes számban a terméket.</em></h3>
                <div class="row" style="">
					<div class="col-md-12">
						<?=$this->copyMsg?>
						<form action="" method="post" role="form">
							<input type="hidden" name="tid" value="<?=$this->termek[ID]?>" />
							<div class="input-group">
								<input type="number" class="form-control" min="0" value="0" name="copyNum" />
								<span class="input-group-addon">darab</span>
								<span class="input-group-btn"><button class="btn btn-danger" name="copyTermek">másolás</button></span>
							</div>
						</form>
					</div>
                </div>
            </div>
       		<? endif; ?>



			<? if( true ): ?>
	        <div class="con con-extra">
            	<h3>Ajánlott termékek <em class="info">Fűzzön a termékhez ajánlott termékeket.</em></h3>
               	<div class="row">
               		<div class="col-md-12">
               			<label for="productRelativesText">Keresés</label>
               			<input type="text" id="productRelativesText" exc-id="<?=$this->termek['ID']?>" value="" placeholder="termék keresése..." class="form-control">
               		</div>
               	</div>
               	<br>
               	<div class="row">
               		<div class="col-md-12">
               			<label for="">Keresési találatok (<span id="productRelativesNumber">0</span>)</label>
               			<div class="productRelativesList" id="productRelativesList"></div>
               		</div>
               	</div>

               	<div class="row">
               		<div class="col-md-12">
               			<label for="">Aktív ajánlott termékek (<?=($this->termek['related_products_ids']) ? count($this->termek['related_products_ids']) : 0?>)</label>
               			<div><?=$this->kapcsolatok?></div>
               		</div>
               	</div>
            </div>
        	<? endif; ?>

	    </div>
	</div>

<script type="text/javascript">
	$(function(){
		// Termék ajánlások becsatoláshoz
		//loadProductRelatives();

		$('#productRelativesText').bind( 'keyup', function(){
			loadProductRelatives(function(d){

			});
		} );

		$('.modkat i').click(function(){
			$('.modkat .shinkat').hide(0);
			$('.modkat i').removeClass('showed');
			var key = $(this).attr('key');
			var sh 	= $(this).attr('sh');


			if(sh == 0){
				$('.modkat #inkatid'+key).show(0).html('<div style="padding:10px; text-align:center;"><i class="fa fa-spinner fa-spin"></i> betöltés...</div>');
				$(this).attr('sh',1);
				$(this).addClass('showed');
				loadKatValaszto(key);
			}else{
				$('.modkat #inkatid'+key).hide(0);
				$(this).attr('sh',0);
			}
		});

		$('.images .item').click(function(e){
			var del = $(this).attr('del');
			$('.images .item').removeClass('main');

			if(del == '0'){
				setMainImage($(this));
			}else{
				delImage($(this));
			}
		});

		$('#imgDelMode').bind('change',function(){
			var ch = $(this).is(':checked');

			if(ch){
				$('.images .item').attr('del','1');
			}else{
				$('.images .item').attr('del','0');
			}
		});

		$('#addMoreGyujtkat').click(function(){
			addNewLister();
		});
		$('#addMoreLink').click(function(){
			addNewLinkRow();
		});
	})

	var newKat = 0;
	function loadKatValaszto(tid){
		$.post("<?=AJAX_GET?>",{
			type : 'loadCheckKat',
			id 	: tid,
		},function(d){
			$('.modkat #inkatid'+tid).html(d);
		},"html");
	}
	function addNewLister(){
		newKat++;
		$('.inkat .item:last').after('<div class="item new"><div class="selModszer i'+newKat+'"></div><div class="selGyujto i'+newKat+'"></div></div>');
		loadModszerek();
	}
	function loadProductRelatives ( callback ) {
		var handler = $('#productRelativesText');
		var excid = handler.attr('exc-id');
		var srctext = handler.val();

		$('#productRelativesList').html( '<i class="fa fa-spinner fa-spin"></i> betöltés...' );

		$.post("<?=AJAX_POST?>",{
			type 	: 'loadProducts',
			by  	: 'nev',
			val 	: srctext,
			template : 'relatives',
			fromid 	: excid,
			mode 	: 'json'
		},function(d){
			var ret = jQuery.parseJSON(d);
			$('#productRelativesList').html( ret.result );
			$('#productRelativesNumber').text( ret.info.results );
		},"html");
	}

	function connectProductRelatives( e, foid, tid ) {
		$.post("<?=AJAX_POST?>",{
			type 	: 'addProductConnects',
			idfrom  : foid,
			idto 	: tid
		},function(d){
			loadProductRelatives(function(d){

			});
		},"html");
	}

	function removeProductRelatives( e, foid, tid ) {
		var rtarget = $('.product-li-items.mode-remove').find('li.item.item_'+foid+"_"+tid);

		rtarget.css({ opacity: 0.5 });
		rtarget.find('button').removeClass('btn-danger').addClass('btn-success');
		rtarget.find('i').removeClass('fa-minus-circle').addClass('fa-spinner fa-spin');

		$.post("<?=AJAX_POST?>",{
			type 	: 'removeProductConnects',
			idfrom  : foid,
			idto 	: tid
		},function(d){
			rtarget.remove();
		},"html");
	}

	function addNewLinkRow(){
		var e ='<br />'+
			'<div class="row np link">'+
				'<div class="col-md-1"><em>új</em></div>'+
				'<div class="col-md-4"><input type="text" name="linkNev[]" class="form-control" value=""  placeholder="Felirat"/></div>'+
			   ' <div class="col-md-7"><input type="text" name="linkUrl[]" class="form-control" value="" placeholder="URL"/></div>'+
			'</div>';

		$(e).insertAfter('.alink .link:last');
	}

	function delImage(e){
		e.addClass('del');
		var c = confirm('Biztos, hogy törli a képet?');

		if(c){
			$.post('<?=AJAX_POST?>',{
				type : 'termekChangeActions',
				mode : 'delTermekImg',
				tid  : '<?=$this->termek[ID]?>',
				i : e.find('img').attr('isrc')
			},function(d){
				console.log(d);
				e.remove();
			},"html");

		}else{
			e.removeClass('del');
		}
	}

	function loadModszerek(){
		$.post('/ajax/get/',{
			type : 'loadModszerek',
			i : newKat
		},function(d){
			$('.item .selModszer.i'+newKat).html(d);
		},"html");
	}

	function setMainImage(e){
		var img = e.find('img').attr('isrc');
		e.addClass('prog');
		$.post("<?=AJAX_POST?>",{
			type 	: 'termekChangeActions',
			mode 	: 'changeTermekKep',
			id 		: '<?=$this->termek[ID]?>',
			i 		: img
		},function(d){
			e.removeClass('prog').addClass('main');
		},"html");
	}
</script>
<? endif; ?>
<pre><? //print_r($this->termek); ?></pre>
