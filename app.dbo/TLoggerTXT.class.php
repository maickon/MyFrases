<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

class TLoggerTXT extends TLogger{

	public function write($message){
		date_default_timezone_set('America/Sao_Paulo');
		$time = date("Y-m-d H:i:s");

		$text  = "$time :: $message:\r\n";

		$handler = fopen($this->filename, 'a');
		fwrite($handler, $text);
		fclose($handler);
	}
}
?>