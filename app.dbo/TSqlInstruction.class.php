<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
abstract class TSqlInstruction{
	private $sql;
	private $criteria;

	final public function setEntity($entity){
		$this->entity = $entity;
	}

	final public function getEntity(){
		return $this->entity;
	}

	public function setCriteria(TCriteria $criteria){
		$this->criteria = $criteria;
	}

	public function getCriteria(){
		return $this->criteria;
	}

	abstract function getInstruction();
}
?>