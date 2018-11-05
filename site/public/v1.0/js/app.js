var app = angular.module('vemend', ['ngMaterial', 'ngMessages', 'ngCookies', 'ngMaterialDateRangePicker', 'ngRoute']);

app.config(function($mdDateLocaleProvider){
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

app.controller('App', ['$scope', '$sce', '$http', '$mdToast', '$mdDialog', '$location','$cookies', '$routeParams', '$cookieStore', '$httpParamSerializerJQLike', '$mdDateRangePicker', function($scope, $sce, $http, $mdToast, $mdDialog, $location, $cookies, $routeParams, $cookieStore, $httpParamSerializerJQLike, $mdDateRangePicker)
{
  var date = new Date();

  $scope.urlGET = {};
  $scope.vehicle_num = 0;
  $scope.vehicles = [];
  $scope.vehicles_selected = [];
  $scope.vehicle_childs = {};
  $scope.vehicle_saving = false;

  $scope.fav_num = 0;
  $scope.fav_ids = [];
  $scope.in_progress_favid = false;
  $scope.requesttermprice = {};
  $scope.order_accepted = false;
  $scope.accept_order_key = 'acceptedOrder';
  $scope.accept_order_text = null;
  $scope.accept_order_title = 'Szerződési feltételek elfogadása';

  $scope.customDateEnable = false;
  $scope.calendarModel = {
    selectedTemplate: 'Aktuális hét',
    selectedTemplateName: null,
    dateStart: null,
    dateEnd: null
  };

  $scope.localizationMap = {
    'Mon': 'H',
    'Tue': 'K',
    'Wed': 'Sz',
    'Thu': 'Cs',
    'Fri': 'P',
    'Sat': 'Szo',
    'Sun': 'V',
    'Today': 'Ma',
    'Yesterday': 'Tegnap',
    'This week': 'Ez a hét',
    'Last week': 'Utolsó hét',
    'This month': 'Ez a hónap',
    'Last month': 'Utolsó hónap',
    'This year': 'Ez az év',
    'Last year': 'Utolsó év',
    'January': 'Január',
    'February': 'Február',
    'March': 'Március',
    'April': 'Április',
    'May': 'Május',
    'June': 'Június',
    'July': 'Július',
    'August': 'Augusztus',
    'September': 'Szeptember',
    'October': 'Október',
    'November': 'November',
    'December': 'December'
  };

  $scope.dateFormating = function( date ) {

    var d = new Date(date);
    var mm = d.getMonth() + 1;
    var dd = d.getDate();
    var yy = d.getFullYear();
    return yy + '-' + mm + '-' + dd;
  }

  $scope.isDisabledDate = function(date)
  {
    var d = new Date(date);

    // Hétvégék kikapcsolása
    if(d.getDay() == 6 || d.getDay() == 0){
        return true;
    }

    return false;
  }

  $scope.getWeekDay = function( what )
  {
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week
    var last = first + 6; // last day is the first day + 6

    var firstday = new Date(curr.setDate(first));
    var lastday = new Date(curr.setDate(last));

    return ( what == 'first') ? firstday : lastday;
  }

  $scope.customPickerTemplates = [
    {
      name: 'Ma',
      dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate()),
      dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate())
    },
    {
      name: 'Aktuális hét',
      dateStart: $scope.getWeekDay('first'),
      dateEnd: $scope.getWeekDay('last')
    }
  ];

  $scope.openVehicleSelector = function() {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "vehicles",
        mode: 'getList'
      })
    }).success(function(r){
      $scope.vehicles = r.data;
      $scope.displaySelectedVehicleChilds();
      $mdDialog.show({
        controller: VehicleDialogController,
        templateUrl: '/app/templates/vehicleSelector',
        parent: angular.element(document.body),
        clickOutsideToClose: false,
        fullscreen: true,
        preserveScope: true,
        scope: $scope
      })
      .then(function(answer) {
        $scope.status = 'You said the information was "' + answer + '".';
      }, function() {
        $scope.status = 'You cancelled the dialog.';
      });

      if (typeof callback !== 'undefined') {
        callback(r.data);
      }
    });
  }

  $scope.saveVehicleFilter = function( callback ){
    $scope.vehicle_saving = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "vehicles",
        mode: "saveFilter",
        ids: $scope.vehicles_selected
      })
    }).success(function(r){
      $scope.vehicle_saving = false;
      if (typeof callback !== 'undefined') {
        callback(r.data);
      }
    });
  }

  function VehicleDialogController($scope, $mdDialog) {
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };

    $scope.save = function(){
      $scope.saveVehicleFilter(function() {
        $scope.syncVehicles(function(n, vehicles){
          $scope.vehicle_num = n;
          $scope.vehicles_selected = vehicles;
        });
        $mdDialog.hide();
      });
    }
  }

  $scope.removeChildIDS = function( id, callback ){
    if ( $scope.vehicle_childs && $scope.vehicle_childs[id] && $scope.vehicle_childs[id].data ) {
      angular.forEach($scope.vehicle_childs[id].data, function(e,i){
        var ins = $scope.vehicles_selected.indexOf(i);
        if ( ins !== -1 ) {
          $scope.vehicles_selected.splice($scope.vehicles_selected.indexOf(i), 1);
        }
      });
    }

    if (typeof callback !== 'undefined') {
      callback();
    }
  }

  $scope.selectVehicleItem = function( id ){
    if ($scope.vehicles_selected.indexOf(id) !== -1) {
      $scope.removeChildIDS( id, function(){
        $scope.vehicles_selected.splice($scope.vehicles_selected.indexOf(id), 1);
      } );
    } else {
      $scope.vehicles_selected.push(id);
    }
    $scope.displaySelectedVehicleChilds();
  }

  $scope.displaySelectedVehicleChilds = function(){
    $scope.vehicle_childs = {};
    angular.forEach($scope.vehicles_selected, function(e,i){
      if (typeof $scope.vehicles[e] !== 'undefined') {
        if (typeof $scope.vehicle_childs[e] === 'undefined') {
          $scope.vehicle_childs[e] = {};
        }
        $scope.vehicle_childs[e].title = $scope.vehicles[e].title;
        $scope.vehicle_childs[e].data = $scope.vehicles[e].child;
      }
    });
  }

  $scope.productAddToFav = function( id, ev ){
    var infav = $scope.fav_ids.indexOf(id);

    if ( infav !== -1 ) {
      var confirmRemoveFav = $mdDialog.confirm()
          .title('Biztos, hogy eltávolítja a kedvencekből?')
          .textContent('Ez a termék jelenleg a kedvencei közt szerepel.')
          .ariaLabel('Eltávolítás a kedvencek közül')
          .targetEvent(ev)
          .ok('Eltávolítás')
          .cancel('Mégse');

      $mdDialog.show(confirmRemoveFav).then(function() {
        $scope.doFavAction('remove', id, function(){
          $scope.syncFavs(function(err, n){
            $scope.fav_num = n;
            $scope.in_progress_favid = false;
          });
        });
      }, function() {

      });
    } else {
      $scope.in_progress_favid = id;
      $scope.doFavAction('add', id, function(){
        $scope.syncFavs(function(err, n){
          $scope.fav_num = n;
          $scope.in_progress_favid = false;
        });
      });
    }
  }

  $scope.decodeURIString = function( queryString ) {
    var query = {};
     var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
     for (var i = 0; i < pairs.length; i++) {
         var pair = pairs[i].split('=');
         query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
     }
     return query;
  }

  $scope.init = function( ordernow, httpgetstr )
  {
    // URL get serialized paraméter dekódolása
    $scope.urlGET = $scope.decodeURIString(httpgetstr);

    // Étlap esetén a from és to időpontok alapján való dátum kiválasztás
    if ( $scope.urlGET && $scope.urlGET.tag == 'etlap/' ) {
      if ($scope.urlGET.from != '' && $scope.urlGET.from != '') {
        $scope.calendarModel.dateStart = new Date($scope.urlGET.from);
        $scope.calendarModel.dateEnd = new Date($scope.urlGET.to);
        $scope.calendarModel.selectedTemplate = null;
      }
    }

    /*
    $scope.syncVehicles(function(n, vehicles){
      $scope.vehicle_num = n;
      $scope.vehicles_selected = vehicles;
    });
    $scope.syncFavs(function(err, n){
      $scope.fav_num = n;
    });

    if (typeof ordernow !== 'undefined' && ordernow === true ) {
      $scope.loadSettings( ['tuzvedo_order_pretext','tuzvedo_order_pretext_wanted','tuzvedo_order_pretext_title'], function(settings){
        if (settings.tuzvedo_order_pretext_wanted == '1') {
          $scope.accept_order_title = (settings.tuzvedo_order_pretext_title != '') ? settings.tuzvedo_order_pretext_title : $scope.accept_order_title ;
          $scope.accept_order_text = settings.tuzvedo_order_pretext;
          $scope.acceptBeforeDoneOrder();
        } else {
          $scope.order_accepted = true;
        }
      });
    }*/
  }

  $scope.loadSettings = function( key, callback ){
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "settings",
        key: key
      })
    }).success(function(r){
      callback(r.data);
    });
  }

  $scope.productRemoveFromFav = function( id ){

  }

  $scope.acceptBeforeDoneOrder = function(){
    var accepted = $cookieStore.get( $scope.accept_order_key );

    if ( typeof accepted === 'undefined' )
    {
      var confirm = $mdDialog.confirm({
  			controller: acceptBeforeDoneOrderController,
  			templateUrl: '/app/templates/acceptBeforeDoneOrder',
        scope: $scope,
        preserveScope: true,
  			parent: angular.element(document.body),
        locals: {
          order_accepted: $scope.order_accepted,
          accept_order_key: $scope.accept_order_key
        }
  		});

      function acceptBeforeDoneOrderController( $scope, $mdDialog, order_accepted, accept_order_key) {
        $scope.order_accepted = order_accepted;
        $scope.accept_order_key = accept_order_key;

  			$scope.closeDialog = function(){
  				$mdDialog.hide();
  			}
        $scope.acceptOrder = function(){
          $cookies.put($scope.accept_order_key, 1);
          $scope.order_accepted = true;
          $mdDialog.hide();
  			}
  		}
      $mdDialog.show(confirm);
    } else {
      $scope.order_accepted = true;
    }
  }

  $scope.requestPrice = function( id ){
    var confirm = $mdDialog.confirm({
			controller: RequestPriceController,
			templateUrl: '/app/templates/ProductItemPriceRequest',
			parent: angular.element(document.body),
			locals: {
        termid: id,
        requesttermprice: $scope.requesttermprice
			}
		});

    function RequestPriceController( $scope, $mdDialog, termid, requesttermprice) {
      $scope.sending = false;
      $scope.termid = termid;
      $scope.requesttermprice = requesttermprice;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}

      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.requesttermprice.name !== 'undefined' && $scope.requesttermprice.name.length >= 5) &&
          (typeof $scope.requesttermprice.phone !== 'undefined' && !$scope.requesttermprice.phone.$error) &&
          (typeof $scope.requesttermprice.email !== 'undefined' && !$scope.requesttermprice.email.$error)
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $scope.requesttermprice.termid = parseInt($scope.termid);

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope.requesttermprice
      			})
      		}).success(function(r) {
      			$scope.sending = false;
            $scope.requesttermprice = {};
            console.log(r);

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast(r.msg, 'success', 10000);
      			}
      		});
        }
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
		}

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "getTermItem",
        id: id
      })
    }).success(function(r){
      console.log(r);
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $scope.requesttermprice.product = r.product;
        $mdDialog.show(confirm)
    		.then(function() {
          $scope.status = 'You decided to get rid of your debt.';
        }, function() {
          $scope.status = 'You decided to keep your debt.';
        });
      }
    });

  }

  $scope.doFavAction = function( type, id, callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: type,
        tid: id
      })
    }).success(function(r){
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $mdToast.hide();
        $scope.toast(r.msg, 'success', 5000);
      }

      if (typeof callback === 'function') {
        callback(r.error, r.msg, r);
      }
    });
  }

  $scope.syncVehicles = function( callback ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "vehicles",
        mode: 'getFilter'
      })
    }).success(function(r){
      if (typeof callback === 'function') {
        callback(r.num, r.ids);
      }
    });
  }

  $scope.syncFavs = function( callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: 'get',
        own: 1
      })
    }).success(function(r){
      if (r.ids) {
        $scope.fav_ids = [];
        angular.forEach(r.ids, function(v,i){
          $scope.fav_ids.push(v);
        });
      }
      if (typeof callback === 'function') {
        callback(r.error, r.num);
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

app.controller('ActionButtons', ['$scope', '$http', '$mdDialog', '$mdToast', function($scope, $http, $mdDialog, $mdToast){

  $scope.showHints = true;
  $scope.recall = {};
  $scope.ajanlat = {};

  /**
  * Ingyenes visszahívás modal
  **/
  $scope.requestRecall = function(){
		var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/recall',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        recall: $scope.recall,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, recall, ajanlat) {
      $scope.showHints = showHints;
      $scope.recall = recall;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.recall.name !== 'undefined' && $scope.recall.name.length >= 5) &&
          (typeof $scope.recall.phone !== 'undefined' && !$scope.recall.phone.$error) &&
          (typeof $scope.recall.subject !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			$scope.sending = false;
      			$scope.recall = {};

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast(r.msg, 'success', 10000);
      			}
      		});
        }
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
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }
  /**
  * Ajánlatkérés modal
  **/
  $scope.requestAjanlat = function()
  {
    var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/ajanlatkeres',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, ajanlat) {
      $scope.showHints = showHints;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.ajanlat.name !== 'undefined' && $scope.ajanlat.name.length >= 5) &&
          (typeof $scope.ajanlat.phone !== 'undefined' && !$scope.ajanlat.phone.$error) &&
          (typeof $scope.ajanlat.email !== 'undefined' && !$scope.ajanlat.email.$error) &&
          (typeof $scope.ajanlat.message !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			$scope.sending = false;
      			$scope.ajanlat = {};

            console.log(r);

            if (r.error == 1) {
              $scope.toast(r.msg, 'alert', 10000);
            } else {
              $mdToast.hide();
              $scope.closeDialog();
              $scope.toast(r.msg, 'success', 10000);
            }
      		});
        }
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
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }


}]);

