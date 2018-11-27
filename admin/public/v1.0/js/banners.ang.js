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
  this.uploadFileToUrl = function (bannerid, file, uploadUrl, params, callback) {
      var fileFormData = new FormData();
      fileFormData.append('file', file);
      var deffered = $q.defer();

      $http({
        method: 'POST',
        url: uploadUrl,
        params: {
          type: 'BannerUploader',
          id: bannerid,
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

            var img = new Image();
            img.src = loadEvent.target.result;
            img.onload = function(){
              scope.$apply(function(){
                scope.selectedprofilimg.width = img.width;
                scope.selectedprofilimg.height = img.height;

                if (img.width == img.height) {
                  scope.selectedprofilimg.ratio = '1:1';
                  scope.selectedprofilimg.sizegroup = '1P1';
                  scope.create.sizegroup = '1P1';
                } else if(img.width == (img.height * 2)){
                  scope.selectedprofilimg.ratio = '2:1';
                  scope.selectedprofilimg.sizegroup = '2P1';
                  scope.create.sizegroup = '2P1';
                } else if(img.width == (img.height * 5)){
                  scope.selectedprofilimg.ratio = '10:2';
                  scope.selectedprofilimg.sizegroup = 'BILLBOARD';
                  scope.create.sizegroup = 'BILLBOARD';
                } else {
                  scope.selectedprofilimg.ratio = '';
                  scope.selectedprofilimg.sizegroup = '';
                  scope.create.sizegroup = '';
                }
              });
            };
          }
          reader.readAsDataURL(scope.fileinput);
          scope.cansavenow = true;
          scope.selectedprofilimg.typecorrect = true;
        } else {
          scope.selectedprofilimg.typecorrect = false;
        }

        if(scope.selectedprofilimg.size > 5120) {
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
  $scope.terms = [];
  $scope.allowProfilType = ['jpg', 'jpeg', 'png', 'gif'];
  $scope.selectedprofilimg = {
    size: 0,
    sizecorrect: true,
    type: null,
    typecorrect: true,
    name: '',
    width: 0,
    height: 0,
    ratio: '',
    sizegroup: ''
  };
  $scope.filter = {
    name: ''
  };
  $scope.create = {
    ID: 0
  };

  $scope.creating = false;
  $scope.savingbanner = false;
  $scope.uploadingbanner= false;
  $scope.deletingImages = [];
  $scope.banners = [];

  $scope.baseMsg = {
    'type': 'success',
    'msg': ''
  };

  $scope.tinymceOptions = {};

	$scope.init = function(){
    $scope.loadTerms(function() {
      $scope.loadBanners(function() {

      });
    });
	}

  $scope.searchBanners = function(v) {
    console.log(v);
  }

  $scope.bannerAdder = function() {
    $scope.creating = true;
    $scope.create = {
      ID: 0
    };
  }

  $scope.pickBanner = function( id ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'getBanner',
        id: id
      })
    }).success(function( r ){
      if (r.error == 0) {
        $scope.creating = true;
        $scope.create = r.data;
      }
      console.log(r);
    });
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

  $scope.uploadBannerImage = function(bannerid, callback)
  {
    var file = $scope.fileinput;
    var uploadUrl = "/ajax/data/", //Url 1of webservice/api/server
    promise = fileUploadService.uploadFileToUrl(bannerid, file, uploadUrl, false, function(re){
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

  $scope.loadTerms = function( callback )
  {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'Settings',
        terms: ['sizegroups']
      })
    }).success(function( r ){
      if (r.data) {
        angular.forEach(r.data,function(e,i){
          if (typeof $scope.terms[i] === 'undefined') {
            $scope.terms[i] = e;
          }
        });
      }
			if (typeof callback !== 'undefined') {
				callback();
			}
    });
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

  $scope.saveBanner = function() {
    $scope.savingbanner = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'saveBanner',
        data: $scope.create
      })
    }).success(function( r ){
      if (r.error == 0) {
        if ($scope.fileinput) {
          $scope.uploadingbanner = true;
          $scope.uploadBannerImage(r.data, function(re){
            $scope.saveUploadedBanner(r.data, $scope.selectedprofilimg, re, function(save){
              if (save.error == 0) {
                $scope.loadBanners(function() {});
                $scope.savingbanner = false;
                $scope.uploadingbanner= false;
                $scope.selectedprofilimg = {
                  size: 0,
                  sizecorrect: true,
                  type: null,
                  typecorrect: true,
                  name: '',
                  width: 0,
                  height: 0,
                  ratio: '',
                  sizegroup: ''
                };
                $scope.fileinput = false;
                $scope.create = {
                  ID: 0
                };
                $scope.creating = false;
                $scope.baseMsg.type = 'success';
                $scope.baseMsg.msg = r.msg;
                $timeout(function(){
                  $scope.baseMsg.msg = '';
                }, 5000);
              }
            });
          });
        } else {
          $scope.loadBanners(function() {});
          $scope.savingbanner = false;
        }
      }
    });
  }

  $scope.removeBannerContent  = function(id) {
    $scope.savingbanner = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'removeContent',
        id: id
      })
    }).success(function( r ){
      if (r.error == 0) {
        $scope.savingbanner = false;
        $scope.pickBanner( id );
      }
    });
  }

  $scope.saveUploadedBanner = function( bannerid, imageobject, uploadreturn, callback ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Banners",
        key: 'registerUploadedBanner',
        id: bannerid,
        imageobject: imageobject,
        origin_name: imageobject.name,
        size: imageobject.size,
        ext: imageobject.type,
        filepath: uploadreturn.uploaded_path
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
