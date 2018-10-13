var szallasok = angular.module('Szallasok', ['ngMaterial','ui.tinymce']);

szallasok.config(function($mdDateLocaleProvider){
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

szallasok.service('fileUploadService', function($http, $q){
  this.uploadFileToUrl = function (file, uploadUrl, callback) {
      var fileFormData = new FormData();
      fileFormData.append('file', file);
      var deffered = $q.defer();

      $http({
        method: 'POST',
        url: uploadUrl,
        params: {
          type: 'SzallasProfilUpload'
        },
        data: fileFormData,
        headers: {
           'Content-Type': undefined
        },
      }).then(function successCallback(response) {
        console.log(response);
        callback(response.data);
      }, function errorCallback(response) {
        console.log(response);
        callback(response.data);
      });
  }
});

szallasok.filter('html', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

szallasok.directive('fileModel', ['$parse', function ($parse) {
  return {
    link: function(scope, element, attributes) {
      element.bind("change", function(changeEvent) {
        scope.fileinput = changeEvent.target.files[0];

        var ext = scope.fileinput.name.split('.').pop().toLowerCase();
        var correct_ext = scope.allowProfilType.indexOf(ext) > -1;

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
        } else {
          scope.cansavenow = false;
        }

        if(scope.selectedprofilimg.size > 2024) {
          scope.cansavenow = false;
        } else {
          if(correct_ext){
            scope.cansavenow = true;
          }
        }
      });
    }
  }
}]);

szallasok.controller("Szallas", ['$scope', '$http', '$mdToast', '$timeout', 'fileUploadService', function($scope, $http, $mdToast, $timeout, fileUploadService)
{

  $scope.allowProfilType = ['jpg', 'jpeg', 'png'];
  $scope.selectedprofilimg = {
    size: 0,
    type: null
  };

  $scope.savingszallas = false;
  $scope.creating = false;
  $scope.editing = false;
  $scope.author = 0;
  $scope.create = {
    id: 0
  };
  $scope.szallasok = [];
  $scope.baseMsg = {
    'type': 'success',
    'msg': ''
  };

  $scope.tinymceOptions = {};

	$scope.init = function( author ){
    if (typeof author !== 'undefined') {
      $scope.author = author;
    }
    $scope.loadSzallasok();
	}

  $scope.resetSzallas = function() {
    $scope.creating = false;
    $scope.editing = false;
    $scope.create = {};
  }

  $scope.creatingSwitch = function() {
    $scope.creating = true;
    $scope.editing = false;
    $scope.create.id = 0;
  }

  $scope.pickSzallas = function( szallas ){
    $scope.create = szallas;
    $scope.create.text = szallas.title;
    $scope.create.id = parseInt(szallas.ID);
    $scope.creating = true;
    $scope.editing = true;
  }

  $scope.removePickedEtel = function( where ){
    $scope.create[where] = {
      text: '',
      id: null
    };
  }

  $scope.uploadSzallasImage = function(callback){
    var file = $scope.fileinput;
    var uploadUrl = "/ajax/data/", //Url 1of webservice/api/server
    promise = fileUploadService.uploadFileToUrl(file, uploadUrl, function(re){
      callback(re);
    });
  }

  $scope.menuDateChange = function() {
    $scope.create.daydate = $scope.create.daydate.toLocaleDateString('hu-HU');
    $scope.menuDateChecking = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'CheckMenuDateUsage',
        day: $scope.create.daydate
      })
    }).success(function( r ){
      $scope.menuDateChecking = false;
      if (r.data != 0) {
        $scope.menuDateUsed = true;
      } else {
        $scope.menuDateUsed = false;
      }
    });
  }

	$scope.loadSzallasok = function( callback )
	{
    $scope.szallasok = [];
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'List',
        author: $scope.author
      })
    }).success(function( r ){
      if (r.data && r.data.list.length != 0) {
        $scope.szallasok = r.data.list;
      }
			if (typeof callback !== 'undefined') {
				callback(r);
			}
    });
	}

  $scope.saveSzallas = function()
  {
    $scope.savingszallas = true;
    $scope.uploadSzallasImage(function(re){
      console.log('Image Upload');
      console.log(re);
    });

    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'SaveCreate',
        szallas: $scope.create
      })
    }).success(function( r ){

      $scope.savingszallas = false;
      $scope.baseMsg.msg = r.msg;

      console.log(r);

      if (r.error == 0) {
        $scope.loadSzallasok();
        $scope.create = {};
        $scope.creating = false;
        $scope.editing = false;
        $scope.baseMsg.type = 'success';
      } else {
        $scope.baseMsg.type = 'danger';
      }
      $timeout(function(){
        $scope.baseMsg.msg = '';
      }, 5000);
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
