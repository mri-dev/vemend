<div class="szallas-kereso-block" ng-controller="SzallasCalculator" ng-init="init(0, '<?=http_build_query($_GET)?>')">
  <div class="wrapper">
    <form class="" action="/szallasok" method="get" onclick="return false;">
      <div class="head">
        <i class="fa fa-briefcase"></i> Szállás keresése
      </div>
      <div class="fcont">
        <div class="wrapper">
          <div class="inp">
            <div class="wrapper">
              <label for="szallas_erkezes">Érkezés</label>
              <md-datepicker ng-model="config.datefrom" ng-change="dateChanged('datefrom')" id="szallas_erkezes" md-placeholder="Válasszon érkezést"></md-datepicker>
            </div>
          </div>
          <div class="inp">
            <div class="wrapper">
              <label for="szallas_tavozas">Távozás</label>
              <md-datepicker ng-model="config.dateto" ng-change="dateChanged('dateto')" id="szallas_erkezes" md-placeholder="Válasszon távozást"></md-datepicker>
            </div>
          </div>
          <div class="inp">
            <div class="wrapper">
              <label for="szallas_ellatas">Ellátás</label>
              <select ng-change="picked_rooms={}" id="szallas_ellatas" ng-model="config.ellatas" ng-options="ellatas.ID as ellatas.name for ellatas in terms.ellatas">
                <option value="">Mindegy</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="space"></div>
      <div class="fcont grey">
        <div class="wrapper">
          <div class="adults">
            <div class="wrapper">
              <label for="szallas_adults">Felnőtt</label>
              <div class="inp">
                <select id="szallas_adults" ng-model="config.adults" ng-options="n for n in [] | range:1:10"></select>
              </div>
            </div>
          </div>
          <div class="children">
            <div class="wrapper">
              <label for="szallas_children">Gyermek</label>
              <div class="inp">
                <select id="szallas_children" ng-model="config.children" ng-options="n for n in [] | range:0:10"></select>
              </div>
            </div>
          </div>
          <div class="children-ages" ng-show="(config.children>0)">
            <div class="wrapper">
              <label for="szallas_childrenages">Gyermekek kora</label>
              <div class="inp">
                <div class="" ng-repeat="x in [].constructor(config.children) track by $index">
                  <select name="children" id="szallas_children" ng-model="config.children_age[$index]">
                    <?php for ($i=1; $i <= 16 ; $i++) { ?>
                      <option value="<?=$i?>"><?=$i?> év</option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="space"></div>
      <div class="foot">
        <div class="flex">
          <div class="opt">
            <div class="fcont grey">
              <div class="wrapper">
                <input type="checkbox" ng-model="config.kisallatot_hoz" id="kisallat"> <label for="kisallat">kisállat hozható</label>
              </div>
            </div>
          </div>
          <div class="button">
            <button type="button" ng-click="listSearcher()"><i class="fa fa-search"></i> keresés</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
