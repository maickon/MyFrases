<?php
/*
	Classe criada com ajuda do livro PHP OO
	Escrita por Maickon Ranegel

	Classe TStyle
	Classe para abstracao de estilos css
*/

class TStyle{
	private $name; //nome do estilo
	private $properties; //atributos
	static private $loaded; //array de estilos carregados

	/*
		Metodo construtor
		instancia uma tag html
		@param $name = nome da tag
	*/

	public function __construct($name){
		//atribui o nome do estilo
		$this->name = $name;
	}

	/*
		Metodo set()
		intercepta as atribuicoes a propiedades do objeto
		@param $name = nome da propriedade
		@param $value = valor
	*/

	public function __set($name, $value){
		//substitui o '_' por '-' no nome da propriedade
		$name = str_replace('_', '-', $name);
		//armazena os valores atribuidos ao array properties
		$this->properties[$name] = $value;
	}

	/*
		Metodo show()
		exibe a tg na tela
	*/

	public function show(){
		//verifica se esse estilo ja foi carregado
		if(!self::$loaded[$this->name]):
			echo "<style type='text/css' media='screen'>\n";
			//exibe a abertura do estilo
			echo ".{$this->name}\n";
			echo "{\n";
			if($this->properties):
				//percorre as propiedades
				foreach($this->properties as $name => $value):
					echo "\t {$name}: {$value};\n";
				endforeach;
			endif;
			echo "}\n";
			echo "</style>\n";
			//marca o estilo como ja carregado
			self::$loaded[$this->name] = TRUE;

		endif;

	}
}

?>