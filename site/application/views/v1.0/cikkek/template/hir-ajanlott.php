<div class="item">
	<div class="in">
		<div class="img img-thb"><a href="/hirek/<?=$eleres?>"><img class="" src="/render/thumbnail/?i=<?=\PortalManager\Formater::sourceImg($belyeg_kep)?>&w=250" alt="<?=$cim?>"></a></div>
		<div class="title"><a title="<?=$cim?>" href="/cikkek/<?=$eleres?>"><?=(strlen($cim) > 60)?substr($cim, 0, 60).'...':$cim?></a></div>
		<div class="date"><?=substr(\PortalManager\Formater::dateFormat($letrehozva, $date_format),0,-6)?></div>
	</div>
</div>
