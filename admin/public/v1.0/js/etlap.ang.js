var etlap = angular.module('Etlap', ['ngMaterial']);

etlap.controller("Creator", ['$scope', '$http', '$mdToast', function($scope, $http, $mdToast)
{
  $scope.create = {
    daydate: new Date(),
    etel_leves: {
      text: '',
      id: null
    },
    etel_fo: {
      text: '',
      id: null
    },
    etel_va: {
      text: '',
      id: null
    },
    etel_vb: {
      text: '',
      id: null
    }
  };
  $scope.etelek = [];

	$scope.init = function(){
    $scope.loadResources();
	}

  $scope.pickEtel = function( where, o ){
    console.log(o);
    $scope.create[where].text = o.neve;
    $scope.create[where].id = parseInt(o.ID);
  }

  $scope.removePickedEtel = function( where ){
    $scope.create[where] = {
      text: '',
      id: null
    };
  }

	$scope.loadResources = function( callback )
	{
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'Etelek'
      })
    }).success(function( r ){
      console.log(r);
      if (r.data.length != 0) {
        $scope.etelek = r.data;
      }
			if (typeof callback !== 'undefined') {
				callback(r);
			}
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
