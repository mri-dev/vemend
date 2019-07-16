<div class="shop-author">
  <div class="wrapper">
    <div class="head">
      Kiválasztott bolt
    </div>
    <?php if ($this->shopauthor['shop']['shoplogo']): ?>
      <div class="logo">
        <img src="<?=$this->shopauthor['shop']['shoplogo']?>" alt="<?=$this->shopauthor['shop']['shopnev']?>">
      </div>
    <?php endif; ?>
    <div class="author">
      <h3><?=$this->shopauthor['shop']['shopnev']?></h3>
      <div class="address">
        <?=$this->shopauthor['shop']['address']?>
      </div>
      <div class="phone">
        <?=$this->shopauthor['shop']['telefon']?>
      </div>
    </div>
    <div class="opens">
      <h4>Nyitva tartás</h4>
      <?php foreach ((array)$this->shopauthor['shop']['nyitvatartas'] as $day => $op): ?>
      <div class="open">
        <div class="day"><?=$day?></div>
        <div class="op"><?=(empty($op))?'N/A':$op?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
