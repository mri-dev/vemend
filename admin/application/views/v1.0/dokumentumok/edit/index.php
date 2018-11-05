<div class="con con-edit">
	<h2>Fájl szerkesztése</h2>
	<div>
		<strong style="color:black;"><?=$this->file['cim']?></strong> fájl adatainak szerkesztése: <br><br>
		<form method="post" action="">
			<input type="hidden" name="id" value="<?=$this->file['ID']?>">
			<label for="cim">Megjelenő név</label>
			<input type="text" id="cim" class="form-control" name="data[cim]" placeholder="A feltöltött fájl megjelenő neve..." value="<?=$this->file['cim']?>">
			<br>
			<label for="keywords">Kulcsszavak</label>
			<input type="text" id="keywords" class="form-control" name="data[keywords]" placeholder="kulcsszavak megadása..." value="<?=$this->file['keywords']?>">
			<small>Vesszővel válassza el a kulcsszavakat.</small>
			<br><br>
			<label for="filepath">Tárolási mód</label>
			<div><?=($this->file[tipus] == 'local')?'Lokális fájl (szerveren tárolt)':'Külső hivatkozás (linkelt tartalom)'?></div>
			<br>
			<label>Katgóriák</label>
			<div class="">
			<? foreach($this->doc_groupes as $key => $name): ?>
				<div class=""><input <?=(in_array($key,(array)$this->file[in_cat][ids]))?'checked="checked"':''?> type="checkbox" name="data[kategoriak][]" id="group<?=$key?>" value="<?=$key?>"> <label for="group<?=$key?>"><?=$name?></label></div>
			<? endforeach; ?>
			</div>
			<br>
			<label for="filepath">Elérési út</label>
			<input type="text" id="filepath" <?=($this->file[tipus] == 'local')?'readonly="readonly"':''?> class="form-control" name="data[filepath]" value="<?=$this->file['filepath']?>">
			<br>
			<div class="right">
				<a href="/dokumentumok" class="btn btn-danger"><i class="fa fa-times"></i> mégse</a>
				<button class="btn btn-success" name="saveFile" value="1">Változások mentése <i class="fa fa-save"></i></button>
			</div>
		</form>
	</div>
</div>
