<?php
/**
 * HTMLClass
 *
 * @author    Maickon José Rangel <maickonmaickonmaickon@Gmail.com>
 * @copyright 2013 Maickon José Rangel <maickonmaickonmaickon@Gmail.com>
 * @category PHP
 * @version   1.0
 */

class HTMLtags{
	private $sinal_maior;
	private $sinal_menor;
	private $barra;
	
	function __construct(){
		$this->sinal_maior_com_barra = "/>";
		$this->sinal_maior = ">";
		$this->sinal_menor = "<";
		$this->sinal_menor_com_barra = "</";
	}
	function open($tag, $propiedades=null){
		if($propiedades == null):
			if($tag == 'meta'):
				echo "$this->sinal_menor$tag$this->sinal_maior\n";
			elseif($tag == 'hr' || $tag == 'br' || $tag == 'img' || $tag == 'input' || $tag == 'link'):
				echo "$this->sinal_menor$tag$this->sinal_maior_com_barra\n";
			else:
				echo "$this->sinal_menor$tag$this->sinal_maior\n";
			endif;
		else:
			if($tag == 'meta'):
				echo "$this->sinal_menor$tag $propiedades$this->sinal_maior\n";
			elseif($tag == 'hr' || $tag == 'br' || $tag == 'img' || $tag == 'input' || $tag == 'link'):
				echo "$this->sinal_menor$tag $propiedades $this->sinal_maior_com_barra\n";
			else:
				echo "$this->sinal_menor$tag $propiedades$this->sinal_maior\n";
			endif;
		endif;
	}
	
	function close($tag){
		echo "$this->sinal_menor_com_barra$tag$this->sinal_maior\n";
	}
	
	function inprime($string, $modo=null){
		$barra_n = "\n";
		$tabulacao = "\t";
		if($modo == 'decode'):
			print $tabulacao.utf8_decode($string).$barra_n;
		elseif($modo == 'encode'):
			print $tabulacao.utf8_encode($string).$barra_n;
		else:
			print $tabulacao.$string.$barra_n;
		endif;
		
	}
	
	function loadCss($css_path,$import=false){
		if(opendir($css_path)):
			$pasta = opendir($css_path);
			$barra_n = "\n";
			$css = array();
			$i=0;
			while($p = readdir($pasta)):
				if($p != '.' and $p != '..'):
					$css[$i] = $p;
					$i++;
				endif;
			endwhile;
			
			$arqCss = $css;
			for($i=0;$i<count($arqCss);$i++):
				if($import == true):
					print '<style type="text/css">@import url("'.$css_path.$arqCss[$i].'");</style>'.$barra_n.'';
				else:
					print '<link href="'.$css_path.$arqCss[$i].'" rel="stylesheet"" />'.$barra_n.'';
				endif;
			endfor;
		else:
			return 0;
		endif;
	}
	
	function loadJs($js_path){
		if(opendir($js_path)):
			$pasta = opendir($js_path);
			$barra_n = "\n";
			$js = array();
			$i=0;
			while($p = readdir($pasta)):
				if($p != '.' and $p != '..'):
					$js[$i] = $p;
					$i++;
				endif;
			endwhile;
			
			$arqJs = $js;
			for($i=0;$i<count($arqJs);$i++):
				print '<script src="'.$js_path.$arqJs[$i].'"></script>'.$barra_n.'';		
			endfor;
		else:
			return 0;
		endif;
	}

	function loadClass($class_path){
		if(opendir($class_path)):
			$pasta = opendir($class_path);
			$barra_n = "\n";
			$js = array();
			$i=0;
			while($p = readdir($pasta)):
				if($p != '.' and $p != '..'):
					$js[$i] = $p;
					$i++;
				endif;
			endwhile;
			
			$arqJs = $js;
			for($i=0;$i<count($arqJs);$i++):
				print "require_once('.$js_path.$arqJs[$i].')".$barra_n;		
			endfor;
		else:
			return 0;
		endif;
	}
}
?>
