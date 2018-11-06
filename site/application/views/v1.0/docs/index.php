<div class="docs-view">
  <div class="holder">
    <div class="sidebar">
      <div class="categories">
        <div class="header">
          <h2>Kategóriák</h2>
        </div>
        <div class="list">
          <div class="cat <?=(!isset($_GET['cat']))?'active':''?>">
            <a href="/docs/<?=(isset($_GET['src']))?'?src='.$_GET['src']:''?>">Összes kategória</a>
          </div>
          <?php foreach ($this->doc_groupes as $dgid => $dg): ?>
          <div class="cat <?=($_GET['cat'] == $dgid)?'active':''?>">
            <a href="/docs/?cat=<?=$dgid?><?=(isset($_GET['src']))?'&src='.$_GET['src']:''?>"><span class="dot" style="color:<?=$this->doc_colors[$dgid]?>;">&nbsp;&nbsp;&nbsp;</span> <?=$dg?></a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="documents">
      <div class="wrapper">
        <div class="title-head">
          <div class="flex">
            <div class="head">
              <?php if (isset($_GET['cat']) && !empty($_GET['cat'])): ?>
              <h1><?=$this->doc_groupes[$_GET['cat']]?> &mdash; dokumentumok</h1>
              <?php else: ?>
              <h1>Összes dokumentum</h1>
              <?php endif; ?>
            </div>
            <div class="searchform">
              <form action="/docs/" method="get">
                <div class="flex flexmob-exc-resp">
                  <div class="input">
                    <input type="text" name="src" value="<?=$_GET['src']?>" placeholder="Keresés...">
                  </div>
                  <div class="button">
                    <button type="submit"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php if (isset($_GET['src']) && !empty($_GET['src'])): ?>
          <div class="search-for">
           <i class="fa fa-search"></i> Keresés, mint: <?php foreach (explode(" ", $_GET['src']) as $src): ?><span><?=$src?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="doc-list">
          <?php foreach ($this->files as $docgroup => $doc): if(isset($_GET['cat']) && $doc['ID'] != $_GET['cat']) continue; ?>
          <div class="doc-group">
            <h2><?=$doc['name']?></h2>
            <div class="count">
              <?=count($doc['docs'])?> db dokumentum.
            </div>
            <div class="docs-items">
              <?php foreach ((array)$doc['docs'] as $d): ?>
              <div class="file type-of-<?=$d['doc_icon_fa']?>" style="border-color:<?=$this->doc_colors[$doc['ID']]?>;">
                <a href="/docs/v/<?=$d['hashname']?>">
                  <div class="wrapper">
                    <div class="ext">
                      <i class="fa fa-<?=$d['doc_icon_fa']?>" style="color:<?=$this->doc_colors[$doc['ID']]?>;"></i>
                    </div>
                    <div class="data">
                      <div class="title" style="color:<?=$this->doc_colors[$doc['ID']]?>;">
                        <?=$d['doc_title']?>
                      </div>
                      <div class="sub">
                        <span class="ext" title="Kiterjesztés"><?=$d['extension']?></span>
                        <?php if ($d['sizes']['kb']): ?>
                        <span class="size" title="Fájlméret"><?=number_format($d['sizes']['kb'], 2, ".", "")?> KB</span>
                        <?php endif; ?>
                        <span class="time" title="Feltöltés ideje"><?=date('Y/m/d H:i', strtotime($d['feltoltve']))?></span>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if (empty($this->files)): ?>
          <div class="no-documents">
            <h3>Nincsenek dokumentumok.</h3>
            A keresési feltételek alapján nem találtunk elérhető dokumentumot, letöltést.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
