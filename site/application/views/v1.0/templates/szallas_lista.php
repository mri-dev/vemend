<div class="szallas-list grid-x2">
  <div class="wrapper">

    <?php if ( $this->szallasok ): ?>
    <?php foreach ( (array)$this->szallasok['list'] as $szallas ): ?>
    <div class="szallas">
      <div class="wrapper">
        <div class="image autocorrett-height-by-width" data-image-ratio="4:3">
          <?php if ($szallas['prices']['discount']): ?>
          <div class="discount">-<?=$szallas['prices']['discount']?>%</div>
          <?php endif; ?>
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
          <?=($szallas['cim_sub'])?:'&nbsp;'?>
        </div>
        <div class="info-block">
          <div class="block-left">
            <div class="wrapper">
              <?=$szallas['prices']['total_person']?> fő / <?=$szallas['prices']['nights']?> éj
            </div>
          </div>
          <div class="block-center">
            <div class="wrapper">
              <div class="prices">
                <?php if ($szallas['prices']['old']): ?>
                <div class="old">
                  <?php echo \Helper::cashFormat($szallas['prices']['old']); ?> Ft
                </div>
                <?php endif; ?>
                <div class="current" <? if($szallas['prices']['datas']): ?>title="Szoba: <?=$szallas['prices']['datas']['room']['name']?>, <?=$szallas['prices']['datas']['roomprice']['ellatas_name']?> esetén."<? endif; ?>>
                  <?php echo \Helper::cashFormat($szallas['prices']['current']); ?> Ft<span class="frm">-tól</span>
                </div>
              </div>
            </div>
          </div>
          <div class="block-right">
            <div class="wrapper">
              <?=$szallas['kiemelt_szoveg']?>
            </div>
          </div>
        </div>
        <div class="button">
          <?php
          $get = $_GET;
          unset($get['tag']);
          unset($get['page']);
          ?>
          <a href="<?=$szallas['url']?>?<?=http_build_query($get)?>">Szálláshely megtekintése</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<pre><?php //print_r($this->szallasok); ?></pre>
