<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/html4"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml" lang="hu-HU" <?=(defined('PILOT_ANGULAR_CALL'))?'ng-app="pilot"':''?>>
<head>
	<title><?=$this->title?></title>
    <?=$this->addMeta('robots','index,folow')?>
    <?=$this->SEOSERVICE?>
   	<? $this->render('meta'); ?>
    <script type="text/javascript">
    	$(function(){
			var slideMenu 	= $('#content .slideMenu');
			var closeNum 	= slideMenu.width() - 58;
			var isSlideOut 	= getMenuState();
			var prePressed = false;
			$(document).keyup(function(e){
				var key = e.keyCode;
				if(key === 17){
					prePressed = false;
				}
			});
			$(document).keydown(function(e){
				var key = e.keyCode;
				var keyUrl = new Array();
					keyUrl[49] = '/'; keyUrl[97] = '/';
					keyUrl[50] = '/termekek'; keyUrl[98] = '/termekek';
					keyUrl[51] = '/reklamfal'; keyUrl[99] = '/reklamfal';
					keyUrl[52] = '/menu'; keyUrl[100] = '/menu';
					keyUrl[53] = '/oldalak'; keyUrl[101] = '/oldalak';
					keyUrl[54] = '/kategoriak'; keyUrl[102] = '/kategoriak';
					keyUrl[55] = '/markak'; keyUrl[103] = '/markak';
				if(key === 17){
					prePressed = true;
				}
				if(typeof keyUrl[key] !== 'undefined'){
					if(prePressed){
						//document.location.href=keyUrl[key];
					}
				}
			});

			if(isSlideOut){
				slideMenu.css({
					'left' : '0px'
				});
				$('.ct').css({
					'paddingLeft' : '220px'
				});
			}else{
				slideMenu.css({
					'left' : '-'+closeNum+'px'
				});
				$('.ct').css({
					'paddingLeft' : '75px'
				});
			}

			$('.slideMenuToggle').click(function(){
				if(isSlideOut){
					isSlideOut = false;
					slideMenu.animate({
						'left' : '-'+closeNum+'px'

					},200);
					$('.ct').animate({
						'paddingLeft' : '75px'
					},200);
					saveState('closed');
				}else{
					isSlideOut = true;
					slideMenu.animate({
						'left' : '0px'
					},200);
					$('.ct').animate({
						'paddingLeft' : '220px'
					},200);
					saveState('opened');
				}
			});
		})

		function saveState(state){
			if(typeof(Storage) !== "undefined") {
				if(state == 'opened'){
					localStorage.setItem("slideMenuOpened", "1");
				}else if(state == 'closed'){
					localStorage.setItem("slideMenuOpened", "0");
				}
			}
		}

		function getMenuState(){
			var state =  localStorage.getItem("slideMenuOpened");

			if(typeof(state) === null){
				return false;
			}else{
				if(state == "1") return true; else return false;
			}
		}
    </script>
</head>
<body class="<? if(!$this->adm->logged): ?>blured-bg<? endif; ?>">
<div id="top" class="container-fluid">
	<div class="row">
		<? if(!$this->adm->logged): ?>
		<div class="col-md-12 center"><img height="34" src="<?=IMG?>logo_white.svg" alt="<?=TITLE?>"></div>
		<? else: ?>
    	<div class="col-md-7 left">
    		<img height="34" class="top-logo" src="<?=IMG?>logo_white.svg" alt="<?=TITLE?>">
    		<div class="link">
    			<a href="<?=HOMEDOMAIN?>" target="_blank">www.<?=str_replace(array('https://','www.'), '', $this->settings['page_url'])?></a>
    		</div>
    	</div>

        <div class="col-md-5" align="right">
        	<div class="shower">
            	<i class="fa fa-user"></i>
            	<?=$this->adm->admin?>
                <i class="fa fa-caret-down"></i>
                <div class="dmenu">
                	<ul>
                		<li><a href="/home/exit">Kijelentkezés</a></li>
                	</ul>
                </div>
            </div>
        	<div class="shower no-bg">
        		<a href="<?=FILE_BROWSER_IMAGE?>" data-fancybox-type="iframe" class="iframe-btn">Galéria <i class="fa fa-picture-o"></i></a>
            </div>
        </div>
        <? endif; ?>
    </div>
