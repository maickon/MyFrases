<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
class TFilter extends TExpression{
	private $variable;
	private $operator;
	private $value;

	public function __construct($variable, $operator, $value){
		$this->variable = $variable;
		$this->operator = $operator;
		$this->value 	= $this->transform($value); 
	}

	private function transform($value){
		if(is_array($value)):
			foreach ($value as $key):
				if(is_integer($key)):
					$iten_value[$key] = $key;
				elseif(is_string($key)):
					$iten_value[$key] = "'$key'";
				endif;
			endforeach;
			$result = '('.implode(',', $iten_value).')';
		elseif(is_string($value)):
			$result = "'$value'";
		elseif(is_null($value)):
			$result = 'NULL';
		elseif(is_bool($value)):
			$result = $value?'TRUE':'FALSE';
		else:
			$result = $value;
		endif;

		return $result;
	}

	public function dump(){
		return "{$this->variable} {$this->operator} {$this->value}";
	}
}
?>