<?php
  $aktualis_etlap = $this->etlap->aktualisMenu();
  $kieg = array();
  $kieg[] = $aktualis_etlap['menu']['etel_va'];
  $kieg[] = $aktualis_etlap['menu']['etel_vb'];
  $foetel = $aktualis_etlap['menu']['etel_fo'];
  $leves = $aktualis_etlap['menu']['etel_leves'];
?>
<div class="etlap-visual">
  <div class="wrapper">
    <div class="line-one">
      <div class="flex">
        <div class="side-left">
          <div class="date-on">
            <div class="air-text">
              <div class="week">
                 <?php echo $aktualis_etlap['hetvege']; ?>. hét
              </div>
              <div class="date">
                <?php echo date('Y.m.d.', strtotime($aktualis_etlap['nap'])); ?>
              </div>
            </div>
          </div>
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
        </div>
        <div class="side-right">
          <div class="foetel<?=(!$foetel)?' disabled':''?><?=(!$foetel[kep])?' no-image':''?>">
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
        </div>
      </div>
    </div>
    <div class="line-two">
      <div class="flex">
        <div class="side-left">
          <div class="kieg1<?=(!$kieg[1])?' disabled':''?><?=(!$kieg[1][kep])?' no-image':''?>">
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
        <div class="side-right">
          <div class="kieg2<?=(!$kieg[0])?' disabled':''?><?=(!$kieg[0][kep])?' no-image':''?>">
            <div class="image">
              <div class="wrapper">
                <img src="<?=($kieg[0][kep])?:IMG.'no-meal.png'?>" alt="<?php echo $kieg[0][neve]; ?>">
              </div>
            </div>
            <div class="text">
              <div class="air-text" id="rothorwidthfix">
                <?php echo $kieg[0][neve]; ?>
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
