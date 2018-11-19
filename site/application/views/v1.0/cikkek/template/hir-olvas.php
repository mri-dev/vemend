<div class="news-content">
	<?php if ($optional_logo): ?>
	<div class="logo">
		<img src="<?=ADMROOT?><?=$optional_logo?>" alt="<?=$cim?>">
	</div>
	<?php endif; ?>
	<div class="head">
		<h1><?=$cim?></h1>
		<div class="subline">
			<div class="backurl">
				<a href="/cikkek"><i class="fa fa-th" aria-hidden="true"></i> összes cikk</a>
				<?php if (isset($_GET['cat']) && $_GET['cat'] != '' && $_GET['cat'] != 'olvas'): ?>
					<a href="<?=($is_tematic)?'/':'/cikkek/'?><?=$_GET['cat']?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> <?=$newscats[$_GET['cat']]['neve']?></a>
				<?php endif; ?>
			</div>
			<div class="share">
				<div class="fb-like" data-href="<?=DOMAIN?>cikkek/<?=$eleres?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
			</div>
			<div class="date"><i class="fa fa-clock-o"></i> <?=substr(\PortalManager\Formater::dateFormat($letrehozva, $date_format),0,-6)?></div>
			<div class="nav">
				<ul class="cat-nav">
					<li><a href="/"><i class="fa fa-home"></i></a></li>
					<?php if (!$is_tematic): ?>
					<li><a href="/cikkek">Cikkek</a></li>
					<?php endif; ?>
					<li>
						<?php foreach ( (array)$categories['list'] as $cat ): ?>
						<a class="cat" href="<?=($is_tematic)?'/':'/cikkek/'?><?=\Helper::makeSafeUrl($cat['neve'])?>"><?=$cat['label']?></a>
						<?php endforeach; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="content">
		<?php if ($optional_firstimage): ?>
		<div class="content-firstimage">
			<img src="<?=ADMROOT?><?=$optional_firstimage?>" alt="<?=$cim?>">
		</div>
		<?php endif; ?>
		<?=\PortalManager\News::textRewrites($szoveg)?>
		<?php $contacts = json_decode($optional_contacts, \JSON_UNESCAPED_UNICODE);?>
		<?php if ($contacts['email'] ||$contacts['phone']): ?>
			<div class="contact-infos">
				<h2>Kapcsolat adatok</h2>
				<?php if ($contacts['email']): ?>
				<div class="">
					<i class="fa fa-envelope-o"></i> <a href="mailto:<?=$contacts['email']?>"><?=$contacts['email']?></a>
				</div>
				<?php endif; ?>
				<?php if ($contacts['phone']): ?>
				<div class="">
					<i class="fa fa-phone"></i> <a href="tel:<?=$contacts['phone']?>"><?=$contacts['phone']?></a>
				</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="clr"></div>

	<div class="row">
		<?php if ($optional_nyitvatartas != ''): $nyitvatartas = json_decode($optional_nyitvatartas, \JSON_UNESCAPED_UNICODE); ?>
		<?php $noopensdata = 0; foreach ((array)$nyitvatartas as $nap => $v): if($v == '') $noopensdata++; endforeach; ?>
		<?php  if ($noopensdata < 7): ?>
		<div class="<?=($optional_maps == '') ? 'col-md-4' : 'col-md-4'?>">
			<div class="content-block content-nyitvatartas">
				<div class="wrapper">
					<div class="header">
						<i class="fa fa-clock-o"></i> Nyitvatartási idő
					</div>
					<div class="nyitvatartas">
					<?php foreach ((array)$nyitvatartas as $nap => $v): ?>
					<div class="row">
						<div class="col-md-4 day">
							<strong><?=$nap?></strong>
						</div>
						<div class="col-md-8 opens">
							<?=($v != '')?$v:'&mdash;'?>
						</div>
					</div>
					<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php endif; ?>
		<?php if ($optional_maps != ''): ?>
		<div class="<?=($noopensdata == 7) ? 'col-md-12' : 'col-md-8'?>">
			<div class="content-block content-map">
				<div class="wrapper">
					<div class="header">
						<i class="fa fa-map"></i> <?php echo $optional_maps; ?>
					</div>
					<div class="map" id="map">
						<iframe
						  width="100%"
						  height="450"
						  frameborder="0" style="border:0"
						  src="https://www.google.com/maps/embed/v1/place?
							key=<?=APIKEY_GOOGLE_MAP_EMBEDKEY?>&
							q=<?=$optional_maps?>&
							language=hu-HU" allowfullscreen>
						</iframe>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>




</div>
