<div class="banner-modul" ng-app="Banners" ng-controller="Bannerek" ng-init="init()">
  <div class="" ng-show="creating">
    <div class="con">
      <h2 ng-show="!create.ID">Új banner rögzítése</h2>
      <h2 ng-show="create.ID">Banner szerkesztése</h2>
      <div class="alert alert-warning" ng-show="savingbanner">
        Banner adatainak mentése folyamatban <i class="fa fa-spin fa-spinner"></i>
      </div>
      <div class="alert alert-warning" ng-show="uploadingbanner">
        Banner anyagának feltöltése folyamatban <i class="fa fa-spin fa-spinner"></i>
      </div>
      <div class="row-neg">
        <div class="row">
          <div class="col-md-2">
            <label for="acc_id">Felhasználó ID *</label>
            <input type="number" id="acc_id" class="form-control" ng-model="create.acc_id">
          </div>
          <div class="col-md-4">
            <label for="sizegroup">Formátum *</label>
            <select readonly="readonly" class="form-control" id="sizegroup" ng-options="key as value for (key,value) in terms.sizegroups" ng-model="create.sizegroup"></select>
          </div>
          <div class="col-md-5">
            <label for="target_url">Banner cél URL</label>
            <input type="text" id="target_url" class="form-control" ng-model="create.target_url">
          </div>
          <div class="col-md-1">
            <label for="active">Aktív</label>
            <input type="checkbox" id="active" class="form-control" ng-model="create.active">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="comment">Megjegyzés</label>
            <input type="text" id="comment" class="form-control" ng-model="create.comment">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="banner">Banner anyag</label>
            <div class="uploaded-banner" ng-show="create.content">
              <img src="{{create.content}}" alt="">
              <div class="">
                <a href="javascript:void(0);" ng-click="removeBannerContent(create.ID)"><i class="fa fa-times-circle"></i> Banner anyag törlése</a>
              </div>
            </div>
            <div class="uploader" ng-hide="create.content">
              <input type="file" class="lab-selector" id="banner" file-model="banner">
              <label for="banner">
                <strong>Banner anyag kiválasztásához kattintson ide.</strong>
                <div class="allows">Engedélyezett méret: max. 5 MB. Fájlformátumok: jpg, jpeg, png, gif.</div>
                <div class="selected-image-data" ng-show="selectedprofilimg.size">
                  <h4>Kiválaszott kép adatai:</h4>
                  Fájlformátum: <strong ng-class="(!selectedprofilimg.typecorrect)?'uncorrect':''">{{selectedprofilimg.type}}</strong><br>
                  Fájlméret: <strong ng-class="(!selectedprofilimg.sizecorrect)?'uncorrect':''">{{selectedprofilimg.size|number}} KB</strong><br>
                  Képméret: <strong>{{selectedprofilimg.width}} x {{selectedprofilimg.height}}</strong><br>
                  Képarány: <strong>{{selectedprofilimg.ratio}}</strong><br>
                  Formátum egyezés: <strong>{{selectedprofilimg.sizegroup}}</strong><br>
                </div>
                <div class="img" ng-show="profilpreview">
                  <img ng-src="{{profilpreview}}" alt="{{selectedprofilimg.name}}" class="preview">
                </div>
                <div class="clr"></div>
              </label>
              <div class="" ng-show="profilpreview && !selectedprofilimg.typecorrect">
                <div class="alert alert-danger">
                  A kép formátuma nem megfelelő! Csak az engedélyezett fájlformátumú képek tölthetőek fel!
                </div>
              </div>
              <div class="" ng-show="profilpreview && !selectedprofilimg.sizecorrect">
                <div class="alert alert-danger">
                  A kép fájmérete nem megfelelő! Túl nagy fájlt szeretne feltölteni.
                </div>
              </div>
            </div>
            <div class="clr"></div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-warning" ng-show="!create.acc_id">
              Felhasználó ID megadása kötelező!
            </div>
            <div class="alert alert-warning" ng-show="!create.sizegroup">
              Formátum kiválasztás kötelező! Töltse fel a banner anyagot az automatikus kiválasztás érdekében!
            </div>
            <div class="alert alert-warning" ng-show="profilpreview && !selectedprofilimg.ratio">
              A banner képaránya nem megfelelő! Nincs megfelelő formátum.
            </div>
            <div class="right" ng-show="create.sizegroup && create.acc_id && (!create.ID && profilpreview && selectedprofilimg.ratio)">
              <button type="button" class="btn btn-success" ng-click="saveBanner()">Banner feltöltése <i class="fa fa-upload"></i></button>
            </div>
            <div class="right" ng-show="create.sizegroup && create.acc_id && (create.ID)">
              <button type="button" class="btn btn-success" ng-click="saveBanner()">Banner változásainak mentése <i class="fa fa-save"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
  </div>
  <div style="float:right;">
    <button type="button" ng-click="bannerAdder()" class="btn btn-primary"><i class="fa fa-plus"></i> új banner</button>
  </div>
  <h1>Bannerek</h1>
  <div class="clr"></div>
  <div class="table-filter">
    <div class="row-neg">
      <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-3 right">
          <input type="text" ng-model="filter.name" class="form-control" placeholder="Gyors keresés...">
        </div>
      </div>
    </div>
  </div>
  <div class="con con-row-list">
    <div class="row row-header">
        <div class="col-md-7">Banner tulajdonos</div>
        <div class="col-md-4 center">Bannerek</div>
        <div class="col-md-1 center"></div>
    </div>
    <div class="" ng-repeat="(authorid, data) in banners | searchbanners: filter">
      <div class="row deep markarow" >
        <div class="col-md-7">
          <div class="author">
            <div class="name">
              <strong>{{data.author.nev}}</strong> (#{{data.author.ID}} {{data.author.user_group}})
            </div>
            <div class="email">
              {{data.author.email}}
            </div>
          </div>
        </div>
        <div class="col-md-4 center">
          <div class="total">
            <strong>{{data.banner_nums|number}} db</strong>
          </div>
          <div class="par">
            <span class="active">{{data.banner_active|number}} aktív</span> / <span class="inactive">{{data.banner_inactive|number}} inaktív</span>
          </div>
        </div>
        <div class="col-md-1 right">
          <i class="fa fa-bars" onclick="toggleBannerList(this)" data-authorid="{{data.author.ID}}"></i>
        </div>
      </div>
      <div class="row banner-list" id="bannerlist-author{{data.author.ID}}" style="padding: 0; border: 5px solid #d5d5d5;">
        <div class="col-md-12" style="padding: 0;">
          <div class="row row-header">
              <div class="col-md-1 center">Banner</div>
              <div class="col-md-1 center">Formátum</div>
              <div class="col-md-5">Leírás / URL</div>
              <div class="col-md-3 center">Stat</div>
              <div class="col-md-1 center">Aktiv</div>
              <div class="col-md-1 center"></div>
          </div>
          <div class="banner row markarow" ng-repeat="banner in data.banners">
            <div class="col-md-1 center">
              <a href="{{banner.content}}" ng-show="banner.content" class="preview" target="_blank">Megtekint <img src="{{banner.content}}" alt=""></a>
            </div>
            <div class="col-md-1 center">
              {{banner.sizegroup}}
            </div>
            <div class="col-md-5">
              <div class="comment">
                <div class="no-data" ng-show="!banner.comment">
                  Nincs leírás.
                </div>
                <div class="" ng-hide="!banner.comment">
                  {{banner.comment}}
                </div>
              </div>
              <div class="url">
                <div class="no-data" ng-show="!banner.target_url">
                  Nincs hivatkozás a banneren.
                </div>
                <div class="" ng-hide="!banner.target_url">
                  <i class="fa fa-external-link"></i> {{banner.target_url}}
                </div>
              </div>
            </div>
            <div class="col-md-3 center">
              <div class="month">
                Adott hónap:<br>
                <span title="Megjelenés: Összes (egyedi)"><i class="fa fa-eye"></i> <strong>{{banner.stat.month.all_show}}</strong> ({{banner.stat.month.unique_show}})</span>
                <span title="Kattintás: Összes (egyedi)"><i class="fa fa-bullseye"></i> <strong>{{banner.stat.month.all_click}}</strong> ({{banner.stat.month.unique_click}})</span>
              </div>
              <div class="total">
                Összes:<br>
                <span title="Megjelenés: Összes (egyedi)"><i class="fa fa-eye"></i> <strong>{{banner.stat.total.all_show}}</strong> ({{banner.stat.total.unique_show}})</span>
                <span title="Kattintás: Összes (egyedi)"><i class="fa fa-bullseye"></i> <strong>{{banner.stat.total.all_click}}</strong> ({{banner.stat.total.unique_click}})</span>
              </div>
            </div>
            <div class="col-md-1 center actions">
              <i class="fa fa-ban" ng-show="banner.active!=1"></i>
              <i class="fa fa-check" ng-hide="banner.active!=1"></i>
            </div>
            <div class="col-md-1 center actions">
              <i class="fa fa-pencil" ng-click="pickBanner(banner.ID)"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function  toggleBannerList(e) {
    var id = $(e).data('authorid');
    var c = $('#bannerlist-author'+id).hasClass('active');

    if (!c) {
      $('#bannerlist-author'+id).addClass('active');
    } else {
      $('#bannerlist-author'+id).removeClass('active');
    }
  }
</script>
