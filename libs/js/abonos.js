app.controller('abonosCtrl', function($scope, $http, store, $state, jwtHelper) {

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

// LEE TODOS LOS EJERCICIOS
$scope.leeEjercicios = function(){
    $http.get("obtiene_ejercicios.php").success(function(response){
        $scope.ejercicios = response.records;
    });
}

// LEE TODOS LOS AHORROS
$scope.leeAbonos = function(){
    $http.get("obtiene_abonos.php").success(function(response){
        $scope.names = response.records;
    });
}

// AGREGA AHORRO
    $scope.detalleAbono = function(id, nombre){
        $http.post('obtiene_abono_empleados.php', {
            'id_empleado' : id
        })
        .success(function(response){
            $scope.det_abono = response.records;
            $('#modal-text-detalle').text(id+" - "+nombre);
            $('#modal-detalle-abonos').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

// AGREGA AHORRO
    $scope.agregaAbono = function(id){
        $('#btn-update-product').show();
        $http.post('obtiene_un_empleado.php', {
            'id_empleado' : id
        })
        .success(function(data, status, headers, config){
            $scope.id_empleado = parseInt(data[0]["id_empleado"]);
            $scope.nombre = data[0]["nombre"];
            $("#form-id").attr("disabled", "true");
            $("#form-nombre").attr("disabled", "true");
            $('#modal-add-abono').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

    //ACTUALIZA LOS DATOS PARA LOS EMPELADOS
$scope.guardaAbono = function(){
    $http.post('guarda_abono.php', {
        'id_empleado' : $scope.id_empleado,
        'nombre' : $scope.nombre,
        'fecha' : $('#fecha').val(),
        'monto' : $scope.monto,
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $('#modal-add-abono').closeModal();
        $scope.limpiaFormulario();
        $scope.leeAbonos();
    });
}

//BORRA UN AHORRO
$scope.borraAbono = function(id,fecha,monto){
    $http.post('borra_abono.php', {
        'id_empleado' : id,
        'fecha' : fecha,
        'monto' : monto,
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $scope.detalleAbonoAct(id);
        $scope.leeAbonos();
    });
}

$scope.detalleAbonoAct = function(id){
        $http.post('obtiene_abono_empleados.php', {
            'id_empleado' : id
        })
        .success(function(response){
            $scope.det_abono = response.records;
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

// LIMPIA EL FORMULARIO DE EMPLEADOS
    $scope.limpiaFormulario = function(){
        $scope.id_empleado = "";
        $scope.nombre = "";
        $scope.monto = "";
    }


// MUESTRA EL MODAL DE EMPLEADOS
    $scope.muestraImportar = function(){
        $scope.ruta_archivo="";
    }
});
//FILTRO DE LA PAGINACION
 app.filter('startFromGrid', function() {
        return function(input, start) {
            if (!input || !input.length) { return; }
            start = +start; //parse to int
            return input.slice(start);
        };
    });

    //IMPORTACION DE ARCHIVO DE AHORROS

    //directiva de archivo
    app.directive('uploadAbonos', function (httpPostFactory) {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attr) {
                element.bind('change', function () {
                    var formData = new FormData();
                    formData.append('file', element[0].files[0]);
                    httpPostFactory('importar_abono.php', formData, function (callback) {
                         $('#modal-import-abonos').closeModal();
                        scope.leeAbonos();
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
