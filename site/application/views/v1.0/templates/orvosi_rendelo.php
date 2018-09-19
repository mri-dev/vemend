<div class="title">
  <div class="ico">
    <img src="<?=IMG?>heartheart.svg" alt="Orvosi rendelő">
  </div>
  <h3>Orvosi rendelő</h3>
</div>
<div class="cont">
  <div class="infos">
    <div class="">
      <i class="fa fa-user"></i> <?=$this->settings['orvosi_rendelo_orvos_nev']?>
    </div>
    <div class="">
      <i class="fa fa-home"></i> <?=$this->settings['orvosi_rendelo_cim']?>
    </div>
    <div class="">
      <i class="fa fa-phone"></i> <?=$this->settings['orvosi_rendelo_telefon']?>
    </div>
    <div class="">
      <i class="fa fa-clock-o"></i> <?=$this->settings['orvosi_rendelo_ugyelet']?>
    </div>
  </div>
  <div class="opens">
    <?php foreach ($this->settings['orvosi_rendelo_nyitva_tartas'] as $nap => $val): ?>
      <div class="flex">
        <div class="day">
          <?=$nap?>
        </div>
        <div class="hours">
          <?=$val?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
