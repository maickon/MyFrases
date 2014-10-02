<?php
$tag = new Tags();
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

?>