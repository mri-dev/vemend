<h1 class="title"><?php echo $program->getTitle(); ?></h1>
<?php if ($program->getIdopont()): ?>
<div class="date-on">
  <span title="Az esemény ideje" class="date"><?=date('Y.m.d. H:i', strtotime($program->getIdopont()))?></span>
</div>
<?php endif; ?>

<div class="content">
  <?php echo $program->getHtmlContent(); ?>
</div>
