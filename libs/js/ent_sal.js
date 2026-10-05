app.controller('ent_salCtrl', function($scope, $http, store, $state, jwtHelper) {
$scope.ValidaLogin = function(){
    	var token = store.get("token") || null;
        if(!token){
        	$state.go("login");
        	console.log('SI valida que no haya token');
        }else{
        	var bool = jwtHelper.isTokenExpired(token);
        	if(bool === true){
        		store.remove('token');
        		console.log('SI valida tojken caducado');
        		$state.go("login");
        	}
       	}
    }

//INICIALIZA VARIABLES
    $scope.array = [];
    $scope.array_ = angular.copy($scope.array);
    $scope.currentPage = 1;
    $scope.pageSize = 25;
    $scope.names = [];

// LEE TODOS LOS MOVIMIENTOS
    $scope.leeMovimientos = function(){
       $http.get("obtiene_ent_sal.php").success(function(response){
           $scope.names = response.records;
           $scope.configPages($scope.names);
       });
    }


// MUESTRA EL MODAL DE MOVIMIENTOS
    $scope.muestraFormulario = function(){
        $scope.limpiaFormulario();
        $('#modal-product-title').text("Agregar Movimiento");
        $('#btn-update-product').hide();
        $('#btn-create-product').show();
    }

// LIMPIA EL FORMULARIO DE MOVIMIENTOS
    $scope.limpiaFormulario = function(){
        $scope.monto = "";
        $scope.concepto = "";
        $('select').prop('selectedIndex', 0); //Sets the first option as selected
        $('select').material_select();
    }

// CREA UN MOVIMIENTO
    $scope.creaMovimiento = function(){
        $http.post('crear_ent_sal.php', {
            'fecha' : $('#fecha').val(),
            'importe' : $scope.monto,
            'tipo' : $('#tipo').val(),
            'concepto' : $scope.concepto
        }
    ).success(function (data, status, headers, config) {
        console.log(data);
        Materialize.toast(data, 4000);
        $('#modal-product-form').closeModal();
        $scope.limpiaFormulario();
        $scope.leeMovimientos();
    });
    }



// MUESTRA EL MODAL DE EMPLEADOS
    $scope.muestraImportar = function(){
        $scope.ruta_archivo="";
    }


$scope.borraMovimiento = function(id_ent_sal){
    if(confirm("Esta seguro de eliminar el movimiento?")){
        $http.post('borra_movimiento.php', {
            'id_ent_sal' : id_ent_sal
        }).success(function (data, status, headers, config){
            Materialize.toast(data, 4000);
            $scope.leeMovimientos();
        });
    }
}


//CONFIGURACION DE LA PAGINACION



});

//FILTRO DE LA PAGINACION
 app.filter('startFromGrid', function() {
        return function(input, start) {
            if (!input || !input.length) { return; }
            start = +start; //parse to int
            return input.slice(start);
        };
    });



//directiva de archivo
    app.directive('uploadFileMovimiento', function (httpPostFactory) {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attr) {
                element.bind('change', function () {
                    var formData = new FormData();
                    formData.append('file', element[0].files[0]);
                    httpPostFactory('importar_movimiento.php', formData, function (callback) {
                         $('#modal-import-empleados').closeModal();
                        scope.leeMovimientos();
                   $('#archivo').val('');
                        Materialize.toast(callback,4000);
                    });
                });

            }
        };
    });

    app.factory('httpPostFactory', function ($http) {
        return function (file, data, callback) {
            $http({
                url: file,
                method: "POST",
                data: data,
                headers: {'Content-Type': undefined}
            }).success(function (response) {
                callback(response);
            });
        };
    });
