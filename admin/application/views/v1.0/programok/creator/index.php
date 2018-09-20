<div style="float:right;">
	<a href="/programok/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> program kategóriák</a>
	<a href="/programok/" class="btn btn-default"><i class="fa fa-th"></i> programok</a>
</div>
<h1>programek</h1>
<?=$this->msg?>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row np">
	<div class="col-md-12">
    	<div class="con con-del">
            <h2>program törlése</h2>
            Biztos, hogy törli a kiválasztott programot?
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
              <input type="checkbox" id="cats<?=$cat['ID']?>" name="cats[]" value="<?=$cat['ID']?>" <?=(($this->news && in_array($cat['ID'], $scats['ids'])) || in_array($cat['ID'], $_POST['cats']) )?'checked="checked"':''?>> <label for="cats<?=$cat['ID']?>"><?=$cat['neve']?></label>
            </div>

            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
    	<div class="col-md-9">
        	<div class="con <?=($this->gets[2] == 'szerkeszt')?'con-edit':''?>">
            <h2><? if($this->gets[2] == 'szerkeszt'): ?>program szerkesztése<? else: ?>Új program hozzáadása<? endif; ?></h2>
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
                    <label for="szoveg">Program tartalma</label>
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
    })

    function responsive_filemanager_callback(field_id){
        var imgurl = $('#'+field_id).val();
        $('#url_img').find('img').attr('src',imgurl).show();
        $('#remove_url_img').show();
    }
</script>
