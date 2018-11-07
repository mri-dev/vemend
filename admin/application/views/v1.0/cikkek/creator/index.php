<div style="float:right;">
	<a href="/cikkek/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> cikk kategóriák</a>
	<a href="/cikkek/" class="btn btn-default"><i class="fa fa-th"></i> cikkek</a>
</div>
<h1>Cikkek</h1>
<?=$this->msg?>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row np">
	<div class="col-md-12">
    	<div class="con con-del">
            <h2>Cikk törlése</h2>
            Biztos, hogy törli a kiválasztott cikket?
            <div class="row np">
                <div class="col-md-12 right">
                    <a href="/<?=$this->gets[0]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<? endif; ?>
<? if($this->gets[2] != 'torles'): ?>
<?php $scats = ($this->news) ? $this->news->getCategories() : array(); ?>
<form action="" method="post" enctype="multipart/form-data">
  <div class="row-neg">
    <div class="row">
      <div class="col-md-3">
        <div class="con">
          <h2>Kategóriák</h2>
          <?php if ( $this->categories ): ?>
            <?php while( $this->categories->walk() ):
            $cat = $this->categories->the_cat(); ?>
            <div class="">
              <input type="checkbox" class="cont-binder" data-cont-value="<?=$cat['slug']?>" id="cats<?=$cat['ID']?>" name="cats[]" value="<?=$cat['ID']?>" <?=(($this->news && in_array($cat['ID'], $scats['ids'])) || in_array($cat['ID'], $_POST['cats']) )?'checked="checked"':''?>> <label for="cats<?=$cat['ID']?>"><?=$cat['neve']?></label>
            </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
				<div class="con cont-option" data-cont-option="boltok,vendeglatas">
					<div class="option-label">Opcionális adatmező</div>
					<h2>Nyitvatartás</h2>
					<?php
					$nyt_values = $this->news->getOptional('nyitvatartas', true);
					foreach (array('Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap') as $nap): ?>
						<label for="option_nyitvatartas_<?=$nap?>"><?=$nap?></label>
						<input type="text" id="option_nyitvatartas_<?=$nap?>" placeholder="Pl.: 08:00 - 17:00, zárva" name="optional[nyitvatartas][<?=$nap?>]" class="form-control" value="<?=$nyt_values[$nap]?>">
						<br>
					<?php endforeach; ?>
				</div>
				<div class="con cont-option" data-cont-option="intezmenyek,boltok,vendeglatas,turizmus">
					<div class="option-label">Opcionális adatmező</div>
					<h2>Google térkép</h2>
					<label for="option_maps">Pontos cím megadása</label>
					<input type="text" id="option_maps"name="optional[maps]" class="form-control" value="<?=$this->news->getOptional('maps')?>">
				</div>
      </div>
    	<div class="col-md-9">
        	<div class="con <?=($this->gets[2] == 'szerkeszt')?'con-edit':''?>">
            <h2><? if($this->gets[2] == 'szerkeszt'): ?>Cikk szerkesztése<? else: ?>Új cikk hozzáadása<? endif; ?></h2>
            <div class="row-neg">
              <div class="row">
                  <div class="col-md-6">
                    <label for="cim">Cím*</label>
                      <input type="text"class="form-control" name="cim" id="cim" value="<?=($this->news ? $this->news->getTitle() : '')?>">
                  </div>
                  <div class="col-md-5">
                      <label for="eleres">Elérési kulcs: <?=\PortalManager\Formater::tooltip('Hagyja üresen, hogy a rendszer automatikusan generáljon elérési kulcsot. <br><br>Kérjük ne használjon ékezeteket, speciális karaktereket és üres szóközöket.<br> Példa a helyes használathoz: ez_az_elso_bejegyzesem');?></label>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="fa fa-home" title="<?=HOMEDOMAIN?>hirek/"></i>
                          </span>
                        <input type="text" class="form-control" placeholder="valami_szoveg" name="eleres" id="eleres" value="<?=($this->news ? $this->news->getAccessKey() : '')?>">
                      </div>
                  </div>
                  <div class="col-md-1">
                      <label for="lathato">Látható:</label>
                      <input type="checkbox" class="form-control" <?=($this->news && $this->news->getVisibility() ? 'checked="checked"' : '')?> id="lathato" name="lathato" />
                  </div>
                </div>
                <br>
                <div class="row">
                   <div class="col-md-2">
                      <label for="belyegkep">Bélyegkép <?=\PortalManager\Formater::tooltip('Ajánlott kép paraméterek:<br>Dimenzió: 1400 x * pixel <br>Fájlméret: max. 1 MB <br><br>A túl nagy fájlméretű képek lassítják a betöltés idejét és a facebook sem tudja időben letölteni, így megosztáskor kép nélkül jelenhet meg a megosztott bejegyzés az idővonalon.');?></label>
                      <div style="display:block;">
                          <input type="text" id="belyegkep" name="belyegkep" value="<?=($this->news) ? $this->news->getImage() : ''?>" style="display:none;">
                          <a title="Kép kiválasztása" href="<?=FILE_BROWSER_IMAGE?>&field_id=belyegkep" data-fancybox-type="iframe" class="btn btn-sm btn-default iframe-btn" type="button"><i class="fa fa-search"></i></a>
                          <span id="url_img" class="img-selected-thumbnail"><a href="<?=($this->news) ? $this->news->getImage() : ''?>" class="zoom"><img src="<?=($this->news) ? $this->news->getImage() : ''?>" title="Kiválasztott menükép" alt=""></a></span>
                          <i class="fa fa-times" title="Kép eltávolítása" id="remove_url_img" style="color:red; <?=($this->news && $this->news->getImage() ? '' :'display:none;')?>"></i>
                      </div>
                  </div>
              </div>
              <br />
              <div class="row">
                  <div class="col-md-12">
                      <label for="bevezeto">Bevezető szöveg (a listázásban jelenik meg)</label>
                      <div style="background:#fff;"><textarea name="bevezeto" id="bevezeto" class="form-control no-editor"><?=($this->news ? $this->news->getDescription() : '')?></textarea></div>
                  </div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                    <label for="szoveg">Cikk tartalma</label>
                    <div style="background:#fff;"><textarea name="szoveg" id="szoveg" class="form-control"><?=($this->news ? $this->news->getHtmlContent() : '')?></textarea></div>
                  </div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12 right">
                  <? if($this->gets[2] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[2]?>" />
                    <a href="/<?=$this->gets[0]?>"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
                    <button name="save" class="btn btn-success">Változások mentése <i class="fa fa-check-square"></i></button>
                    <? else: ?>
                    <button name="add" class="btn btn-primary">Hozzáadás <i class="fa fa-check-square"></i></button>
                  <? endif; ?>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>
  </div>
</form>
<? endif; ?>
<script>
    $(function(){
			bindContentHandler();

      $('#menu_type').change(function(){
          var stype = $(this).val();
          $('.type-row').hide();
          $('.type_'+stype).show();
          $('.submit-row').show();
      });
      $('#remove_url_img').click( function (){
          $('#url_img').find('img').attr('src','').hide();
          $('#belyegkep').val('');
          $(this).hide();
      });

			$('.cont-binder').click(function(){
				bindContentHandler();
			});
    })

		function bindContentHandler() {
			var selected = [];
			jQuery.each($('input[type=checkbox].cont-binder:checked'), function(i,v) {
				var val = $(v).data('cont-value');
				selected.push(val);
			});

			jQuery.each($('.cont-option'), function(i,e){
				$(e).removeClass('active');
				var keys = $(e).data('cont-option').split(",");
				jQuery.each(keys, function(ii,ee){
					var p = selected.indexOf(ee);
					if ( p !== -1 ) {
						$(e).addClass('active');
					}
				});
			});

		}

    function responsive_filemanager_callback(field_id){
        var imgurl = $('#'+field_id).val();
        $('#url_img').find('img').attr('src',imgurl).show();
        $('#remove_url_img').show();
    }
</script>
