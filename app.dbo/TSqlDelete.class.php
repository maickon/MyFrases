<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

final class TSqlDelete extends TSqlInstruction{

	public function getInstruction(){
		$this->sql = " DELETE FROM {$this->entity}";
		if($this->getCriteria()):
			$expression = $this->getCriteria()->dump();
			if($expression):
				$this->sql .= ' WHERE '.$expression;
			endif;
		endif;

		return $this->sql;
	}
}
?>