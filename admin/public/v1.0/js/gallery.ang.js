var gallery = angular.module('Gallery', ['ngMaterial','ui.tinymce']);

gallery.filter('html', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

gallery.service('fileUploadService', function($http, $q){
  this.uploadFileToUrl = function (group, gallery_id, file, uploadUrl, params, callback) {
      var fileFormData = new FormData();
      fileFormData.append('file', file);
      var deffered = $q.defer();

      $http({
        method: 'POST',
        url: uploadUrl,
        params: {
          type: 'GalleryUploader',
          group: group,
          id: gallery_id,
          params: params
        },
        data: fileFormData,
        headers: {
           'Content-Type': undefined
        },
      }).then(function successCallback(response) {
        callback(response.data);
      }, function errorCallback(response) {
        callback(response.data);
      });
  }
});

gallery.directive('imageUploader', ['$parse', function ($parse) {
  return {
    link: function(scope, element, attributes) {
      element.bind("change", function(changeEvent)
      {
        scope.selectedUploadingImages = [];
        scope.uploadimages = [];

        angular.forEach( changeEvent.target.files, function(file,index)
        {
          var ext = file.name.split('.').pop().toLowerCase();
          var correct_ext = scope.allowProfilType.indexOf(ext) > -1;
          var imageobj = {
            name: file.name,
            type: ext,
            size: file.size/1024,
            correct_size: false,
            correct_extension: false,
            preview: null,
            uploaded: false
          };

          // Fájlméret ellenőrzése
          if(imageobj.size > 2024) {
            imageobj.correct_size = false;
          } else {
            imageobj.correct_size = true;
          }

          // Kiterjesztés ellenőrzése
          if (correct_ext) {
            if (imageobj.correct_size) {
              var reader = new FileReader();
              reader.onload = function(loadEvent) {
                scope.$apply(function() {
                  imageobj.correct_extension = true;
                  imageobj.preview = loadEvent.target.result;
                });
              }
              reader.readAsDataURL(file);
              imageobj.correct_extension = true;
            }
          } else {
            imageobj.correct_extension = false;
          }

          scope.uploadimages.push(file);
          scope.selectedUploadingImages.push(imageobj);
        });
      });
    }
  }
}]);

gallery.controller("Creator", ['$scope', '$http', '$mdToast', '$timeout', '$parse', 'fileUploadService', function($scope, $http, $mdToast, $timeout, $parse, fileUploadService)
{
  $scope.allowProfilType = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
  $scope.selectedUploadingImages = [];
  $scope.galleries = [];
  $scope.pickedfolder = null;
  $scope.imageseditor = false;

  $scope.init = function( author ){
    $scope.loadGalleries(function() {
    });
	}

  $scope.pickFolder = function( folderslug )
  {
    $scope.pickedfolder = folderslug;
  }

  $scope.uploadGalleryImages = function(group, gallery_id, callback)
  {
    var uploadUrl = "/ajax/data/"; //Url 1of webservice/api/server
    angular.forEach($scope.uploadimages, function( file, i ){
      promise = fileUploadService.uploadFileToUrl(group, gallery_id, file, uploadUrl, false, function(re){
        callback(i, re);
      });
    });
  }

  $scope.saveUploadedImageToGallery = function( gallery_id, imageobject, uploadreturn, callback ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Gallery",
        key: 'registerUploadedImage',
        id: gallery_id,
        origin_name: imageobject.name,
        size: imageobject.size,
        ext: imageobject.type,
        filepath: uploadreturn.uploaded_path
      })
    }).success(function( r ){
        callback(r);
    });
  }

  $scope.uploadImages = function(id)
  {
    $scope.uploadingimages = true;
    var uploaded = 0;
    var group = $scope.pickedfolder;
    $scope.uploadGalleryImages(group, id, function(index, re)
    {
      console.log($scope.selectedUploadingImages[index]);
      console.log(re);
      uploaded++;

      if (re && !re.error) {
        $scope.saveUploadedImageToGallery(id, $scope.selectedUploadingImages[index], re, function(save){
          if (save.error == 0) {
            // Finish upload
            $scope.selectedUploadingImages[index].uploaded = true;
          } else {
            $scope.selectedUploadingImages[index].uploaded = false;
          }
        });
      } else {
        $scope.selectedUploadingImages[index].uploaded = re.msg;
      }

      if (uploaded == $scope.selectedUploadingImages.length) {
        $scope.uploadingimages = false;
        $timeout(function(){
          $scope.selectedUploadingImages = [];
          $scope.uploadimages = [];
          $scope.loadGalleries();
        }, 2000);
      }

    });
  }

  $scope.loadGalleries = function( callback )
  {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Gallery",
        key: 'Load'
      })
    }).success(function( r ){
      console.log(r);
      if (r.data && r.error == 0) {
        $scope.galleries = r.data;
      }
			if (typeof callback !== 'undefined') {
				callback();
			}
    });
  }

}]);
