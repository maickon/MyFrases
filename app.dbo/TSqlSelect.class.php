<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
final class TSqlSelect extends TSqlInstruction{
	private $columns;

	public function addColumn($column){
		$this->columns[] = $column;
	}

	public function getInstruction(){
		$this->sql = 'SELECT ';
		$this->sql .= implode(',', $this->columns);	
		$this->sql .= ' FROM '.$this->getEntity();

		if($this->getCriteria()):
			$expression = $this->getCriteria()->dump();
			if($expression):
				$this->sql .= ' WHERE '.$expression;
			endif;

			$order = $this->getCriteria()->getProperty('order');
			$limit = $this->getCriteria()->getProperty('limit');
			$offset = $this->getCriteria()->getProperty('offset');

			if($order):
				$this->sql .= ' ORDER BY '.$order;
			endif;

			if($limit):
				$this->sql .= ' LIMIT '.$limit;
			endif;	

			if($offset):
				$this->sql .= ' OFFSET '.$offset;
			endif;
		endif;

		return $this->sql;
	}
}
?>