app.controller('Tudastar',['$scope', '$http', '$mdToast', '$element', '$location', '$window', function($scope, $http, $mdToast, $element, $location, $window)
{
  $scope.found_items = 0;
  $scope.loading = false;
  $scope.loaded = false;
  $scope.categories = [];
  $scope.searchKeys = [];
  $scope.validitems = [];
  $scope.catFilters = {};
  $scope.selected_article = false;
  $scope.precats = false;
  $scope.picked_article = false;

  $scope.init = function()
  {
    $scope.doSearch( true );
  }

  $scope.rebuildPath = function()
  {
    // TAGS
    var src = $location.search();
    var tags = $scope.implodeObj($scope.searchKeys, ',');
    src.tags = tags;

    // PICKED ARTICLE
    if ( $scope.selected_article && typeof src.pick === 'undefined' ) {
      src.pick = $scope.selected_article;
    } else if( $scope.selected_article && src.pick != $scope.selected_article ) {
      src.pick = $scope.selected_article;
    } else if( $scope.selected_article === false ){
      src.pick = null;
      $scope.picked_article = false;
    }

    // CATS
    var tempcat = [];
    if ( $scope.catFilters.length != 0 ) {
      angular.forEach( $scope.catFilters, function(e,i){
        tempcat.push( e.ID );
      });
      src.cat = $scope.implodeObj(tempcat, ',');
    }

    $location.path('?', false).search(src);
  }

  $scope.prepareFilters = function(){
    $scope.selected_article = $scope.getURLParam('pick');
    var tags = $scope.getURLParam('tags');
    $scope.precats = $scope.getURLParam('cat');

    if (tags != '') {
      var xtags = tags.split(',');
      if (typeof xtags !== 'undefined') {
        angular.forEach(xtags, function(tag,i){
          $scope.putTagToSearch(tag);
        });
      }
    }
  }

  $scope.implodeObj = function( list, sep )
  {
    var l = '';
    angular.forEach(list, function(e,i){
      l += e + sep;
    });

    l = l.slice(0, -1);

    return l;
  }

  $scope.findArticle = function( article )
  {
    var obj;

    if ( $scope.categories.length != 0 ) {
        angular.forEach( $scope.categories, function(cat, i){
          if (cat.articles.length != 0) {
            angular.forEach( cat.articles, function(art, i){
              if (art.ID == article) {
                obj = art;
              }
            });
          }
        });
    }

    return obj;
  }

  $scope.getURLParam = function( key ){
    var src = $location.search();

    if ( typeof src[key] !== 'undefined' ) {
      return src[key];
    }

    return false;
  }

  $scope.doSearch = function( loader )
  {
    if ( !loader ) {
      $scope.rebuildPath();
    }

    $scope.prepareFilters();
    $scope.loadCategories(function( success ){
      $scope.loaded = true;
      $scope.loading = false;

      var cats = $scope.precats;
      if (cats != '') {
        var cats = cats.split(',');
        if (typeof cats !== 'undefined') {
          angular.forEach(cats, function(cat,i){
            if( !$scope.catInFilter(parseInt(cat)) ) {
              $scope.filterCategory(cat);
            }
          });
        }
      }

      if ( $scope.selected_article ) {
        $scope.picked_article = $scope.findArticle( $scope.selected_article );
      }

    });
  }

  $scope.catInFilter = function( catid ){
    var isin = false;
    if ( $scope.catFilters.length != 0 ) {
      angular.forEach( $scope.catFilters, function(cf, i){
        if( cf.ID == catid){
          isin = true;
        }
      });
    }

    return isin;
  }

  $scope.emptyCatFilters = function(){
    if ( angular.equals({}, $scope.catFilters)) {
      return true;
    }else {
      return false;
    }
  }

  $scope.getcatData = function(catid) {
    var obj;

    if ( $scope.categories.length != 0 ) {
        angular.forEach( $scope.categories, function(cat, i){
          if( cat.ID == catid ) {
            obj = cat;
          }
        });
    }

    return obj;
  }

  $scope.filterCategory = function( catid )
  {
    $scope.selected_article = false;
    var key = 'cat' + catid;
    if ( typeof $scope.catFilters[key] === 'undefined') {
      $scope.catFilters[key] = {};
      $scope.catFilters[key] = $scope.getcatData( catid );
    } else {
      delete $scope.catFilters[key];
    }

    $scope.doSearch( false );
  }

  $scope.toTop = function(){
    $window.scrollTo(0, 0);
  }

  $scope.putTagToSearch = function( tag ){
    if ( $scope.searchKeys.indexOf(tag) === -1) {
      $scope.searchKeys.push(angular.lowercase(tag));
    }
  }

  $scope.inSearchTag = function(tag){
    if ( $scope.searchKeys.indexOf(tag) === -1) {
      return false;
    } else {
      return true;
    }
  }

  $scope.highlightArticle = function( articleid ){
    $scope.pickArticle(articleid, function(){
      $scope.doSearch( false );
    });
  }

  $scope.removeHighlightArticle = function(){
    $scope.selected_article = false;
    $scope.doSearch( false );
  }

  $scope.pickArticle = function( articleid, callback ){
    $scope.selected_article = articleid;

    if ( typeof callback !== 'undefined' ) {
      callback(articleid);
    }
  }

  $scope.loadCategories = function( callback ){
    $scope.loading = true;
    $scope.loaded = false;

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Helpdesk",
        action: 'getcategories',
        search: $scope.searchKeys,
        cats: $scope.catFilters
      })
    }).success(function(r){
      if (r.success == 1) {
        $scope.categories = r.data;
        $scope.found_items = r.count;
      } else {
        $scope.toast( r.msg , 'alert', 10000);
      }

      if (typeof callback !== 'undefined') {
        callback(r.success);
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


/**
* Szállás konfigurátor
**/
app.controller('SzallasCalculator', ['$scope', '$http', '$timeout', function( $scope, $http, $timeout )
{
  //var m = new moment();
  //console.log(m);
  $scope.urlGET = {};
  $scope.szallasid = 0;
  $scope.szallas_adat = {};
  $scope.loading = false;
  $scope.loaded = false;
  $scope.sendingorder = false;
  $scope.sendedorder = false;
  $scope.terms = [];
  var startdate = new Date();
  var enddate = new Date();
  enddate.setDate(enddate.getDate() + 2 );
  $scope.config = {
    'datefrom': startdate,
    'dateto': enddate,
    'nights': 1,
    'ellatas': '',
    'adults': 2,
    'children': 0,
    'children_age': [],
    'room_prices': 0,
    'ifa_price': 0,
    'kisallat_dij': 0,
    'kisallatot_hoz': false,
    'total_price': 0,
    'startorder': false,
    'order_contacts': {
      'name': '',
      'email': '',
      'phone': '',
      'comment': ''
    }
  };
  $scope.picked_rooms = {};
  $scope.rooms = [];

  $scope.decodeURIString = function( queryString ) {
    var query = {};
     var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
     for (var i = 0; i < pairs.length; i++) {
         var pair = pairs[i].split('=');
         query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
     }
     return query;
  }

  $scope.init = function( id, httpgetstr )
  {
    if (typeof httpgetstr !== 'undefined') {
      // URL get serialized paraméter dekódolása
      $scope.urlGET = $scope.decodeURIString(httpgetstr);
      console.log($scope.urlGET);
    }

    $scope.szallasid = id;

    if ( $scope.urlGET ) {
      if ( $scope.urlGET.erkezes ) { $scope.config.datefrom = new Date($scope.urlGET.erkezes); }
      if ( $scope.urlGET.tavozas ) { $scope.config.dateto = new Date($scope.urlGET.tavozas); }
      if ( $scope.urlGET.adults ) { $scope.config.adults = parseInt($scope.urlGET.adults); }
      if ( $scope.urlGET.children ) { $scope.config.children = parseInt($scope.urlGET.children); }
      if ( $scope.urlGET.ellatas ) { $scope.config.ellatas = parseInt($scope.urlGET.ellatas); }
      if ( $scope.urlGET.kisallat ) { $scope.config.kisallatot_hoz = ($scope.urlGET.kisallat == 'true') ? true : false; }
      if ( $scope.urlGET.childrenage ) { $scope.config.children_age = $scope.urlGET.childrenage.split(","); }
    }

    $scope.loadTerms(function() {
      if ($scope.szallasid != 0 && $scope.urlGET ) {
        $scope.refresh();
      }
    });
  }

  $scope.dateFormating = function( date ) {
    var d = (typeof date === 'undefined') ? new Date() : new Date(date);
    var mm = d.getMonth() + 1;
    var dd = d.getDate();
    var yy = d.getFullYear();
    return yy + '-' + mm + '-' + dd;
  }

  $scope.getDateDayDiff = function( d1, d2 )
  {
    var date1 = new Date(d1);
    var date2 = new Date(d2);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    return diffDays;
  }

  $scope.dateChanged = function( pos )
  {
    var df = new Date($scope.config.datefrom);
    var dt = new Date($scope.config.dateto);

    if ( pos == 'datefrom' ) {
      if (dt <= df) {
        var nd = df;
        nd.setDate(nd.getDate() + 1);
        $scope.config.dateto = nd;
      }
    }
  }

  $scope.loadTerms = function( callback )
  {
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'Settings',
        terms: ['ellatas']
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

  $scope.sendOrder = function() {
    $scope.sendingorder = true;
    $scope.sendedorder = false;

    $scope.config.datefrom = $scope.dateFormating($scope.config.datefrom);
    $scope.config.dateto = $scope.dateFormating($scope.config.dateto);

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'sendOrder',
        szallasid: $scope.szallasid,
        config: $scope.config,
        room: $scope.picked_rooms
      })
    }).success(function(r)
    {
      $scope.sendingorder = false;
      console.log(r);

      if (r.error == 0) {
        $scope.config.order_contacts = {
          'name': '',
          'email': '',
          'phone': '',
          'comment': ''
        };
        $scope.sendedorder = true;
        $scope.config.startorder = false;

        $timeout(function()
        {
          $scope.sendedorder = false;
          $scope.picked_rooms = {};
        }, 5000);
      }
    });
  }

  $scope.listSearcher = function() {
    var url = '/szallasok/?';

    $scope.config.datefrom = $scope.dateFormating($scope.config.datefrom);
    $scope.config.dateto = $scope.dateFormating($scope.config.dateto);

    url += 'erkezes='+$scope.config.datefrom;
    url += '&tavozas='+$scope.config.dateto;
    url += '&adults='+$scope.config.adults;
    url += '&children='+$scope.config.children;
    url += '&ellatas='+$scope.config.ellatas;
    url += '&kisallat='+$scope.config.kisallatot_hoz;
    url += '&childrenage='+$scope.config.children_age.join();

    window.location = url;
  }

  $scope.refresh = function( callback )
  {
    if ( !$scope.loading ) {
      var diff = $scope.getDateDayDiff($scope.config.datefrom, $scope.config.dateto);
      console.log(diff);
      $scope.config.nights = diff - 1;

      $scope.loading = true;
      $scope.picked_rooms = {};
      $http({
        method: 'POST',
        url: '/ajax/post',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $.param({
          type: "Szallasok",
          key: 'getConfig',
          szallasid: $scope.szallasid,
          config: $scope.config
        })
      }).success(function(r){
        $scope.loading = false;
        console.log(r);
        if (r.error == 0) {
          $scope.loaded = true;
          $scope.rooms = r.data.rooms;
          $scope.szallas_adat = r.data.szallas;
        }
      });
    }
  }

  $scope.pickConfig = function( room, priceconfig ) {
    $scope.picked_rooms.room = room;
    $scope.picked_rooms.priceconfig = priceconfig;

    console.log( $scope.picked_rooms );

    $scope.calcAjanlat();
  }

  $scope.calcAjanlat = function()
  {
    var pc = $scope.picked_rooms.priceconfig;
    var adults = $scope.config.adults;
    var children = $scope.config.children;
    var nights = $scope.config.nights;
    var roomprices = 0;
    var total_prices = 0;
    var ifaprices = 0;

    if (adults > 0) {
      roomprices += nights * (adults * parseFloat(pc.felnott_ar));
      ifaprices += nights * (adults * parseFloat($scope.szallas_adat.datas.ifa));
    }

    if (children > 0) {
      roomprices += nights * (children * parseFloat(pc.gyerek_ar));
      ifaprices += nights * (children * parseFloat($scope.szallas_adat.datas.ifa));
    }

    if ($scope.config.kisallatot_hoz)
    {
      $scope.config.kisallat_dij += ($scope.szallas_adat.datas.kisallat_dij*nights);
    }

    total_prices += roomprices;
    total_prices += ifaprices;

    $scope.config.ifa_price = ifaprices;
    $scope.config.total_price = total_prices;
    $scope.config.room_prices = roomprices;
  }

}]);

