<h1>Webáruház beállítások</h1>
<?php $settings = $this->adm->user['webshop']; ?>
<form class="" action="" method="post">
  <div class="row-neg">
    <div class="row">
      <div class="col-md-12 right">
        <button type="submit" name="saveShopSettings" class="btn btn-success">Változások mentése <i class="fa fa-save"></i></button>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-8">
        <div class="con">
          <div class="row-neg">
            <div class="row">
              <div class="col-md-7">
                <label for="">Bolt elnevezése</label>
                <input type="text" name="shopnev" class="form-control" value="<?=$settings['shopnev']?>">
              </div>
              <div class="col-md-4">
                <label for="">Bolt címe</label>
                <input type="text" name="address" class="form-control" value="<?=$settings['address']?>">
              </div>
              <div class="col-md-1">
                <label for="">Aktív</label>
                <input type="checkbox" name="aktiv" class="form-control" <?=($settings['aktiv'])?'checked="checked"':''?> value="1">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <label for="">Bolt címe</label>
                <input type="text" name="address" class="form-control" value="<?=$settings['address']?>">
              </div>
              <div class="col-md-3">
                <label for="">Értesítési e-mail cím</label>
                <input type="text" name="alert_email" class="form-control" value="<?=$settings['alert_email']?>">
              </div>
              <div class="col-md-3">
                <label for="">Telefonszám</label>
                <input type="text" name="telefon" class="form-control" value="<?=$settings['telefon']?>">
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">Rövid ismertető</label>
                <textarea name="leiras"><?=$settings['leiras']?></textarea>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label for="">Webshop Általános Felhasználási Feltételek és információk</label>
                <textarea name="aszf"><?=$settings['aszf']?></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="con">
          <h3>Nyitva tartás</h3>
          <?php
					$nyt_values = (array)$settings['nyitvatartas'];
					foreach (array('Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap') as $nap): ?>
						<label for="nyitvatartas_<?=$nap?>"><?=$nap?></label>
						<input type="text" id="nyitvatartas_<?=$nap?>" placeholder="Pl.: 08:00 - 17:00, zárva" name="nyitvatartas[<?=$nap?>]" class="form-control" value="<?=$nyt_values[$nap]?>">
						<br>
					<?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</form>
