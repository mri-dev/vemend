<div style="float:right;">
	<a href="/<?=$this->gets[0]?>/" class="btn btn-default"><i class="fa fa-arrow-left"></i> vissza</a>
</div>
<h1>Termékek / Szállítási idők</h1>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row">
	<div class="col-md-12">
    	<div class="panel panel-danger">
        	<div class="panel-heading">
            <h2><i class="fa fa-times"></i> Szállítási idő törlése</h2>
            </div>
        	<div class="panel-body">
            	<div style="float:right;">
                	<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
                </div>
            	<strong>Biztos, hogy törli a szállítási időt?</strong>
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
        	<h2><? if($this->gets[2] == 'szerkeszt'): ?>Szerkesztése<? else: ?>Új szállítási idő hozzáadása<? endif; ?></h2>
            <br>
            <div class="row">
                <div class="col-md-9">
                    Megnevezés: <input type="text" class="form-control" name="nev" placeholder="pl.: 2-3 hét" value="<?=$this->sm[elnevezes]?>">
                </div>
                <div class="col-md-3" align="right">
                <br>
                	<? if($this->gets[2] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[2]?>" />
                    <a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
                    <button name="save" class="btn btn-success">Változások mentése <i class="fa fa-check-square"></i></button>
                    <? else: ?>
                    <button name="add" class="btn btn-primary">Hozzáadás <i class="fa fa-check-square"></i></button>
                    <? endif; ?>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="con">
	    	<div><h2>Szállítási idők</h2></div>
        <div class="" style="padding:10px;">
        	<div class="row" style="color:#cccccc; margin-bottom:15px;">
            	<div class="col-md-9">Megnevezés</div>
                <div class="col-md-3"></div>
            </div>
        	<? foreach($this->n as $d): ?>
        	<div class="row markarow">
            	<div class="col-md-9">
                	<strong><?=$d[elnevezes]?></strong>
                </div>
                <div class="col-md-3" align="right">
								<?php if (!is_null($d['author'])): ?>
									<a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/szerkeszt/<?=$d[ID]?>" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                  <a href="/<?=$this->gets[0]?>/<?=$this->gets[1]?>/torles/<?=$d[ID]?>" title="Törlés"><i class="fa fa-times"></i></a>
								<?php else: ?>
									<em>Alapé. értékek</em>
								<?php endif; ?>
                </div>
            </div>
            <? if($d[arres_mod] == '1'): ?>
            <div class="row arresrow mk<?=$d[ID]?>" style="padding-right:15px;">
            	<div class="col-md-8 col-md-offset-6 box">
            	<? if(count($d[arres_savok]) > 0): foreach($d[arres_savok] as $s): ?>
                	<div class="row">
                    	<div class="col-md-3"><?=Helper::cashFormat($s[ar_min])?> Ft-tól</div>
                        <div class="col-md-3"><?=($s[ar_max] > 0) ? Helper::cashFormat($s[ar_max]).' Ft-ig':'végtelenig'?></div>
                        <div class="col-md-1"><?=Helper::cashFormat($s[arres])?>%</div>

                    </div>
                <? endforeach; else:?>
							<? endif;?>
              </div>
            </div>
            <? endif; ?>
            <? endforeach; ?>
        </div>
	    </div>
    </div>
</div>
<? endif; ?>
