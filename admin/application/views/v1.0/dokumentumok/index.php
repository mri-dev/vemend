<link rel="stylesheet" type="text/css" href="<?=SSTYLE?>docgroupes-scheme.css">
<div style="float:right;">
	<a href="/dokumentumok/kategoriak" class="btn btn-default">Dokumentum kategóriák <i class="fa fa-th"></i></a>
	<a href="/dokumentumok/upload" class="btn btn-info">Feltöltés <i class="fa fa-upload"></i></a>
</div>
<h1>DOKUMENTUMOK <span><strong><?=count($this->files)?> db</strong> eltöltött fájl a szerverre</span></h1>
<?=$this->msg?>

<? if(isset($_GET['reg'])): ?>
<div class="con con-edit">
	<h2>Feltöltött fájl regisztrálása</h2>
	<div>
		A(z) <strong style="color:black;"><?=base64_decode($_GET['reg'])?></strong> fájl regisztrálása az adatbázisba
		<br><br>
		<form method="post" action="">
			<input type="hidden" name="filename" value="<?=base64_decode($_GET['reg'])?>">
			<input type="hidden" name="filepath" value="<?=base64_decode($_GET['p'])?>">
			<div class="input-group">
				<input type="text" class="form-control" name="name" placeholder="A feltöltött fájl megjelenő neve...">
				<span class="input-group-btn">
					<a href="/dokumentumok" class="btn btn-default" title="Mégse" type="button"><i class="fa fa-times"></i></a>
			    	<button class="btn btn-success" name="regFile" type="submit">Fájl regisztrálása <i class="fa fa-arrow-right"></i></button>
			    </span>
			</div>
		</form>
	</div>
</div>
<? endif; ?>

<div class="doc-groupes">
	<div class="<?=(!isset($_GET['cat']))?'filtered':''?>">
		<a href="/dokumentumok">Összes dokumentum</a>
	</div>
<? foreach($this->doc_groupes as $key => $name): ?>
	<div class="<?=($key==$_GET['cat'])?'filtered':''?>"><span style="background:<?=$this->doc_colors[$key]?>;" class="">&nbsp;&nbsp;&nbsp;</span> <a href="/dokumentumok/?cat=<?=$key?>"><?=$name?></a></div>
<? endforeach; ?>
</div>

<form action="" method="post">
<table class="table termeklista table-bordered document-list-table">
	<thead>
    	<tr>
	      <th>Fájlnév</th>
	    	<th width="80">Kiterjesztés</th>
	    	<th width="120">Fájlméret</th>
	    	<th width="120">Állapot</th>
	    	<th width="100">Megtekintés</th>
	    	<th width="50">Látható</th>
	    	<th width="80">
	    		<i class="fa fa-gear"></i>
	    	</th>
        </tr>
	</thead>
    <tbody>
    	<? if(count($this->files) > 0): foreach($this->files as $d):  ?>
    	<tr class="color-schame">
	    	<td>
					<div style="font-weight: bold;">
						<? if( !isset($d['doc_title'])): ?><i class="fa fa-info-circle"></i> <em><?=$d['name']?></em><? else: ?><?=$d['doc_title']?><? endif; ?> <a href="<?=($d['tipus'] == 'external')?$d['filepath']:DOMAIN.$d['filepath']?>" target="_blank">[megtekint]</a>
					</div>
					<div class="keywords" title="Kulcsszavak">
						<?php echo $d['keywords']; ?>
					</div>
					<?php if (!empty($d[in_cat])): ?>
					<div class="categories">
						<?php foreach ($d['in_cat']['list'] as $cat): ?>
						<span style="background:<?=$cat['color']?>;"><?=$cat['neve']?></span>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</td>
	    	<td class="center"><?=$d['extension']?></td>
	    	<td class="center"><?
	    		if( $d['sizes']['mb'] >= 1 )
	    		{
	    			echo number_format( $d['sizes']['mb'], 2, ".", " ") . ' MB';
	    		} else {
					echo number_format( $d['sizes']['kb'], 2, ".", " ") . ' KB';
	    		}
	    	?></td>
	    	<td class="center">
	    		<? if( isset($d['doc_title'])): ?>
	    			Rendben <i class="fa fa-check"></i>
	    		<? else: ?>
	    			Nincs mentve <i class="fa fa-info-circle"></i>
	    		<? endif; ?>
	    	</td>
	    	<td class="center">
	    		<?=$d['click']?>
	    	</td>
	    	<td class="center">
	    		<? if( isset($d['doc_title']) ): ?>
					<? if($d['lathato'] == '1'): ?><i class="fa fa-check vtgl" vmode="io" title="Aktív / Kattintson az inaktiváláshoz" tid="<?=$d['doc_id']?>"></i><? else: ?><i class="fa fa-times vtgl" vmode="io" title="Inaktív / Kattintson az aktiváláshoz" tid="<?=$d['doc_id']?>"></i><? endif; ?>
	    		<? else: ?>
	    		<a href="/dokumentumok/?reg=<?=base64_encode($d['name'])?>&p=<?=base64_encode($d['src_path'])?>">[mentés]</a>
	    		<? endif; ?>
	    	</td>
	    	<td align="center">
	    		<? if( isset($d['doc_title']) ): ?>
	            <div class="dropdown">
	            	<i class="fa fa-gears dropdown-toggle" title="Beállítások" id="dm<?=$d['doc_id']?>" data-toggle="dropdown"></i>
	                  <ul class="dropdown-menu" role="menu" aria-labelledby="dm<?=$d['doc_id']?>">
	                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="/dokumentumok/edit/<?=$d['doc_id']?>">szerkesztés <i class="fa fa-pencil"></i></a></li>
					    <li role="presentation"><a role="menuitem" tabindex="-1" href="/dokumentumok/del/<?=$d['doc_id']?>">törlés <i class="fa fa-times"></i></a></li>
					  </ul>
	            </div>
	            <? endif; ?>
            </td>
        </tr>
        <? endforeach; else: ?>
        <tr>
	    	<td colspan="15" align="center">
            	<div style="padding:25px;">Nincs találat!</div>
            </td>
        </tr>
        <? endif; ?>
    </tbody>
</table>
</form>

<script type="text/javascript">
	$(function(){
		$('.termeklista i.vtgl').click(function(){
			visibleToggler($(this));
		});
	})

	function visibleToggler(e){
        var tid = e.attr('tid');
        var src =  e.attr('class').indexOf('check');

        if(src >= 0){
            e.removeClass('fa-check').addClass('fa-spinner fa-spin');
            doVisibleChange(e, tid, false);
        }else{
            e.removeClass('fa-times').addClass('fa-spinner fa-spin');
            doVisibleChange(e, tid, true);
        }
    }

	function doVisibleChange(e, tid, show){
		var v 		= (show) ? '1' : '0';
		var mode 	= e.attr('vmode');

		$.post("<?=AJAX_POST?>",{
			type : 'documentChangeActions',
			mode : mode,
			id : tid,
			val : v
		},function(d){
			if(!show){
				e.removeClass('fa-spinner fa-spin').addClass('fa-times');
			}else{
				e.removeClass('fa-spinner fa-spin').addClass('fa-check');
			}
		},"html");
	}
</script>
