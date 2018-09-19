<div class="item">
	<div class="irow">
	 	<div class="image">
			<a href="<?=$link?>"><img title="<?=$product_nev?>" src="<?=$profil_kep?>" alt="<?=$product_nev?>"></a>
		</div>
		<div class="details">
			<div class="holder">
				<div class="info-row-top">
					<? if($garancia_honap > 0): ?>
					<div class="garancia">
						<div class="year">
							<? $gh = $garancia_honap / 12;  ?>
							<? if($gh < 1 ): ?>
								<?=$garancia_honap?> <?=__('hónap')?>
							<? else: ?>
								<?=$gh?> <?=__('év')?>
							<? endif; ?>

						</div>
						<?=__('EU garancia')?>
					</div>
					<? endif; ?>
					<div class="infos">
						<div class="cimkek">
							<? if($ujdonsag): ?>
                <img src="<?=IMG?>new_icon.png" title="Újdonság!" alt="Újdonság">
                <? endif; ?>
            </div>
						<div class="title">
							<h3><a href="<?=$link?>"><?=$product_nev?></a></h3>
						</div>
						<div class="subtitle"><?=__($csoport_kategoria)?></div>
						<div class="params">
							<? if( false ): ?>
								<? if( $parameters ): ?>
	                <table>
                        <? $pi = 0; foreach( $parameters as $param ): if( $pi >= 3 ) break; ?>
                        <tr>
                            <td><?=__($param['neve'])?></td>
                            <td><strong><?=__($param['ertek'])?> <?=__($param['me'])?></strong></td>
                        </tr>
                        <?  $pi++; endforeach; ?>
	                </table>
	            	<? else: ?>
	            	<div style="display:block; line-height: 51px; text-align:center;">&mdash;<em> <?=__('nincsennek termék jellemzők')?> </em>&mdash;</div>
	                <? endif; ?>
	            <? endif; ?>
	            <? if( true ): ?>
	            	<div class="short-desc">
									<?=$marketing_leiras?>
	            	</div>
	            <? endif; ?>
						</div>
					</div>
				</div>
				<div class="info-row-bottom">
					<div class="price">
						<? $ar = $brutto_ar; ?>
						<?
						if( $akcios == '1' ):
						$ar = $akcios_fogy_ar;
						?>
						<div class="old"><div class="percents">-<? echo 100-round($akcios_fogy_ar / ($brutto_ar / 100)); ?>%</div> <?=Helper::cashFormat($brutto_ar)?> <?=$valuta?> </div>
						<? endif; ?>
						<div class="current"><?=Helper::cashFormat($ar)?> <?=$valuta?></div>
					</div>
					<? if( !empty($ajandek) ): ?>
					<div class="gift">
						<img src="<?=IMG?>gift_20pxh.png" alt="<?=__('Ajándék')?>">
						<?=$ajandek?>
					</div>
					<? endif; ?>
					<? if( !empty($termek_site_url) ): ?>
					<div class="url"><a target="_blank" href="<?=$termek_site_url?>"><?=__('termék oldal')?></a></div>
					<? endif; ?>
				</div>
			</div>
		</div><!--
	 --><div class="buttons">
	 		<div class="holder">
	 			<div class="watch"><a href="<?=$link?>"><?=__('megnézem')?></a></div>
				<div class="order"><a href="<?=$link?>?buy=now"><?=__('megrendelem')?></a></div>
	 		</div>
		</div>
	</div>
	<div class="clr"></div>
	<div class="extra-info">
	<? if( false ): ?>
		<? if( true ): ?>
			<? if( $ar >= $settings['FREE_TRANSPORT_ABOVEPRICE']): ?>
				<div class="each"><img src="<?=IMG?>clock_small_white_15pxh.png" alt="<?=__('24 órán belüli ingyenes szállítás')?>"> &nbsp; <?=__('24 órán belüli ingy
			enes szállítás')?></div>
			<? else: ?>
				<div class="each"><img src="<?=IMG?>clock_small_white_15pxh.png" alt="<?=__('24 órán belüli szállítás')?>"> &nbsp; <?=__('24 órán belüli szállítás')?></div>
			<? endif; ?>
		<? endif; ?>
		<div class="each"><img src="<?=IMG?>person_small_white_10pxh.png" alt="<?=__('Személyesen is átvehető')?>"> &nbsp; <?=__('Személyesen is átvehető')?></div>
	<? endif; ?>
	<div class="each"><i class="fa fa-truck"></i> &nbsp; <?=__('Ingyenes kiszállítás otthonába!')?></div>
	<div class="each"><i class="fa fa-building-o"></i> &nbsp; <?=__('Casada Shopokban személyesen is átvehető!')?></div>
	</div>
</div>
