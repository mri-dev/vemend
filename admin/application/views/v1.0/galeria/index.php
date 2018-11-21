<div ng-app="Gallery" ng-controller="Creator" ng-init="init()">
  <div style="float:right;">
  	<a href="/galeria/kategoriak" class="btn btn-default"><i class="fa fa-picture-o"></i> galéria mappák</a>
  </div>
  <h1>Galéria</h1>

  <div class="row-neg">
    <div class="row gallery-modul">
      <div class="" ng-class="(pickedfolder)?'col-md-5':'col-md-12'">
        <div class="con">
          <h3>Mappák / kollekciók</h3>
          <div class="row np row-head">
            <div class="col-md-10"><em>Mappa/Kollekció</em></div>
            <div class="col-md-2 center"><em>Képek</em></div>
          </div>
          <div class="categories">
            <div class="row np deep0" ng-repeat="(gall, gallery) in galleries" ng-class="(gall==pickedfolder)?'on-edit':''">
              <div class="col-md-10">
                <a href="javascript:void(0);" ng-click="pickFolder(gall)">{{gallery.neve}}</a>
              </div>
              <div class="col-md-2 center">
                {{gallery.imagesnum}} <i class="fa fa-picture-o"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div ng-class="(pickedfolder)?'col-md-7':'col-md-12'" ng-hide="!pickedfolder">
        <div class="con">
          <h2><strong>{{galleries[pickedfolder].neve}}</strong> &mdash; képeinek kezelése</h2>
          <br>
          <h3>Új képek feltöltése</h3>
          <?php if (true): ?>
          <div class="uploader">
            <input type="file" class="lab-selector" id="imagesuploader" image-uploader="profil" multiple="multiple">
            <label for="imagesuploader">
              <strong>Képek kiválasztásához kattintson ide.</strong>
              <div class="allows">Engedélyezett méret: max. 2024 KB. Fájlformátumok: jpg, jpeg, png.</div>
              <div class="clr"></div>
            </label>
            <div class="uploading-image-dataset" ng-show="selectedUploadingImages.length!=0">
              <h3>Újonnan feltöltendő képek ({{selectedUploadingImages.length}}):</h3>
              <div class="picture" ng-class="(img.correct_extension && img.correct_size)?'':'unable-to-upload'" ng-repeat="img in selectedUploadingImages" >
                <div class="wrapper">
                  <div class="image">
                    <img src="{{img.preview}}" alt="">
                  </div>
                  <div class="data">
                    <div class="title">
                      <strong>{{img.name}}</strong>
                    </div>
                    <div class="size" ng-hide="img.uploaded" ng-class="(img.correct_size)?'correct-val':'incorrect-val'">
                      Méret: <strong>{{img.size|number}} KB</strong>
                    </div>
                    <div class="size" ng-hide="img.uploaded" ng-class="(img.correct_extension)?'correct-val':'incorrect-val'">
                      Kiterjesztés: <strong>{{img.type}}</strong>
                    </div>
                    <div class="uploaded-msg"  ng-show="img.uploaded===true">
                      <i class="fa fa-check-circle-o"></i> Kép feltöltve.
                    </div>
                    <div class="uploaded-msg msg-error"  ng-show="img.uploaded!==true&&img.uploaded!==false">
                      <i class="fa fa-times"></i> {{img.uploaded}}
                    </div>
                  </div>
                </div>
              </div>
              <div class="alert alert-warning" ng-show="uploadingimages">
                <i class="fa fa-spin fa-spinner"></i> Képek feltöltése folyamatban.
              </div>
              <div class="upload-button" ng-show="selectedUploadingImages.length!=0" ng-hide="uploadingimages">
                <button type="button" ng-click="uploadImages(galleries[pickedfolder].ID)" class="btn btn-default" name="button">Képek feltöltése <i class="fa fa-upload"></i></button>
              </div>
            </div>
          </div>
          <div class="clr"></div>
          <?php endif; ?>
          <br>
          <div class="rnav">
            <div style="color: #8ab160; cursor: pointer;"  ng-show="!imageediting" ng-click="toggleVar('imageediting', true)">
              Képszerkesztő bekapcsolása <i class="fa fa-pencil"></i>
            </div>
            <div style="color: #e69a9a; cursor: pointer;" ng-hide="!imageediting" ng-click="toggleVar('imageediting', false)">
              Képszerkesztő kikapcsolása <i class="fa fa-ban"></i>
            </div>
          </div>
          <h3>Feltöltött képek ({{galleries[pickedfolder].imagesnum}})</h3>
          <div class="clr"></div>
          <div class="uploaded-images">
            <div class="" ng-show="galleries[pickedfolder].images.length==0">
              Jelenleg nincsenek feltöltött képek!
            </div>
            <div class="wrapper" ng-class="(imageediting)?'editor':''">
              <div class="image" ng-repeat="img in galleries[pickedfolder].images">
                <div class="wrapper">
                  <div class="img-wrapper">
                    <img src="{{img.filepath}}" alt="{{img.title}}">
                  </div>
                  <div class="info">
                    <div class="ext" ng-show="!imageediting">
                      {{img.kiterjesztes}}
                    </div>
                    <div class="size" ng-show="!imageediting">
                      {{img.filemeret}} KB
                    </div>
                    <div class="editor-inps" ng-show="imageediting">
                      <div class="title">
                        <label for="">Kép címe</label>
                        <input type="text" ng-model="img.title" class="form-control">
                      </div>
                      <div class="desc">
                        <label for="">Kép rövid leírása</label>
                        <textarea class="form-control" ng-model="img.description"></textarea>
                      </div>
                      <div class="delete">
                        <input id="galimg_del_img{{img.ID}}" type="checkbox" ng-model="img.deleting"> <label for="galimg_del_img{{img.ID}}">kép törlése</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="right" ng-show="imageediting">
              <br>
              <div class="alert alert-warning left" ng-show="imagesaving">
                Képek mentése folyamatban...<i class="fa fa-spin fa-spinner"></i>
              </div>
              <button ng-hide="imagesaving" type="button" class="btn btn-success" ng-click="saveImages()">Képek mentése <i class="fa fa-save"></i></button>
            </div>
          </div>
          <script type="text/javascript">
            $(function(){
              $('.uploaded-images > .wrapper').sortable({
                update: function(e, ui){
                  var list = $('.uploaded-images > .wrapper > .image');
                  $.each(list, function(li, le){
                    var ae = angular.element(le).scope();
                    ae.$apply(function(){
                      ae.img.sorrend = li;
                    });
                  });
                }
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
