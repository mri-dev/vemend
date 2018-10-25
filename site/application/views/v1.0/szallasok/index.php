<?php if (isset($_GET['adatlap'])): ?>
  <div class="szallas-adatlap">
    <?php echo $this->render("szallasok/template/szallas_configurator"); ?>
    <?php echo $this->render("szallasok/template/adatlap"); ?>
  </div>
<?php else: ?>
  <div class="szallas-lista">
    <?php echo $this->render("templates/szallas_kereso"); ?>
    <?php echo $this->render("templates/szallas_lista"); ?>
  </div>
<?php endif; ?>
