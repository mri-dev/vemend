<div class="news-page">
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
					dots: true
				});
			})
		</script>

		<? else: ?>
		<div class="news-list">
			<div class="pw">
				<div class="categories sidebar">
					<h2>Kategóriák</h2>
					<div class="list">
						<div class="cat <?=($_GET['cat'] == '')?'active':''?>">
							<a href="/cikkek/"><span class="dot" style="color:black;"></span> Összes bejegyzés</a>
						</div>
						<?php foreach ( (array)$this->newscats as $nc ): ?>
						<div class="cat <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
							<a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':'/cikkek/'?><?=($nc['slug'])?>"><span class="dot" style="color:<?=$nc['bgcolor']?>;"></span> <?=$nc['neve']?></a>
						</div>
						<?php endforeach; ?>
					</div>

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
