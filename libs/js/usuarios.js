app.controller('userCtrl', function($scope, $http, store, $state, jwtHelper) {
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

// LEE TODOS LOS USUARIOS

    $scope.leeUsuarios = function(){
       $http.get("obtiene_usuarios.php").success(function(response){
           $scope.names = response.records;
       });
    }


// MUESTRA EL MODAL DE Usuarios
    $scope.muestraFormulario = function(){
        $scope.limpiaFormulario();
        $('#modal-product-title').text("Agregar Usuario");
        $('#btn-update-product').hide();
        $('#btn-create-product').show();
    }


// CREA UN USUARIO
    $scope.creaUsuario = function(){
    	if ($scope.password == $scope.password2){
    		$http.post('crear_usuario.php', {
            'id_empleado' : $scope.id_empleado,
            'nom_usr' : $scope.nombre,
            'nivel' :  $('#nivel').val(),
            'password' : $scope.password
	        }
		    ).success(function (data, status, headers, config) {
		        console.log(data);
		        Materialize.toast(data, 4000);
		        $('#modal-product-form').closeModal();
		        $scope.limpiaFormulario();
		        $scope.leeUsuarios();
		    });
    	}else{
    		Materialize.toast("Las Contraseñas no coinciden", 4000);
    	}
    }

// BORRA UN EMPLEADO
$scope.borraUsuario = function(id_empleado){
    if(confirm("Esta seguro de eliminar el usuario?")){
        $http.post('borra_usuario.php', {
            'id_empleado' : id_empleado
        }).success(function (data, status, headers, config){
            Materialize.toast(data, 4000);
            $scope.leeUsuarios();
        });
    }
}

//BORRAR USUARIOS
$scope.borraUsuarios = function(){
    if(confirm("Esta seguro de eliminar los Usuarios Seleccionados?")){
        for ( i=0; i < $scope.array.length; i++) {
            $http.post('borra_usuario.php', {
                'id_empleado' : $scope.array[i]
            }).success(function (data, status, headers, config){
                $scope.leeUsuarios();
                $scope.selectedAll = false;
        		Materialize.toast("Usuarios Borrados con exito", 4000);
            });
        };
    }
}


// LEE UN EMPLEADO PARA EDICION
$scope.leeUsuario = function(id){
$('#modal-product-title').text("Modificar Usuario");
    $('#btn-update-product').show();
    $('#btn-create-product').hide();
    $http.post('obtiene_un_usuario.php', {
        'id_empleado' : id
    })
    .success(function(data, status, headers, config){
        $scope.id_empleado = parseInt(data[0]["id_empleado"]);
        $scope.nombre = data[0]["nom_usr"];
        $scope.nivel = data[0]["categoria"];
        $('select').prop('selectedIndex',  parseInt(data[0]["nivel"]));
        $('select').material_select();
        $("#form-id").attr("disabled", "true");
        $('#modal-product-form').openModal();
    })
    .error(function(data, status, headers, config){
        Materialize.toast('Imposible Obtener Registro.', 4000);
    });
}


// LIMPIA EL FORMULARIO DE EMPLEADOS
    $scope.limpiaFormulario = function(){
        $("#form-id").removeAttr( "disabled" )
        $scope.id_empleado = "";
        $scope.nombre = "";
        $scope.password = "";
        $scope.password2 = "";
        $('select').prop('selectedIndex', 0); //Sets the first option as selected
        $('select').material_select();
    }

//ACTUALIZA LOS DATOS PARA LOS EMPELADOS
$scope.actualizaUsuario = function(){
	if ($scope.password == $scope.password2){
	    $http.post('actualiza_usuario.php', {
	        'id_empleado' : $scope.id_empleado,
	        'nom_usr' : $scope.nombre,
	        'nivel' :  $('#nivel').val(),
	        'password' : $scope.password
	    })
	    .success(function (data, status, headers, config){
	        Materialize.toast(data, 4000);
	        $('#modal-product-form').closeModal();
	        $scope.limpiaFormulario();
	        $scope.leeUsuarios();
	    });
	   }else{
	   	Materialize.toast("Las Contraseñas no coinciden", 4000);
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
