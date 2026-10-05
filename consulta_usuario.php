<?php 
include_once 'config/database.php'; 
include_once 'objects/usuario.php'; 
include_once 'libs/php-jwt/JWT.php'; 
use \Firebase\JWT\JWT;

$database = new Database(); 
$db = $database->Coneccion();
$usuario = new Usuario($db);     
$usuario->id_empleado = $_POST['id_empleado'];
$usuario->password = $_POST['password'];
$usuario->leeUsuario();
$usuario_arr = array(
	"iat" => time(),
    "exp" => time() + 2070,
    "id_empleado" =>  $usuario->id_empleado,
    "nivel" => $usuario->nivel,
    "nombre" => $usuario->nombre
);
$key = envValue('JWT_SECRET');
if (!$key) {
    error_log('JWT_SECRET is not configured.');
    http_response_code(500);
    echo json_encode(
        array(
            "code" => 1,
            "response" => "Configuracion de autenticacion incompleta."
        )
    );
    exit;
}

$jwt = JWT::encode($usuario_arr, $key);
echo json_encode(
    array(
        "code" => 0,
        "response" => array(
            "token" => $jwt
        )
    )
);
//print_r(json_encode($usuario_arr));
?>
