<?php
/*
	Funcao __autoload()
	Esta funcao e responsavel por requisitar uam classe
	via require_once no momento em que ela e instanciada
*/

function __autoload($classe){
	if(file_exists("app.dbo/{$classe}.class.php")):
		require_once "app.dbo/{$classe}.class.php";
	endif;
}

/*
	Classe AlunoRecord, filha de TRecord
	persiste um aluno no banco de dados
*/
class AlunoRecord extends TRecord{

}

/*
	Classe CursoRecord, filha de TRecord
	persiste um curso no banco de dados
*/
class CursoRecord extends TRecord{
	
}
function teste01(){
	try{
		TTransaction::open('my_livro');
		TTransaction::setLogger(new TLoggerHTML('tmp/arquivo.html'));
		TTransaction::log("Inserindo registro de Maickon Rangel");

		$sql = new TSqlInsert();
		$sql->setEntity('livros');
		$sql->setRowData('id', 5);
		$sql->setRowData('nome', 'OpenGl');
		$sql->setRowData('autor', 'Steve Woz');

		$conn = TTransaction::get();
		$resul = $conn->query($sql->getInstruction());
		TTransaction::log($sql->getInstruction());

		//-----------------------------------------------

		TTransaction::setLogger(new TLoggerXML('tmp/arquivo.xml'));
		TTransaction::log("Inserindo registro de Albert Eintein");

		$sql = new TSqlInsert();
		$sql->setEntity('livros');
		$sql->setRowData('id', 6);
		$sql->setRowData('nome', 'Criando a Apple');
		$sql->setRowData('autor', 'Steve Jobs');

		$conn = TTransaction::get();
		$resul = $conn->query($sql->getInstruction());
		TTransaction::log($sql->getInstruction());
		TTransaction::close();
	}catch(Exception $e){
		echo $e->getMessage();
		TTransaction::rollback();
	}
}


function teste02(){
	//insere novos objetos no banco de dados
	try{
		//inicia transacao com o banco 'my_livro'
		TTransaction::open('my_livro');
		//define o arquivo para log
		TTransaction::setLogger(new TLoggerTXT('tmp/log01.txt'));

		//armazena a string abaixo no arquivo de log
		TTransaction::log("** inserindo alunos");

		//instancia um novo objeto aluno
		$maickon = new AlunoRecord();
		$maickon->nome 		=	"Maickon Rangel";
		$maickon->endereco	=	"Rua dos passos";	
		$maickon->telefone	=	"22 99709-4529";
		$maickon->cidade 	=	"Sao joao da Barra";
		$maickon->store();//armazena o objeto

		$jose = new AlunoRecord();
		$jose->nome 	=	"Jose Rangel";
		$jose->endereco	=	"Rua dos passos";	
		$jose->telefone	=	"22 99709-4529";
		$jose->cidade 	=	"Sao joao da Barra";
		$jose->store();//armazena o objeto

		//armazena a string abaixo no arquivo de log
		TTransaction::log("** inserindo cursos");

		//instancia um novo objeto curso
		$curso = new CursoRecord();
		$curso->descricao 	= "Orientacao a objetos com PHP";
		$curso->duracao 	= "20";
		$curso->store();//armazeno o objeto	

		//instancia um novo objeto curso
		$curso = new CursoRecord();
		$curso->descricao 	= "Desenvolvendo em PHP-GTK";
		$curso->duracao 	= "32";
		$curso->store();//armazeno o objeto	

		//finaliza a transacao
		TTransaction::close();
		echo "Registros inseridos com sucesso!<br />";
	}catch(Exception $e){//em caso de excecao
		//exibe a mensagem gerada pela excecao
		echo '<b>Erro</b>'.$e->getMessage();
		//desfaz todas as alteracoes no banco de dados
		TTransaction::rollback();
	}
}

