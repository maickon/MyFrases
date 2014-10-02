<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel
*/

/*
	Classe TRepository
	Esta classe prove metodos necessarios para manipular colecoes de objetos
*/

final class TRepository{

	private $class; // nome da classe manipulada pelo repositorio

	/*
		Metodo __construct()
		instancia um repositorio de objetos
		@param $class = classe dos objetos
	*/
	function __construct($class){
		$this->class = $class;
	}

	/*
		Metodo load()
		Recupera um conjunto de objetos (collection) da base de dados 
		atraves de um criterio de selecao, e instancia-los em memoria
		@param $criteria = objeto do tipo TCriteria
	*/
	function load(TCriteria $criteria){
		//instancia a instrucao de SELECT
		$sql = new TSqlSelect();
		$sql->addColumn('*');
		$sql->setEntity($this->class);
		//atribui o criterio passado como parametro
		$sql->setCriteria($criteria);

		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//registra a mensagem de log
			TTransaction::log($sql->getInstruction());

			//executa a consulta  no banco de dados
			$result = $conn->Query($sql->getInstruction());
			if($result):
				//percorre os resultados da consulta, retornando um objeto
				while($row = $result->fetchObject($this->class.'Record')):
					//armazena no array results
					$results[] = $row;
				endwhile;
			endif;
			return $results;
		else:
			//se nao tiver uma transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}

	/*
		Metodo Delete()
		Exclui um conjjunto de objetos (collection) da base de dados
		atraves de um criterio de selecao
		@param $criteria = objeto do tipo TCriteria
	*/
	function delete(TCriteria $criteria){
		//instancia uma instrucao de delete
		$sql = new TSqlDelete();
		$sql->setEntity($this->class);
		//atribui o criterio passado como parametro
		$sql->setCriteria($criteria);

		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//registra a mensagem de log
			TTransaction::log($sql->getInstruction());
			//executa a instrucao de delete
			$result = $conn->exec($sql->getInstruction());
			return $result;
		else:
			//se nao tiver uma transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}

	/*
		Metodo Count()
		Retorna a quantidade de objetos na base de dados 
		que satisfazem um determindado criterio de selecao
		@param $criteria = objeto do tipo TCriteria
	*/
	function count(TCriteria $criteria){
		//instancia uma intrucao select
		$sql = new TSqlSelect();
		$sql->addColumn('count(*)');
		$sql->setEntity($this->class);
		//atribui o criterio passado como parametro
		$sql->setCriteria($criteria);

		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//registra a mensagem de log
			TTransaction::log($sql->getInstruction());
			//executa a instrucao select
			$result = $conn->Query($sql->getInstruction());
			if($result):
				$row = $result->fetch();
			endif;
			//retorna o resultado
			return $row[0];
		else:
			//se nao tiver uma transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}
}
























?>
