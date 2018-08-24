<div class="szallas-kereso">
  <div class="wrapper">
    <form class="" action="/szallasok" method="get">
      <div class="head">
        <i class="fa fa-briefcase"></i> Szállás keresése
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
    </form>
  </div>
</div>
