<article class="<?=($belyeg_kep == '')?'no-img':''?>">
  <div class="wrapper">
    <?php $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep); ?>
    <div class="img autocorrett-height-by-width" data-image-ratio="4:3"><a href="<?php echo $url; ?>"><img src="<?=$belyeg_kep?>" alt="<?=$cim?>"></a></div>
    <div class="title"><h2><a href="<?php echo $url; ?>"><?=$cim?></a></h2></div>
  	<div class="content"><?=$bevezeto?></div>
  	<div class="more"><a href="<?php echo $url; ?>">Tovább <i class="fa fa-angle-right"></i></a></div>
  	<div class="clr"></div>
  </div>
</article>
