var banners = angular.module('Banners', ['ngMaterial','ui.tinymce']);

banners.config(function($mdDateLocaleProvider){
  $mdDateLocaleProvider.firstDayOfWeek = 1;
  $mdDateLocaleProvider.months = ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'];
  $mdDateLocaleProvider.shortMonths = ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún', 'Júl', 'Aug', 'Szep', 'Okt', 'Nov', 'Dec'];
  $mdDateLocaleProvider.days = ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'];
  $mdDateLocaleProvider.shortDays = ['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'];

  $mdDateLocaleProvider.formatDate = function(date) {
     var m = moment(date);
     return m.isValid() ? m.format('L') : '';
   };
});

banners.service('fileUploadService', function($http, $q){
  this.uploadFileToUrl = function (szallas_id, file, uploadUrl, params, callback) {
      var fileFormData = new FormData();
      fileFormData.append('file', file);
      var deffered = $q.defer();

      $http({
        method: 'POST',
        url: uploadUrl,
        params: {
          type: 'SzallasProfilUpload',
          id: szallas_id,
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

banners.filter('html', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

banners.directive('imageUploader', ['$parse', function ($parse) {
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

banners.directive('fileModel', ['$parse', function ($parse) {
  return {
    link: function(scope, element, attributes) {
      element.bind("change", function(changeEvent) {
        scope.fileinput = changeEvent.target.files[0];

        var ext = scope.fileinput.name.split('.').pop().toLowerCase();
        var correct_ext = scope.allowProfilType.indexOf(ext) > -1;

        scope.selectedprofilimg.name = scope.fileinput.name;
        scope.selectedprofilimg.type = ext;
        scope.selectedprofilimg.size = scope.fileinput.size / 1024;

        if(correct_ext) {
          var reader = new FileReader();
          reader.onload = function(loadEvent) {
            scope.$apply(function() {
              scope.profilselected = true;
              scope.profilpreview = loadEvent.target.result;
            });
          }
          reader.readAsDataURL(scope.fileinput);
          scope.cansavenow = true;
          scope.selectedprofilimg.typecorrect = true;
        } else {
          scope.selectedprofilimg.typecorrect = false;
        }

        if(scope.selectedprofilimg.size > 2024) {
          scope.selectedprofilimg.sizecorrect = false;
          scope.cansavenow = false;
        } else {
          if(correct_ext){
            scope.selectedprofilimg.sizecorrect = true;
            scope.cansavenow = true;
          }
        }
      });
    }
  }
}]);

banners.filter('searchbanners', function() {
  return function(list,search) {
    var newlist = {};
    angular.forEach(list, function(e,i){
      if( search.name == '' ||
        (e.author.nev.search(new RegExp(search.name, "i")) !== -1 || e.author.email.search(new RegExp(search.name, "i")) !== -1)
      ){
        newlist[e.author.ID] = e;
      }
    });
    return newlist;
  }
});

banners.controller("Bannerek", ['$scope', '$http', '$mdToast', '$timeout', '$parse', 'fileUploadService', function($scope, $http, $mdToast, $timeout, $parse, fileUploadService)
{
  $scope.allowProfilType = ['jpg', 'jpeg', 'png', 'gif'];
  $scope.selectedUploadingImages = [];
  $scope.selectedprofilimg = {
    size: 0,
    sizecorrect: true,
    type: null,
    typecorrect: true,
    name: ''
  };
  $scope.filter = {
    name: ''
  };

  $scope.imageediting = false;
  $scope.imageeditprogress = false;
  $scope.currentProfilkep = false;
  $scope.deletingImages = [];
  $scope.banners = [];

  $scope.baseMsg = {
    'type': 'success',
    'msg': ''
  };

  $scope.tinymceOptions = {};

	$scope.init = function(){
    $scope.loadBanners(function() {

    });
	}

  $scope.searchBanners = function(v) {
    console.log(v);
  }

  $scope.serviceQuerySearch = function(query)
  {
    var results = query ? $scope.serviceCategories.filter( $scope.serviceQueryFilterFor(query) ) : $scope.serviceCategories;
    return results;
  }

  $scope.serviceQueryFilterFor = function(query) {
    return function filterFn(serv) {
      return (serv.indexOf(query) === 0);
    };
  }

  $scope.toggleVar = function(o,v) {
    var m = $parse(o);
    m.assign($scope, v);
  }

  $scope.collectDeletingImages = function() {
    var dcb = $('input[type=checkbox].deletingImageCb:checked');
    $scope.deletingImages = [];

    angular.forEach(dcb, function(e,i){
      var id = $(e).val();
      $scope.deletingImages.push(id);
    });
  }

  $scope.uploadSzallasImage = function(szallas_id, callback)
  {
    var file = $scope.fileinput;
    var uploadUrl = "/ajax/data/", //Url 1of webservice/api/server
    promise = fileUploadService.uploadFileToUrl(szallas_id, file, uploadUrl, false, function(re){
      callback(re);
    });
  }

  $scope.findTermByID = function( key, id, field ) {
    var list = $scope.terms[key];

    if (typeof list !== 'undefined') {
      for(var i = list.length -1; i >= 0; i--){
        var set = list[i];
        if (set.ID == id) {

          return (typeof field === 'undefined') ? set : set[field];
        }
      }
    }

    return false;
  }

	$scope.loadBanners = function( callback )
	{
    $scope.banners = [];
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'AdminList'
      })
    }).success(function( r ){
      console.log(r);
      if (r.data && r.data.list && r.data.list.length != 0) {
        $scope.banners = r.data.list;
      }
			if (typeof callback !== 'undefined') {
				callback(r);
			}
    });
	}

  $scope.saveUploadedImageToSzallas = function( szallasid, imageobject, uploadreturn, profil, callback ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'registerUploadedImageToSzallas',
        id: szallasid,
        origin_name: imageobject.name,
        size: imageobject.size,
        ext: imageobject.type,
        filepath: uploadreturn.uploaded_path,
        profil: profil
      })
    }).success(function( r ){
        callback(r);
    });
  }

	$scope.toast = function( text, mode, delay ){
		mode = (typeof mode === 'undefined') ? 'simple' : mode;
		delay = (typeof delay === 'undefined') ? 5000 : delay;

		if (typeof text !== 'undefined') {
			$mdToast.show(
				$mdToast.simple()
				.textContent(text)
				.position('top')
				.toastClass('alert-toast mode-'+mode)
				.hideDelay(delay)
			);
		}
	}

}]);
