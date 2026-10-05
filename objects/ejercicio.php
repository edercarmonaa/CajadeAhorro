<?php
class Ejercicio{

  private $conn;
  private $table_name = "ejercicio";

  public $id_ejercicio;
  public $fecha_ini;
  public $fecha_fin;
  public $nom_ejercicio;
	public $saldo_ini;
	public $interes;
	public $estatus;
	public $semana_ini;

  public function __construct($db){
      $this->conn = $db;
    }
function leeEjercicios(){
    $query = "SELECT * FROM " . $this->table_name;
     $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function EjercicioActivoCampos(){
   $query = "SELECT * FROM " . $this->table_name  . " WHERE activo  = 1";
   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;

}

function leeEjerciciosConfig(){
   $query = "SELECT id_ejercicio, nom_ejercicio, fecha_inicial, fecha_final, saldo_inicial, intereses,
   case when activo = 1 then 'Activo' else 'Desactivado' end as estatus FROM " . $this->table_name;
   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function calculaEjercicio($fecha){
    $query = "SELECT id_ejercicio FROM " . $this->table_name ."
                WHERE ?  between  fecha_inicial and fecha_final";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $fecha);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id_ejercicio'];
}

function EjercicioActivo(){
    $query = "SELECT nom_ejercicio FROM " . $this->table_name ."
                WHERE activo  = 1";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['nom_ejercicio'];
}

function EjercicioActivoYears(){
    $query = "SELECT nom_ejercicio, year(fecha_inicial) as year_ini, year(fecha_final) as year_fin FROM " . $this->table_name ."
                WHERE activo  = 1";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt;
}
function creaEjercicio(){
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                nom_ejercicio=:nom_ejercicio, fecha_inicial=:fecha_ini, fecha_final=:fecha_fin, saldo_inicial=:saldo_ini, activo=:estatus,
                semana_ini=:semana_ini, intereses=0";
    $stmt = $this->conn->prepare($query);

    $this->nom_ejercicio=htmlspecialchars(strip_tags($this->nom_ejercicio));
    $this->fecha_ini=htmlspecialchars(strip_tags($this->fecha_ini));
    $this->fecha_fin=htmlspecialchars(strip_tags($this->fecha_fin));
    $this->saldo_ini = htmlspecialchars(strip_tags($this->saldo_ini));
	$this->estatus=htmlspecialchars(strip_tags($this->estatus));
	$this->semana_ini=htmlspecialchars(strip_tags($this->semana_ini));

    $stmt->bindParam(":nom_ejercicio", $this->nom_ejercicio);
    $stmt->bindParam(":fecha_ini", $this->fecha_ini);
    $stmt->bindParam(":fecha_fin", $this->fecha_fin);
    $stmt->bindParam(":saldo_ini", $this->saldo_ini);
	$stmt->bindParam(":semana_ini", $this->semana_ini);
	$stmt->bindParam(":estatus", $this->estatus);

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}
function leeEjercicio(){
   $query = "SELECT * FROM " . $this->table_name  . " WHERE id_ejercicio=:id_ejercicio";
   $stmt = $this->conn->prepare( $query );
   $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
   $stmt->bindParam(":id_ejercicio", $this->id_ejercicio);

   $stmt->execute();
   return $stmt;

}
function actualizaEjercicio(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                nom_ejercicio=:nom_ejercicio, fecha_inicial=:fecha_ini, fecha_final=:fecha_fin, saldo_inicial=:saldo_ini, activo=:estatus,
                semana_ini=:semana_ini WHERE id_ejercicio=:id_ejercicio";
    $stmt = $this->conn->prepare($query);

    $this->nom_ejercicio=htmlspecialchars(strip_tags($this->nom_ejercicio));
	$this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $this->fecha_ini=htmlspecialchars(strip_tags($this->fecha_ini));
    $this->fecha_fin=htmlspecialchars(strip_tags($this->fecha_fin));
    $this->saldo_ini = htmlspecialchars(strip_tags($this->saldo_ini));
	$this->estatus=htmlspecialchars(strip_tags($this->estatus));
	$this->semana_ini=htmlspecialchars(strip_tags($this->semana_ini));

    $stmt->bindParam(":nom_ejercicio", $this->nom_ejercicio);
	$stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
    $stmt->bindParam(":fecha_ini", $this->fecha_ini);
    $stmt->bindParam(":fecha_fin", $this->fecha_fin);
    $stmt->bindParam(":saldo_ini", $this->saldo_ini);
    $stmt->bindParam(":semana_ini", $this->semana_ini);

	$stmt->bindParam(":estatus", $this->estatus);

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
