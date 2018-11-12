var etlap = angular.module('Etlap', ['ngMaterial']);

etlap.config(function($mdDateLocaleProvider){
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

etlap.controller("Etel", ['$scope', '$http', function($scope, $http)
{
  $scope.syncetelek = false;
  $scope.etelek = [];
  $scope.etelkats = [];
  $scope.src_etelnev = '';
  $scope.creator = {
    id: 0,
    neve: '',
    kategoria: '',
    kep: '',
    kaloria: 0,
    feherje: 0,
    rost: 0,
    zsir: 0,
    cukor: 0,
    so: 0,
    allergenek: ''
  };
  $scope.init = function( editid ){
    $scope.loadResources();

    if (editid) {
      $scope.pickEtel(editid);
    }
	}

  $scope.changeCreatorName = function() {
    $scope.src_etelnev = $scope.creator.neve;
  }

  $scope.resetEditor = function() {
    $scope.creator = {
      id: 0,
      neve: '',
      kategoria: '',
      kep: '',
      szenhidrat: 0,
      kaloria: 0,
      feherje: 0,
      rost: 0,
      zsir: 0,
      cukor: 0,
      so: 0,
      allergenek: ''
    };
    $scope.src_etelnev = '';
  }

  $scope.saveEtel = function() {
    $scope.creator.kep = $('#kep').val();

    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'EtelCreate',
        id: $scope.creator.id,
        data: $scope.creator
      })
    }).success(function( r ){
      if (r.error == 0) {
        $scope.loadResources();
        $scope.creator = {
          id: 0,
          neve: '',
          kategoria: '',
          kep: '',
          szenhidrat: 0,
          kaloria: 0,
          feherje: 0,
          rost: 0,
          zsir: 0,
          cukor: 0,
          so: 0,
          allergenek: ''
        };
      }
    });
  }

  $scope.pickEtel = function( id )
  {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'EtelAdat',
        id: id
      })
    }).success(function( r ){
      if (r.data) {
        $scope.creator = r.data;
        $scope.src_etelnev = r.data.neve;
      } else {
        $scope.src_etelnev = '';
        $scope.creator = {
          id: 0,
          neve: '',
          kategoria: '',
          kep: '',
          szenhidrat: 0,
          kaloria: 0,
          feherje: 0,
          rost: 0,
          zsir: 0,
          cukor: 0,
          so: 0,
          allergenek: ''
        };
      }
    });
  }

  $scope.loadResources = function( callback )
  {
    $scope.syncetelek = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'Etelek'
      })
    }).success(function( r ){
      $scope.syncetelek = false;
      if (r.data.length != 0) {
        $scope.etelek = r.data;

        angular.forEach(r.data, function(e,i)
        {
          if ( $scope.etelkats.indexOf(e.kategoria) === -1 ) {
            $scope.etelkats.push(e.kategoria);
          }
        });
      }
      if (typeof callback !== 'undefined') {
        callback(r);
      }
    });
  }
}]);

etlap.controller("Creator", ['$scope', '$http', '$mdToast', function($scope, $http, $mdToast)
{
  $scope.saveEtlap = false;
  $scope.menuDateChecking = false;
  $scope.menuDateUsed = false;
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
  $scope.usedDate = [];
  $scope.menu = [];

	$scope.init = function(){
    $scope.loadResources();
	}

  $scope.checkDisbledDate = function( date ) {
    var val = true;
    var now = new Date();
    var date = date.toLocaleDateString('hu-HU');

    if (new Date(date) < now ) {
      // Előző napok kikapcsolása
      val = false;
    } else {
      if ($scope.usedDate && $scope.usedDate.length != 0) {
        if ($scope.usedDate.indexOf(date) !== -1) {
          val = false;
        } else {
          val = true;
        }
      }
    }

    return val;
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

	$scope.loadResources = function( callback )
	{
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'Load'
      })
    }).success(function( r ){
        console.log(r);
      if (r.data.length != 0) {
        if (r.data.etelek && r.data.etelek.length != 0) {
          $scope.etelek = r.data.etelek;
        }
        if (r.data.useddates && r.data.useddates.length != 0) {
          $scope.usedDate = r.data.useddates;
        }
        if (r.data.set && r.data.set.length != 0) {
          $scope.menu = r.data.set;
        }
      }

        console.log($scope.menu);
			if (typeof callback !== 'undefined') {
				callback(r);
			}
    });
	}

  $scope.menuSave = function(){
    $scope.saveEtlap = true;

    console.log($scope.create.daydate);

    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Etlap",
        key: 'AddMenu',
        menu: $scope.create
      })
    }).success(function( r ){
      $scope.saveEtlap = false;
      $scope.loadResources();
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
