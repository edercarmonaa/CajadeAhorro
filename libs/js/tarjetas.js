app.controller('tarjetasCtrl', function($scope, $http, store, $state, jwtHelper) {
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

    $scope.leeEmpleadosTarjetas = function(){
       $http.get("obtiene_tarjetas.php").success(function(response){
           $scope.names = response.records;
       });
    }





//DESCARGA TARJETAS

$scope.descargaTarjetas = function(){
	for ( i=0; i < $scope.array.length; i++) {
		var id_empleado = $scope.array[i];
		$http({
                url: 'xls_tarjeta.php?id_empleado='+$scope.array[i],
                method: 'POST',
                responseType: 'arraybuffer',
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            }).success(function(data, status, header){
            	var id = header('Content-Disposition').split("\"");
                var blob = new Blob([data], {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"});
                saveAs(blob, id[1]);
            }).error(function(){
                Materialize.toast("Error al descargar los Archivos",400);
            });
	};
	$scope.selectedAll = false;
}

//IMPRIME TARJETAS EN PDF
$scope.imprimeTarjetas = function(){
  $( "#preloader" ).toggle();
  $http({
          url: 'pdf_tarjeta.php',
          method: 'POST',
          responseType: 'arraybuffer',
          headers: {
              'Content-type': 'application/json',
              'Accept': 'application/pdf'
          }
      }).success(function(data, status, header){
        $( "#preloader" ).toggle();
      	var id = header('Content-Disposition').split("\"");
          var blob = new Blob([data], {type: "application/pdf"});
          saveAs(blob, id[1]);
      }).error(function(){
        $( "#preloader" ).toggle();
          Materialize.toast("Error al descargar los Archivos",400);
      });
}

//CONFIGURACION DE LA PAGINACION
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
