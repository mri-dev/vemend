<?
$show 	= true;
$url 	= 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

// Ne jelenjen meg
/////////////////////
// Kosár
if( $this->gets[0] == 'kosar') $show = false;
if( $this->gets[0] == 'user' && $this->gets[1] != 'referring') $show = false;

if ($this->gets[0] == 'user' && $this->gets[1] == 'referring') {
	$url 	= $this->settings['domain'] . '/casada_termek_0ft';
	if ($this->user) {
		$url .= '?partner='.$this->user['data']['refererID'];
	}
}

if( $show ): ?>
<div class="share-box">
	<div class="facebook" 	title="Megosztás Facebook-on!"><a href="javascript:void(0);" onclick="window.open('https://www.facebook.com/dialog/share?app_id=<?=$this->settings['FB_APP_ID']?>&display=popup&href=<?=$url?>&redirect_uri=<?=$this->settings['site_url']?>','','width=800, height=240')"><i class="fa fa-facebook"></i></a></div>
	<div class="googleplus" 	title="Megosztás Google Plus-on!"><a href="https://plus.google.com/share?url=<?=$url?>" onclick="javascript:window.open('https://plus.google.com/share?url=<?=$url?>',
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a></div>
	<div class="twitter" 	title="Megosztás Twitter-en!"><a href="javascript:void(0);" onclick="window.open('https://twitter.com/intent/tweet?text=<? echo urlencode($this->title.' - ' . $url ); ?>&via=casada', '', 'width=800, height=240');"><i class="fa fa-twitter"></i></a></div>
	<div class="youtube" title="Youtube csatornánk"><a href="https://www.youtube.com/channel/UC8mD3UPPP_A_ir7pNicCUig" target="_blank"><i class="fa fa-youtube-play"></i></span></a></div>
	<div class="mailer" 	title="Hivatkozás küldése email-ben!"><a href="mailto:?subject=<? echo $this->title; ?>&body=<?=__('Szia! <br><br><br> Találtam egy jó oldalt!<br><br>Weboldal elérhetősége')?>: http%3A%2F%2Fwww.<?php echo $url; ?>"><i class="fa fa-envelope"></i></span></a></div>
	<div class="print" style="display: none;" title="Oldal nyomtatása!"><a href="javascript:void(0);" onclick="window.print();"><i class="fa fa-print"></i></a></div>
</div>
<? endif; ?>
