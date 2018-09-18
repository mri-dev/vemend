<article class="<?=($belyeg_kep == '')?'no-img':''?>">
  <div class="wrapper">
    <?php
      $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep);
      $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
    ?>
    <div class="img"><a href="/cikkek/<?=$read_prefix?>/<?=$eleres?>"><img src="<?=$belyeg_kep?>" alt="<?=$cim?>"></a></div>
    <div class="title">
      <h2><a href="/cikkek/<?=$read_prefix?>/<?=$eleres?>"><?=$cim?></a></h2>
    </div>
  	<div class="content"><?=$bevezeto?></div>
  	<div class="more"><a href="/cikkek/<?=$read_prefix?>/<?=$eleres?>">Tovább <i class="fa fa-angle-right"></i></a></div>
  	<div class="clr"></div>
  </div>
</article>
