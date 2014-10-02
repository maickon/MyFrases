<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/
final class TSqlUpdate extends TSqlInstruction{

	public function setRowData($column, $value){
		if(is_string($value)):
			$value = addslashes($value);
			$this->columnValues[$column] = "'$value'";
		elseif(is_bool($value)):
			$this->columnValues[$column] = $value?'TRUE':'FALSE';
		elseif(isset($value)):
			$this->columnValues[$column] = $value;
		else:
			$this->columnValues[$column] = "NULL";
		endif;
	}

	public function getInstruction(){
		$this->sql = "UPDATE {$this->entity}";
		if($this->columnValues):
			foreach($this->columnValues as $column => $value):
				$set[] = "{$column} = {$value}";
			endforeach;
		endif;
		$this->sql .= ' SET '.implode(',', $set);
		if($this->getCriteria()):
			$this->sql .= ' WHERE '.$this->getCriteria()->dump();
		endif;

		return $this->sql;
	}
} 	
?>