<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel

	Esta classe prove ós metodos necessarios para persistir e 
	recuperar objetos da base de dados. Baseado no modelo (Active Record)
*/
abstract class TRecord{

	protected $data; //Array contendo os dados do objeto

	/*
	 	Metodo construtor  __construct()
	 	instancia um Active Record. Se passado o $id, ja carrega o objeto
	 	@param [$id] =  ID do objeto 
	*/

	function __construct($id = NULL){
		if($id)://se o id for informado
			//carrega o objeto correspondente
			$object = $this->load($id);
			if($object):
				$this->fromArray($object->toArray());
			endif;
		endif;
	}	


	/*
		Metodo __clone()
		executado quando o objeto for clonado.
		limpa o ID para que seja gerado um novo ID para o clone
	*/
	function __clone(){
		unset($this->id);
	}

	/*
		Metodo __set()
		Sera executado sempre que uma propiedade for atribuida
	*/
	public function __set($prop, $value){
		//verifica se existe metodo set_<atributo> para o objeto
		if(method_exists($this, 'set_'.$prop)):
			//executa o metodo set_<atributo> do objeto
			call_user_func(array($this, 'set_'.$prop));
		else:
			//Atribui o valor da propriedade
			$this->data[$prop] = $value;
		endif;
	}

	/*
		Metodo __get()
		Executado sempre que uma propriedade for requisitada
	*/
	public function __get($prop){
		//verifica se existe metodo get_<atributo>
		if(method_exists($this, 'get_'.$prop)):
			//executa o metodo get_<propiedade>
			return call_user_func(array($this, 'get_'.$prop));
		else:
			//retorna o valor da propriedade
			return $this->data[$prop];
		endif;
	}

	/*
		Metodo getEntity()
		Retorna o nome da entidade. 
		No caso a tabela do banco de dados que nada mais e que o proprio nome da classe sem o sufixo Record
	*/
	private function getEntity(){
		//obtem o nome da classe
		$classe = strtolower(get_class($this));
		//retorna o nome da classe - "Record"
		return substr($classe, 0, -6);
	}

	/*
		Metodo fromArray()
		preenche os dados do objeto com um array
	*/
	public function fromArray($data){
		$this->data = $data;
	}

	/*
		Metodo toArray()
		Retorna os dados do objeto como array
	*/
	public function toArray(){
		return $this->data;
	}

	/*
		Metodo store()
		Armazena o objeto na base de dados e retorna 
		o numero de linhas afetadas pela instrucao SQL (zero ou um)
	*/
	public function store(){
		//verifica se tem ID ou se existe na base de dados 
		if(empty($this->data['id']) or (!$this->load($this->id))):
			//incrementa o ID
			$this->id = $this->getLast()+1;
			$sql = new TSqlInsert();
			$sql->setEntity($this->getEntity());
			//precorre os dados do objeto
			foreach($this->data as $key => $value):
				//passa os dados do objeto para o SQL
				$sql->setRowData($key, $this->$key);
			endforeach;
		else:
			//instancia intrucao update
			$sql = new TSQLUpdate();
			$sql->setEntity($this->getEntity());
			//cria uma selecao baseado no ID
			$criteria = new TCriteria();
			$criteria->add(new TFilter('id', '=', $this->id));
			$sql->setCriteria($criteria);
			//percorre os dados do objeto
			foreach($this->data as $key => $value):
				if($key != 'id')://o ID nao precisa entrar no update
					//passa os dados do objeto para o SQL
					$sql->setRowData($key, $this->$key);
				endif;
			endforeach;
		endif;

		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//faz o log e executa o SQL
			TTransaction::log($sql->getInstruction());
			$result = $conn->exec($sql->getInstruction());
			//retorna o resultado
			return $result;
		else:
			//se nao houver uma transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}

	/*
		Metodo load()
		Recupera, retorna um objeto da base de dados
		atraves do seu ID e instancia ele na memoria 
		@param $id = ID do objeto
	*/
	public function load($id){
		//instancia uma instrucao SELECT
		$sql = new TSQLSelect();
		$sql->setEntity($this->getEntity());
		$sql->addColumn('*');

		//cria o criterio de selecao baseado no ID
		$criteria = new TCriteria();
		$criteria->add(new TFilter('id', '=', $id));
		//define o criterio de selecao de dados 
		$sql->setCriteria($criteria);
		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//cria a mensagem de log e executa a consulta
			TTransaction::log($sql->getInstruction());
			$result = $conn->Query($sql->getInstruction());
			//se retornar algum dado
			if($result):
				//retorna os dados em forma de objeto
				$object = $result->fetchObject(get_class($this));
			endif;
			return $object;
		else:
			//se nao houver transacao, lancara uma excecao
			throw new Exception("Nao ha transacao ativa!");
			
		endif;
	}

	/*
		Metodo delete()
		exclui um objeto da base de dados atraves do seu ID.
		@param $id = ID do objeto
	*/

	public function delete($id = NULL){
		//o ID e o parametro ou a propriedade ID
		$id = $id ? $id : $this->id;
		//instancia uma instrucao de DELETE
		$sql = new TSqlDelete();
		$sql->setEntity($this->getEntity());

		//cria o criterio de selecao de dados
		$criteria = new TCriteria();
		$criteria->add(new TFilter('id', '=', $id));
		//define o criterio de selecao baseado no ID
		$sql->setCriteria($criteria);

		//obtem a transacao ativa
		if($conn = TTransaction::get()):
			//faz o arquivo de log e executa o sql
			TTransaction::log($sql->getInstruction());
			$result = $conn->exec($sql->getInstruction());
			//retorna o resultado
			return $result;
		else:
			//se nao tiver transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}

	/*
		Metodo getLast()
		Retorna o ultimo ID
	*/

	private function getLast(){
		//inicia a transacao
		if($conn = TTransaction::get()):
			//instancia a instrucao SELECT
			$sql = new TSQLSelect();
			$sql->addColumn('max(ID) as ID');
			$sql->setEntity($this->getEntity());
			//cria o log e executa a instrucao SQL
			TTransaction::log($sql->getInstruction());
			$result = $conn->Query($sql->getInstruction());
			//retorna os dados do banco
			$row = $result->fetch();
			return $row[0];
		else:
			//se nao houver transacao, retorna uma excecao
			throw new Exception("Nao ha transacao ativa!");
		endif;
	}

}



















?>