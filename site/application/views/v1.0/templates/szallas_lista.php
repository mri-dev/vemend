<div class="szallas-list grid-x2">
  <div class="wrapper">
    
    <?php if ( $this->szallasok || true ): ?>
    <?php foreach ( (array)$this->szallasok['list'] as $szallas ): ?>
    <div class="szallas">
      <div class="wrapper">
        <div class="image">
          <div class="discount">-30%</div>
          <div class="wrapper image-abs-center">
            <img src="<?=$szallas['profilkep']?>" alt="">
          </div>
          <div class="title">
            <div class="in">
              <h3><a href="<?=$szallas['url']?>"><?=$szallas['title']?></a></h3>
            </div>
          </div>
        </div>
        <div class="address">
          <i class="fa fa-map-marker"></i> <?=$szallas['cim']?>
        </div>
        <div class="distance">
          1 km a központtól
        </div>
        <div class="info-block">
          <div class="block-left">
            <div class="wrapper">
              1 fő/1 éj
            </div>
          </div>
          <div class="block-center">
            <div class="wrapper">
              <div class="prices">
                <div class="old">
                  23 780 Ft
                </div>
                <div class="current">
                  18 950 Ft
                </div>
              </div>
            </div>
          </div>
          <div class="block-right">
            <div class="wrapper">
              A szálláshely kisállatokat is fogad!
            </div>
          </div>
        </div>
        <div class="button">
          <a href="<?=$szallas['url']?>">Szálláshely megtekintése</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<pre><?php //print_r($this->szallasok); ?></pre>
