app.controller('loginCtrl', function($scope, $http, $state, $log, authFactory, jwtHelper, store) { 
    $scope.rsJSON = [ ];
    // Ocultamos los divs de Alertas
    $scope.alertaLoginCorrecto = true;
    $scope.alertaLoginError    = true;
    // obtenemos el evento submit del formulario ng-submit="entrar()"
    $scope.entrar = function() {
    consultarUsuario($http,$scope);
    };
    // obtenemos el evento click del boton limpiar ng-click="limpiar()"
    $scope.limpiar = function() {
    limpiarForm($scope);
    };

    $scope.limpiarForm = function(){
        $scope.alertaLoginError    = true;   
        $scope.alertaLoginCorrecto = true;   
        $scope.nom_usr    = '';
        $scope.pass_usr = '';   
    }
 
    $scope.consultarUsuario = function(){
    	authFactory.login($scope.nom_usr, $scope.pass_usr).then(function(res)
        {
            if(res.data && res.data.code == 0)
            {
                store.set('token', res.data.response.token);
                $('#modal-login').closeModal();
                $state.go('reportes');
            }else{
            	$scope.nom_usr    = '';
                $scope.pass_usr = '';  
                 Materialize.toast('Usuario y/o Contraseña Invalidos',4000);
            }
        });
    }
    $scope.muestraformualrio=function(){

    	$('#modal-login').openModal({
        	dismissible: false
    	});
    }
}); 

app.factory("authFactory", function($http, $q){
	return {
		login: function($user, $password)
		{
			var deferred;
            deferred = $q.defer();
            $http({
                method: 'POST',
                skipAuthorization: true,//no queremos enviar el token en esta petición
                url: 'consulta_usuario.php',
                data: "id_empleado=" + $user + "&password=" + $password,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .then(function(res)
            {
                deferred.resolve(res);
            })
            .then(function(error)
            {
                deferred.reject(error);
            })
            return deferred.promise;
		}
	}
});

