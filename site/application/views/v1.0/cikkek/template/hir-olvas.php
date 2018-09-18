<div class="news-content">
	<div class="head">
		<h1><?=$cim?></h1>
		<div class="subline">
			<div class="backurl">
				<a href="/cikkek"><i class="fa fa-th" aria-hidden="true"></i> összes cikk</a>
				<?php if (isset($_GET['cat']) && $_GET['cat'] != '' && $_GET['cat'] != 'olvas'): ?>
					<a href="/cikkek/<?=$_GET['cat']?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> <?=$newscats[$_GET['cat']]['neve']?></a>
				<?php endif; ?>
			</div>
			<div class="share">
				<div class="fb-like" data-href="<?=DOMAIN?>cikkek/<?=$eleres?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
			</div>
			<div class="date"><i class="fa fa-clock-o"></i> <?=substr(\PortalManager\Formater::dateFormat($letrehozva, $date_format),0,-6)?></div>
			<div class="nav">
				<ul class="cat-nav">
					<li><a href="/"><i class="fa fa-home"></i></a></li>
					<li><a href="/cikkek">Cikkek</a></li>
					<li>
						<?php foreach ( (array)$categories['list'] as $cat ): ?>
						<a class="cat" href="/cikkek/<?=\Helper::makeSafeUrl($cat['neve'])?>"><?=$cat['label']?></a>
						<?php endforeach; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="content">
		<?=\PortalManager\News::textRewrites($szoveg)?>
	</div>
</div>
