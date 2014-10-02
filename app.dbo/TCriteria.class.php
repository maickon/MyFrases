<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
class TCriteria extends TExpression{
	private $expressions;
	private $operators;
	private $properties;

	public function add(TExpression $expression, $operator = self::AND_OPERATOR){
		if(empty($this->expressions)):
			unset($operator);
			$operator = '';
		endif;
		$this->expressions[] = $expression;
		$this->operators[]	 = $operator;
	}

	public function dump(){
		$result = '';
		if(is_array($this->expressions)):
			foreach($this->expressions as $key => $expression):
				$operator = $this->operators[$key];
				$result .= $operator.$expression->dump().' ';
			endforeach;
			$result = trim($result);
			return "({$result})";
		endif;
	}

	public function setProperty($property, $value){
		$this->properties[$property] = $value;
	}

	public function getProperty($property){
		if(isset($this->properties[$property])):
			return $this->properties[$property];
		else:
			return 0;
		endif;
	}
}
?>