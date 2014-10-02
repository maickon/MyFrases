<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

class TLoggerHTML extends TLogger{

	public function write($message){
		date_default_timezone_set('America/Sao_Paulo');
		$time = date("Y-m-d H:i:s");

		$text  = "<p>\n";
		$text .= "<b>$time</b>\n";
		$text .= "<i>$message</i>\n";
		$text .= "</p>\n";

		$handler = fopen($this->filename, 'a');
		fwrite($handler, $text);
		fclose($handler);
	}
}
?>