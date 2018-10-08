<div style="float:right;">
	<a href="/etlapok" class="btn btn-info"><i class="fa fa-bars"></i> Étlapok</a>
</div>
<h1>Étlap / Ételek</h1>

<div class="row etel-creator" ng-app="Etlap" ng-controller="Etel" ng-init="init(<?=(isset($_GET['eid'])?$_GET['eid']:'0')?>)">
  <div class="col-md-4">
    <div class="con" ng-class="(creator.id!=0)?' con-edit':''">
      <h3 ng-hide="creator.id!=0">Új étel hozzáadása</h3>
      <h3 ng-show="creator.id!=0">{{creator.neve}} szerkesztése</h3>
      <div class="row-neg">
        <div class="row">
          <div class="col-md-12">
            <label for="neve">Étel neve *:</label>
            <input type="text" ng-change="changeCreatorName()" ng-model="creator.neve" class="form-control">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="neve">Étel kategória *:</label>
            <select class="form-control" ng-options="i for i in ['leves', 'főétel', 'kiegészítő']" ng-model="creator.kategoria"></select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="neve">Étel képe:</label>
            <div class="input-group">
                <input type="text" id="kep"class="form-control" ng-model="creator.kep">
                <div class="input-group-addon"><a title="Kép kiválasztása a galériából" href="<?=FILE_BROWSER_IMAGE?>&field_id=kep" data-fancybox-type="iframe" class="iframe-btn" ><i class="fa fa-image"></i></a></div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="kaloria">Kalória:</label>
            <div class="input-group">
              <input type="number" id="kaloria" class="form-control" ng-model="creator.kaloria">
              <span class="input-group-addon">kcal</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="feherje">Fehérje:</label>
            <div class="input-group">
              <input type="number" id="feherje" class="form-control" ng-model="creator.feherje">
              <span class="input-group-addon">g</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="rost">Rost:</label>
            <div class="input-group">
              <input type="number" id="rost" class="form-control" ng-model="creator.rost">
              <span class="input-group-addon">g</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="zsir">Zsír:</label>
            <div class="input-group">
              <input type="number" id="zsir" class="form-control" ng-model="creator.zsir">
              <span class="input-group-addon">g</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="cukor">Cukor:</label>
            <div class="input-group">
              <input type="number" id="cukor" class="form-control" ng-model="creator.cukor">
              <span class="input-group-addon">g</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="so">Só:</label>
            <div class="input-group">
              <input type="number" id="so" class="form-control" ng-model="creator.so">
              <span class="input-group-addon">g</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="neve">Allergének:</label>
            <input type="text" ng-model="creator.allergenek" class="form-control" placeholder="Vesszővel elválasztva. Pl.: liszt, glutén">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 right">
            <button ng-show="creator.id!=0" type="button" class="btn btn-default" ng-click="resetEditor()"> Mégse </button>
            <button type="button" ng-show="creator.id==0 && (creator.neve && creator.kategoria)" class="btn btn-primary" ng-click="saveEtel()"> Étel hozzáadása <i class="fa fa-plus-circle"></i> </button>
            <button type="button" ng-show="creator.id!=0 && (creator.neve && creator.kategoria)" class="btn btn-success" ng-click="saveEtel()"> Étel módosítása <i class="fa fa-save"></i> </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="con">
      <div class="alert alert-warning" ng-show="syncetelek">
        Elérhető ételek betöltése folyamatban...
      </div>
      <div class="etel-list" ng-show="!syncetelek && etelek.length != 0">
        <div class="header">
          <table>
            <tr>
              <td>
                <strong>{{etelek.length}} db</strong> étel
              </td>
              <td width="250">
                <div class="input-group">
                  <span class="input-group-addon">Kategória:</span>
                   <select class="form-control" ng-model="src_etelkat" ng-options="kat for kat in etelkats" placeholder="Kategória">
                     <option ng-selected="true" value="">Összes kategória</option>
                   </select>
                </div>
              </td>
              <td width="200">
                <input type="text" ng-model="src_etelnev" class="form-control" placeholder="Gyorskeresés...">
              </td>
            </tr>
          </table>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th width="120"></th>
              <th>Étel</th>
              <th width="120" class="center">Kategória</th>
              <th width="80"></th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="etel in etelek | filter:{neve: src_etelnev, kategoria: (!src_etelkat) ? undefined : src_etelkat}" ng-class="(etel.ID == creator.id)?'picked':''">
              <td>
                <div class="image">
                  <img src="{{etel.kep}}" alt="{{etel.neve}}">
                </div>
              </td>
              <td>
                <div class="title">{{etel.neve}}</div>
                <div class="ertekek">
                  <span><strong>Kalória:</strong> {{etel.kaloria}} kcal</span>
                  <span><strong>Fehérje:</strong> {{etel.feherje}}g</span>
                  <span><strong>Rost:</strong> {{etel.rost}}g</span>
                  <span><strong>Zsír:</strong> {{etel.zsir}}g</span>
                  <span><strong>Cukor:</strong> {{etel.cukor}}g</span>
                  <span><strong>Só:</strong> {{etel.so}}g</span>
                </div>
                <div class="allergen">
                  <strong>Allergének:</strong> {{etel.allergenek}}
                </div>
              </td>
              <td class="center">
                {{etel.kategoria}}
              </td>
              <td class="center actions">
                <i class="fa fa-pencil" ng-click="pickEtel(etel.ID)" title="Szerkesztés"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
