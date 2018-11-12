<div class="adatlap-view">
  <div class="header">
    <div class="infos">
      infók
    </div>
    <div class="titles">
      <div class="wrapper">
        <div class="title">
          <h1><?=$this->szallas['datas']['title']?></h1>
          <div class="address">
            <i class="fa fa-map-marker"></i> <?=$this->szallas['datas']['cim']?>
          </div>
        </div>
        <div class="badgetext">
          <?php if ($this->szallas['datas'][kisallat]): ?>
          <div class="kisallat">
            <img class="ico" src="<?=IMG?>icons/ico-mancs.svg" alt="Állatbarát szálláshely"> Állatbarát szálláshely!
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="adatlap-body">
    <div class="images">
      <div class="profil">
        <a class="zoom" href="<?=$this->szallas['datas']['profilkep']?>"><img src="<?=$this->szallas['datas']['profilkep']?>" alt="<?=$this->szallas['datas']['title']?>"></a>
      </div>
      <div class="image-set <?=(count($this->szallas[datas][pictures])>4)?'slide':''?>">
        <div class="wrapper">
          <?php foreach ((array)$this->szallas[datas][pictures] as $img): ?>
          <div class="image">
            <div class="wrapper">
              <a class="zoom" rel="imageset" href="<?php echo IMGDOMAIN.$img['filepath']; ?>"><img src="<?php echo IMGDOMAIN.$img['filepath']; ?>" alt="<?php echo $img['cim']; ?>"></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="contents">
      <?php if (count($this->kiemelt_services) != 0): ?>
      <div class="top-services">
        <div class="head">
          Itt nem fog csalódni
        </div>
        <div class="wrapper">
          <?php foreach ((array)$this->kiemelt_services as $service): ?>
          <div class="">
            <i class="fa fa-check"></i> <?=$service['title']?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      <div class="desc">
        <?=$this->szallas['datas']['leiras']?>
      </div>
      <?php if ($this->szallas['datas']['services']): ?>
      <div class="services">
        <div class="wrapper">
          <?php foreach ((array)$this->szallas['datas']['services'] as $sgroup => $services): ?>
          <div class="group">
            <h3><?=$sgroup?></h3>
            <div class="serv-list">
              <?php foreach ((array)$services as $service): ?>
              <div class="">
                <i class="fa fa-star"></i> <?=$service['title']?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    fixSizes();
    $('.images .image-set > .wrapper').slick({
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: true,
      speed: 1200
    });
  });

  function fixSizes() {
    var inbody = $('.adatlap-body').width();
    $('.images .image-set.slide').css({
      width: inbody-80
    });
  }
</script>
<pre><?php //print_r($this->szallas['datas']); ?></pre>
