<div class="wrapper">
  <?php if ($etlap): ?>
  <?php foreach ($etlap as $day => $e): ?>
  <div class="nap<?=(date('Y-m-d') == $day)?' aktualis':''?>">
    <div class="wrapper">
      <div class="head">
        <div class="day">
          <?php echo date('Y.m.d.', strtotime($day)); ?>
        </div>
        <div class="weekday">
          <?php echo $e['weekday']; ?>
        </div>
      </div>
      <div class="image autocorrett-height-by-width" data-image-ratio="4:3">
        <div class="wrapper">
          <img src="<?=($e['menu']['etel_fo'][kep])?:IMG.'no-meal.png'?>" alt="<?php echo $e['menu']['etel_fo'][neve]; ?>">
        </div>
      </div>
      <div class="menu">
        <div class="air-text">
          <?php if ($e['menu']['etel_leves']): ?>
          <div class="leves"><?=$e['menu']['etel_leves']['neve']?></div>
          <?php endif; ?>
          <?php if ($e['menu']['etel_fo']): ?>
          <div class="foetel"><?=$e['menu']['etel_fo']['neve']?></div>
          <?php endif; ?>
          <?php if ($e['menu']['etel_va']): ?>
          <div class="kieg1"><?=$e['menu']['etel_va']['neve']?></div>
          <?php endif; ?>
          <?php if ($e['menu']['etel_vb']): ?>
          <div class="kieg2"><?=$e['menu']['etel_vb']['neve']?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="ertekek">
        <div class="tabla">
          <div class="kcal">
            <table>
              <tr>
                <td class="h">Kcal</td>
                <td class="v"><?=$e['menu']['ertekek']['kaloria']?></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <td class="h">Szénhidrát (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['szenhidrat']?></td>
              </tr>
              <tr>
                <td class="h">Fehérje (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['feherje']?></td>
              </tr>
              <tr>
                <td class="h">Rost (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['ch']?></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <td class="h">Zsír (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['zsir']?></td>
              </tr>
              <tr>
                <td class="h">Cukor (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['cukor']?></td>
              </tr>
              <tr>
                <td class="h">Só (g)</td>
                <td class="v"><?=$e['menu']['ertekek']['so']?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="allergenek">
        <strong>Allergének:</strong>
        <?php if (empty($e['menu']['ertekek']['allergenek'])): ?>
          Nem tartalmaz allergéneket.
        <?php else: ?>
          <?php echo implode(", ", $e['menu']['ertekek']['allergenek']); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="no-item">
    Nem található étlap erre az időszakra. Kérjük, hogy nézzen vissza később!
  </div>
  <?php endif; ?>
</div>
