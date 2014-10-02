<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
abstract class TExpression{

	const AND_OPERATOR 	= 'AND ';
	const OR_OPERATOR	= 'OR ';

	abstract public function dump();
}
?>