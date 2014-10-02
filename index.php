<?php
require_once 'load.php';

$tag = new HTMLtags();


$tag->loadCss('css/');

$tag->open('div','class="navbar"');
	$tag->open('div','class="navbar-inner"');
		$tag->open('a','class="brand" href="#"');
			$tag->inprime('My Frases');
		$tag->close('a');
		$tag->open('ul','class="nav"');
			$tag->open('li','class="active"');
				$tag->open('a','href="?p=home"');
					$tag->inprime('Home');
				$tag->close('a');
			$tag->close('li');

			$tag->open('li');
				$tag->open('a','href="?p=sobre"');
					$tag->inprime('Sobre');
				$tag->close('a');
			$tag->close('li');
			
			$tag->open('li');
				$tag->open('a','href="?p=frases"');
					$tag->inprime('Frases');
				$tag->close('a');
			$tag->close('li');
		$tag->close('ul');
	$tag->close('div');
$tag->close('div');




$tag->open('div','class="hero-unit"');
	$tag->open('h1');
		$tag->inprime('My Frases');
	$tag->close('h1');
	$tag->open('p');
		$tag->inprime('Frases, o que vem na minha cabeça eu escrevo... simples assim rsrs ');		
	$tag->close('p');
$tag->close('div');

$tag->open('div','class="row-fluid"');
	$tag->open('div','class="container"');
		$tag->open('div','class="page-header"');
			try{
				//abre uma transacao com o banco de dados
				TTransaction::open('my_msg');
				//cria o arquivo de log
				TTransaction::setLogger(new TLoggerTXT('tmp/frases_log.txt'));
				
				//instancia um repositorio para a home page
				$repository = new TRepository('Home_page');
				//retorna todos os objetos que satisfazem o criterio
				$frases = $repository->load(new TCriteria());
			
				//verifica se rototnou alguma pagina
				if($frases):
					//percorre todas as paginas encontradas
					foreach($frases as $frase):
						$tag->open('h1');
							$tag->inprime($frase->titulo);
						$tag->close('h1');
						$tag->open('p');
							$tag->inprime($frase->texto);
						$tag->close('p');
					endforeach;
				endif;
				
				//finaliza a transacao
				TTransaction::close();
			}catch(Exception $e){//em caso de excecao
				//exibe a mensagem gerada pela excecao
				echo '<b>Erro</b> '.$e->getMessage();
				//desfaz todas as alteracoes nobanco de dados
				TTransaction::rollback();
			}
			
		$tag->close('div');
	$tag->close('div');
$tag->close('div');


$tag->open('div','class="hero-unit"');
	$tag->open('p');
		$tag->inprime('Desenvolvido por Maickon Rangel &copy 2014');		
	$tag->close('p');
$tag->close('div');
?>