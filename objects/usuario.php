<?php 
class Usuario{ 
     
    private $conn; 
    private $table_name = "usuarios"; 
 
    public $id_empleado; 
    public $password; 
    public $nivel;
    public $nom_usr;
 
    public function __construct($db){ 
        $this->conn = $db;
    }

function leeUsuario(){
    $query = "SELECT id_empleado, id_nivel as nivel, nom_usr as nombre, password   
            FROM " . $this->table_name . "
           WHERE id_empleado=:id_empleado ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($this->password,  $row['password'])) {
        $this->id_empleado = $row['id_empleado'];
        $this->nivel = $row['nivel'];
        $this->nombre=$row['nombre'];
    } else {
        echo 'Usuario O contraseña invalido';
    }

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
    
}

function leeUsuarios(){
    $query = "SELECT id_empleado, nivel, nom_usr   
			FROM " . $this->table_name. 
			" as u left join niveles as n on u.id_nivel = n.id_nivel";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt; 
}

function creaUsuario(){
    $query = "INSERT INTO 
                " . $this->table_name . "
            SET 
                id_empleado=:id_empleado, nom_usr=:nom_usr, id_nivel=:id_nivel, password=:password";
    $stmt = $this->conn->prepare($query);
    
    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->nom_usr=htmlspecialchars(strip_tags($this->nom_usr));
    $this->nivel=htmlspecialchars(strip_tags($this->nivel));
    

    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":nom_usr", $this->nom_usr);
    $stmt->bindParam(":id_nivel", $this->nivel);
    $stmt->bindParam(":password", $this->password);
    
    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function BorraUsuario(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id_empleado = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id_empleado);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function leeUsuarioEdit(){
    $query = "SELECT id_empleado, id_nivel as nivel, nom_usr as nombre, password   
            FROM " . $this->table_name . "
           WHERE id_empleado=:id_empleado ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id_empleado = $row['id_empleado'];
    $this->nivel = $row['nivel'];
    $this->nombre=$row['nombre'];

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
    
}


function actualizaUsuario(){
    $query = "UPDATE 
                " . $this->table_name . "
            SET 
                nom_usr = :nom_usr, 
                id_nivel = :id_nivel, 
                password = :password
            WHERE
                id_empleado = :id_empleado";
    $stmt = $this->conn->prepare($query);
   	$this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->nom_usr=htmlspecialchars(strip_tags($this->nom_usr));
    $this->nivel=htmlspecialchars(strip_tags($this->nivel));
	
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":nom_usr", $this->nom_usr);
    $stmt->bindParam(":id_nivel", $this->nivel);
    $stmt->bindParam(":password", $this->password);
    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
	}
}



}
?>