<?php 
class Operacion{ 
     
    private $conn; 
    private $table_name = "tipo_operacion"; 
 
    public $id_tipo; 
    public $descripcion; 

    public function __construct($db){ 
        $this->conn = $db;
    }



function calculaOperacion($id_tipo){
    $query = "SELECT id_tipo FROM " . $this->table_name ."
                WHERE descripcion= ?  ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $id_tipo);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id_tipo'];
}





}
?>