<div style="float:right;">
	<a href="/etlapok/etel" class="btn btn-info"><i class="fa fa-cutlery"></i> Ételek</a>
</div>
<h1>Étlap</h1>

<div class="row etel-creator" ng-app="Etlap" ng-controller="Creator" ng-init="init()">
  <div class="col-md-4">
    <div class="con">
      <h3 ng-show="!create.editor">Napi menü rögzítése</h3>
	    <h3 ng-show="create.editor">Napi menü szerkesztése</h3>
      <div class="row-neg">
        <div class="row">
          <div class="col-md-12">
            <label for="daydate">Nap kiválasztása</label>
            <br>
            <md-datepicker md-date-filter="checkDisbledDate" ng-change="menuDateChange(true)" ng-model="create.daydate" id="daydate" md-placeholder="Kiválasztás"></md-datepicker>
            <div class="label label-warning" ng-show="menuDateChecking">
              Menü időpont használatának ellenőrzése...
            </div>
            <div class="label label-danger" ng-show="!menuDateChecking && menuDateUsed">
              Erre az időpontra már lett menü regisztrálva!
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="etel_leves">Leves</label>
            <input id="etel_leves" type="text" class="form-control" ng-model="create.etel_leves.text">
            <div class="sel-etel-item" ng-show="create.etel_leves.id">
              <i title="Töröl" ng-click="removePickedEtel('etel_leves')" class="fa fa-times"></i> Kiválasztva: #{{create.etel_leves.id}} - <strong>{{create.etel_leves.text}}</strong>
            </div>
            <div class="inp-selector" ng-show="(create.etel_leves.text.length != 0 && !create.etel_leves.id)">
              <div class="wrapper">
                <div class="etel" ng-click="pickEtel('etel_leves', e)" ng-repeat="e in etelek | filter:{kategoria:'leves', neve: create.etel_leves.text}">
                  {{e.neve}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="etel_fo">Főétel</label>
            <input id="etel_fo" type="text" class="form-control" ng-model="create.etel_fo.text">
            <div class="sel-etel-item" ng-show="create.etel_fo.id">
              <i title="Töröl" ng-click="removePickedEtel('etel_fo')" class="fa fa-times"></i> Kiválasztva: #{{create.etel_fo.id}} - <strong>{{create.etel_fo.text}}</strong>
            </div>
            <div class="inp-selector" ng-show="(create.etel_fo.text.length != 0 && !create.etel_fo.id)">
              <div class="wrapper">
                <div class="etel" ng-click="pickEtel('etel_fo', e)" ng-repeat="e in etelek | filter:{kategoria:'főétel', neve: create.etel_fo.text}">
                  {{e.neve}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="etel_va">Kiegészítő extra 1.</label>
            <input id="etel_va" type="text" class="form-control" ng-model="create.etel_va.text">
            <div class="sel-etel-item" ng-show="create.etel_va.id">
              <i title="Töröl" ng-click="removePickedEtel('etel_va')" class="fa fa-times"></i> Kiválasztva: #{{create.etel_va.id}} - <strong>{{create.etel_va.text}}</strong>
            </div>
            <div class="inp-selector" ng-show="(create.etel_va.text.length != 0 && !create.etel_va.id)">
              <div class="wrapper">
                <div class="etel" ng-click="pickEtel('etel_va', e)" ng-repeat="e in etelek | filter:{kategoria:'kiegészítő', neve: create.etel_va.text}">
                  {{e.neve}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="etel_vb">Kiegészítő extra 2.</label>
            <input id="etel_vb" type="text" class="form-control" ng-model="create.etel_vb.text">
            <div class="sel-etel-item" ng-show="create.etel_vb.id">
              <i title="Töröl" ng-click="removePickedEtel('etel_vb')" class="fa fa-times"></i> Kiválasztva: #{{create.etel_vb.id}} - <strong>{{create.etel_vb.text}}</strong>
            </div>
            <div class="inp-selector" ng-show="(create.etel_vb.text.length != 0 && !create.etel_vb.id)">
              <div class="wrapper">
                <div class="etel" ng-click="pickEtel('etel_vb', e)" ng-repeat="e in etelek | filter:{kategoria:'kiegészítő', neve: create.etel_vb.text}">
                  {{e.neve}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row" ng-show="saveEtlap">
          <div class="col-md-12 right">
            Napi menü rögzítése folyamatban...
          </div>
        </div>
        <div class="row" ng-show="!saveEtlap && !menuDateUsed">
          <div class="col-md-12 right">
						<button type="button" class="btn btn-default" ng-show="create.editor" ng-click="cancelDayEdit()">mégse</button>
            <button type="button" class="btn btn-primary" ng-show="!create.editor" ng-click="menuSave()">Napi menü rögzítése <i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-success" ng-show="create.editor" ng-click="menuSave()">Napi menü mentése <i class="fa fa-save"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="con">
      <div class="week" ng-repeat="(week, info) in menu.weeks">
        <h2><strong>{{week}}. hét</strong> <span class="daterange">({{info.dateranges.range}})</span> </h2>
        <div class="days">
          <div class="day" ng-repeat="(daydate, day) in info.days">
            <div class="wrapper">
              <div class="onday">
                <div class="date">
									<a href="javascript:void(0);" ng-click="pickDayEdit(day)">{{daydate}} <i class="fa fa-pencil"></i></a>
                </div>
                <div class="weekday">
                  {{day.weekday}}
                </div>
              </div>
              <div class="c">
                <div class="leves" ng-show="day.menu.etel_leves">
                  <div class="lab">
                    Leves
                  </div>
                  <div class="v">
										<a href="/etlapok/etel/?eid={{day.menu.etel_leves.ID}}" target="_blank">{{day.menu.etel_leves.neve}}</a>
                  </div>
                </div>
                <div class="foetel" ng-show="day.menu.etel_fo">
                  <div class="lab">
                    Főétel
                  </div>
                  <div class="v">
                    <a href="/etlapok/etel/?eid={{day.menu.etel_fo.ID}}" target="_blank">{{day.menu.etel_fo.neve}}</a>
                  </div>
                </div>
                <div class="kieg1"  ng-show="day.menu.etel_va">
                  <div class="lab">
                    Kiegészítő 1.
                  </div>
                  <div class="v">
                    <a href="/etlapok/etel/?eid={{day.menu.etel_va.ID}}" target="_blank">{{day.menu.etel_va.neve}}</a>
                  </div>
                </div>
                <div class="kieg2"ng-show="day.menu.etel_vb">
                  <div class="lab">
                    Kiegészítő 2.
                  </div>
                  <div class="v">
                    <a href="/etlapok/etel/?eid={{day.menu.etel_vb.ID}}" target="_blank">{{day.menu.etel_vb.neve}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