app.filter('unsafe', function($sce){ return $sce.trustAsHtml; });
app.filter('range', function() {
  return function(input, min, max) {
    min = parseInt(min); //Make string input int
    max = parseInt(max);
    for (var i=min; i<=max; i++)
      input.push(i);
    return input;
  };
})


/**
* Popop app
**/
app.controller('popupReceiver', ['$scope', '$sce', '$cookies', '$http', '$location', '$window', '$timeout', function($scope, $sce, $cookies, $http, $location, $window, $timeout)
{
	var ctrl 	= this;
	var _url 	= $location.absUrl();
	var _path 	= $location.path();
	var _host 	= $location.host();
	var loadedsco = false;
	// Defaults
	var _config = {
		'contentWidth' : 970,
		'headerHeight' : 75,
		'responsiveBreakpoint' : 960,
		'domain' : false,
		'receiverdomain' : '',
		'imageRoot' : 'https://www.cp.vemend.web-pro.hu/'
	};

	var param 	= function(obj) {
	    var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

	    for(name in obj) {
	      value = obj[name];

	      if(value instanceof Array) {
	        for(i=0; i<value.length; ++i) {
	          subValue = value[i];
	          fullSubName = name + '[' + i + ']';
	          innerObj = {};
	          innerObj[fullSubName] = subValue;
	          query += param(innerObj) + '&';
	        }
	      }
	      else if(value instanceof Object) {
	        for(subName in value) {
	          subValue = value[subName];
	          fullSubName = name + '[' + subName + ']';
	          innerObj = {};
	          innerObj[fullSubName] = subValue;
	          query += param(innerObj) + '&';
	        }
	      }
	      else if(value !== undefined && value !== null)
	        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
	    }

	    return query.length ? query.substr(0, query.length - 1) : query;
	};

	$http.defaults.headers.post["Content-Type"] = 'application/x-www-form-urlencoded;charset=utf-8';
	$http.defaults.transformRequest = [function(data) {
	    return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
	}];

	$scope.showed = false;
	$scope.test = 'minta';

	/**
	* Böngésző szélesség
	*/
	$scope.windowWidth = function(){
		return parseInt($window.innerWidth);
	}

	/**
	* Böngésző magasság
	*/
	$scope.windowHeight= function(){
		return parseInt($window.innerHeight);
	}

	$scope.init = function ( settings )
	{

		angular.extend( _config, settings );

		ctrl.checkCookie(_config.domain);

		// Dokumentum magasság (px)
		var _documentHeight = jQuery(document).height();

		ctrl.loadScreen(_url, function(sco, template)
		{
			if (sco.show)
			{
				// Timed event
				if (sco.data.creative.type == 'timed')
				{
					$timeout(function()
					{
						ctrl.loadedsco = sco;
						ctrl.loadTemplate(template);
						$scope.showed = true;
						ctrl.logShow( sco.data.creative.id, sco.data.screen_loaded );

					}, sco.data.creative.settings.timed_delay_sec * 1000);
				}

				// Scroll event on scroll
				if (sco.data.creative.type == 'scroll')
				{
					var opencount = 0;

					jQuery(window).scroll(function()
					{
						// Távolság a felső résztől (px)
						var _top = jQuery('body').scrollTop();
						var realheight = jQuery(document).height() - $scope.windowHeight();
						var scrollpercent = _top / (realheight / 100);

						if (scrollpercent > sco.data.creative.settings.scroll_percent_point)
						{
							//console.log(view);
							if ( !$scope.showed && opencount == 0 )
							{
								opencount++;
								ctrl.loadedsco = sco;
								ctrl.loadTemplate(template);
								$scope.showed = true;
								ctrl.logShow( sco.data.creative.id, sco.data.screen_loaded );
							};
						}

					});
				};

				// Mousemove event
				if (sco.data.creative.type == 'exit')
				{
					var delay_pause = (typeof sco.data.creative.settings.exit_pause_delay_sec === 'undefined') ? 0 : sco.data.creative.settings.exit_pause_delay_sec;

					$timeout(function()
					{
						var opencount = 0;
						jQuery(document).mousemove(function(e)
						{
							var w = e.clientX;
							var h = e.clientY;

							if (h < _config.headerHeight )
							{
								if ( !$scope.showed && opencount == 0  )
								{
									opencount++;
									ctrl.loadedsco = sco;
									ctrl.loadTemplate(template);
									$scope.showed = true;
									ctrl.logShow( sco.data.creative.id, sco.data.screen_loaded );
								};
							}
						});

					}, delay_pause * 1000);

				};

			};

		});

	}

	$scope.redirect = function()
	{
		ctrl.logInteraction(true, function()
		{
			ctrl.loadedsco 	= false;
			$scope.showed 	= false;
		});
	}

	$scope.close = function()
	{
		ctrl.logInteraction(false, function()
		{
			ctrl.loadedsco 	= false;
			$scope.showed 	= false;
		});
	}

	// TODO
	this.logInteraction = function(positive, callback)
	{
		$http.post(_config.receiverdomain+'/ajax/post/',
		{
			type 		: 'logPopupClick',
			creative 	: ctrl.loadedsco.data.creative.id,
			screen 		: ctrl.loadedsco.data.screen.id,
			closed 		: (positive) ? 0 : 1,
			sessionid	: ctrl.getSessionID()

		}).success(function(d,s,h,c){
			callback();
		});
	}

	this.loadScreen = function( url, callback )
	{
		$http.post(_config.receiverdomain+'/ajax/post/',
		{
			type 		: 'getPopupScreenVariables',
			url 		: url,
			sessionid	: ctrl.getSessionID()

		}).success(function(d,s,h,c){
			//
			var template = {};

			if (d.show) {
				template = {
					'settings' 	: angular.fromJson(d.data.screen.variables.settings),
					'screen' 	: angular.fromJson(d.data.screen.variables.screen),
					'content' 	: angular.fromJson(d.data.screen.variables.content),
					'interacion': angular.fromJson(d.data.screen.variables.interacion),
					'links' 	: angular.fromJson(d.data.screen.variables.links),
				}
			};

			callback(d, template);

		});
	}

	this.logShow = function( c, s) {

		$http.post(_config.receiverdomain+'/ajax/post/',
		{
			type 		: 'logPopupScreenshow',
			creative 	: c,
			screen 		: s,
			sessionid	: ctrl.getSessionID()

		}).success(function(d,s,h,c){
			console.log(d);
		});
	}

	this.getSessionID = function() {
		return $cookies.get('popupHostSessionID');
	}

	this.checkCookie = function( domain ) {
		var user = $cookies.get('popupHostSessionID');

		// Create
		if (typeof user === 'undefined')
		{
			var key = Math.floor((Math.random()*999999999)+111111111);
			var expires = new Date();
			expires.setDate(expires.getDate() + 30);
			$cookies.put('popupHostSessionID', key, { 'path' : '/', 'domain' : domain, 'expires' : expires });
			user = key;
		}
	}

	this.loadTemplate = function( savedTemplate )
	{

		if ( savedTemplate.settings.type == '%' && $scope.windowWidth() < _config.contentWidth )
		{
			savedTemplate.settings.width = 95;
			savedTemplate.content.title.size  = savedTemplate.content.title.size - (savedTemplate.content.title.size / 100 * 20 );
			savedTemplate.interacion.main.text_size = savedTemplate.interacion.main.text_size - (savedTemplate.interacion.main.text_size / 100 * 30);
		}

		if ( savedTemplate.settings.type == 'px' && $scope.windowWidth() < savedTemplate.settings.width  )
		{
			savedTemplate.settings.width = $scope.windowWidth() - 10;
			savedTemplate.content.title.size  = savedTemplate.content.title.size - (savedTemplate.content.title.size / 100 * 20 );
			savedTemplate.interacion.main.text_size = savedTemplate.interacion.main.text_size - (savedTemplate.interacion.main.text_size / 100 * 30);
		}

		// Settings
		$scope.settings = {};
		$scope.settings.width 	= 50;
		$scope.settings.type 	= '%';
		$scope.settings.width_types = ['px', '%'];
		$scope.settings.background_color = 'rgba(255, 121, 154, 0.79)';
		angular.extend($scope.settings, savedTemplate.settings);

		// Screen
		$scope.screen = {};
		$scope.screen.padding 			= 10;
		$scope.screen.background_color 	= 'rgba(212, 28, 79, 1)';
		$scope.screen.background_image 	= '';
		$scope.screen.background_pos 	= {
			'left top' : 'Balra fentre',
			'left center' : 'Balra középre',
			'left bottom' : 'Balra alulra',
			'right top' : 'Jobbra fentre',
			'right center' : 'Jobbra középre',
			'right bottom' : 'Jobbra alulra',
			'center top' : 'Középre fentre',
			'center center' : 'Középre',
			'center bottom' : 'Középre alulra'
		};
		$scope.screen.background_pos_sel= 'center center';
		$scope.screen.background_reps   = { 'no-repeat' : 'Nincs ismétlődés', 'repeat' : 'Ismétlődik', 'repeat-x' : 'Horizontális tengelyen ismétlődik', 'repeat-y' : 'Vertikális tengelyen ismétlődik'};
		$scope.screen.background_repeat = 'no-repeat';
		$scope.screen.background_sizes 	= { '' : 'Eredeti méret', 'contain' : 'Tartalomhoz igazít', 'cover' : 'Kitöltés', '100%' : '100% szélesség'};
		$scope.screen.background_size 	= '';
		$scope.screen.border_color 		= 'rgba(255, 255, 255, 0.2)';
		$scope.screen.border_size 		= 5;
		$scope.screen.border_type 		= "solid";
		$scope.screen.border_types 		= ['dotted','dashed','solid','double','groove','ridge','inset','outset'];
		$scope.screen.shadow_radius		= 50;
		$scope.screen.shadow_color		= '#000';
		$scope.screen.shadow			= { 'x' : 0, 'y' : 15 };
		$scope.screen.shadow_width		= -5;
		$scope.screen.cssstyles			= '';

		// Szöveg
		$scope.screen.text_color 		= "#fff";
		$scope.screen.text_size 		= 1;
		$scope.screen.text_align		= 'center';

		savedTemplate.screen.background_pos = $scope.screen.background_pos;
		savedTemplate.screen.background_reps = $scope.screen.background_reps;
		savedTemplate.screen.background_sizes = $scope.screen.background_sizes;
		savedTemplate.screen.border_types = $scope.screen.border_types;

		angular.extend($scope.screen, savedTemplate.screen);

		// Content
		$scope.content 					= {};
		$scope.content.title 			= {};
		$scope.content.title.text 		= 'Főcím';
		$scope.content.title.color 		= '';
		$scope.content.title.size 		= 2.4;
		$scope.content.title.align 		= '';

		$scope.content.subtitle 			= {};
		$scope.content.subtitle.text 		= 'Alcím';
		$scope.content.subtitle.color 		= '';
		$scope.content.subtitle.size 		= 1.4;
		$scope.content.subtitle.align 		= '';

		$scope.content.fill 			= {};
		$scope.content.fill.text 		= 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel metus id arcu fermentum rutrum. Aenean neque ante, dignissim non massa non, cursus malesuada nulla. Ut sodales volutpat leo vel lobortis. Nulla sagittis tempor dolor at laoreet. Donec at pharetra mauris. Cras at tortor at sapien condimentum facilisis. Vivamus quis erat non nisl dapibus fermentum in sit amet mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vivamus non dapibus ligula. Donec ac nunc interdum, ultricies ligula vitae, cursus lacus. Cras imperdiet ultrices turpis a pulvinar. Phasellus id tortor vitae ante ultrices elementum eget at elit. Duis cursus arcu et magna porttitor, eget maximus mauris dignissim.';
		$scope.content.fill.color 		= '';
		$scope.content.fill.size 		= 1;
		$scope.content.fill.align 		= '';


		savedTemplate.content.fill.text = savedTemplate.content.fill.text.replace( '../', _config.imageRoot);

		$scope.textHTML 	= function(){
	 		return $sce.trustAsHtml($scope.content.fill.text);
		}

		angular.extend($scope.content, savedTemplate.content);

		// Interakció
		$scope.interacion 					= {};
		$scope.interacion.main 				= {};
		$scope.interacion.main.text 		= 'Tovább';
		$scope.interacion.main.text_color 	= 'rgba(255,255,255,1)';
		$scope.interacion.main.text_size 	= 1.8;
		$scope.interacion.main.text_custom 	= '';
		$scope.interacion.main.text_align 	= 'center';
		$scope.interacion.main.background  	= 'rgba(0,0,0,1)';
		$scope.interacion.main.width 		= 60;
		$scope.interacion.main.width_type   = '%';
		$scope.interacion.main.width_types  = ['%', 'px'];
		$scope.interacion.main.padding  	= 10;
		$scope.interacion.main.margin  		= 10;
		$scope.interacion.main.border_color = '#fff';
		$scope.interacion.main.border_width = 2;
		$scope.interacion.main.border_style = 'solid';
		$scope.interacion.main.border_radius = 10;

		// Kilépő
		$scope.interacion.exit 				= {};
		$scope.interacion.exit.text 		= 'Nem érdekel';
		$scope.interacion.exit.text_color 	= 'rgba(255,255,255,0.8)';
		$scope.interacion.exit.text_style 	= 'italic';
		$scope.interacion.exit.text_styles 	= { 'bold' : 'Félkövér', 'italic' : 'Dölt', 'normal' : 'Normál' };
		$scope.interacion.exit.text_size 	= 0.8;
		$scope.interacion.exit.text_custom 	= '';

		angular.extend($scope.interacion, savedTemplate.interacion);

		// Linkek
		$scope.links 			= {};
		$scope.links.to_url 	= '#';
		$scope.links.exit_url 	= 'javascript:popupClose();';
		$scope.links.open_type 	= '_blank';
		$scope.links.open_types = {'_blank': 'Új ablakban', '_self':'Helyben'};

		angular.extend($scope.links, savedTemplate.links);
	}
}]);

app.directive('formattedDate', function(dateFilter) {
  return {
    require: 'ngModel',
    scope: {
      format: "="
    },
    link: function(scope, element, attrs, ngModelController) {
      ngModelController.$parsers.push(function(data) {
        //convert data from view format to model format
        return dateFilter(data, scope.format); //converted
      });

      ngModelController.$formatters.push(function(data) {
        //convert data from model format to view format
        return dateFilter(data, 'yyyy. MM. dd.'); //converted
      });
    }
  }
});
