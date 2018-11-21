<div class="home main-content">
  <div class="content-wrapper">
    <div class="pw">
      <div class="top-articles">
        <div class="flex">
          <div class="top">
            <?php
              $top_hir_cats = $this->news[0]->getCategories();
            ?>
            <div class="wrapper">
              <div class="holder">
                <div class="flex">
                  <div class="image image-abs-center autocorrett-height-by-width" data-image-ratio="4:3" data-image-under="398">
                    <img src="<?=($this->news[0]->getImage(true))?$this->news[0]->getImage(true):''?>" alt="">
                  </div>
                  <div class="data">
                    <div class="wrapper">
                      <div class="badges">
                        <?php if ( !empty($top_hir_cats['ids']) ): ?>
                        <?php foreach ( $top_hir_cats['list'] as $list ): ?>
                        <div style="background-color:<?=$list['bgcolor']?>;">
                          <?=$list['neve']?>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="clr"></div>
                      </div>
                      <div class="data-content">
                        <h3><?=$this->news[0]->getTitle()?></h3>
                        <div class="desc">
                          <?=$this->news[0]->getDescription()?>
                        </div>
                      </div>
                      <div class="footer">
                        <div class="flex">
                          <div class="date">
                            <?=$this->news[0]->getIdopont('Y.m.d.')?>
                          </div>
                          <div class="comment">
                            <i class="fa fa-comment-o"></i> <?=$this->news[0]->getCommentCount()?>
                          </div>
                          <div class="views">
                            <i class="fa fa-eye"></i> <?=$this->news[0]->getVisitCount()?>
                          </div>
                          <div class="button">
                            <a href="<?=$this->news[0]->getUrl()?>">Tovább</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="more">
            <div class="wrapper">
              <div class="header">
                <div class="flex">
                  <div class="title">
                    Legújabb
                  </div>
                  <div class="nepszeru">
                    <a href="/cikkek/?by=popular&ord=ASC"><i class="fa fa-star"></i> Legnépszerűbbek</a>
                  </div>
                  <div class="more">
                    <a href="/cikkek/"><i class="fa fa-bars"></i> Összes cikk</a>
                  </div>
                </div>
              </div>
              <div class="article">
                <div class="title">
                  <a href="<?=$this->news[1]->getUrl()?>"><?=$this->news[1]->getTitle()?></a>
                </div>
                <div class="image">
                  <div class="wrapper image-abs-center by-width autocorrett-height-by-width" data-image-ratio="4:3">
                    <img src="<?=($this->news[1]->getImage(true))?$this->news[1]->getImage(true):''?>" alt="">
                  </div>
                  <?php if ($this->news[1]->getDescription() != ''): ?>
                  <div class="excerpt">
                    <div class="wrapper">
                      <?=$this->news[1]->getDescription()?>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
                <div class="footer">
                  <div class="flex">
                    <div class="date">
                      <?=$this->news[1]->getIdopont('Y.m.d.')?>
                    </div>
                    <div class="comment">
                      <i class="fa fa-comment-o"></i> <?=$this->news[1]->getCommentCount()?>
                    </div>
                    <div class="views">
                      <i class="fa fa-eye"></i> <?=$this->news[1]->getVisitCount()?>
                    </div>
                    <div class="button">
                      <a href="<?=$this->news[1]->getUrl()?>">Tovább</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="pw">
      <?php
        // Billboard banner
        $billboard_cap = $this->BANNERS->checkCapability('BILLBOARD', 1);

        if ($billboard_cap) {
          $banners = $this->BANNERS->pick('BILLBOARD', 1);
          echo $this->BANNERS->render('BILLBOARD', $banners);
        }
      ?>
    </div>
  </div>
  <? if( count($this->highlight_text) > 0 ): ?>
  <div class="content-wrapper">
    <div class="pw">
      <div class="szalag">
        <div class="highlight-view">
          <? if( count($this->highlight_text['data']) > 1 ): ?>
          <div class="p"><a href="javascript:void(0);" title="Előző" class="prev handler" key="prev"><i class="fa fa-angle-left"></i></a></div>
          <? endif; ?>
        	<div class="items">
        		<div class="hl-cont">
        			<ul>
        				<? $step = 0; foreach( $this->highlight_text['data'] as $text ): $step++; ?>
        				<li class="<?=($step == 1)?'active':''?>" index="<?=$step?>"><?=$text['tartalom']?></li>
        				<? endforeach; ?>
        			</ul>
              <div class="clr"></div>
        		</div>
        	</div>
          <? if( count($this->highlight_text['data']) > 1 ): ?>
          <div class="p"><a href="javascript:void(0);" title="Következő" class="next handler" key="next"><i class="fa fa-angle-right"></i></a></div>
          <? endif; ?>
        </div>
      </div>
    </div>
  </div>
  <? endif; ?>
  <div class="content-wrapper home-etlap-miserend">
    <div class="pw">
      <div class="holder">
        <div class="flex">
          <div class="miserend">
            <div class="wrapper">
              <div class="header no-border">
                <div class="flex">
                  <div class="title">
                    Miserend
                  </div>
                  <div class="more">
                    <a href="/cikkek/miserend"><i class="fa fa-bars"></i> Összes miserend</a>
                  </div>
                </div>
              </div>
              <div class="content-holder">
                <?php $misn = 0; foreach ($this->miserend_news as $new): $misn++; if($misn == 1){  $is_top = true; }else{  $is_top = false; } ?>
                <article class="top">
                  <?php if ($is_top ): ?>
                    <div class="image image-abs-center autocorrett-height-by-width" data-image-ratio="4:3">
                      <img src="<?=($new->getImage(true)) ? $new->getImage(true) : ''?>" alt="">
                    </div>
                  <?php endif; ?>
                  <div class="title">
                    <a href="<?=$new->getUrl('miserend')?>"><?=$new->getTitle()?></a>
                  </div>
                  <div class="meta">
                    <div class="date">
                      <?=$new->getDescription()?>
                    </div>
                  </div>
                </article>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="etlap">
            <div class="wrapper">
              <div class="header no-border">
                <div class="flex">
                  <div class="title">Étlap</div>
                  <div class="more">
                    <a href="/etlap"><i class="fa fa-cutlery"></i> Részletes étlap</a>
                  </div>
                </div>
              </div>
              <div class="holder">
                <div class="flex">
                  <div class="naptar-block">
                    <?php $this->render('templates/etlap_naptar'); ?>
                  </div>
                  <?php $this->render('templates/etlap_box'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-wrapper white-content-wrapper rendelo-program">
    <div class="pw">
      <div class="holder">
        <div class="flex">
          <div class="rendelo">
            <div class="wrapper">
              <div class="holder">
                <?php echo $this->render('templates/orvosi_rendelo'); ?>
              </div>
            </div>
          </div>
          <div class="programs">
            <div class="wrapper">
              <?php echo $this->render('templates/programs_home'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="mspacer"></div>
      <?php
        $square_cap = $this->BANNERS->checkCapability('1P1', 4);

        if (!$square_cap) {
          $block_cap = $this->BANNERS->checkCapability('2P1', 2);
        }

        if ($square_cap && $square_cap) {
          $mathrand = rand(1,20);
          if($mathrand > 10) {
            $square_cap = false;
            $block_cap = true;
          } else {
            $square_cap = true;
            $block_cap = false;
          }
        }

        if ($square_cap || $block_cap) {
          if ($square_cap) {
            $banners = $this->BANNERS->pick('1P1', 4);
            echo $this->BANNERS->render('1P1', $banners);
          }
          if ($block_cap) {
            $banners = $this->BANNERS->pick('2P1', 2);
            echo $this->BANNERS->render('2P1', $banners);
          }
        }
      ?>
      <div class="banners">
        <div class="groups">
          <div class="banner">
            <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
              <div class="placeholdertext center">
                BANNER<br>
                (1:1 - 265x265)
              </div>
            </div>
          </div>
          <div class="banner">
            <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
              <div class="placeholdertext center">
                BANNER<br>
                (1:1 - 265x265)
              </div>
            </div>
          </div>
          <div class="banner">
            <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
              <div class="placeholdertext center">
                BANNER<br>
                (1:1 - 265x265)
              </div>
            </div>
          </div>
          <div class="banner">
            <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
              <div class="placeholdertext center">
                BANNER<br>
                (1:1 - 265x265)
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="pw">
      <div class="szallasok">
        <div class="holder">
          <div class="flex">
            <?php echo $this->render("templates/szallas_kereso"); ?>
            <div class="szallas-top">
              <div class="wrapper">
                <?php echo $this->render("templates/szallas_lista"); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($this->show_webshop): ?>
  <div class="content-wrapper webshop">
    <div class="pw">
      <div class="webshop">
        <div class="head">
          <div class="ico">
            <img src="<?=IMG?>icons/cart_grey.svg" alt="">
          </div>
          <div class="title">
            Webshop ajánlatok
          </div>
          <div class="desc">
            Oldalunkon jelenleg 0 db termék közül tud választani.
          </div>
          <div class="webshopnavbar">
            <div class="wrapper">
              <div class="buttons">
                <div class="fresh current">
                  <a href="/webshop/termekek/?order=fresh&orderby=ASC"><i class="fa fa-clock-o"></i> Legújabb</a>
                </div>
                <div class="popular">
                  <a href="/webshop/termekek/?order=popular&orderby=ASC"><i class="fa fa-star"></i> Legnépszerűbb</a>
                </div>
                <div class="total">
                  <a href="/webshop/"><i class="fa fa-archive"></i> Összes webshop</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="cont">
          <?php echo $this->render("templates/webshop_lista"); ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
