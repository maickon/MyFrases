<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

final class TConnection{
	private function __construct(){}

	public static function open($name){
		if(file_exists("app.config/$name.ini")):
			$db = parse_ini_file("app.config/$name.ini");
		else:
			throw new Exception("Arquivo $name nao encontrado.");		
		endif;

		$user = $db['user'];
		$pass = $db['pass'];
		$name = $db['name'];
		$host = $db['host'];
		$type = $db['type'];

		switch($type):
			case 'pgsql':
				$conn = new PDO("pgsql:dbname={$name};user={$user};password={$pass};host=$host");
			break;

			case 'mysql':
				$conn = new PDO("mysql:host={$host};port=3306;dbname={$name}",$user,$pass);
			break;

			case 'sqlite':
				$conn = new PDO("sqlite:{$name}");
			break;

			case 'ibase':
				$conn = new PDO("firebird:dbname={$name}",$user,$pass);
			break;

			case 'oci8':
				$conn = new PDO("oci:dbname={$name}",$user,$pass);
			break;

			case 'mssql':
				$conn = new PDO("mssql:host={$host},1433;dbname={$name}",$user,$pass);
			break;
		endswitch;

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
} 
?>