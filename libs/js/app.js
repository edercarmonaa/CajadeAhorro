var app = angular.module('ahorroApp', ['ui.router','angular-jwt','angular-storage','angularUtils.directives.dirPagination' ]);
app.config(function($stateProvider, $urlRouterProvider) {

$urlRouterProvider.otherwise("/login");
$stateProvider
    .state('login', {
        url: "/index",
        templateUrl: "views/login.php",
         controller: "loginCtrl"
    })
    .state('empleados', {
        url: "/empleados",
        templateUrl: "views/empleado.php",
        controller: "empleadosCtrl"
    })
    .state('ahorros', {
        url: "/ahorros",
        templateUrl: "views/ahorro.php",
        controller: "ahorrosCtrl"
    })
    .state('prestamos', {
        url: "/prestamos",
        templateUrl: "views/prestamo.php",
        controller: "prestamosCtrl"
    })
    .state('abonos', {
        url: "/abonos",
        templateUrl: "views/abono.php",
        controller: "abonosCtrl"
    })
    .state('tarjetas', {
        url: "/tarjetas",
        templateUrl: "views/tarjetas.php",
        controller: "tarjetasCtrl"
    })
    .state('reportes', {
        url: "/reportes",
        templateUrl: "views/reportes.php",
        controller: "reportesCtrl"
    })
    .state('ent_sal', {
        url: "/ent_sal",
        templateUrl: "views/ent_sal.php",
        controller: "ent_salCtrl"
    })
    .state('configuracion', {
        url: "/config",
        templateUrl: "views/configuracion.php",
        controller: "configCtrl"
    })
    .state('usuarios', {
        url: "/usuarios",
        templateUrl: "views/usuarios.php",
        controller: "userCtrl"
    })
    .state('abonos_efec', {
        url: "/abono_efec",
        templateUrl: "views/abono_efec.php",
        controller: "abonosefecCtrl"
    })
    .state('devoluciones', {
        url: "/devoluciones",
        templateUrl: "views/devoluciones.php",
        controller: "devolucionesCtrl"
    })
});
app.controller('menu', function($scope, $log, $state, store, jwtHelper) {
    $scope.setEstado = function(estado) {
        $state.go(estado);
    }

    $scope.logout = function() {
        store.remove('token');
        $state.go('login');
    }
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
});
