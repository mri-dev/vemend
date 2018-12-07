<article class="<?=($belyeg_kep == '')?'no-img':''?>">
  <div class="wrapper">
    <?php
      $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep);
    ?>
    <div class="img"><a href="<?=$url?>"><img src="<?=$belyeg_kep?>" alt="<?=$cim?>"></a><?php if ($categories['list']): ?>
    <div class="in-cats">
      <?php foreach ( (array)$categories['list'] as $cat ): ?>
      <a class="cat" href="<?=($cat[is_tematic])?'/':'/cikkek/'?><?=$cat['slug']?>"><?=$cat['label']?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?></div>
    <div class="title">
      <h2><a href="<?=$url?>"><?=$cim?></a></h2>
    </div>
  	<div class="content"><?=$bevezeto?></div>
  	<div class="more"><a href="<?=$url?>">Tovább <i class="fa fa-angle-right"></i></a></div>
  	<div class="clr"></div>
  </div>
</article>
