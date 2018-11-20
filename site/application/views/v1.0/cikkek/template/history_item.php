<article class="cikk">
  <?php
    $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep);
    $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
  ?>
  <div class="wrapper">
    <div class="image image-abs-center autocorrett-height-by-width" data-image-ratio="1:1">
      <img src="<?=$belyeg_kep?>" alt="<?=$cim?>">
    </div>
    <div class="data">
      <div class="title">
        <a href="/cikkek/<?=$read_prefix?>/<?=$eleres?>"><?=$cim?></a>
      </div>
      <?php if ($idopont): ?>
      <div class="date">
        <i class="fa fa-clock-o"></i> <?=date('Y.m.d. H:i',strtotime($idopont))?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</article>
