<h1>Fájlfeltöltés</h1>
<?=$this->msg?>
<div class="con">
	<div>
		<form method="post" action="" enctype="multipart/form-data">
			<label for="sorce">Dokumentum forrása</label>
			<select id="source" name="source">
				<option value="link" selected="selected">Hivatkozás</option>
				<option value="upload">Fájlfeltöltés</option>
			</select>
			<br>
			<div style="display: none;" id="file_upload">
				<br>
				<label for="file">Fájl kiválasztása</label>
				<input type="file" id="file" name="file">
			</div>
			<br>
			<label for="cim">Megjelenő név</label>
			<input type="text" id="cim" class="form-control" name="data[cim]" placeholder="A feltöltött fájl megjelenő neve..." value="<?=$this->file['cim']?>">
			<br>
			<label for="keywords">Kulcsszavak</label>
			<input type="text" id="keywords" class="form-control" name="data[keywords]" placeholder="kulcsszavak megadása..." value="<?=$this->file['keywords']?>">
			<small>Vesszővel válassza le a kulcsszavakat. A későbbi keresés szempontjából fontos.</small>
			<br>
			<div style="display: block;" id="link_url">
				<br>
				<label for="filepath">Hivatkozás (URL)</label>
				<input type="text" id="filepath" class="form-control" name="data[filepath]">
			</div>
			<br>
			<label>Katgóriák</label>
			<div class="">
			<? foreach($this->doc_groupes as $key => $name): ?>
				<div class=""><input <?=(in_array($key,(array)$this->file[in_cat][ids]))?'checked="checked"':''?> type="checkbox" name="data[kategoriak][]" id="group<?=$key?>" value="<?=$key?>"> <label for="group<?=$key?>"><?=$name?></label></div>
			<? endforeach; ?>
			</div>
			<br>
			<input type="checkbox" checked="checked" name="data[lathato]" id="lathato"> <label for="lathato">legyen látható</label>
			<div class="right">
				<a href="/dokumentumok" class="btn btn-default"><i class="fa fa-arrow-left"></i> vissza</a>
				<button class="btn btn-warning" name="uploadFile" value="1">Feltöltés <i class="fa fa-upload"></i></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#source').change(function(){
			var v = $(this).val();

			if (v == 'upload') {
				$('#file_upload').show();
				$('#link_url').hide();
			}else {
				$('#file_upload').hide();
				$('#link_url').show();
			}
		});
	})
</script>
