<div style="float:right;">
	<a href="/<?=$this->gets[0]?>/" class="btn btn-default"><i class="fa fa-arrow-left"></i> vissza</a>
</div>
<h1>Termékek / Szállítási módok</h1>
<?=$this->msg?>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row">
	<div class="col-md-12">
    	<div class="panel panel-danger">
        	<div class="panel-heading">
            <h2><i class="fa fa-times"></i> Szállítási mód törlése</h2>
            </div>
        	<div class="panel-body">
            	<div style="float:right;">
                	<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
                </div>
            	<strong>Biztos, hogy törli a szállítási módot?</strong>
            </div>
        </div>
    </div>
</div>
</form>
<? else: ?>
<div class="row">
	<div class="col-md-12">
		<div class="con <?=($this->gets[2] == 'szerkeszt')?'edit':''?>">
        	<form action="" method="post" enctype="multipart/form-data">
        	<h2><? if($this->gets[2] == 'szerkeszt'): ?>Szerkesztése<? else: ?>Új szállítási mód hozzáadása<? endif; ?></h2>
            <?
                $fizmod     = explode(",",$this->sm['fizetesi_mod']);
            ?>
            <br>
            <div class="row">
                <div class="col-md-6">
                    Megnevezés: <input type="text" class="form-control" name="nev" placeholder="pl.: Futár" value="<?=$this->sm[nev]?>">
                </div>
                <div class="col-md-1">
                    Költség (Ft): <input type="number" class="form-control" name="koltseg" value="<?=$this->sm[koltseg]?>">
                </div>
                <div class="col-md-2">
                    Ingyenes össz.határ (Ft): <input type="number" class="form-control" name="osszeghatar" value="<?=$this->sm[osszeghatar]?>">
                </div>
                <br>
                <div class="col-md-3 right">
                	<? if($this->gets[2] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[3]?>" />
                    <a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
                    <button name="save" class="btn btn-success">Változások mentése <i class="fa fa-check-square"></i></button>
                    <? else: ?>
                    <button name="add" class="btn btn-primary">Hozzáadás <i class="fa fa-check-square"></i></button>
                    <? endif; ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div>Fizetési módok:</div>
                    <div>
                    <? foreach ($this->fizetesiMod as $key => $value ) { ?>
                        <div><label for="fizmod_id<?=$value['ID']?>"><input type="checkbox" <?=( in_array($value['ID'], $fizmod) ? 'checked="checked"' : '' )?> id="fizmod_id<?=$value['ID']?>" name="fizmod[]" value="<?=$value['ID']?>" > <?=$value['nev']?></label></div>
                    <? } ?>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="con">
	    	<div><h2>Szállítási módok</h2></div>
            <div style="padding:10px;">
            	<div class="row" style="color:#cccccc; margin-bottom:15px;">
                	<div class="col-md-3">Megnevezés</div>
                    <div class="col-md-1">Szállítási költség</div>
                    <div class="col-md-2">Ingyenes össz.határ</div>
                    <div class="col-md-3">Fizetési módok</div>
                    <div class="col-md-2"></div>
                </div>
            	<? foreach($this->n as $d): ?>
            	<div class="row markarow">
                	<div class="col-md-3" style="line-height:32px;">
                    	<strong><?=$d[nev]?></strong>
                    </div>
                    <div class="col-md-1">
                        <?=$d[koltseg]?> Ft
                    </div>
                    <div class="col-md-2">
                        <?=$d[osszeghatar]?> Ft
                    </div>
                    <div class="col-md-4">
                        <?
                           $fizmod     = explode(",",$d['fizetesi_mod']);
                            $fimodstr   = '';
                            foreach ( $fizmod as $key ) {
                                if( $this->fizetesiMod[$key] ){
                                    $fimodstr .= $this->fizetesiMod[$key]['nev'].", ";
                                }
                            }

                            $fimodstr = rtrim($fimodstr, ", ");

                            echo $fimodstr;
                        ?>
                    </div>
                    <div class="col-md-2 actions right">
											<?php if (!is_null($d['author'])): ?>
												<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/szerkeszt/<?=$d[ID]?>" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
			                  <a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/torles/<?=$d[ID]?>" title="Törlés"><i class="fa fa-times"></i></a>
											<?php else: ?>
												<em>Alapé. értékek</em>
											<?php endif; ?>
                    </div>
                </div>
                <? endforeach; ?>
            </div>
	    </div>
    </div>
</div>
<? endif; ?>
