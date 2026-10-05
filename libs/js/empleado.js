app.controller('empleadosCtrl', function($scope, $http, store, $state, jwtHelper) {
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

// MUESTRA EL MODAL DE EMPLEADOS
    $scope.muestraFormulario = function(){
        $scope.limpiaFormulario();
        $('#modal-product-title').text("Agregar Empleado");
        $('#btn-update-product').hide();
        $('#btn-create-product').show();
    }

// MUESTRA EL MODAL DE EMPLEADOS
    $scope.muestraImportar = function(){
        $scope.ruta_archivo="";
    }

// LIMPIA EL FORMULARIO DE EMPLEADOS
    $scope.limpiaFormulario = function(){
        $("#form-id").removeAttr( "disabled" )
        $scope.id_empleado = "";
        $scope.nombre = "";
        $scope.accion = "";
        $scope.saldo = "";
        $('select').prop('selectedIndex', 0); //Sets the first option as selected
        $('select').material_select();
    }

// CREA UN EMPLEADO
    $scope.creaEmpleado = function(){
        $http.post('crear_empleado.php', {
            'id_empleado' : $scope.id_empleado,
            'nombre' : $scope.nombre,
            'categoria' :  $('#categoria').val(),
            'accion' : $scope.accion,
            'saldo' : $scope.saldo
        }
    ).success(function (data, status, headers, config) {
        console.log(data);
        Materialize.toast(data, 4000);
        $('#modal-product-form').closeModal();
        $scope.limpiaFormulario();
        $scope.leeEmpleados();
    });
    }

// LEE TODOS LOS EMPLEADOS

    $scope.leeEmpleados = function(){
       $http.get("obtiene_empleados.php").success(function(response){
           $scope.names = response.records;
       });
    }
// LEE UN EMPLEADO PARA EDICION
    $scope.leeEmpleado = function(id){
    $('#modal-product-title').text("Modificar Empleado");
        $('#btn-update-product').show();
        $('#btn-create-product').hide();
        $http.post('obtiene_un_empleado.php', {
            'id_empleado' : id
        })
        .success(function(data, status, headers, config){
            $scope.id_empleado = parseInt(data[0]["id_empleado"]);
            $scope.nombre = data[0]["nombre"];
            $scope.categoria = data[0]["categoria"];
            $scope.accion = parseInt(data[0]["accion"]);
            $scope.saldo = parseInt(data[0]["saldo"]);
            $('select').prop('selectedIndex',  parseInt(data[0]["categoria"]));
            $('select').material_select();
            $("#form-id").attr("disabled", "true");
            $('#modal-product-form').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

//ACTUALIZA LOS DATOS PARA LOS EMPELADOS
$scope.actualizaEmpleado = function(){
    $http.post('actualiza_empleado.php', {
        'id_empleado' : $scope.id_empleado,
        'nombre' : $scope.nombre,
        'categoria' :$('#categoria').val(),
        'accion' : $scope.accion,
        'saldo' : $scope.saldo
    })
    .success(function (data, status, headers, config){
        Materialize.toast(data, 4000);
        $('#modal-product-form').closeModal();
        $scope.limpiaFormulario();
        $scope.leeEmpleados();
    });
}

// BORRA UN EMPLEADO
$scope.borraEmpleado = function(id_empleado){
    if(confirm("Esta seguro de eliminar el empleado?")){
        $http.post('borra_empleado.php', {
            'id_empleado' : id_empleado
        }).success(function (data, status, headers, config){
            Materialize.toast(data, 4000);
            $scope.leeEmpleados();
        });
    }
}

$scope.borraEmpleados = function(){
    if(confirm("Esta seguro de eliminar los Empleados Seleccionados?")){
        for ( i=0; i < $scope.array.length; i++) {
            $http.post('borra_empleado.php', {
                'id_empleado' : $scope.array[i]
            }).success(function (data, status, headers, config){
                $scope.leeEmpleados();
            });
        };
        $scope.selectedAll = false;
        Materialize.toast("Empleados Borrados con exito", 4000);
    }
}

//SELECCIONA TODOS LOS CHECKBOX
   $scope.checkAll = function () {
        if ($scope.selectedAll) {
            $scope.selectedAll = true;
        } else {
            $scope.selectedAll = false;
        }
        var r_ini = ($scope.currentPage * $scope.pageSize)-$scope.pageSize;
        var r_fin = ($scope.currentPage * $scope.pageSize) - 1;
        angular.forEach($scope.names, function (d, key) {
                if(key >= r_ini && key <= r_fin){
                     d.Selected = $scope.selectedAll;
                    if($scope.selectedAll == true){
                    $scope.array.push(d.id_empleado);
                }else{
                    $scope.array.length = 0;
                }
            }
        });

    };

});

//FILTRO DE LA PAGINACION
 app.filter('startFromGrid', function() {
        return function(input, start) {
            if (!input || !input.length) { return; }
            start = +start; //parse to int
            return input.slice(start);
        };
    });

//DIRECTIVA PARA EL CHECKBOX GROUP

app.directive("checkboxGroup", function() {
    return {
        restrict: "A",
        link: function($scope, elem, attrs) {
            // DETERMINA EL CHECK BOX INICIAL
            if ($scope.array.indexOf($scope.d.id_empleado) !== -1) {
                elem[0].checked = true;
            }

            // ACTUALIZA EL ARRAY EL DAR UN CLICK
            elem.bind('click', function() {
                var index = $scope.array.indexOf($scope.d.id_empleado);
                // AGREGA ELEMENTO
                if (elem[0].checked) {
                    if (index === -1) $scope.array.push($scope.d.id_empleado);
                }
                // REMOVE ELEMENTO
                else {
                    if (index !== -1) $scope.array.splice(index, 1);
                }
                //ORDENA EL ARRAY
                $scope.$apply($scope.array.sort(function(a, b) {
                    return a - b
                }));
            });
        }
    }
  });

//directiva de archivo
    app.directive('uploadFile', function (httpPostFactory) {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attr) {
                element.bind('change', function () {
                    var formData = new FormData();
                    formData.append('file', element[0].files[0]);
                    httpPostFactory('importar_empleado.php', formData, function (callback) {
                        $('#modal-import-empleados').closeModal();
                        scope.leeEmpleados();
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

//directiva de saldos
    app.directive('uploadSaldos', function (httpPostFactory) {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attr) {
                element.bind('change', function () {
                    var formData = new FormData();
                    formData.append('file', element[0].files[0]);
                    httpPostFactory('importar_saldos.php', formData, function (callback) {
                         $('#modal-import-saldos').closeModal();
                        scope.leeEmpleados();
                   $('#archivo').val('');
                        Materialize.toast(callback,4000);
                    });
                });

            }
        };
    });