</div>
<!-- Login module -->
<? if(!$this->adm->logged): ?>
<div id="login" class="container-fluid">
	<div class="row">
	    <div class="bg col-md-4 col-md-offset-4">
	    	<h3>Bejelentkezés</h3>
            <? if($this->err){ echo $this->bmsg; } ?>
            <form action="/" method="post">
	            <div class="input-group">
	              <span class="input-group-addon"><i class="fa fa-user"></i></span>
				  <input type="text" class="form-control" name="user">
				</div>
                <br>
                <div class="input-group">
	              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
				  <input type="password" class="form-control" name="pw">
				</div>
                <br>
                <div class="left links"><a href="<?=HOMEDOMAIN?>"><i class="fa fa-angle-left"></i> www.<?=str_replace(array('https://','www.'), '', $this->settings['page_url'])?></a></div>
                <div align="right"><button name="login">Bejelentkezés <i class="fa fa-arrow-circle-right"></i></button></div>
            </form>

	    </div>
    </div>
</div>
<? endif; ?>
<!--/Login module -->
<div id="content">
<div class="container-fluid">
	<? if($this->adm->logged): ?>
    <div class="slideMenu">
    	<div class="slideMenuToggle" title="Kinyit/Becsuk"><i class="fa fa-arrows-h"></i></div>
      <div class="clr"></div>
   		<div class="menu">
        	<ul>
            	<li class="<?=($this->gets[0] == 'home')?'on':''?>"><a href="/" title="Dashboard"><span class="ni">1</span><i class="fa fa-life-saver"></i> Dashboard</a></li>
              <li class="<?=($this->gets[0] == 'megrendelesek')?'on':''?>"><a href="/megrendelesek" title="Megrendelések"><span class="ni">2</span><i class="fa fa-briefcase"></i> Megrendelések</a></li>
              <li class="<?=($this->gets[0] == 'markak')?'on':''?>"><a href="/markak" title="Márkák"><span class="ni">7</span><i class="fa fa-bookmark"></i> Márkák</a></li>
              <li class="<?=($this->gets[0] == 'termekek')?'on':''?>"><a href="/termekek" title="Termékek"><span class="ni">2</span><i class="fa fa-cubes"></i> Termékek</a></li>
              <li class="<?=($this->gets[0] == 'kategoriak')?'on':''?>"><a href="/kategoriak" title="Kategóriák"><span class="ni">6</span><i class="fa fa-bars"></i> Kategóriák</a></li>
              <li class="<?=($this->gets[0] == 'felhasznalok')?'on':''?>"><a href="/felhasznalok" title="Felhasználók"><span class="ni">2</span><i class="fa fa-group"></i> Felhasználók</a></li>
              <li class="<?=($this->gets[0] == 'uzenetek')?'on':''?>"><a href="/uzenetek" title="Üzenetek"><span class="ni">8</span><i class="fa fa-envelope-o"></i> Üzenetek</a></li>
              <li class="<?=($this->gets[0] == 'menu')?'on':''?>"><a href="/menu" title="Menü"><span class="ni">4</span><i class="fa fa-ellipsis-h"></i> Menü</a></li>
              <li class="<?=($this->gets[0] == 'oldalak')?'on':''?>"><a href="/oldalak" title="Oldalak"><span class="ni">5</span><i class="fa fa-file-o"></i> Oldalak</a></li>
              <li class="<?=($this->gets[0] == 'emails')?'on':''?>"><a href="/emails" title="Email sablonok"><span class="ni">8</span><i class="fa fa-envelope"></i> Email sablonok</a></li>
              <!-- MODULS-->
              <?php if ( !empty($this->modules) ): ?>
              <li class="div"></li>
              <?php foreach ($this->modules as $module): ?>
              <li class="<?=($this->gets[0] == $module['menu_slug'])?'on':''?>"><a href="/<?=$module['menu_slug']?>" title="<?=$module['menu_title']?>"><span class="ni"><?=$module['ID']?></span><i class="fa fa-<?=$module['faico']?>"></i> <?=$module['menu_title']?></a></li>
              <?php endforeach; ?>
              <?php endif; ?>
              <!-- End of MODULS-->
              <li class="<?=($this->gets[0] == 'beallitasok')?'on':''?>"><a href="/beallitasok" title="Beállítások"><span class="ni">8</span><i class="fa fa-gear"></i> Beállítások</a></li>
        	</ul>
        </div>
    </div>
    <? endif; ?>
    <div class="ct">
    	<div class="innerContent">
