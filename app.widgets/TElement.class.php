<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel

	Esta classe prove ós metodos necessarios para se trabalhar
	com a camada de apresentacao da aplicacao
*/

class TElement{
	private $name; //nome da tag HTML
	private $properties; //sao as propiedades da tag
	private $children;

	/*
		Metodo construtor
		instancia uma tag HTML
		@param $name = nome da tag
	*/

	public function __construct($name){
		//define o nome do elemento
		$this->name = $name;
	}

	/*
		Metodo __set()
		intercepta as atribuicoes a propiedades  do objeto
		@param $name = nome da propiedade
		@value $value = valor 
	*/

	public function __set($name, $value){
		//armazenas os valores atribuidos ao array properties
		$this->properties[$name] = $value;
	}

	/*
		Metodo add()
		adiciona um elemento filho
		@param $child = objeto filho
	*/

	public function add($child){
		$this->children[] = $child;
	}

	/*
		Metodo open()
		exibe a tag de abertura  na tela
	*/

	private function open(){
		//exibe a tag de abertura
		echo "<{$this->name}";
		if($this->properties):
			//percorre as propiedades
			foreach($this->properties as $name => $value):
				echo " {$name}=\"{$value}\"";
			endforeach;
		endif;
		echo '>';
	}

	/*
		Metodo show()
		exibe a tag na tela, juntamente com seu conteudo
	*/

	public function show(){
		//abre a tag
		$this->open();
		echo "\n";
		//se possui conteudo
		if($this->children):
			//percorre todos os objetos filhos
			foreach($this->children as $child):
				//se for um objeto
				if(is_object($child)):
					echo "\t";
					$child->show();
				elseif((is_string($child)) or (is_numeric($child))):
					//se for texto
					echo "\t";
					echo $child;
					echo "\n";
				endif;
			endforeach;
			//facha a tag
			$this->close();
		endif;
	}

	/*
		Metodo close()
		Fecha uma tag HTML
	*/

	private function close(){
		echo "</{$this->name}>";
		echo "\n";
	}

}

?>