<div class="banner-modul" ng-app="Banners" ng-controller="Bannerek" ng-init="init()">
  <h1>Bannerek</h1>
  <div class="con con-row-list">
    <div class="row row-header">
        <div class="col-md-7">Banner tulajdonos</div>
        <div class="col-md-4 center">Bannerek</div>
        <div class="col-md-1 center"></div>
    </div>
    <div class="" ng-repeat="(authorid, data) in banners">
      <div class="row deep markarow" >
        <div class="col-md-7">
          <div class="author">
            <div class="name">
              <strong>{{data.author.nev}}</strong> ({{data.author.user_group}})
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
            (<span class="active">{{data.banner_active|number}} aktív</span> / <span class="inactive">{{data.banner_inactive|number}} inaktív</span>)
          </div>
        </div>
        <div class="col-md-1 right">
          <i class="fa fa-bars"></i>
        </div>
      </div>
      <div class="row" style="padding: 0;">
        <div class="col-md-12" style="padding: 0;">
          <div class="row row-header">
              <div class="col-md-1 center">Banner</div>
              <div class="col-md-6">Leírás / URL</div>
              <div class="col-md-4">Stat</div>
              <div class="col-md-1 center">Aktiv</div>
          </div>
          <div class="banner row markarow" ng-repeat="banner in data.banners">
            <div class="col-md-1 center">
              <a href="{{banner.content}}" target="_blank">Megtekint</a>
            </div>
            <div class="col-md-6">
              <div class="comment">
                {{banner.comment}}
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
            <div class="col-md-4 center">
              <div class="month">
                Adott hónap:<br>
                <span title="Megjelenés: Összes (egyedi)"><i class="fa fa-eye"></i> {{banner.stat.month.all_show}} ({{banner.stat.month.unique_show}})</span>
                <span title="Kattintás: Összes (egyedi)"><i class="fa fa-bullseye"></i> {{banner.stat.month.all_click}} ({{banner.stat.month.unique_click}})</span>
              </div>
              <div class="total">
                Összes:<br>
                <span title="Megjelenés: Összes (egyedi)"><i class="fa fa-eye"></i> {{banner.stat.total.all_show}} ({{banner.stat.total.unique_show}})</span>
                <span title="Kattintás: Összes (egyedi)"><i class="fa fa-bullseye"></i> {{banner.stat.total.all_click}} ({{banner.stat.total.unique_click}})</span>
              </div>
            </div>
            <div class="col-md-1 center">
              <i class="fa fa-ban" ng-show="banner.active!=1"></i>
              <i class="fa fa-check" ng-hide="banner.active!=1"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
