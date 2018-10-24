<div class="adatlap-view">
  <div class="header">
    <div class="infos">
      infók
    </div>
    <div class="titles">
      <div class="wrapper">
        <div class="title">
          <h1><?=$this->szallas['datas']['title']?></h1>
          <div class="address">
            <i class="fa fa-map-marker"></i> <?=$this->szallas['datas']['cim']?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="adatlap-body">
    <div class="images">
      <div class="profil">
        <a class="zoom" href="<?=$this->szallas['datas']['profilkep']?>"><img src="<?=$this->szallas['datas']['profilkep']?>" alt="<?=$this->szallas['datas']['title']?>"></a>
      </div>
    </div>
    <div class="contents">
      <?php if (count($this->kiemelt_services) != 0): ?>
      <div class="top-services">
        <div class="head">
          Itt nem fog csalódni
        </div>
        <div class="wrapper">
          <?php foreach ((array)$this->kiemelt_services as $service): ?>
          <div class="">
            <i class="fa fa-check"></i> <?=$service['title']?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      <div class="desc">
        <?=$this->szallas['datas']['leiras']?>
      </div>
      <?php if ($this->szallas['datas']['services']): ?>
      <div class="services">
        <div class="wrapper">
          <?php foreach ((array)$this->szallas['datas']['services'] as $sgroup => $services): ?>
          <div class="group">
            <h3><?=$sgroup?></h3>
            <div class="serv-list">
              <?php foreach ((array)$services as $service): ?>
              <div class="">
                <i class="fa fa-star"></i> <?=$service['title']?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<pre><?php //print_r($this->szallas); ?></pre>
