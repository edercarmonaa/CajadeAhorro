<?php 
require_once __DIR__ . '/env.php';

class Database{ 
 
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    public $conn; 

    public function __construct()
    {
        $this->host = envValue('DB_HOST', 'localhost');
        $this->db_name = envValue('DB_NAME', 'ahorro');
        $this->username = envValue('DB_USERNAME', '');
        $this->password = envValue('DB_PASSWORD', '');
        $this->charset = envValue('DB_CHARSET', 'utf8');
    }
 
    // get the database connection 
    public function Coneccion(){ $this->conn = null;
         
        try{
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            error_log("Database connection error: " . $exception->getMessage());
            http_response_code(500);
            echo "Error de conexion a la base de datos.";
        }
         
        return $this->conn;
    }
}
?>
