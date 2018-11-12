<?php
  $menu = $this->menu;
  $set = $this->set;

  $kieg = array();
  $kieg[] = $menu['menu']['etel_va'];
  $kieg[] = $menu['menu']['etel_vb'];
  $foetel = $menu['menu']['etel_fo'];
  $leves = $menu['menu']['etel_leves'];
?>
<div class="etlap-page">
  <div class="pw">
    <div class="page-wrapper">
      <div class="menu-line">
        <div class="naptar-block">
          <?php $this->render('templates/etlap_naptar'); ?>
        </div>
        <div class="current-menu">
          <div class="top">
            <div class="pretitle">
              <div class="m">
                <div class="fin">
                  <i class="fa fa-calendar"></i> MENÜ
                </div>
              </div>
              <div class="v">
                <div class="fin">
                  <div class="date"><?php echo date('Y.m.d.', strtotime($menu['nap'])); ?></div>
                  <div class="day"><?=$menu['nap_nev']?></div>
                </div>
              </div>
            </div>
            <div class="eteltablazat">
              <div class="head">
                <div class="fin">
                  <img src="<?=IMG?>evoeszkoz_grey.svg" alt="Evőeszköz">
                </div>
              </div>
              <div class="ertekek">
                <div class="tabla">
                  <div class="kcal">
                    <table>
                      <tr>
                        <td class="h">Kcal</td>
                        <td class="v"><?=$menu['menu']['ertekek']['kaloria']?></td>
                      </tr>
                    </table>
                  </div>
                  <div>
                    <table>
                      <tr>
                        <td class="h">Szénhidrát (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['szenhidrat']?></td>
                      </tr>
                      <tr>
                        <td class="h">Fehérje (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['feherje']?></td>
                      </tr>
                      <tr>
                        <td class="h">Rost (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['ch']?></td>
                      </tr>
                    </table>
                  </div>
                  <div>
                    <table>
                      <tr>
                        <td class="h">Zsír (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['zsir']?></td>
                      </tr>
                      <tr>
                        <td class="h">Cukor (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['cukor']?></td>
                      </tr>
                      <tr>
                        <td class="h">Só (g)</td>
                        <td class="v"><?=$menu['menu']['ertekek']['so']?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="allergenek">
                  <strong>Allergének:</strong>
                  <?php if (empty($menu['menu']['ertekek']['allergenek'])): ?>
                    Nem tartalmaz allergéneket.
                  <?php else: ?>
                    <?php echo implode(", ", $menu['menu']['ertekek']['allergenek']); ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="menu">
            <div class="leves<?=(!$leves)?' disabled':''?><?=(!$leves[kep])?' no-image':''?>">
              <div class="image">
                <div class="wrapper">
                  <img src="<?=($leves[kep])?:IMG.'no-meal.png'?>" alt="<?php echo $leves[neve]; ?>">
                </div>
              </div>
              <div class="text">
                <div class="air-text">
                  <?php echo $leves[neve]; ?>
                </div>
              </div>
            </div>
            <div class="footel<?=(!$foetel)?' disabled':''?><?=(!$foetel[kep])?' no-image':''?>">
              <div class="image">
                <div class="wrapper">
                  <img src="<?=($foetel[kep])?:IMG.'no-meal.png'?>" alt="<?php echo $foetel[neve]; ?>">
                </div>
              </div>
              <div class="text">
                <div class="air-text">
                  <?php echo $foetel[neve]; ?>
                </div>
              </div>
            </div>
            <div class="kieg1<?=(!$kieg[0])?' disabled':''?><?=(!$kieg[0][kep])?' no-image':''?>">
              <div class="image">
                <div class="wrapper">
                  <img src="<?=($kieg[0][kep])?:IMG.'no-meal.png'?>" alt="<?php echo $kieg[0][neve]; ?>">
                </div>
              </div>
              <div class="text">
                <div class="air-text">
                  <?php echo $kieg[0][neve]; ?>
                </div>
              </div>
            </div>
            <div class="kieg2<?=(!$kieg[1])?' disabled':''?><?=(!$kieg[1][kep])?' no-image':''?>">
              <div class="image">
                <div class="wrapper">
                  <img src="<?=($kieg[1][kep])?:IMG.'no-meal.png'?>" alt="<?php echo $kieg[1][neve]; ?>">
                </div>
              </div>
              <div class="text">
                <div class="air-text">
                  <?php echo $kieg[1][neve]; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="bottom">
            <div class="deadline">
              <div class="wrapper">
                <div class="text">
                  <div class="fin">
                    <i class="fa fa-clock-o"></i> A következő heti menü befizetésének időpontja:
                  </div>
                </div>
                <div class="val">
                  <div class="fin">
                    <?php echo date('Y.m.d.', strtotime($this->mondayfriday[1])); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="contact">
              <div class="wrapper">
                <div class="text">
                  <div class="fin">
                    <i class="fa fa-phone"></i> Érdeklődés telefonon
                  </div>
                </div>
                <div class="val">
                  <div class="fin">
                    06 30 123 1234
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="week-menus">
        <h2>Aktuális hét</h2>
        <div class="menu-set">
          <div class="header">
            <h3><?=$menu['hetvege']?>. hét <span class="daterange"><?=$set['weeks'][$menu['hetvege']]['dateranges']['range']?></span> </h3>
          </div>
          <div class="set">
            <?php echo $this->template->get('etlap_het', array(
              'etlap' => $set['weeks'][$menu['hetvege']]['days']
            )); ?>
          </div>
        </div>
        <?php if (isset($_GET['from']) && isset($_GET['to'])): ?>
          <a name="/menuset"></a>
          <h2 class="datefilterweek">Kiválasztott időpontok szerinti étlapok <span class="daterange"><?=date('Y.m.d.', strtotime($_GET['from']))?> - <?=(isset($_GET['to']) && !empty($_GET['to']))?date('Y.m.d.', strtotime($_GET['to'])):''?></span></h2>
          <?php $weeks = $set['weeks']; unset($weeks[$menu['hetvege']]); ?>
          <?php if (empty($weeks)): ?>
            <div class="no-etlap-selection">
              A kiválasztott időpontra nincs elérhető étlap. Kérjük, hogy nézzen vissza később vagy adjon meg közelebbi dátumot.
            </div>
          <?php endif; ?>
          <?php foreach ((array)$weeks as $week => $weekdata): ?>
          <div class="menu-set">
            <div class="header">
              <h3><?=$week?>. hét <span class="daterange"><?=$weekdata['dateranges']['range']?></span></h3>
            </div>
            <div class="set">
              <?php echo $this->template->get('etlap_het', array(
                'etlap' => $weekdata['days']
              )); ?>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
