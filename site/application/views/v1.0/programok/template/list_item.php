<article class="program<?=($belyeg_kep == '')?' no-img':''?>">
  <?php
    $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep);
    $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
  ?>
  <div class="wrapper">
    <div class="image image-abs-center autocorrett-height-by-width" data-image-ratio="4:3">
      <img src="<?=$belyeg_kep?>" alt="<?=$cim?>">
    </div>
    <div class="datain">
      <?php if ($idopont): ?>
      <div class="ondate">
        <div class="in" title="Esemény ideje"><i class="fa fa-clock-o"></i> <?=date('Y.m.d. H:i',strtotime($idopont))?></div>
      </div>
      <?php endif; ?>
      <div class="title">
        <h3><a href="/programok/<?=$read_prefix?>/<?=$eleres?>"><?=$cim?></a></h3>
      </div>
      <?php if ($helyszin): ?>
        <div class="pos">
          <i class="fa fa-map-marker"></i> <?=$helyszin?>
        </div>
      <?php endif; ?>
      <div class="details">
        <a href="/programok/<?=$read_prefix?>/<?=$eleres?>">Program részletei</a>
      </div>
    </div>
  </div>
</article>
