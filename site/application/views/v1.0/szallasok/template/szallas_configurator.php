<div class="szallas-kereso-block configurator" ng-controller="SzallasCalculator" ng-init="init(<?=$this->szallas['datas']['ID']?>)">
  <div class="wrapper">
    <div class="head">
      <i class="fa fa-briefcase"></i> Szállás ajánlatkérő
    </div>
    <div class="fcont">
      <div class="wrapper">
        <div class="inp">
          <div class="wrapper">
            <label for="szallas_erkezes">Érkezés</label>
            <input type="text" id="szallas_erkezes" name="erkezes" value="">
          </div>
        </div>
        <div class="inp">
          <div class="wrapper">
            <label for="szallas_tavozas">Távozás</label>
            <input type="text" id="szallas_tavozas" name="tavozas" value="">
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
        <div class="children-ages">
          <div class="wrapper">
            <label for="szallas_childrenages">Gyermekek kora</label>
            <div class="inp">
              <select name="children" id="szallas_children">
                <?php for ($i=1; $i <= 16 ; $i++) { ?>
                  <option value="<?=$i?>"><?=$i?> év</option>
                <?php } ?>
              </select>
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
              <input type="checkbox" name="opt[kisallat]" id="opt_kisallat"> <label for="opt_kisallat">kisállat hozható</label>
            </div>
          </div>
        </div>
        <div class="button">
          <button type="submit" ng-click="refresh()">frissítés <i class="fa fa-refresh" ng-class="(loading)?'fa-spin':''"></i></button>
        </div>
      </div>
    </div>

    <div class="szallas-ajanlatok" ng-show="(rooms.length!=0)">
      <div class="ajanlat-head">
        Válassza ki a megfelelő ajánlatot
        {{config.ellatas}}
      </div>
      <div class="room" ng-repeat="room in rooms" ng-show="(room.prices.length!=0 && (config.ellatas==0||(config.ellatas!=0 && room.ellatas_ids.indexOf(config.ellatas) !== -1)))">
        <div class="roomhead">
          <div class="name">
            {{room.name}}
          </div>
          <div class="desc">
            {{room.leiras}}
          </div>
          <div class="cap">
            max. <span>{{room.felnott_db}} felnőtt</span><span ng-show="room.gyermek_db>0">, {{room.gyermek_db}} gyermek</span>
          </div>
        </div>
        <div class="price-configs">
          <div class="price-config" ng-repeat="pc in room.prices" ng-class="(config.ellatas!=0&&pc.ellatas_id!=config.ellatas)?'disabled':( (picked_rooms.priceconfig && pc.ID == picked_rooms.priceconfig.ID) ? 'picked' : '' )" ng-click="(config.ellatas!=0&&pc.ellatas_id!=config.ellatas)?'':pickConfig(room, pc)">
            <div class="wrapper">
              <div class="ellatas">
                {{pc.ellatas_name}}
              </div>
              <div class="adult-price">
                {{pc.felnott_ar}}
                <div class="lab">
                  Ft / felnőtt / nap
                </div>

              </div>
              <div class="child-price">
                <div class="" ng-show="room.gyermek_db>0">
                  {{pc.gyerek_ar}}
                  <div class="lab">
                    Ft / gyermek / nap
                  </div>
                </div>
                <div class="" ng-hide="room.gyermek_db>0">
                  --
                </div>
              </div>
              <div class="total-price-calc">
                {{(((pc.felnott_ar * config.adults)*config.nights) + ((pc.gyerek_ar * config.children)*config.nights))}} Ft
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="szallas-calculation" ng-show="(rooms.length!=0 && picked_rooms.room)">
      <div class="config-stat">
        <i class="fa fa-calendar"></i>
      </div>
      <div class="divider"></div>
      <div class="calc-info">
        <div class="prices">
          <div class="price">
            {{config.room_prices}} Ft
          </div>
          <div class="addon-price">
            + idegenforgalmi adó: <strong>{{config.ifa_price}} Ft</strong>
          </div>
          <div class="addon-price">
            + kisállat díj: <strong>{{config.kisallat_dij}} Ft</strong>
          </div>
        </div>
        <div class="spacer"></div>
        <div class="total-price">
          Előzetesen kalkulált díj:
          <div class="tprice">
            {{config.total_price}} Ft
          </div>
        </div>
      </div>
      <div class="foot">
        <div class="alert-foglalas">
          <i class="fa fa-clock-o"></i> Siessen, mert kevés szabad szoba van!
        </div>
        <div class="spacer"></div>
        <div class="foglal air-text-in">
          <div class="atext">
            Ajánlatkérés
          </div>
        </div>
      </div>
    </div>

    <div class="connect-msg">
      Az ajánlatkérésről azonnal megkapja a visszaigazolást!
    </div>

    <div class="map">
      <div class="head">
        <img class="ico" src="<?=IMG?>icons/ico-map.svg" alt="Térkép"> Megtekintés térképen
      </div>
      <div class="map-canvas">
        <iframe
          frameborder="0" style="border:0"
          src="https://www.google.com/maps/embed/v1/place?key=<?=APIKEY_GOOGLE_MAP_EMBEDKEY?>
            &q=<?=$this->szallas[datas][cim]?>" allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="szallas-infok">
      <div class="head">
        Fontos tudnivalók - házirend
      </div>

      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-be-people.svg" alt="Bejelentkezés"> Bejelentkezés</h3>
        <div class="timeviewer">
          <div class="wrapper">
            <?php
              $ora_from = $this->szallas[datas][bejelentkezes_data][from][ora];
              $ora_to = $this->szallas[datas][bejelentkezes_data][to][ora];
            ?>
            <? for($i=1;$i<=24;$i++): ?>
            <div class="hour h<?=$i?><?=($i>=$ora_from && $i<= $ora_to)?' hl':''?><?=($i==$ora_from)?' dstart':''?><?=($i==$ora_to)?' dend':''?>">
              <div class="dstart-text"><?=$this->szallas[datas][bejelentkezes_data][from][ora].':'.$this->szallas[datas][bejelentkezes_data][from][perc]?></div>
              <div class="dend-text"><?=$this->szallas[datas][bejelentkezes_data][to][ora].':'.$this->szallas[datas][bejelentkezes_data][to][perc]?></div>
            </div>
            <? endfor; ?>
          </div>
        </div>
      </div>

      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-ki-people.svg" alt="Kijelentkezés"> Kijelentkezés</h3>
        <div class="timeviewer">
          <div class="wrapper">
            <?php
              $ora_from = $this->szallas[datas][kijelentkezes_data][from][ora];
              $ora_to = $this->szallas[datas][kijelentkezes_data][to][ora];
            ?>
            <? for($i=1;$i<=24;$i++): ?>
            <div class="hour h<?=$i?><?=($i>=$ora_from && $i<= $ora_to)?' hl':''?><?=($i==$ora_from)?' dstart':''?><?=($i==$ora_to)?' dend':''?>">
              <div class="dstart-text"><?=$this->szallas[datas][kijelentkezes_data][from][ora].':'.$this->szallas[datas][kijelentkezes_data][from][perc]?></div>
              <div class="dend-text"><?=$this->szallas[datas][kijelentkezes_data][to][ora].':'.$this->szallas[datas][kijelentkezes_data][to][perc]?></div>
            </div>
            <? endfor; ?>
          </div>
        </div>
      </div>

      <?php if ($this->szallas[datas][fizetes] != ''): ?>
      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-people-pays.svg" alt="Fizetés"> Fizetés</h3>
        <div class="text">
          <?php echo $this->szallas[datas][fizetes]; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($this->szallas[datas][elorefizetes] != ''): ?>
      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-arrow-right.svg" alt="Előrefizetés"> Előrefizetés</h3>
        <div class="text">
          <?php echo $this->szallas[datas][elorefizetes]; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($this->szallas[datas][lemondas] != ''): ?>
      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-tilt.svg" alt="Lemondás"> Lemondás</h3>
        <div class="text">
          <?php echo $this->szallas[datas][lemondas]; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($this->szallas[datas][gyerek_potagy] != ''): ?>
      <div class="block">
        <h3><img class="ico" src="<?=IMG?>icons/ico-potagy.svg" alt="Pótágy"> Gyerekekre és pótágyakra vonatkozó szabályzat</h3>
        <div class="text">
          <?php echo $this->szallas[datas][gyerek_potagy]; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>
