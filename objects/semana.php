<?php
class Semana{

  private $conn;
  private $table_name = "semana";
  public $id_semana;
  public $no_semana;
  public $id_ejercicio;
  public $saldo_inicial;
	public $saldo_final;
	public $fecha_ini;
	public $fecha_fin;
	public $inicial;

    public function __construct($db){
        $this->conn = $db;
    }

function creaSemana(){
  $query= "DELETE FROM " . $this->table_name . " WHERE id_ejercicio=:id_ejercicio AND no_semana=:no_semana";
  $stmt = $this->conn->prepare($query);
  $this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
  $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
  $stmt->bindParam(":no_semana", $this->no_semana);
  $stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
  $stmt->execute();

  $query = "INSERT INTO
                " . $this->table_name . "
            SET
                no_semana=:no_semana, saldo_inicial=:saldo_inicial, id_ejercicio=:id_ejercicio, inicial=:inicial, saldo_final=0";
    $stmt = $this->conn->prepare($query);
    $this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
	$this->saldo_inicial=htmlspecialchars(strip_tags($this->saldo_inicial));
	$this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
	$this->id_inicial=htmlspecialchars(strip_tags($this->inicial));
    $stmt->bindParam(":no_semana", $this->no_semana);
	$stmt->bindParam(":saldo_inicial", $this->saldo_inicial);
	$stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
	$stmt->bindParam(":inicial", $this->inicial);

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}
function corteSemanal(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                saldo_final=:saldo_final, fecha_ini=:fecha_ini, fecha_fin=:fecha_fin
            WHERE
            no_semana=:no_semana AND id_ejercicio=:id_ejercicio";
    $stmt = $this->conn->prepare($query);
	echo  $this->id_ejercicio."--".$this->no_semana;
    $this->saldo_final=htmlspecialchars(strip_tags($this->saldo_final));
	$this->fecha_ini=htmlspecialchars(strip_tags($this->fecha_ini));
	$this->fecha_fin=htmlspecialchars(strip_tags($this->fecha_fin));
	$this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
	$this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $stmt->bindParam(":saldo_final", $this->saldo_final);
	$stmt->bindParam(":fecha_ini", $this->fecha_ini);
	$stmt->bindParam(":fecha_fin", $this->fecha_fin);
    $stmt->bindParam(":no_semana", $this->no_semana);
	$stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}
function LeeSemana(){
    $query = "SELECT id_semana, no_semana, s.saldo_inicial as saldo_inicial, s.saldo_final as saldo_final,
			fecha_ini, fecha_fin, s.id_ejercicio as id_jercicio
 			FROM " . $this->table_name . " as s
			left join (select * from ejercicio where activo = 1)  as e on s.id_ejercicio=e.id_ejercicio
			WHERE no_semana=:no_semana ";
    $stmt = $this->conn->prepare($query);
    $this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
    $stmt->bindParam(":no_semana", $this->no_semana);
	$stmt->execute();
    return $stmt;

}

function nextSemana(){
    $query = "SELECT s.id_ejercicio, saldo_final as saldo_inicial,
    		week( ADDDATE(fecha_fin, INTERVAL 2 DAY)) as no_semana
 			FROM " . $this->table_name . " as s
			left join (select * from ejercicio where activo = 1)  as e on s.id_ejercicio=e.id_ejercicio
			WHERE no_semana=:no_semana ";
    $stmt = $this->conn->prepare($query);
    $this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
    $stmt->bindParam(":no_semana", $this->no_semana);
	$stmt->execute();
    return $stmt;
}

function LeeSemanas(){
    $query = "SELECT id_semana, no_semana, s.saldo_inicial as saldo_inicial, s.saldo_final as saldo_final,
			fecha_ini, fecha_fin, s.id_ejercicio as id_jercicio
 			FROM " . $this->table_name . " as s
			left join (select * from ejercicio where activo = 1)  as e on s.id_ejercicio=e.id_ejercicio";
    $stmt = $this->conn->prepare($query);
	$stmt->execute();
    return $stmt;

}

function actualizaSemanaSaldo(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                saldo_inicial=:saldo_inicial,
                no_semana=:no_semana
                WHERE  inicial=1 AND id_ejercicio=:id_ejercicio";
    $stmt = $this->conn->prepare($query);
    $this->no_semana=htmlspecialchars(strip_tags($this->no_semana));
	$this->saldo_inicial=htmlspecialchars(strip_tags($this->saldo_inicial));
	$this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $stmt->bindParam(":no_semana", $this->no_semana);
	$stmt->bindParam(":saldo_inicial", $this->saldo_inicial);
	$stmt->bindParam(":id_ejercicio", $this->id_ejercicio);

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
