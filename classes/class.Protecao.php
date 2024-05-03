<?php

class Protecao {

    function getIP() {
      
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }

    }

    public function acessoBR(){

        $ip = $this->getIP();

        if($_SERVER['HTTP_HOST'] != "localhost"){
        
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=". $ip));
            
            if( $ipdat->geoplugin_currencyCode !="BRL "){
                #echo ('die');
                header('location: ../logout.php');
                exit;  
            }

        }
    }


    public function salvarLog(){                
    
        $URL_ATUAL= $_SERVER['PHP_SELF'];
    
        $file = fopen($_SERVER['DOCUMENT_ROOT']."/gestaoPontoSeduc/classes/log.txt", "r+");

        if (!$file) {
            #exit("Falha ao abrir o arquivo");
        }
        
        $tem = 0;
        while (($line = fgets($file)) !== false) {
            $vet = explode("##",$line);
            if( $vet[0] == $URL_ATUAL && $vet[0] != ''  ){ 
                #exit('as');
                $tem = 1;
                break;
            }

        }
        
        if (!feof($file)) {
            #exit("Falha inesperada do fgets()");
        }


        $nomeMaquina = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ip ="&ip:".$nomeMaquina;

        $data = "&horario:".date('Y-m-d H:i:s');
        $user = "&usuario_id:".$_SESSION['usuario']['id_usuario'].'&usuario_nome:'.$_SESSION['usuario']['nome'];
        if($tem == 0){
            fwrite($file, "\n".$URL_ATUAL."##".$ip.$data.$user);
        }
        fclose($file);

        #exit;
    }

	public function protecao ($params = null){

        
        $this->acessoBR();

        $this->salvarLog();

		$PHP_SELF = $_SERVER['PHP_SELF'];
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		$GET = $params;
		
		///EXECUTA AS FUNCOES DE PROTECAO
		$this->bloquearPalavraChave();
		$this->protecaoSqlInjection($_REQUEST);
		
		
        if (!empty($_POST)) {
            foreach($_POST as $chave1 => $valor1){
                // ARRAY
                #echo $chave1."<br>";
                if(count($valor1)>1){
                    foreach($valor1 as $chave2 => $valor2){
                        $_POST[$chave1][$chave2] = $this->limpaurl($valor2);
                    }
                }else{
                    $_POST[$chave1] =  $this->limpaurl($valor1);
                }
            }
        }

        if (!empty($_GET)) {
            foreach($_GET as $chave1 => $valor1){
                if(count($valor1)>1){
                    foreach($valor1 as $chave2 => $valor2){
                        $_GET[$chave1][$chave2] = $this->limpaurl($valor2);
                    }
                }else{
                    $_GET[$chave1] =  $this->limpaurl($valor1);
                }
            }
        }        
        
		$_SERVER['PHP_SELF'] = $PHP_SELF;
		$_SERVER['HTTP_USER_AGENT'] = $HTTP_USER_AGENT;

	}
	
	
	public function limpaurl($var) {        
		$var = str_replace("from", "", ($var)) ;
		$var = str_replace("select", "'", ($var)) ;
		$var = str_replace("having", "", ($var)) ;
		$var = str_replace("union", "", ($var)) ;
		$var = str_replace("insert", "", ($var)) ;
		$var = str_replace("drop", "", ($var)) ;
		$var = str_replace("delete", "", ($var)) ;
		$var = str_replace("update", "", ($var)) ;
		$var = str_replace("table", "", ($var)) ;
		$var = str_replace("'", '', ($var)) ;
        $var = str_replace("\"", '', ($var)) ;
		return $var;
	
	}

	####### FUNÇÃO PARA BLOQUEAR SQL INJECTION #####
	public function protecaoSqlInjection($vTipo){

        $sTotalVari="";
		foreach($_REQUEST as $nKey => $sV){
			$sTotalVari .= $nKey." = ". urldecode($sV).";  ";
		}
        
		$vClausula = array("having","contains","select","from","union","insert","delete", "xp_","update","table","database","rownum","drop","create" );
		foreach($vTipo as $sVariavel){
			$sValor = strtolower($sVariavel);
			foreach($vClausula as $sClausula){
				if(strstr(strtoupper($sValor),strtoupper($sClausula))){
					//BARRA A CONTINUACAO DA EXECUCAO DA PAGINA CASO TENHA ALGUM TERMO ACIMA
					header('location: ../logout.php');
					exit;
				}
			}
		}
	}


	##### FUNÇÃO PARA BLOQUEAR PROGRAMA ####
	public function bloquearPalavraChave(){

		$vPalavraChave = array("havij","shell","sqlmap","xssf","xss","acunetix","pentest","trojan","ping32","hack","joiners","exploits","keylogger","forense","wannabe","scanners","killers","rootkit","hacker","insecuritynet","spynet", "turkojan","themida","spyone","prospy","prorat","ardamax");

		$sArquivo = strtolower($_SERVER['PHP_SELF']);
		$sUserAgente = strtolower($_SERVER['HTTP_USER_AGENT']);
		foreach($vPalavraChave as $sPalavraChave){
			if(
                strstr( strtoupper($sArquivo),strtoupper($sPalavraChave)) 
                || strstr(strtoupper($sUserAgente),strtoupper($sPalavraChave))){

				header('location: ../logout.php');
				exit;
			}// FIM DA FUNCAO
		}
	}
}










					 