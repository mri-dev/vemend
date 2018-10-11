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

szallasok.controller("Szallas", ['$scope', '$http', '$mdToast', function($scope, $http, $mdToast)
{
  $scope.saveSzallas = false;
  $scope.creating = false;
  $scope.editing = false;
  $scope.author = 0;
  $scope.create = {
    id: 0
  };
  $scope.szallasok = [];

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

  $scope.saveSzallas = function(){
    $scope.saveSzallas = true;
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
      $scope.saveSzallas = false;
      //$scope.loadSzallasok();
      console.log(r);
      //$scope.create = {};
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
