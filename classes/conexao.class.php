<?php

require_once("class.Protecao.php");

class Conexao{
 
	var $conexao;
	var $consulta;  
	var $msg;
	var $banco;
	
	function Conexao($servidor = "Homologacao"){

		$oProtecao = new Protecao();
		$oProtecao->protecao($_REQUEST);
		
		switch ($servidor){			
			/*			
			case "Homologacao":
				$this->conexaoBD("192.168.200.52","apsuser","wc35v@hml#&12","seduc_homologacao");
			break;
			*/

			case "Homologacao":
				$this->conexaoBD("localhost","root","","gestaoponto");
			break;

			default:
				die("ERRO: Servidor $servidor inexistente!");
			break;
		}
	}

	 
	function conexaoBD($host,$user,$senha,$bd){
		/*
			$conexao = pg_connect("host=$host dbname=$bd user=$user password=$senha") or die ('Nao foi possivel conectar com o Banco de Dados Postgres!');
		$this->set_conexao($conexao);
		*/

		$conexao = mysqli_connect($host, $user, $senha, $bd) or die ('Nao foi possivel conectar com o Banco de Dados MySQL!');
		$this->set_conexao($conexao);
	}
	
	// ============ METODOS GET E SET ===================
	function get_conexao(){
	    if(mysqli_ping($this->conexao)){
	        return $this->conexao;
	    } else {
	        die('ERRO: A conexão com o Banco de Dados foi perdida!');
	    }
	}

	// Função para configurar conexão
	function set_conexao($conexao){
	    $this->conexao = $conexao;
	}

	// Função para obter mensagem
	function get_msg(){
	    return $this->msg;
	}

	// Função para configurar mensagem
	function set_msg($msg){
	    $this->msg = $msg;
	}

	// Função para obter consulta
	function get_consulta(){
	    return $this->consulta;
	}

	// Função para configurar consulta
	function set_consulta($consulta){
	    $this->consulta = $consulta;
	}

	// Função para obter número de linhas
	function numRows($consulta = NULL){
	    if(!$consulta)
	        $consulta = $this->get_consulta();
	    return ($consulta) ? mysqli_num_rows($consulta) : false;
	}

	// Função para buscar registro associativo
	function fetchReg($consulta = NULL){
	    if(!$consulta)
	        $consulta = $this->get_consulta();
	    return ($consulta) ? mysqli_fetch_assoc($consulta) : false;
	}

	// Função para buscar linha
	function fetchRow($consulta = NULL){
	    if(!$consulta) 
	        $consulta = $this->get_consulta();
	    return ($consulta) ? mysqli_fetch_row($consulta) : false;
	}

	// Função para obter último ID inserido
	function lastID(){
	    return mysqli_insert_id($this->get_conexao());
	}

	// Função para fechar conexão
	function close(){
	    mysqli_close($this->get_conexao());
	}

} #fim da classe
?>
