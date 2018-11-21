<div class="galleries">
  <?php if (!isset($_GET['folder']) || $_GET['folder'] == ''): ?>
  <div class="gallery-folders">
    <div class="holder">
      <h2>Galéria mappák</h2>
      <div class="folders">
        <?php foreach ((array)$this->galleries as $group => $gallery): if($gallery['imagesnum'] == 0) continue;  ?>
        <div class="folder">
          <div class="wrapper">
            <a href="/galeria/<?=$group?>">
              <div class="image autocorrett-height-by-width" data-img-ratio="4:3">
                <div class="imagenum"><i class="fa fa-picture-o"> <?=$gallery['imagesnum']?></i></div>
                <img src="<?=$gallery['kep']?>" alt="<?=$gallery['neve']?>">
              </div>
              <div class="title" title="<?=$gallery['neve']?>">
                <?=$gallery['neve']?>
              </div>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="gallery-list">
    <div class="pw">
      <div class="sidebar">
        <div class="categories">
          <h2>Galériák</h2>
          <div class="list">
            <?php foreach ((array)$this->galleries as $group => $gallery): if($gallery['imagesnum'] == 0) continue; ?>
            <div class="cat<?=($group == $_GET['folder'])?' active':''?>"><a href="/galeria/<?=$group?>"><i class="fa fa-folder-o" style="color:#<?=$gallery['bgcolor']?>;"></i> <?=$gallery['neve']?><span class="badge"><?=$gallery['imagesnum']?></span></a></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="gallery-items">
        <div class="wrapper">
          <h1><?=$this->galleries[$_GET['folder']][neve]?></h1>
          <div class="images">
            <?php foreach ((array)$this->galleries[$_GET['folder']]['images'] as $img): ?>
            <div class="image">
              <div class="wrapper autocorrett-height-by-width" data-img-ratio="4:3">
                <a href="<?=$img['filepath']?>" class="zoom" rel="galery" title="<?=$img['title']?>" data-caption="<?=$img['description']?>"><img src="<?=$img['filepath']?>" alt="<?=$img['title']?>"></a>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
