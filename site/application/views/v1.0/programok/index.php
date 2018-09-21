<div class="programs-view">
  <div class="pw">
    <div class="content-view-holder">
      <div class="sidebar">
        <div class="box orange-header">
          <div class="header">
            Program kereső
          </div>
          <div class="c">
            ...
          </div>
        </div>

        <div class="categories">
					<div class="list">
						<div class="cat <?=($_GET['cat'] == '')?'active':''?>">
							<a href="/programok/"><span class="dot" style="color:black;"></span> Összes program</a>
						</div>
						<?php foreach ( (array)$this->programcats as $nc ): ?>
						<div class="cat <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
							<a href="/programok/<?=($nc['slug'])?>"><?=$nc['neve']?></a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
        <div class="box">
          <div class="header">
            Korábban megtekintett
          </div>
          <div class="c">
            ...
          </div>
        </div>
      </div>
      <div class="view-holder">
        <ul class="cat-nav">
          <li><a href="/"><i class="fa fa-home"></i></a></li>
          <li><a href="/programok">Programok</a></li>
          <?php if (isset($_GET['cat']) && $_GET['cat'] != ''): ?>
          <li><a href="/programok/<?=$_GET['cat']?>"><?=$this->programcats[$_GET['cat']]['neve']?></a></li>
          <?php endif; ?>
        </ul>
        <?php if ($_GET['list'] == '1'): ?>
        <div class="program-list">
          <div class="wrapper">
            <div class="articles">
  						<?
  						$step = 0;
  						if ($this->list->tree_items > 0)
  						{
  							while ( $this->list->walk() ) {
  								$step++;
  								$arg = $this->list->the_news();
  								$arg['programcats'] = $this->programcats;
  								$temp = $this->template->get( 'list_item', $arg );
                  echo $temp;
  							}
  						} else {
  							?>
  							<div class="no-news">
  								<h3>Nincsenek programok.</h3>
  								A keresési feltételek alapján nem találtunk programot.
  							</div>
  							<?
  						}
  						?>
  					</div>
  					<?=($this->list->tree_items > 0)?$this->navigator:''?>
          </div>
        </div>
        <?php elseif(isset($_GET['reader'])): ?>
          <div class="program-page">
            <div class="wrapper">
              <?php echo $this->template->get( 'adatlap', array(
                'program' => $this->news
              ) ); ?>
            </div>
          </div>
        <?php endif; ?>
        <?php print_r($_GET); ?>
      </div>
    </div>
  </div>
</div>
