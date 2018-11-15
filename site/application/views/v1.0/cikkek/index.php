<div class="news-page<?=($this->is_archiv)?' archive-list':''?>">
		<? if( $this->news ):
			$arg = $this->news->getFullData();
			$arg['date_format'] = $this->settings['date_format'];
			$arg['categories'] = $this->news->getCategories();
			$arg['newscats'] = $this->newscats;
			$arg['is_tematic'] = (in_array($arg['categories']['list'][0][slug], $this->news->tematic_cikk_slugs)) ? true : false;
		?>
		<div class="pw">
			<? echo $this->template->get( 'hir-olvas',  $arg ); ?>
		</div>

		<div class="news related-news">
			<div class="pw">
				<h2 class="title">További cikkek amik érdekelhetik:</h2>
				<div class="articles">
					<?
					$step = 0;
					while ( $this->related->walk() ) {
						$step++;
						$arg = $this->related->the_news();
						$arg['date_format'] = $this->settings['date_format'];
						$arg['newscats'] = $this->newscats;
						echo $this->template->get( 'slide', $arg );
					}?>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(function(){
				$('.news.related-news .articles').slick({
					infinite: true,
				  slidesToShow: 3,
				  slidesToScroll: 1,
					dots: true,
					mobileFirst:true,
					responsive: [
				    {
				      breakpoint: 1023,
				      settings: {
				        slidesToShow: 2
				      }
				    },
						{
				      breakpoint: 398,
				      settings: {
				        slidesToShow: 1
				      }
				    }
				  ]
				});
				fixSlideWidth();
			});
		</script>

		<? else: ?>
		<div class="news-list">
			<div class="pw">
				<div class="categories sidebar">
					<?php if ($this->is_archiv): ?>
					<div class="backurl">
						<a href="/cikkek/">vissza az aktív bejegyzésekhez</a>
					</div>
					<div class="clr"></div>
					<br>
					<?php endif; ?>
					<h2>Kategóriák</h2>
					<div class="list">
						<div class="cat <?=($_GET['cat'] == '')?'active':''?>">
							<a href="<?=$this->cikkroot?>"><span class="dot" style="color:black;"></span> Összes bejegyzés</a>
						</div>
						<?php foreach ( (array)$this->newscats as $nc ): if($this->is_archiv && in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs)) continue; ?>
						<div class="cat <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
							<a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':$this->cikkroot?><?=($nc['slug'])?>"><span class="dot" style="color:<?=$nc['bgcolor']?>;"></span> <?=$nc['neve']?> <span class="badge"><?=$nc['postc']?></span></a>
						</div>
						<?php endforeach; ?>
					</div>

					<?php if ($this->is_archiv && !empty($this->archive_dates)): ?>
					<h2>Archívum</h2>
					<div class="list">
						<div class="cat <?=($_GET['date'] == '')?'active':''?>">
							<a href="<?=$this->cikkroot?>">Összes</a>
						</div>
						<?php foreach ((array)$this->archive_dates as $nc): ?>
						<div class="cat <?=($_GET['date'] == ($nc['date']))?'active':''?>">
							<a href="<?=$this->cikkroot.'date/'.$nc['date'].'/1'?>"><?=$nc['datef']?> <span class="badge"><?=$nc['posts']?></span></a>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<div class="box">
						<div class="header">
							Megtekintett cikkek
						</div>
						<div class="c">
							<div class="history-list article-list">
								<?php
		            $step = 0;
		            if ($this->history->tree_items > 0)
		            {
		              while ( $this->history->walk() ) {
		                $step++;
		                $arg = $this->history->the_news();
		                $arg['programcats'] = $this->programcats;
		                $temp = $this->template->get( 'history_item', $arg );
		                echo $temp;
		              }
		            }
		            ?>
							</div>
						</div>
					</div>
				</div>
				<div class="art-list">
					<div class="articles">
						<?
						$step = 0;
						if ($this->list->tree_items > 0)
						{
							while ( $this->list->walk() ) {
								$step++;
								$arg = $this->list->the_news();
								$arg['date_format'] = $this->settings['date_format'];
								$arg['newscats'] = $this->newscats;
					      $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
								$arg['url'] = $this->list->getUrl($read_prefix, true);

								echo $this->template->get( 'hir', $arg );
							}
						} else {
							?>
							<div class="no-news">
								<h3>Nincsenek cikkek.</h3>
								A keresési feltételek alapján nem találtunk bejegyzéseket.
							</div>
							<?
						}
						?>
					</div>
					<?=($this->list->tree_items > 0)?$this->navigator:''?>
				</div>
			</div>
		</div>
		<? endif; ?>
</div>
