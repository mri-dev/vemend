<h1>Cikk kategóriák</h1>
<? if($this->err): ?>
	<?=$this->bmsg?>
<? endif; ?>
<div class="row">
	<div class="col-md-4">
		<? if( $this->category_d ): ?>
		<div class="con con-del">
			<h2>Kategória törlése</h2>
			Biztos benne, hogy törli a(z) <strong><u><?=$this->category_d->getName()?></u></strong> elnevezésű kategóriát? A művelet nem visszavonható!
			<div class="row np">
				<div class="col-md-12 right">
					<form action="" method="post">
						<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/" class="btn btn-danger"><i class="fa fa-times"></i> Mégse</a>
						<button name="delCategory" value="1" class="btn btn-success">Igen, véglegesen törlöm <i class="fa fa-check"></i></button>
					</form>
				</div>
			</div>
		</div>
		<? else: ?>
		<div class="con <?=($this->category ? 'con-edit':'')?>">
			<h2><?=($this->category ? 'Kategória szerkesztése':'Új kategória létrehozás')?></h2>
			<div>
				<form action="" method="post">
					<div class="row np">
						<div class="col-md-9" style="padding-right:8px;">
							<label for="name">Elnevezés*</label>
							<input type="text" id="name" name="name" value="<?= ( $this->err ? $_POST['name'] : ($this->category ? $this->category->getName():'') ) ?>" class="form-control">
						</div>
						<div class="col-md-3">
							<label for="sortnum">Sorrend</label>
							<input type="number" id="sortnumber" name="sortnumber" value="<?=($this->err ? $_POST['sortnumber']:($this->category ? $this->category->getSortNumber() : '0'))?>" class="form-control">
						</div>
					</div>

					<? if( true ): ?>
					<br>
					<div class="row np">
						<div class="col-md-12">
							<label for="slug">SEO URL</label>
							<input type="text" id="slug" name="slug" placeholder="pl.: uj-kategoria-eleres" value="<?= ( $this->err ? $_POST['slug'] : ($this->category ? $this->category->getSlug():'') ) ?>" class="form-control">
						</div>
					</div>
					<? endif; ?>
					<? if( false ): ?>
					<br>
					<div class="row np">
						<div class="col-md-12">
							<label for="hashkey">Egyedi azonosító kulcs</label>
							<input type="text" id="hashkey" name="hashkey" value="<?= ( $this->err ? $_POST['hashkey'] : ($this->category ? $this->category->getHashkey():'') ) ?>" class="form-control">
						</div>
					</div>
					<? endif; ?>
          <?php if (false): ?>
					<br>
					<div class="row np">
						<div class="col-md-12">
							<label for="img">Kategória kép</label>
							<div class="input-group">
                  <input type="text" id="img" class="form-control" name="image" value="<?= ( $this->err ? $_POST['image'] : ($this->category ? $this->category->getImage():'') ) ?>">
                  <span class="input-group-addon">
                      <a title="Kép kiválasztása galériából" href="/src/js/tinymce/plugins/filemanager/dialog.php?type=1&amp;lang=hu_HU&amp;field_id=img" data-fancybox-type="iframe" class="iframe-btn"><i class="fa fa-th"></i></a>
                  </span>
              </div>
						</div>
					</div>
          <?php endif; ?>
          <?php if (false): ?>
					<br>
					<div class="row np">
						<div class="col-md-12">
							<label for="parent_category">Szülő kategória</label>
							<select name="parent_category" id="parent_category" class="form-control">
								<option value="" selected="selected">&mdash; ne legyen &mdash;</option>
								<option value="" disabled="disabled"></option>
								<?
									while( $this->categories->walk() ):
									$cat = $this->categories->the_cat();
								?>
								<option value="<?=$cat['ID']?>_<?=$cat['deep']?>" <?=($this->err && $_POST['parent_category'] == $cat['ID'].'_'.$cat['deep'] ? 'selected="selected"' : ($this->category && $this->category->getParentKey() == $cat['ID'].'_'.$cat['deep'] ? 'selected="selected"' : '' ))?>><? for($s=$cat['deep']; $s>0; $s--){echo '&mdash;';}?><?=$cat['neve']?></option>
								<? endwhile; ?>
							</select>
						</div>
					</div>
          <?php endif; ?>
					<? if( false ): ?>
					<br>
					<div class="row np">
						<div class="col-md-12">
							<label for="parent_category">Oldalak kapcsolása a kategóriához</label>
							<div class="categories-oldal-lista tbl-container overflowed">
								<? while ( $this->hashkeyed_pages->walk() ):
									$page = $this->hashkeyed_pages->the_page();
									$hashkeys = array();
									if( $this->category ) {
										$hashkeys = $this->category->getPageHashkeys();
									}
								?>
								<label class=" <?=(is_null($page[hashkey]))?'disabled':''?> "> <? if($page[deep] > 0 ) { for($e = $page[deep]; $e > 0; $e-- ){ echo '&mdash;'; } }?>  <input <?=(is_null($page[hashkey]))?'disabled="disabled"':''?> type="checkbox" <?=(in_array( $page[hashkey], $hashkeys)) ?'checked="checked"':''?> name="oldal_hashkeys[]" value="<?=$page[hashkey]?>"> <?=$page[cim]?></label>
								<? endwhile; ?>
							</div>
						</div>
					</div>
					<? endif; ?>
					<br>
					<div class="row np">
						<div class="col-md-12 right">
							<? if($this->category): ?>
							<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/" class="btn btn-danger"><i class="fa fa-times"></i> mégse</a>
							<? endif; ?>
							<button name="<?=($this->category ? 'saveCategory':'addCategory')?>" value="1" class="btn btn-<?=($this->category ? 'success':'primary')?>"><?=($this->category ? 'Változások mentése <i class="fa fa-save">':'Hozzáadás <i class="fa fa-plus">')?></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<? endif; ?>
	</div>
	<div class="col-md-8">
		<div class="con">
			<h2>Kategóriák</h2>
			<?
				if( false ):
				while( $this->categories->walk() ):
				$cat = $this->categories->the_cat();
			?>
			<?
				if($cat['deep'] == 1) {
					echo '&mdash;';
				} else if($cat['deep'] == 2) {
					echo '&mdash;&mdash;';
				}	 else if($cat['deep'] == 3) {
					echo '&mdash;&mdash;&mdash;';
				}

			?>
			<STRONG style="color:#2c3e50;"><?=$cat['neve']?></STRONG> &nbsp;&mdash;&nbsp; <SPAN STYLE="COLOR:#43a0de;"><?=$cat[hashkey]?></SPAN><BR>
			<? endwhile; endif;  ?>

			<div class="row np row-head">
				<div class="col-md-7"><em>Kategória</em></div>
  			<div class="col-md-2 left"><em>SEO URL</em></div>
				<div class="col-md-2 center"><em>Sorrend</em></div>
				<div class="col-md-1"></div>
			</div>
			<div class="categories">
				<?
					while( $this->categories->walk() ):
					$cat = $this->categories->the_cat();
				?>
				<div class="row np deep<?=$cat['deep']?> <?=($this->category && $this->category->getId() == $cat['ID'] ? 'on-edit' : ( $this->category_d && $this->category_d->getId() == $cat['ID'] ? 'on-del':'') )?>">
					<div class="col-md-7">
						<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/szerkeszt/<?=$cat['ID']?>" title="Szerkesztés"><strong><?=$cat['neve']?></strong></a>
						 <? if( $cat['oldal_hashkeys'] ): ?> | <span style="color: black;">Csatolt oldalak: <?=count(explode(",",$cat[oldal_hashkeys]))?> db</span><? endif; ?>
						<div><? if($cat['hashkey']): ?> <span class="hashkey">#<?=$cat['hashkey']?></span> <? endif; ?></div>
					</div>
          <div class="col-md-2 left">
            <?=$cat['slug']?>
          </div>
					<div class="col-md-2 center">
						<?=$cat['sorrend']?>
					</div>
          <div class="col-md-1 actions" align="right">
          	<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/torles/<?=$cat['ID']?>" title="Törlés"><i class="fa fa-times"></i></a>
          </div>
				</div>
				<? endwhile; ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(function(){

    })
</script>
