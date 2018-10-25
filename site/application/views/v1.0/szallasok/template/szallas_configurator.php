<div class="szallas-kereso-block configurator">
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
            <select class="" id="szallas_ellatas" name="ellatas">
              <option value="">bármely</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="space"></div>
    <div class="fcont grey">
      <div class="wrapper">
        <div class="room">
          <div class="wrapper">
            <label for="szallas_rooms">Szoba</label>
            <div class="inp">
              <select name="rooms" id="szallas_rooms">
                <?php for ($i=1; $i <= 10 ; $i++) { ?>
                  <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="adults">
          <div class="wrapper">
            <label for="szallas_adults">Felnőtt</label>
            <div class="inp">
              <select name="adults" id="szallas_adults">
                <?php for ($i=1; $i <= 50 ; $i++) { ?>
                  <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="children">
          <div class="wrapper">
            <label for="szallas_children">Gyermek</label>
            <div class="inp">
              <select name="children" id="szallas_children">
                <?php for ($i=1; $i <= 50 ; $i++) { ?>
                  <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
              </select>
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
          <button type="submit"><i class="fa fa-search"></i> keresés</button>
        </div>
      </div>
    </div>

    <div class="szallas-calculation">
      <div class="config-stat">
        <i class="fa fa-calendar"></i>
      </div>
      <div class="divider"></div>
      <div class="calc-info">
        <div class="prices">
          <div class="price">
            0 Ft
          </div>
          <div class="addon-price">
            + idegenforgalmi adó: <strong>450 Ft</strong>
          </div>
          <div class="addon-price">
            + kisállat díj: <strong>0 Ft</strong>
          </div>
        </div>
        <div class="spacer"></div>
        <div class="total-price">
          Összesen fizetendő:
          <div class="tprice">
            0 Ft
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
