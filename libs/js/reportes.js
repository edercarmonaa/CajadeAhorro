app.controller('reportesCtrl', function($scope, $http, $filter, store, $state, jwtHelper, jwtHelper) {
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
    $scope.fecha_con = new Date();
    $scope.fecha_rep_sem = new Date();
    $scope.fecha_actual = new Date();


// LEE TODOS LOS EJERCICIOS
$scope.leeEjercicioActivoYear = function(){
    $http.get("obtiene_ejercicio_activo.php").success(function(response){
        $scope.ejercicios = response.records;
    })
}

$scope.resumenPrestamosSemana = function(semana){
    semana = $filter('date')(semana, 'ww');
    $http.post("obtiene_resumen_concentrado.php",{
         'semana' : semana
    }).success(function(response){
        $scope.resumen_concentrado = response.records;
    })
}


$scope.resumenMovimientosSemana = function(semana){
    semana = $filter('date')(semana, 'ww');
    $http.post("obtiene_resumen_rep_sem.php",{
         'semana' : semana
    }).success(function(response){
        $scope.resumen_rep_sem = response.records;
    })
}


$scope.leeEjercicioActivo = function(){
    $http.get("ejercicio_activo.php").success(function(response){
        $scope.ejercicio_activo = response.records;
    })
}


});
