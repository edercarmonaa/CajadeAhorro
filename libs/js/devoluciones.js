app.controller('devolucionesCtrl', function($scope, $http, store, $state, jwtHelper) {
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



// LEE TODOS LOS EMPLEADOS

    $scope.leeEmpleados = function(){
       $http.get("obtiene_devoluciones.php").success(function(response){
           $scope.names = response.records;
       });
    }
//realiza la devolucion
$scope.Devolucion = function(id_empleado, accion,abonos, ahorros,prestamo){
  saldo=parseFloat(accion)+parseFloat(abonos)+parseFloat(ahorros)-parseFloat(prestamo);
  if(saldo > 0){
    if(confirm("Esta seguro de realizar la devolucion del empleado?")){
        $http.post('devolucion.php', {
            'id_empleado' : id_empleado
        }).success(function (data, status, headers, config){
            Materialize.toast(data, 4000);
            $scope.leeEmpleados();
        });
    }
  }else{
    Materialize.toast("Imposible realizar la Devolucion, el Empleado tiene un adeudo de "+saldo, 4000);
  }
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