function teste03(){
	//obtem os objetos do banco de dados 
	try{
		//inicia a transacao com o banco de 'my_livros'
		TTransaction::open('my_livro');
		//define o arquivo de log
		TTransaction::setLogger(new TLoggerTXT('tmp/log02.txt'));

		//exibe algumas mensagens na tela
		echo "Obtendo alunos<br />";
		echo "==================<br />";

		//obtem o aluno de ID 1
		$aluno = new AlunoRecord(1);
		echo "Nome: ".$aluno->nome."<br />";
		echo "Endereco: ".$aluno->endereco."<br />"; 
		echo "<br />";
		$aluno = new AlunoRecord(5);
		echo "Nome: ".$aluno->nome."<br />";
		echo "Endereco: ".$aluno->endereco."<br />"; 

		//obtem alguns cursos
		echo "<br />";
		echo "Obtendo cursos<br />";
		echo "===============<br />";

		//obtem o curso de ID 1
		$cursos = new CursoRecord(1);
		echo "Curso: ".$cursos->descricao."<br />";
		$cursos = new CursoRecord(2);
		echo "Curso: ".$cursos->descricao."<br />";

		//finaliza a transacao
		TTransaction::close();
	}catch(Exception $e){//em caso de excecao
			//exibe mensagem gerada pela excecao
			echo "<b>Erro</b>".$e->getMessage();
			//desfaz todas as alteracoes no banco de dados
			TTransaction::rollback();
		}
	}

function teste04(){
	//altera os objetos na base de dados
	try{
		//define a transacao com o banco
		TTransaction::open('my_livro');
		//define o arquivo para log
		TTransaction::setLogger(new TLoggerTXT('tmp/log03.txt'));
		TTransaction::log("** obtendo o aluno 1");
		//instancia o registro de aluno
		$record = new AlunoRecord();
		//obtem o aluno de id 1
		$aluno = $record->load(1);
		//verifica se ele existe
		if($aluno):
			//altera o telefone
			$aluno->telefone = "(21) 99202-2310";
			TTransaction::log("** persistindo o aluno 1");
			//armazena o objeto
			$aluno->store();
		endif;

		TTransaction::log("** obtendo o curso 1");
		//instancia registro de curso
		$record = new CursoRecord();
		//obtem o curso de ID 1
		$curso = $record->load(1);
		if($curso):
			//altera a duracao
			$curso->duracao = 96;
			TTransaction::log("** persistindo o curso 1");
			//armazena o objeto
			$curso->store();
		endif;

		//finaliza a transacao
		TTransaction::close();
		//exibe mensagem de sucesso
		echo "Registros alterados com sucesso!";
	}catch(Exception $e){//em caso de excecao
			//exibe mensagem gerada pela excecao
			echo "<b>Erro</b>".$e->getMessage();
			//desfaz todas as alteracoes no banco de dados
			TTransaction::rollback();
		}
}

function teste05(){
	//exclui objetos da base de dados
	try {
		//inicia a transacao com o banco 'my_livro'
		TTransaction::open('my_livro');
		//define o arquivo para log
		TTransaction::setLogger(new TLoggerTXT('tmp/log05.txt'));

		//armazena esta frase no arquivo de log
		TTransaction::log("** Apagando da primeira forma");
		//carrega o objeto
		$aluno = new AlunoRecord(5);
		//deleta o objeto
		$aluno->delete();

		//armazena esta frase no arquivo de log
		TTransaction::log("** Apagando da segunda forma");
		//instancia o modelo
		$modelo = new AlunoRecord();
		//deleta p objeto
		$modelo->delete(6);

		//finaliza a transacao
		TTransaction::close();
		echo "Exclusao realizada com sucesso";
		
	}catch (Exception $e) {
		echo "<b>Erro</b>".$e->getMessage();
		//desfaz todas as alteracoes no banco de dados
		TTransaction::rollback();	
	}
}

//teste02();
//teste03();
//teste04();
//teste05();
?>