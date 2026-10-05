app.controller('prestamosCtrl', function($scope, $http, store, $state, jwtHelper) {
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
    $scope.currentPage = 1;
    $scope.pageSize = 25;
    $scope.names = [];
    $scope.array = [];
    $scope.array_ = angular.copy($scope.array);
    $scope.fecha_actual = new Date();
    $scope.FromDate = $scope.fecha_actual.getFullYear() + '-' + ('0' + ($scope.fecha_actual.getMonth() + 1)).slice(-2) + '-' + ('0' + $scope.fecha_actual.getDate()).slice(-2);
    $id_prestamo="";

  // LIMPIA EL FORMULARIO DE EMPLEADOS
    $scope.limpiaFormulario = function(){
        $scope.id_empleado = "";
        $scope.nombre = "";
        $scope.monto = 0;
        $scope.plazo = 0;
        $('#fecha').val($scope.FromDate);
    }

// LEE TODOS LOS EJERCICIOS
$scope.leeEjercicios = function(){
    $http.get("obtiene_ejercicios.php").success(function(response){
        $scope.ejercicios = response.records;
    });
}

// LEE TODOS LOS PRESTAMOS
$scope.leePrestamos = function(){
    $http.get("obtiene_prestamos.php").success(function(response){
        $scope.names = response.records;
    });
}


// AGREGA PRESTAMO
    $scope.agregaPrestamo = function(id){
      $scope.limpiaFormulario();
    	$('#btn-guarda-prestamo').show();
      $('#btn-actualiza-prestamo').hide();
        $http.post('obtiene_un_empleado.php', {
            'id_empleado' : id
        })
        .success(function(data, status, headers, config){
            $scope.id_empleado = parseInt(data[0]["id_empleado"]);
            $scope.nombre = data[0]["nombre"];
            $("#form-id").attr("disabled", "true");
            $("#form-nombre").attr("disabled", "true");
            $('#modal-add-prestamo').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

// EDITA PLAZO DE PRESTAMO
    $scope.MuestraPrestamo = function(id){
    	$('#modal-product-title').text('Modificar Prestamo')
        $('#btn-guarda-prestamo').hide();
        $('#btn-actualiza-prestamo').show();
        $http.post('obtiene_un_prestamo.php', {
            'id_prestamo' : id
        })
        .success(function(data, status, headers, config){
            $scope.id_empleado = parseInt(data["id_empleado"]);
            $scope.nombre = data["nombre"];
            $scope.plazo =  parseFloat(data["plazo"]);
            $scope.monto = parseFloat(data["monto"]);
            $('#fecha').val(data["fecha"]);
			id_prestamo=data["id_prestamo"];
            $("#form-id").attr("disabled", "true");
            $("#form-nombre").attr("disabled", "true");
            $('#modal-add-prestamo').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

//ACTUALIZA UN PRESTAMO
$scope.actualizaPrestamo = function(){
	id=$scope.id_empleado;
    $http.post('actualiza_prestamo.php', {
        'id_prestamo' : id_prestamo,
        'id_empleado' : $scope.id_empleado,
        'fecha' : $('#fecha').val(),
        'monto' : $scope.monto,
        'plazo' : $scope.plazo,
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $('#modal-add-prestamo').closeModal();
        $scope.limpiaFormulario();
        $scope.detallePrestamoAct(id);
        $scope.leePrestamos();
    });
}



     //CREA UN PRESTAMO
$scope.guardaPrestamo = function(){
    $http.post('guarda_prestamo.php', {
        'id_empleado' : $scope.id_empleado,
        'fecha' : $('#fecha').val(),
        'monto' : $scope.monto,
        'plazo' : $scope.plazo,
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $('#modal-add-prestamo').closeModal();
        $scope.limpiaFormulario();
        $scope.leePrestamos();
    });
}

// DETALLE DE LOS PRESTAMOS
    $scope.detallePrestamo = function(id, nombre){
        $http.post('obtiene_prestamo_empleados.php', {
            'id_empleado' : id
        })
        .success(function(response){
            $scope.det_prestamo = response.records;
            $('#modal-text-detalle').text(id+" - "+nombre);
            $('#modal-detalle-prestamos').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

//BORRA UN AHORRO
$scope.borraPrestamo = function(id){
    $http.post('borra_prestamo.php', {
        'id_prestamo' : id,
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $scope.detallePrestamoAct(id);
        $scope.leePrestamos();
    });
}

$scope.detallePrestamoAct = function(id){
        $http.post('obtiene_prestamo_empleados.php', {
            'id_empleado' : id
        })
        .success(function(response){
            $scope.det_prestamo = response.records;
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
}

// MUESTRA EL MODAL DE EMPLEADOS
    $scope.muestraImportar = function(){
        $scope.ruta_archivo="";
    }


/******************************************************************************************************* */

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
    app.directive('uploadPrestamos', function (httpPostFactory) {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attr) {
                element.bind('change', function () {
                    var formData = new FormData();
                    formData.append('file', element[0].files[0]);
                    httpPostFactory('importar_prestamo.php', formData, function (callback) {
                         $('#modal-import-prestamos').closeModal();
                        scope.leePrestamos();
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
