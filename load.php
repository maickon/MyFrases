<?php

function __autoload($classe){
	if(file_exists("app.dbo/{$classe}.class.php")):
		require_once "app.dbo/{$classe}.class.php";
	elseif(file_exists("app.widgets/{$classe}.class.php")):
		require_once "app.widgets/{$classe}.class.php";
	else:
		require_once "app.class/{$classe}.class.php";
	endif;
}

?>