<article class="program">
  <?php
    $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep);
    $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
  ?>
  <div class="wrapper">
    <div class="image image-abs-center">
      <img src="<?=$belyeg_kep?>" alt="<?=$cim?>">
    </div>
    <div class="data">
      <div class="title">
        <a href="/programok/<?=$read_prefix?>/<?=$eleres?>"><?=$cim?></a>
      </div>
      <?php if ($helyszin): ?>
      <div class="pos">
        <i class="fa fa-map-marker"></i> <?=$helyszin?>
      </div>
      <?php endif; ?>
      <?php if ($idopont): ?>
      <div class="date">
        <i class="fa fa-clock-o"></i> <?=date('Y.m.d. H:i',strtotime($idopont))?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</article>
