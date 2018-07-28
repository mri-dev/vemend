<div class="home main-content">
  <div class="content-wrapper">
    <div class="pw">
      <div class="top-articles">
        <div class="flex">
          <div class="top">
            <div class="wrapper">
              <div class="flex">
                <div class="image">

                </div>
                <div class="data">
                  <div class="wrapper">
                    <div class="badges">
                      <div class="badge-orange">
                        Közérdekű
                      </div>
                      <div class="badge-red">
                        Fontos
                      </div>
                      <div class="badge-green">
                        Új hír
                      </div>
                      <div class="clr"></div>
                    </div>
                    <div class="data-content">
                      <h3>Szentmisék, szertartások az Évközi 12. és 13. héten.</h3>
                      <div class="desc">
                        Június 25-től 30-ig hétköznap nem lesznek Istentiszteletek.
                      </div>
                    </div>
                    <div class="footer">
                      <div class="flex">
                        <div class="date">
                          2018.08.01.
                        </div>
                        <div class="comment">
                          <i class="fa fa-comment-o"></i> 0
                        </div>
                        <div class="views">
                          <i class="fa fa-eye"></i> 123
                        </div>
                        <div class="button">
                          <a href="#">Tovább</a>
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
                    <a href="#"><i class="fa fa-star"></i> Legnépszerűbbek</a>
                  </div>
                  <div class="more">
                    <a href="#"><i class="fa fa-bars"></i> Összes cikk</a>
                  </div>
                </div>
              </div>
              <div class="article">
                <div class="title">
                  <a href="#">A tarló- és a növényi hulladék égetésének szabályai.</a>
                </div>
                <div class="image">
                  <div class="wrapper">

                  </div>
                  <div class="excerpt">
                    <div class="wrapper">
                      A mezőgazdasági erő- és munkagépek, illetve az aratás tűzvédelmi szabályai A szabályok betartása különösen fontos...
                    </div>
                  </div>
                </div>
                <div class="footer">
                  <div class="flex">
                    <div class="date">
                      2018.08.01.
                    </div>
                    <div class="comment">
                      <i class="fa fa-comment-o"></i> 0
                    </div>
                    <div class="views">
                      <i class="fa fa-eye"></i> 123
                    </div>
                    <div class="button">
                      <a href="#">Tovább</a>
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
      <div class="banners banner-billboard">
        <div class="wrapper">
          <div class="placeholdertext">
            NAGY BANNER
          </div>
        </div>
      </div>
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
      <div class="flex">
        <div class="miserend">
          <div class="header no-border">
            <div class="flex">
              <div class="title">
                Miserend
              </div>
              <div class="more">
                <a href="#"><i class="fa fa-bars"></i> Összes miserend</a>
              </div>
            </div>
          </div>
          <div class="content-holder">
            <article class="top">
              <?php if ($is_top || true): ?>
                <div class="image">
                  <img src="" alt="">
                </div>
              <?php endif; ?>
              <div class="title">
                <a href="#">Misrended cikk címe, xx. és xx. héten.</a>
              </div>
              <div class="meta">
                <div class="date">
                  2018. június 24-től július 8-ig
                </div>
              </div>
            </article>

            <article>
              <div class="title">
                <a href="#">Misrended cikk címe, xx. és xx. héten.</a>
              </div>
              <div class="meta">
                <div class="date">
                  2018. június 24-től július 8-ig
                </div>
              </div>
            </article>

            <article>
              <div class="title">
                <a href="#">Misrended cikk címe, xx. és xx. héten.</a>
              </div>
              <div class="meta">
                <div class="date">
                  2018. június 24-től július 8-ig
                </div>
              </div>
            </article>

            <article>
              <div class="title">
                <a href="#">Misrended cikk címe, xx. és xx. héten.</a>
              </div>
              <div class="meta">
                <div class="date">
                  2018. június 24-től július 8-ig
                </div>
              </div>
            </article>
          </div>
        </div>
        <div class="etlap">
          <div class="header no-border">
            <div class="flex">
              <div class="title">Étlap</div>
              <div class="more"></div>
            </div>
          </div>
          <div class="holder">
            <div class="flex">
              <div class="naptar-block">
                <div class="wrapper">
                  <div class="content-holder">
                    naptár
                  </div>
                </div>
              </div>
              <div class="etlap-visual">
                <div class="wrapper">
                  <div class="line-one">
                    <div class="flex">
                      <div class="side-left">
                        <div class="date-on">
                          <div class="air-text">
                            <div class="week">
                               24. hét
                            </div>
                            <div class="date">
                              2018.06.09.
                            </div>
                          </div>
                        </div>
                        <div class="leves">
                          <div class="image">

                          </div>
                          <div class="text">
                            <div class="air-text">
                              Brokkolikrémleves
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="side-right">
                        <div class="foetel">
                          <div class="image">

                          </div>
                          <div class="text">
                            <div class="air-text">
                              Tarhonyás sertéshús
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="line-two">
                    <div class="flex">
                      <div class="side-left">
                        <div class="kieg1">
                          <div class="image">

                          </div>
                          <div class="text">
                            <div class="air-text">
                              Csemege uborka
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="side-right">
                        <div class="kieg2">
                          <div class="image">

                          </div>
                          <div class="text">
                            <div class="air-text" id="rothorwidthfix">
                              Alma
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <script type="text/javascript">

                    $(function(){
                      fixRotateText();
                    });

                    function fixRotateText() {
                      $('#rothorwidthfix').css({
                        width: $('.etlap-visual .line-two').height()
                      });
                    }
                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-wrapper white-content-wrapper">
    <div class="pw">
      <div class="flex">
        <div class="rendelo">
          Rendelő
        </div>
        <div class="programs">
          Programok
        </div>
      </div>
      <div class="banners">
        Bannerek
      </div>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="pw">
      <div class="szallasok">
        <div class="flex">
          <div class="szallas-kereso">
            Szállás kereső
          </div>
          <div class="szallas-top">
            Top szállás ajánlatok
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-wrapper">
    <div class="pw">
      <div class="webshop">
        webshop termékek
      </div>
    </div>
  </div>
</div>
