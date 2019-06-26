
	<?php if ( !$this->homepage ): ?>
		</div> <!-- .inside-content -->
		<div class="clr"></div>
		</div><!-- #main -->
		<div class="clr"></div>
	</div><!-- website -->
	<?php endif; ?>

	<footer>
		<div class="main">
			<div class="pw">
				<div class="wrapper">
					<div class="flex">
						<div class="links">
							<div class="segitseg">
								<h3>Tájékoztatók</h3>
								<ul>
									<? foreach ( $this->menu_footer->tree as $menu ): ?>
										<li>
											<? if($menu['link']): ?><a href="<?=($menu['link']?:'')?>"><? endif; ?>
												<span class="item <?=$menu['css_class']?>" style="<?=$menu['css_styles']?>">
													<? if($menu['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($menu['kep'])?>"><? endif; ?>
													<?=$menu['nev']?></span>
											<? if($menu['link']): ?></a><? endif; ?>
											<? if($menu['child']): ?>
												<? foreach ( $menu['child'] as $child ) { ?>
													<div class="item <?=$child['css_class']?>">
														<?
														// Inclue
														if(strpos( $child['nev'], '=' ) === 0 ): ?>
															<? echo $this->templates->get( str_replace('=','',$child['nev']), array( 'view' => $this ) ); ?>
														<? else: ?>
														<? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
														<? if($child['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
														<span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
														<? if($child['link']): ?></a><? endif; ?>
														<? endif; ?>
													</div>
												<? } ?>
											<? endif; ?>
										</li>
									<? endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="subs">
							<?php if (false): ?>
								<h3>Feliratkozás</h3>
								<div class="subbox">
									<div class="wrapper">
										<div class="form">
											<form class="" action="/feliratkozas" method="get">
												<div class="name">
													<div class="flex flexmob-exc-resp">
														<div class="ico">
															<i class="fa fa-user"></i>
														</div>
														<div class="input">
															<input type="text" name="name" value="" placeholder="Név">
														</div>
													</div>
												</div>
												<div class="email">
													<div class="flex flexmob-exc-resp">
														<div class="ico">
															<i class="fa fa-envelope"></i>
														</div>
														<div class="input">
															<input type="text" name="email" value="" placeholder="E-mail">
														</div>
													</div>
												</div>
												<div class="aszf">
													<input type="checkbox" name="cb_av" id="subs_av" value=""> <label for="subs_av"> <a href="/p/adatvedelmi-tajekoztato" target="_blank">Adatvédelmi Tájékoztatót</a> elolvastam és megértettem.</label>
												</div>
												<div class="aszf">
													<input type="checkbox" name="cv_marketing" id="subs_marketing" value=""> <label for="subs_marketing"> Hozzájárulok e-mail címem marketing célú használatához.</label>
												</div>

												<div class="button">
													<button type="submit" name="subscribe">Mehet</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="contacts">
							<div class="searcher">
								<h3>Keresés</h3>
			          <div class="searchform">
			            <form class="" action="<?=$this->searchercontrol['url']?>" method="get">
			            <div class="flex flexmob-exc-resp">
			              <div class="input">
			                <input type="text" name="src" value="<?=$_GET['src']?>" placeholder="<?=$this->searchercontrol['placeholder']?>">
			              </div>
			              <div class="button">
			                <button type="submit"><i class="fa fa-search"></i></button>
			              </div>
			            </div>
			            </form>
			          </div>
			        </div>
							<div class="contact">
								<h3>Kapcsolat</h3>
								<h2>Polgármesteri hivatal</h2>
								<div class="">
									<i class="fa fa-phone"></i> Telefon: <a href="tel:<?php echo $this->settings['page_author_phone']; ?>"><?php echo $this->settings['page_author_phone']; ?></a>
								</div>
								<div class="">
									<i class="fa fa-envelope"></i> E-mail: <?php echo $this->settings['office_email']; ?>
								</div>
								<div class="">
									<i class="fa fa-map-marker"></i> Cím: <?php echo $this->settings['page_author_address']; ?>
								</div>
							</div>
							<div class="social">
								<div class="flex flexmob-exc-resp">
									<?php if ( !empty($this->settings['social_facebook_link'])) : ?>
									<div class="facebook">
										<a target="_blank" title="Facebook oldalunk" href="<?=$this->settings['social_facebook_link']?>"><i class="fa fa-facebook"></i></a>
									</div>
									<?php endif; ?>
									<?php if ( !empty($this->settings['social_youtube_link'])) : ?>
									<div class="youtube">
										<a target="_blank" title="Youtube csatornánk" href="<?=$this->settings['social_youtube_link']?>"><i class="fa fa-youtube"></i></a>
									</div>
									<?php endif; ?>
									<?php if ( !empty($this->settings['social_googleplus_link'])) : ?>
									<div class="googleplus">
										<a target="_blank" title="Google+ oldalunk" href="<?=$this->settings['social_googleplus_link']?>"><i class="fa fa-google-plus"></i></a>
									</div>
									<?php endif; ?>
									<?php if ( !empty($this->settings['social_twitter_link'])) : ?>
									<div class="twitter">
										<a target="_blank" title="Twitter oldalunk" href="<?=$this->settings['social_twitter_link']?>"><i class="fa fa-twitter"></i></a>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="info">
			<div class="pw">
				<div class="flex">
					<div class="contact">
						<div class="megbizott">
							<i class="fa fa-user"></i> Körzeti megbízott neve: <?=$this->settings['korzeti_megbizott']?>
						</div>
						<div class="phone">
							 <i class="fa fa-phone"></i> <?=$this->settings['mobile_number']?>
						</div>
						<div class="fogadoora">
							<div class="flex">
								<div class="ido">
									<i class="fa fa-clock-o"></i> <strong>Fogadóóra:</strong> <?=$this->settings['mobile_number_elerhetoseg']?>
								</div>
								<div class="hely">
									<i class="fa fa-map-pin"></i> <strong>Fogadóóra helye:</strong> <?=$this->settings['fogadoora_helye']?>
								</div>
							</div>
						</div>
					</div>
					<div class="right-side">
						<div class="flex emblems">
							<div class="szod">
								<img src="<?=IMG?>szod_emblem.png" alt="SZÖD" style="height:60px;">
							</div>
							<div class="tett">
								<img src="<?=IMG?>tett_emblem.png" alt="Tett">
							</div>
							<div class="kerekpar">
								<img src="<?=IMG?>kerekparbarat_emblem.png" alt="Kerékpárbarát munkahely">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="bottom">
				<div class="pw">
					&copy; 2018 <span class="author"><?=$this->settings['page_author']?></span> internetes oldala &mdash; Minden jog fenntartva! | Fejlesztette: <a href="https://www.web-pro.hu" target="_blank">www.web-pro.hu</a>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
