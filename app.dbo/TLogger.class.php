<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

abstract class TLogger{
	protected $filename;

	public function __construct($filename){
		$this->filename = $filename;
		file_put_contents($filename, '');
	}

	abstract function write($message);
}
?>