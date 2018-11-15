<div class="header no-border">
  <div class="flex">
    <div class="title">
      Legközelebbi program
    </div>
    <div class="more">
      <a href="/programok"><i class="fa fa-archive"></i> Összes program</a>
    </div>
  </div>
</div>
<div class="cont-holder">
  <div class="wrapper">
    <div class="image image-abs-center autocorrett-height-by-width" data-image-ratio="4:3" data-image-under="398">
      <img src="<?=($this->program->getImage(true))?$this->program->getImage(true):''?>" alt="<?php echo $this->program->getTitle(); ?>">
    </div>
    <div class="data">
      <div class="wrapper">
        <div class="napico">
          <i class="fa fa-calendar"></i>
        </div>
        <div class="badges">
          <?php if (!is_null($this->program->getIdopont())): ?>
          <div class="badge-orange" title="Esemény ideje">
            <i class="fa fa-clock-o"></i> <?php echo $this->program->getIdopont('Y.m.d. H:i'); ?>
          </div>
          <?php endif; ?>
        </div>
        <div class="data-content">
          <h3><?php echo $this->program->getTitle(); ?></h3>
          <div class="desc">
            <?php echo $this->program->getDescription(); ?>
          </div>
        </div>
        <div class="footer">
          <div class="flex">
            <div class="off"></div>
            <div class="button">
              <a href="<?php echo $this->program->getUrl(); ?>">Tovább</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
