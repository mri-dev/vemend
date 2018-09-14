<div class="item top-item">
	<h2><a href="/cikkek/<?=$eleres?>"><?=$cim?></a></h2>
	<div class="subline">
		<div class="share">
			<div class="fb-like" data-href="<?=DOMAIN?>cikkek/<?=$eleres?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
		</div>
		<span class="date"><?=substr(\PortalManager\Formater::dateFormat($letrehozva, $date_format),0,-6)?></span>
	</div>
	<div class="clr"></div>
	<div class="img"><a href="/cikkek/<?=$eleres?>"><img src="<?=\PortalManager\Formater::sourceImg($belyeg_kep)?>" alt="<?=$cim?>"></a></div>		
	<div class="content"><?=str_replace( '../../src/','admin/src/', $bevezeto)?></div>
	<div class="more"><a href="/cikkek/<?=$eleres?>">Tovább <i class="fa fa-angle-right"></i></a></div>
	<div class="clr"></div>
</div>
