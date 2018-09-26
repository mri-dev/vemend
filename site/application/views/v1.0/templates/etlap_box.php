<?php
  $aktualis_etlap = $this->etlap->aktualisMenu();
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
          <div class="leves">
            <div class="image">

            </div>
            <div class="text">
              <div class="air-text">
                Brokkolikrémleves
              </div>
            </div>
          </div>
        </div>
        <div class="side-right">
          <div class="foetel">
            <div class="image">

            </div>
            <div class="text">
              <div class="air-text">
                Tarhonyás sertéshús
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="line-two">
      <div class="flex">
        <div class="side-left">
          <div class="kieg1">
            <div class="image">

            </div>
            <div class="text">
              <div class="air-text">
                Csemege uborka
              </div>
            </div>
          </div>
        </div>
        <div class="side-right">
          <div class="kieg2">
            <div class="image">

            </div>
            <div class="text">
              <div class="air-text" id="rothorwidthfix">
                Alma
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
