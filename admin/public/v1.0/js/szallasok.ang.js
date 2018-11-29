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

szallasok.filter('html', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

szallasok.directive('imageUploader', ['$parse', function ($parse) {
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

szallasok.directive('fileModel', ['$parse', function ($parse) {
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

szallasok.filter('szallassearcher', function() {
  return function(list,search) {
    var newlist = {};
    var customfilter = false;

    if (search.name.search(new RegExp('user:', "i")) !== -1) {
      customfilter = true;

      angular.forEach(list, function(e,i){
        if(search.name == ''){
          newlist[i] = e;
        } else {
          var grab = search.name.match(/user:(\d+)/);
          if (grab && grab[1] != '' && grab[1] > 0 ) {
            if(e.author_data.ID == grab[1]){
              newlist[i] = e;
            }
          }
        }
      });


    } else {
      customfilter = false;
      angular.forEach(list, function(e,i){
        if(
          search.name == '' ||
          (
            e.title.search(new RegExp(search.name, "i")) !== -1 ||
            e.cim.search(new RegExp(search.name, "i")) !== -1 ||
            e.contact_email.search(new RegExp(search.name, "i")) !== -1 ||
            e.author_data.nev.search(new RegExp(search.name, "i")) !== -1 ||
            e.author_data.email.search(new RegExp(search.name, "i")) !== -1
          )
        ){
          newlist[i] = e;
        }
      });
    }
    return newlist;
  }
});

szallasok.controller("Szallas", ['$scope', '$http', '$mdToast', '$timeout', '$parse', 'fileUploadService', function($scope, $http, $mdToast, $timeout, $parse, fileUploadService)
{
  $scope.allowProfilType = ['jpg', 'jpeg', 'png'];
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

  $scope.loadinglist = false;
  $scope.listloaded = false;
  $scope.imageediting = false;
  $scope.imageeditprogress = false;
  $scope.currentProfilkep = false;
  $scope.deletingImages = [];

  $scope.tabs = [
    {
      name: 'general',
      title: 'Általános'
    },
    {
      name: 'rooms',
      title: 'Szobák'
    },
    {
      name: 'pictures',
      title: 'Képek'
    },
    {
      name: 'services',
      title: 'Szolgáltatások'
    }
  ];
  $scope.editing_page = 'general';

  $scope.savingszallas = false;
  $scope.creating = false;
  $scope.editing = false;
  $scope.cansavenow = true;
  $scope.roomsaving = false;
  $scope.servicessaving = false;
  $scope.uploadingimages = false;
  $scope.author = 0;
  $scope.roomediting = false;
  $scope.create = {
    id: 0,
    ellatasok: [],
    rooms: []
  };
  $scope.szallasok = [];
  $scope.szallas_datas = {};
  $scope.servicecreator = [];
  $scope.serviceCategories = [];
  $scope.baseMsg = {
    'type': 'success',
    'msg': ''
  };
  $scope.terms = {};

  $scope.tinymceOptions = {};

	$scope.init = function( author, httpgetstr ){

    // URL get serialized paraméter dekódolása
    $scope.urlGET = $scope.decodeURIString(httpgetstr);

    if ($scope.urlGET.filter) {
      $scope.filter.name = $scope.urlGET.filter;
    }

    if (typeof author !== 'undefined') {
      $scope.author = author;
    }
    $scope.loadTerms(function() {
      $scope.loadSzallasok();
    });
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

    if ($scope.editing) {
      $scope.loadSzallasDatas($scope.create.id);
    }
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

  $scope.imageEditing = function() {
    if ( !$scope.imageeditprogress )
    {
      $scope.imageeditprogress = true;
      $scope.collectDeletingImages();

      $http({
        method: 'POST',
        url: '/ajax/get',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        data: $.param({
          type: "Szallasok",
          key: 'imageModifier',
          id: $scope.create.ID,
          deleteimages: $scope.deletingImages,
          profilkep: $scope.currentProfilkep
        })
      }).success(function( r ){
        console.log(r);
        if (r.error == 0)
        {
          $scope.imageeditprogress = false;
          $scope.deletingImages = [];
          $scope.loadSzallasDatas( $scope.create.ID );
        }
      });
    }
  }

  $scope.collectDeletingImages = function() {
    var dcb = $('input[type=checkbox].deletingImageCb:checked');
    $scope.deletingImages = [];

    angular.forEach(dcb, function(e,i){
      var id = $(e).val();
      $scope.deletingImages.push(id);
    });
  }

  $scope.loadSzallasDatas = function( id ) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'LoadSzallasData',
        id: id
      })
    }).success(function( r ){
      if (r.data && r.data.datas) {
        $scope.currentProfilkep = false;
        angular.forEach(r.data.datas, function(e,i){
          if (typeof $scope.create[i] === 'undefined') {
            $scope.create[i] = e;
          } else {
            $scope.create[i] = e;
          }

          if (i == 'services') {
            $scope.serviceCategories = [];
            angular.forEach(e, function(c,i){
              $scope.serviceCategories.push(i);
            });
          }
        });

        angular.forEach($scope.create.pictures, function(i, ii){
          if (i.profilkep) {
            $scope.currentProfilkep = i;
          }
        });
      }
    });
  }

  $scope.addRooms = function() {
    $scope.create.rooms.push({});
  }
  $scope.addService = function() {
    $scope.servicecreator.push({
      kategoria: '',
      title: ''
    });
  }

  $scope.saveService = function(id, services) {
    $scope.servicessaving = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'SaveRoomServices',
        szallas: id,
        services: services,
        update: $scope.create.services
      })
    }).success(function( r ){
      $scope.servicessaving = false;
      $scope.serviceediting = false;

      if (r.error) {
        $scope.baseMsg.type = 'danger';
        $scope.baseMsg.msg = r.msg;
      } else {
        $scope.servicecreator = [];
        $scope.baseMsg.type = 'success';
        $scope.baseMsg.msg = r.msg;
        $timeout(function(){
          $scope.baseMsg.msg = '';
          $scope.loadSzallasDatas( id );
        }, 5000);
      }
    });
  }

  $scope.saveRooms = function(id, rooms) {
    $scope.roomsaving = true;
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'SaveRooms',
        szallas: id,
        rooms: rooms
      })
    }).success(function( r ){
      $scope.roomsaving = false;

      if (r.error) {
        $scope.baseMsg.type = 'danger';
        $scope.baseMsg.msg = r.msg;
      } else {
        $scope.baseMsg.type = 'success';
        $scope.baseMsg.msg = r.msg;
        $timeout(function(){
          $scope.baseMsg.msg = '';
          $scope.loadSzallasDatas( id );
        }, 5000);
      }

      console.log(r);
    });
  }

  $scope.switchTab = function( tab ) {
    $scope.editing_page = tab;
  }

  $scope.removePickedEtel = function( where ){
    $scope.create[where] = {
      text: '',
      id: null
    };
  }

  $scope.uploadSzallasImage = function(szallas_id, callback)
  {
    var file = $scope.fileinput;
    var uploadUrl = "/ajax/data/", //Url 1of webservice/api/server
    promise = fileUploadService.uploadFileToUrl(szallas_id, file, uploadUrl, false, function(re){
      callback(re);
    });
  }

  $scope.uploadSzallasImages = function(szallas_id, callback)
  {
    var uploadUrl = "/ajax/data/"; //Url 1of webservice/api/server
    angular.forEach($scope.uploadimages, function( file, i ){
      promise = fileUploadService.uploadFileToUrl(szallas_id, file, uploadUrl, false, function(re){
        callback(i, re);
      });
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

	$scope.loadSzallasok = function( callback )
	{
    $scope.szallasok = [];
    $scope.loadinglist = true;
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
      console.log(r);
      $scope.loadinglist = false;
      $scope.listloaded = true;
      if (r.data && r.data.list && r.data.list.length != 0) {
        $scope.szallasok = r.data.list;
      }
			if (typeof callback !== 'undefined') {
				callback(r);
			}
    });
	}

  $scope.refreshSzallasProfilkepURI = function(id, path) {
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'UpdateProfilkepURI',
        id: id,
        path: path
      })
    }).success(function( r ){
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

  $scope.uploadImages = function(id)
  {
    $scope.uploadingimages = true;
    var uploaded = 0;
    $scope.uploadSzallasImages(id, function(index, re)
    {
      console.log($scope.selectedUploadingImages[index]);
      console.log(re);
      uploaded++;

      if (re && !re.error) {
        $scope.saveUploadedImageToSzallas(id, $scope.selectedUploadingImages[index], re, false, function(save){
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
          $scope.loadSzallasDatas( $scope.create.id );
        }, 2000);
      }

    });
  }

  $scope.saveSzallas = function()
  {
    $scope.savingszallas = true;

    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Szallasok",
        key: 'SaveCreate',
        szallas: $scope.create
      })
    }).success(function( r )
    {
      if (r.error == 0) {
        // Képek feltöltése
        /* */
        $scope.uploadingimages = true;
        $scope.uploadSzallasImage(r.data, function(re)
        {
          $scope.saveUploadedImageToSzallas(r.data, $scope.selectedprofilimg, re, true, function(save){
            if (save.error == 0) {
              if (re.FILE) {
                $scope.refreshSzallasProfilkepURI(r.data, re.uploaded_path);
              }
              $scope.savingszallas = false;
              $scope.loadSzallasok();
              $scope.uploadingimages = false;
              $scope.create = {};
              $scope.creating = false;
              $scope.editing = false;
              $scope.baseMsg.type = 'success';
              $scope.baseMsg.msg = r.msg;
              $timeout(function(){
                $scope.baseMsg.msg = '';
              }, 5000);
            }
          });
        });
        /* */
        /* * /
        $scope.loadSzallasok();
        $scope.create = {};
        $scope.creating = false;
        $scope.editing = false;
        $scope.baseMsg.type = 'success';
        /* */
      } else {
        $scope.savingszallas = false;
        $scope.uploadingimages = true;
        $scope.baseMsg.type = 'danger';
        $scope.baseMsg.msg = r.msg;
        $timeout(function(){
          $scope.baseMsg.msg = '';
        }, 5000);
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
