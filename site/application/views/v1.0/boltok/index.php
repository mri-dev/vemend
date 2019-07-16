<? if( true ): ?>
    <div class="category-listing page-width">
        <div class="list-view webshop-product-top">
          <div class="grid-layout">
            <div class="grid-row filter-sidebar">
              <? $this->render('templates/sidebar'); ?>
            </div>
            <div class="grid-row products">
              <h1>Boltok</h1>
            </div>
          </div>
        </div>
    </div>
<? else: ?>
    <?=$this->render('home')?>
<? endif; ?>
