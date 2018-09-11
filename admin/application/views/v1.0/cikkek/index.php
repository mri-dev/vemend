<div style="float:right;">
	<a href="/cikkek/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> cikk kategóriák</a>
	<a href="/cikkek/creator" class="btn btn-primary"><i class="fa fa-plus"></i> új cikk</a>
</div>
<h1>Cikkek</h1>
<? if( true ): ?>
<div class="row">
	<div class="col-md-12">
    	<div class="con">
            <div class="row" style="color:#aaa;">
            	<div class="col-md-6">
                	<em>Cím</em>
                </div>
                <div class="col-md-2 center">
              		<em>Utoljára frissítve</em>
                </div>
                <div class="col-md-2 center">
                    <em>Létrehozva</em>
                </div>
                <div class="col-md-1 center">
                	<em>Látható</em>
                </div>
                <div class="col-md-1" align="right">
                    <em></em>
                </div>
           	</div>
        	<?
            if( $this->news_list->has_news() ):
            while( $this->news_list->walk() ):
                $news = $this->news_list->the_news();
            ?>
            <div class="row np deep<?=$news['deep']?> markarow  <?=($this->news && $this->gets[1] == 'szerkeszt' && $this->news->getId() == $news['ID'] ? 'on-edit' : '')?> <?=($this->news && $this->gets[1] == 'torles' && $this->news->getId() == $news['ID'] ? 'on-del' : '')?>">
            	<div class="col-md-6">
                    <div class="img-thb">
                        <a href="<?=$news['belyeg_kep']?>" class="zoom"><img src="<?=$news['belyeg_kep']?>" alt=""></a>
                    </div>
                	<strong><?=$news[cim]?></strong>
                    <div><a target="_blank" href="<?=HOMEDOMAIN?>hirek/<?=$news['eleres']?>" class="news-url"><?=HOMEDOMAIN?>hirek/<strong><?=$news[eleres]?></strong></a></div>
                </div>
                <div class="col-md-2 center">
                	<?=\PortalManager\Formater::dateFormat($news['idopont'], $this->settings['date_format'])?>
                </div>

                <div class="col-md-2 center">
                    <?=\PortalManager\Formater::dateFormat($news['letrehozva'], $this->settings['date_format'])?>
                </div>
                 <div class="col-md-1 center">
                	<? if($news[lathato] == '1'): ?><i style="color:green;" class="fa fa-check"></i><? else: ?><i style="color:red;" class="fa fa-times"></i><? endif; ?>
                </div>
                <div class="col-md-1 actions" align="right">
                    <a href="/<?=$this->gets[0]?>/creator/szerkeszt/<?=$news[ID]?>" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                    <a href="/<?=$this->gets[0]?>/creator/torles/<?=$news[ID]?>" title="Törlés"><i class="fa fa-times"></i></a>
                </div>
           	</div>
            <? endwhile; else:?>
            	<div class="noItem">
                	Nincs létrehozott hír!
                </div>
            <? endif; ?>

        </div>
    </div>
</div>
<?=$this->navigator?>
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
<? endif; ?>
