<h1>MENÜ</h1>
<?=$this->msg?>
<? if($this->gets[1] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row">
	<div class="col-md-12 con con-del">
        <h2></i> Menü elem törlése</h2>           
    	<div>
        	<div style="float:right;">
            	<a href="/<?=$this->gets[0]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
            </div>
        	Biztos benne, hogy törli a kiválasztott menü elemet? A művelet nem visszavonható!
        </div>
    </div>
</div>
</form>
<? endif; ?>
<? if( true ): ?>
<? if($this->gets[1] != 'torles'): ?>
<div class="row">
	<div class="col-md-12">
    	<div class="con <?=($this->gets[1] == 'szerkeszt')?'con-edit':''?>">
        	<form action="" method="post" enctype="multipart/form-data">
        	<h2><? if($this->gets[1] == 'szerkeszt'): ?>Menü szerkesztése<? else: ?>Új menü hozzáadása<? endif; ?></h2>
            <br>
            <div class="row">
                <div class="col-md-2">
                    <label for="menu_pos">Menü pozíció* <?=\PortalManager\Formater::tooltip('Elérhető menü pozíció kiválasztása. Alapértelmezetten kettő létezik: header (azaz felső menü) és footer (azaz lábrész menü). ')?></label>
                    <select name="menu_pos" id="menu_pos" class="form-control">
                        <option value="" selected="selected">&mdash; kérjük válasszon &mdash;</option>
                        <option value="" disabled="disabled"></option>
                        <? foreach ( $this->menu_positions as $mp ) { ?>
                        <option value="<?=$mp?>" <?=($this->menu && $this->menu->getPosition() == $mp)?'selected="selected"':''?>><?=$mp?></option>    
                        <? } ?>
                    </select>
                </div>                 
                <div class="col-md-3">
                    <label for="menu_parent">Szülő menü <?=($this->menus->filters['menu_type'] ? '<span style="font-weight:normal;">(csak <strong>'.$this->menus->filters['menu_type'].'</strong> elemek)</span>': '')?></label>
                    <select name="parent" id="menu_parent" class="form-control">
                        <option value="" selected="selected">&mdash; ne legyen / legfelső menüelem &mdash;</option>
                        <option value="" disabled="disabled"></option>
                        <option value="" disabled="disabled">Szülő menü kiválasztása:</option>
                         <? 
                            while( $this->menus->walk() ): 
                            $menu = $this->menus->the_menu();
                        ?>
                        <option value="<?=$menu['ID']?>_<?=$menu['deep']?>" <?=($this->menu && $this->menu->getParentKey() == $menu['ID'].'_'.$menu['deep'] ? 'selected="selected"':'')?>><? for($s=$menu['deep']; $s>0; $s--){echo '&mdash;';}?><?=$menu['nev']?></option>
                        <? endwhile; ?>                  
                    </select>
                </div>  
                <div class="col-md-3">
                    <label for="menu_type">Menü típus kiválasztása*</label>
                    <select name="menu_type" id="menu_type" class="form-control">
                        <option value="" selected="selected">&mdash; kérjük válasszon &mdash;</option>
                        <option value="" disabled="disabled"></option>
                        <? foreach ( $this->menu_types as $key => $type ) { ?>
                        <option value="<?=$key?>" <?=($this->menu && $this->menu->getType() == $key)?'selected="selected"':''?>><?=$type?></option>    
                        <? } ?>                       
                    </select>
                </div>  
                <div class="col-md-2">
                    <label for="menu_nev">Felirat <?=\PortalManager\Formater::tooltip('<strong>URL típus esetén:</strong><br> A felirat szövege fog megjelenni a menüben. Ha nem adjuk meg, akkor nem lesz megjelenő szöveg a linknél. <br><br> <strong>Egyéb (csatolt) típusú menük esetén:</strong><br>Nem szükséges megadni. A becsatolt oldal címe / elnevezése lesz az alapértelmezetten megjelenő szöveg, ha nem adjuk meg. Amennyiben megadjuk a feliratot, felüldefiniálja az eredeti szöveget és a megadott felirat fog megjelenni.')?></label>
                    <input type="text" class="form-control" name="nev" id="menu_nev" value="<?=($this->menu) ? $this->menu->getTitle() : ''?>">
                </div>  

                <div class="col-md-1">
                    <label for="menu_order">Sorrend</label>
                    <input type="number" class="form-control" name="sorrend" id="menu_order" value="<?=($this->menu)?$this->menu->getSortNumber():0?>" min="-100" max="100" step="1">
                </div>   
                <div class="col-md-1">
                    <label for="menu_lathato">Látható</label>
                    <input type="checkbox" class="form-control" name="lathato" <?=( ($this->menu && $this->menu->isVisible()) || !$this->menu )?'checked="checked"':''?> id="menu_lathato">
                </div>       
            </div>
            <br>
            <div class="row type-row type_template" id="type_template" style="<?=($this->menu && $this->menu->getType() == 'template' )?'':'display:none;'?>">                
                <div class="col-md-12">
                    <label for="type_template">Template azonosító </label>
                    <input type="text" class="form-control" name="data_value" id="type_template" value="<?=($this->menu) ? $this->menu->getValue() : ''?>">
                </div>
            </div>
            <div class="row type-row type_url" id="type_url" style="<?=($this->menu && $this->menu->getType() == 'url' )?'':'display:none;'?>">                
                <div class="col-md-12">
                    <label for="url">Hivatkozás <?=\PortalManager\Formater::tooltip('Ha nem adja meg az URL-t, akkor a menü elem sima szövegként fog megjenni. Ez alkalmas lehet főcím használatára. Ha főcím a cél, akkor a CSS osztálynál adjuk meg a text-item-header osztályt.')?></label>
                    <input type="text" class="form-control" name="url" id="url" value="<?=($this->menu) ? $this->menu->getUrl() : ''?>">
                </div>
            </div>
            <div class="row type-row type_kategoria_alkategoria_lista type_kategoria_link" id="type_cat" style="<?=($this->menu && ($this->menu->getType() == 'kategoria_link' || $this->menu->getType() == 'kategoria_alkategoria_lista') )?'':'display:none;'?>">                
                <div class="col-md-12">
                    <label for="cat_select">Kapcsolódó kategória kiválasztása*</label>
                    <select name="cat_elem_id" id="cat_select" class="form-control">
                        <option value="" selected="selected">&mdash; kérjük válasszon &mdash;</option>
                        <option value="" disabled="disabled"></option>
                        <? 
                            while( $this->categories->walk() ): 
                            $cat = $this->categories->the_cat();
                        ?>
                        <option value="<?=$cat['ID']?>" <?=($this->menu && $this->menu->getElemId() == $cat['ID'] ? 'selected="selected"':'')?>><? for($s=$cat['deep']; $s>0; $s--){echo '&mdash;';}?><?=$cat['neve']?></option>
                        <? endwhile; ?>                   
                    </select>
                    <div class="info">
                        <a href="/kategoriak" target="_blank"><i class="fa fa-gear"></i> <em>kategóriák kezelése</em></a>
                    </div>
                </div>
            </div>
            <div class="row type-row type_oldal_link" id="type_cat" style="<?=($this->menu && ($this->menu->getType() == 'oldal_link') )?'':'display:none;'?>">                
                <div class="col-md-12">
                    <label for="page_select">Kapcsolódó oldal kiválasztása*</label>
                    <select name="page_elem_id" id="page_select" class="form-control">
                        <option value="" selected="selected">&mdash; kérjük válasszon &mdash;</option>
                        <option value="" disabled="disabled"></option>
                        <? 
                            while( $this->pages->walk() ): 
                            $page = $this->pages->the_page();
                        ?>
                        <option value="<?=$page['ID']?>" <?=($this->menu && $this->menu->getElemId() == $page['ID'] ? 'selected="selected"':'')?>><? for($s=$page['deep']; $s>0; $s--){echo '&mdash;';}?><?=$page['cim']?></option>
                        <? endwhile; ?>                   
                    </select>
                    <div class="info">
                        <a href="/oldalak" target="_blank"><i class="fa fa-plus"></i> <em>új oldal létrehozása</em></a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row submit-row" style="<?=($this->menu)?'':'display:none;'?>">
                <div class="col-md-4">
                     <label for="css_class">CSS osztályok <?=\PortalManager\Formater::tooltip('
                        Szóközzel válasszuk el az osztályokat. <br>
                        Pl.: text-strong text-underline<br><br>
                        <strong>Használható osztályok:</strong><br>
                        text-item-header - főcím stílus megjelenés<br>
                        text-strong - vastagon szedett szöveg<br>
                        text-italic - dőlt szöveg<br>
                        text-underline - aláhúzott szöveg<br>
                        text-uc - nagybetűs szöveg<br>
                        text-big - megnövelt betűméret (22px)<br>
                        text-huge - megnövelt betűméret (28px)<br>
                        text-spaced - megnövelt karakterköz (2px) / szellős szöveg<br>
                        text-height-medium - szöveg sormagasság (40px) <br>
                        text-height-large - szöveg sormagasság (80px) <br>
                        text-height-big - szöveg sormagasság (125px) <br>
                        nav-link-stackview - kiemelt szöveg, nyillal a végén<br>
                        nav-img-icon - menü kép ikon méret (20px magas)<br>
                        nav-img-small - menü kép kicsi méret (48px magas)<br>
                        nav-img-medium - menü kép médium méret (96px magas)<br>
                        nav-img-large - menü kép nagy méret (180px magas)<br>
                        nav-img-top - menü kép a szöveg felé helyezése<br>
                        item-align-center - középre rendezett tartalom                        
                     ')?></label>
                     <input type="text" class="form-control" name="css_class" id="css_class" value="<?=($this->menu) ? $this->menu->getCssClass() : ''?>">
                </div>
                <div class="col-md-3">
                     <label for="css_style">Style <?=\PortalManager\Formater::tooltip('Pl.: font-weight: bold; font-size: 16px; color: black;')?></label>
                     <input type="text" class="form-control" name="css_styles" id="css_style" value="<?=($this->menu) ? $this->menu->getCssStyle() : ''?>">
                </div>                
                <div class="col-md-2">
                    <label for="uimg">Menü kép</label>
                    <div style="display:block;">
                        <input type="text" id="uimg" name="url_img" value="<?=($this->menu) ? $this->menu->getImage() : ''?>" style="display:none;">
                        <a title="Kép kiválasztása" href="<?=FILE_BROWSER_IMAGE?>&field_id=uimg" data-fancybox-type="iframe" class="btn btn-sm btn-default iframe-btn" type="button"><i class="fa fa-search"></i></a>
                        <span id="url_img" class="img-selected-thumbnail"><img src="<?=($this->menu) ? $this->menu->getImage() : ''?>" title="Kiválasztott menükép" alt=""></span>
                        <i class="fa fa-times" title="Kép eltávolítása" id="remove_url_img" style="color:red; <?=($this->menu && $this->menu->getImage() ? '' :'display:none;')?>"></i>
                    </div>
                </div>
                <div class="col-md-3 right">
                    <br>
                    <? if($this->gets[1] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[2]?>" />
                    <a href="/<?=$this->gets[0]?>"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
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
<? endif; ?>
<div class="row">
	<div class="col-md-12">
    	<div class="con">
            <div style="float:right;">
                <form action="" id="flag_menu_type_filter" method="post">
                    <input type="hidden" name="flag" value="flag_menu_type_filter">
                    <span style="padding:0 5px 0 0; line-height:22px; float:left;">Menü pozíció szűrés</span>
                    <select name="flag_menu_type_filter" onchange="$('#flag_menu_type_filter').submit();">
                        <option value="" selected="selected">összes</option>
                        <option value="" disabled="disabled"></option>
                        <? foreach ( $this->menu_positions as $mp ) { ?>
                        <option value="<?=$mp?>" <?=($_COOKIE['flag_menu_type_filter'] == $mp)?'selected="selected"':''?>><?=$mp?></option>    
                        <? } ?>
                    </select>
                </form>
            </div>
        	<h2>Menü elemek</h2>
            <div><?=($this->menus->filters['menu_type'] ? 'Szűrt lista, mint <u>menü pozíció</u>: <strong>'.$this->menus->filters['menu_type'].'</strong>': '')?></div>
            <br />
            <div class="row" style="color:#aaa;">
            	<div class="col-md-5">
                	<em>Felirat</em>
                </div>
                <div class="col-md-1">
                    <em>Pozíció</em>
                </div>
                <div class="col-md-3">
                	<em>Hivatkozás</em>
                </div>
                <div class="col-md-1">
                	<em>Sorrend</em>
                </div>
                <div class="col-md-1">
                    <em>Látható</em>
                </div>
                <div class="col-md-1">
                	
                </div>
           	</div>
        	<?  if( $this->menus->has_menu() ): 
                while( $this->menus->walk() ): 
                $menu = $this->menus->the_menu(); 
            ?>
            <div class="row np markarow deep<?=$menu['deep']?> <?=($this->menu && $this->gets[1] == 'szerkeszt' && $this->menu->getId() == $menu['ID'] ? 'on-edit' : '')?> <?=($this->menu && $this->gets[1] == 'torles' && $this->menu->getId() == $menu['ID'] ? 'on-del' : '')?>">
            	<div class="col-md-5">
                    <? if($menu['kep']): ?>
                    <div class="img-thb"><img src="<?=$menu['kep']?>" alt=""></div>
                    <? endif; ?>
                	<strong><?=$menu[nev]?></strong> 
                    <div><em class="menu-type" title="menü típus">(<?=$this->menus->the_menu_type()['text']?>)</em></div>
                </div>
                <div class="col-md-1">
                    <?=$menu[gyujto]?>
                </div>
                <div class="col-md-3">
                	<em><?=$menu[url]?></em>
                </div>
                <div class="col-md-1 center">
                	<?=$menu[sorrend]?>
                </div>
                 <div class="col-md-1 center">
                    <?=($menu[lathato] == '1') ? '<i title="Látható" style="color:green;" class="fa fa-check"></i>':'<i title="Rejtve" style="color:red;" class="fa fa-times"></i>'?>
                </div>
                <div class="col-md-1 actions" align="right">
                    	<a href="/<?=$this->gets[0]?>/szerkeszt/<?=$menu[ID]?>" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                        <a href="/<?=$this->gets[0]?>/torles/<?=$menu[ID]?>" title="Törlés"><i class="fa fa-times"></i></a>
                    </div>
           	</div>
            <? endwhile; else:?>
            	<div class="noItem">
                	Nincs létrehozott menü elemek!
                </div>
            <? endif; ?>
            
        </div>
    </div>
</div>
<? endif; ?>

<script>
    $(function(){
        $('#menu_type').change(function(){
            var stype = $(this).val();
            $('.type-row').hide();
            $('.type_'+stype).show();
            $('.submit-row').show();
        });
        $('#remove_url_img').click( function (){
            $('#url_img').find('img').attr('src','').hide();
            $('#uimg').val('');
            $(this).hide();
        });
    })

    function responsive_filemanager_callback(field_id){
        var imgurl = $('#'+field_id).val();
        $('#url_img').find('img').attr('src',imgurl).show();
        $('#remove_url_img').show();
    }
</script>