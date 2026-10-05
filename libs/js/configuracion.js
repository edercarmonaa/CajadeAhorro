app.controller('configCtrl', function($scope, $http, $filter, store, $state, jwtHelper) {
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
    id_ejercicio="";
    $scope.array_ = angular.copy($scope.array);
    $scope.fecha_con = new Date();
    $scope.sem_ejer = new Date();
    $scope.fecha_actual = new Date();
    $scope.FromDate = $scope.fecha_actual.getFullYear() + '-' + ('0' + ($scope.fecha_actual.getMonth() + 1)).slice(-2) + '-' + ('0' + $scope.fecha_actual.getDate()).slice(-2);


 $scope.muestraFormulario = function(){
 	$http.get("obtiene_ejercicio_activo.php").success(function(response){
       if (response.records.length == 0){
	 	   $scope.limpiaFormulario();
	 	    $('#btn-update-ejercicio').hide();
	 	     $('#btn-create-ejercicio').show();
	    	$('#modal-ejerciio-title').text("Crear ejercicio");
	    	$('#btn-corte-sem').show();
	    	$('#modal-ejercicio-form').openModal();
    	}else{
			Materialize.toast('Existe un Ejercicio activo, debe Realizar el cierre de este, para crear uno nuevo', 4000);
		}
    })
 }
 $scope.muestraFormularioCorteSem = function(no){
        $scope.limpiaFormulario();
        $("#fecha_corte_sem").attr("disabled", "true");
        $scope.fecha_corte_sem=parseInt(no);
        $('#modal-ejerciio-title').text("Corte Semanal");
        $('#btn-create-ejercicio').show();
        $('#modal-corte-sem-form').openModal();
 }

// LIMPIA EL FORMULARIO DE EMPLEADOS
    $scope.limpiaFormulario = function(){
        $scope.nom_ejercicio = "";
        $scope.saldo_ini = "";
        $('select').prop('selectedIndex', 0); //Sets the first option as selected
        $('select').material_select();
        $('#fecha_ini').val($scope.FromDate);
        $('#fecha_fin').val($scope.FromDate);
        $('#fecha_ini_sem').val($scope.FromDate);
        $('#fecha_fin_sem').val($scope.FromDate);
   }

   // AGREGA AHORRO
    $scope.creaEjercicio = function(){
		$http.post('crea_ejercicio.php', {
		'nom_ejercicio' : $scope.nom_ejercicio,
		'fecha_ini' :  $('#fecha_ini').val(),
		'fecha_fin' :  $('#fecha_fin').val(),
		'estatus' :  $('#estatus').val(),
		'semana_ini' : $('#sem_ejer').val().substring(6),
		'saldo_ini' : $scope.saldo_ini
		})
		.success(function(data, status, headers, config){
		    Materialize.toast(data, 4000);
		    $scope.leeEjercicios();
		    $scope.leeSemanas();
			$('#modal-ejercicio-form').closeModal();
			$scope.limpiaFormulario();
		})
		.error(function(data, status, headers, config){
		    Materialize.toast('Imposible Crear el ejercicio.', 4000);
		});
    }
    $scope.corteSemanal = function(){
	    $http.post("corte_semanal.php",{
	         'semana' : $scope.fecha_corte_sem,
	         'fecha_ini' : $('#fecha_ini_sem').val(),
	         'fecha_fin' : $('#fecha_fin_sem').val(),
	    }).success(function(data, status, headers, config){
	        Materialize.toast(data, 4000);
	        $('#modal-corte-sem-form').closeModal();
        	$scope.limpiaFormulario();
        	$scope.leeSemanas();
	    })
	}

	// LEE TODOS LOS EMPLEADOS

    $scope.leeEjercicios = function(){
       $http.get("obtiene_ejercicios_config.php").success(function(response){
           $scope.names = response.records;
       });
    }

     $scope.MuestraEjercicio = function(id){
    	$('#modal-product-title').text('Modificar Ejercicio')
        $('#btn-create-ejercicio').hide();
        $('#btn-update-ejercicio').show();
        $http.post('obtiene_un_ejercicio.php', {
            'id_ejercicio' : id
        })
        .success(function(data, status, headers, config){
            $scope.nom_ejercicio = parseInt(data["nom_ejercicio"]);
            $('#fecha_ini').val(data["fecha_inicial"]);
            $('#fecha_fin').val(data["fecha_final"]);
            $('#sem_ejer').val(data["fecha_inicial"].substring(0, 4)+'-W'+data['semana_ini']);
            //$scope.sem_ejer=data["fecha_inicial"].substring(0, 4)+'-W'+data['semana_ini'];
            $('select').prop('selectedIndex',  parseInt(data["estatus"]));
            $('select').material_select();
            $scope.saldo_ini =  parseFloat(data["saldo_inicial"]);
			id_ejercicio=data["id_ejercicio"];

            $('#modal-ejercicio-form').openModal();
        })
        .error(function(data, status, headers, config){
            Materialize.toast('Imposible Obtener Registro.', 4000);
        });
    }

    // Actualiza ejercicio
    $scope.actualizaEjercicio = function(){
		$http.post('actualiza_ejercicio.php', {
		'id_ejercicio': id_ejercicio,
		'nom_ejercicio' : $scope.nom_ejercicio,
		'fecha_ini' :  $('#fecha_ini').val(),
		'fecha_fin' :  $('#fecha_fin').val(),
		'estatus' :  $('#estatus').val(),
		'saldo_ini' : $scope.saldo_ini,
		'semana_ini' : $('#sem_ejer').val().substring(6)
		})
		.success(function(data, status, headers, config){
		    Materialize.toast(data, 4000);
		    $scope.leeEjercicios();
		    $scope.leeSemanas();
			$('#modal-ejercicio-form').closeModal();
			$scope.limpiaFormulario();
		})
		.error(function(data, status, headers, config){
		    Materialize.toast('Imposible Actualizar el ejercicio.', 4000);
		});
    }
    // LEE TODOS LOS EMPLEADOS

    $scope.leeSemanas = function(){
       $http.get("obtiene_semanas.php").success(function(response){
           $scope.names2 = response.records;
       });
    }
});
