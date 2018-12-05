<h1 class="title"><?php echo $program->getTitle(); ?></h1>
<div class="details">
  <?php if ($program->getIdopont()): ?>
  <span title="Az esemény ideje" class="date"><i class="fa fa-clock-o"></i> <?=date('Y.m.d. H:i', strtotime($program->getIdopont()))?></span>
  <?php endif; ?>
  <?php if ($program->getHelyszin()): ?>
  <span title="Az esemény helyszíne" class="position"><i class="fa fa-map-marker"></i> <?=$program->getHelyszin()?></span>
  <?php endif; ?>
</div>
<div class="content">
  <?php echo $program->getHtmlContent(); ?>
</div>
