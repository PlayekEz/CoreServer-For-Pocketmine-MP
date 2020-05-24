<?php

namespace Playek\Core\utils;

class DataBase {
	
	private $connection = null;
	private $db;
	public function __construct(){
		/*
		$this->db = $db;
		$this->init();
		*/
	}
	
	public function init(){
		 // Host, User, Password, Db
		$connection = mysqli_connect("fdb26.awardspace.net", "3244098_playek", "Gokudios12", "3244098_playek");
		if(!$connection){
			echo "Error en la conexion a base de datos: MySQL";
		}else{
			$this->connection = $connection;
		}
	}
	
	//En caso de ser rangos
	
	public function setRank(Player $player, string $rank){
		$db = $this->connection;
		if(!$db) return;
		$name = $player->getName();
		$anio = date("y");
		$mes = date("m");
		$dia = date("d");
		$date = $anio."-".$mes."-".$dia;
		$sql = "INSERT INTO ranks (tag, rank, date) VALUES ($name, $rank, $date)";
		if(mysqli_query($db, $sql)){
			$player->sendMessage("§7§oRank agregado en la base de datos");
		}
	}
	
	public function getRankData($name){
		$db = $this->connection;
		if(!$db) return;
		mysql_select_db("ranks", $db) or die("Error al obtener la tabla de datos");
		$result = mysql_query("SELECT * FROM ranks");
		$rank = "NULL";
		$name = strtlower($name);
		$data = ["tag" => $name, "rank" => "NULL", "date" => "0-0-0"];
		while ($row =mysql_fetch_row($result)) {
			if($row["tag"] == $name){
				$data = ["tag" => $row["tag"], "rank" => $row["rank"], "date" => $row["date"]];
			}
		}
		return $data;
	}
	
	public function close(){
		mysql_close($this->connection);
	}
}