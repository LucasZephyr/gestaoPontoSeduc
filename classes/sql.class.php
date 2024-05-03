<?php

require_once("conexao.class.php");

class SQL {

    var $conexao;

    function SQL() {
        $oConexao = new Conexao();
        $this->conexao = $oConexao->conexao;
    }

    // Função para executar uma consulta e retornar resultados
    function executarQuery($sql){
        $res = mysqli_query($this->conexao, $sql);
        if($res){
            if(mysqli_num_rows($res) > 0){
                while($aReg = mysqli_fetch_assoc($res)){
                    $aObj[] = $aReg;
                }
            }
            return (mysqli_num_rows($res) > 0) ? $aObj : false;
        } else {
            return false;
        }
    }

    // Função para executar uma consulta e retornar um resultado booleano
    function executarQueryBoleano($sql){
        $res = mysqli_query($this->conexao, $sql);
        if($res){
            $array = array("informacao" => "SUCESSO");
            return $array;
        } else {
            $array = array("informacao" => "ERROR", "SQLErro" => mysqli_error($this->conexao), "SQL" => $sql);
            return $array;  
        }
    }

    function executarQueryBoleanoTransaction($sql){
        mysqli_autocommit($this->conexao, FALSE); #desativa o autocommit
        
        if(mysqli_begin_transaction($this->conexao)) {
            $res = mysqli_query($this->conexao, $sql);
    
            if($res){
                mysqli_commit($this->conexao); #confirma a transacao se a consulta for bem sucedida
                mysqli_autocommit($this->conexao, TRUE); #reativa o autocommit
                $array = array("informacao" => "SUCESSO");
                return $array;
            } else {
                mysqli_rollback($this->conexao); #desfaz a transacao se houver erro
                mysqli_autocommit($this->conexao, TRUE); #reativa o autocommit
                $array = array("informacao" => "ERROR", "SQLErro" => mysqli_error($this->conexao), "SQL" => $sql);
                return $array;  
            }
        } else {
            $array = array("informacao" => "ERROR", "SQLErro" => "Erro ao iniciar transação", "SQL" => $sql);
            return $array;
        }
    }
    



    function verificarLogin($usuario, $senha){      

        $sql = "
            select * from gestaoponto.usuario u
            where 1 = 1 and 
            u.ativo = 1 and
            u.login = '$usuario' and
            u.senha = '$senha'
        ";

        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);
    }

    function insertRegistroPonto($id, $vet){

        $lat = $vet['latitude'];
        $log = $vet['longitude'];

        $sql = "
            INSERT INTO gestaoponto.registros_ponto
                ( 
                    id_usuario, 
                    data, 
                    hora, 
                    longitude, 
                    latitude
                )

                VALUES 
                (
                    $id,
                    now(),
                    CURTIME(),
                    '$log',
                    '$lat'
                )
        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQueryBoleano($sql);   
    }

    function getRegistroPontoUsuario($id){

        $dataAtual = date('Y-m-d');

        $sql = "
            SELECT 
                * 
            FROM gestaoponto.registros_ponto 
            WHERE 1 = 1 and
            id_usuario = $id and
            data = '$dataAtual'
        ";

        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

    function getRegistroPonto($mes, $ano, $id_usuario){

         $sql = "
        SELECT 
            *
        FROM gestaoponto.registros_ponto rp
        WHERE 
            id_usuario = $id_usuario 
            AND YEAR(rp.data) = $ano 
            AND MONTH(rp.data) = $mes

    ";


       #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

    function getPerfilUsuario($id_usuario){

        $sql = "
            select u.perfil from usuario u
            where u.id_usuario = $id_usuario;
        ";
     
        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

    function getRegistroAbonoPorDia($data, $id_usuario){


        $sql = "

            select * from gestaoponto.registros_ponto rp
            where 1 = 1 and 
            rp.id_usuario = $id_usuario and 
            rp.data = '$data' 
        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

    function insertAbono($vet){

        $id = $vet['id_usuario'];
        $dia = $vet['dia'];
        $justificativaAbono = utf8_decode($vet['justificativaAbono']);

        $batidas = [];
        foreach ($vet as $key => $value) {
            #erifica se a chave comeca com "abono_" para identificar as batidas
            if (strpos($key, 'abono_') === 0) {
                $batidas[] = $value;
            }
        }

        #preenche com NULL caso n haja 6 batidas
        while (count($batidas) < 6) {
            $batidas[] = 'NULL';
        }

        #constroi a lista de batidas para o INSERT
        $batidasSql = implode(",", array_map(function ($val) {
            return $val === 'NULL' ? $val : "'$val'";
        }, $batidas));



        $sql = "
            INSERT INTO solicitacaoabonos 
            (id_usuario, batida1, batida2, batida3, batida4, batida5, batida6, justificativa, dia, status)
            VALUES 
            ('$id', $batidasSql, '$justificativaAbono', '$dia', 1)
        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQueryBoleano($sql);      
    }


    function getAbonosPorUsuarios($id_usuario){

        $sql = "

            select 
                *
            from gestaoponto.solicitacaoabonos sa
            where 1 = 1 and 
            sa.id_usuario = '$id_usuario'
            ;

        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

        function getAbonosPorUsuarios2($id_usuario, $id_abono){

        $sql = "

            select 
                *
            from gestaoponto.solicitacaoabonos sa
            where 1 = 1 and 
            sa.id_usuario = '$id_usuario' and
            sa.id = $id_abono
            ;

        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   
    }

    function atualizarDados($vet, $id){

        $cpf = str_replace(['.', '-', ','], '', $vet['cpf']);
        $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);

        $email = $vet['email'];
        $telefone = $vet['telefone'];
        $nome = $vet['nome'];
        $senha = $vet['senha'];


        $sql = "

            UPDATE usuario 
                SET email = '$email',
                nome = '$nome',
                telefone = '$telefone',
                cpf = '$cpf',
                senha = '$senha',
                primeiro_acesso = '0'
                WHERE usuario.id_usuario = $id;


        ";


        #echo "<pre>" . $sql ;exit;
        return $this->executarQueryBoleano($sql);   
    }


    function verificaDadosDuplicados($vet){


        $nome = strtoupper($vet['nome']);
        $mat = $vet['mat'];
        $mail = $vet['email'];
        $cpf = $vet['cpf'];

        $sql = "

            SELECT 
                *
            FROM usuario u 
                WHERE 1 = 1 AND
                u.nome = '$nome' or u.login = '$mat' or u.email = '$mail' or u.cpf = '$cpf'

        ";

        #echo "<pre>" . $sql ;exit;
        return $this->executarQuery($sql);   

    }


    function insertUsuario ($vet){

        $nome = strtoupper($vet['nome']);
        $nascimento = $vet['nascimento'];
        $setor = strtoupper($vet['set']);
        $matricula = $vet['mat'];
        $funcao = strtoupper($vet['func']);
        $email = $vet['email'];
        $cpf =  $vet['cpf'];
        $telefone = $vet['telefone'];
        $perfil = $vet['Perfil'];

        $sql = "

            INSERT INTO usuario 
                (
                    login,
                    senha, 
                    data_cadastro, 
                    ativo, 
                    email, 
                    cpf, 
                    nome, 
                    primeiro_acesso, 
                    telefone, 
                    perfil, 
                    data_nascimento, 
                    setor, 
                    funcao
                ) 
                VALUES (
                    '$matricula',
                    '$matricula',
                    now(),
                    1,
                    '$email',
                    '$cpf',
                    '$nome',
                    '1',
                    '$telefone',
                    '$perfil',
                    '$nascimento',
                    '$setor',
                    '$funcao'
                    )


        ";

     
        #echo "<pre>" . $sql ;exit;
        return $this->executarQueryBoleano($sql);
    }

    function getAbonos(){

        $sql = "
            select 
                * 
            from gestaoponto.solicitacaoabonos a
            where a.status = 1
            order by a.id ASC
        ";

        #echo "<pre>".$sql;exit;
        return $this->executarQuery($sql);
    }

    function getSolicitacaoAbonosPorId($id){

        $sql = "
            SELECT 
                *
            FROM gestaoponto.solicitacaoabonos a 
            WHERE a.id = $id
        ";
        #echo "<pre>".$sql;exit;
        return $this->executarQuery($sql);
    }

    function deleteRegistroPontoPorDia($dia, $id_usuario){

        $sql = "
            DELETE FROM gestaoponto.registros_ponto 
            WHERE 
            data = '$dia' and
            id_usuario = $id_usuario 
        ";

        #echo "<pre>".$sql;exit;
        return $this->executarQueryBoleano($sql);
    }


    function aprovarAbono($id){

        $dados = $this->getSolicitacaoAbonosPorId($id);
        $deleteRegistro = $this->deleteRegistroPontoPorDia($dados[0]['dia'], $dados[0]['id_usuario']);
        $sqlInsert = "";
        if($deleteRegistro['informacao'] == "SUCESSO"){
            foreach ($dados as $registro) {
                for ($i = 1; $i <= 6; $i++) {
                    $hora = $registro["batida$i"];
                    
                    if (!empty($hora)) {
                        $id_usuario = $registro["id_usuario"];
                        $data = $registro["dia"];
                        $longitude = '';
                        $latitude = '';
                        
                        $sqlInsert = "
                        INSERT INTO registros_ponto 
                        (
                            id_usuario, 
                            data, 
                            hora, 
                            longitude, 
                            latitude
                        ) 
                        VALUES 
                        (
                            $id_usuario,
                            '$data', 
                            '$hora', 
                            '$longitude', 
                            '$latitude'
                        );"
                        ;

                        $var = $this->executarQueryBoleano($sqlInsert);
                        if($var['informacao'] == 'ERROR'){
                            echo json_encode($var);exit;
                        }

                    }
                }
            }
        }

         $sql = "
            UPDATE 
                gestaoponto.solicitacaoabonos 
            SET status = '2' WHERE id = $id; 
        ";

        #echo "<pre>".$sql;exit;
        return $this->executarQueryBoleano($sql);
    }

    function reprovarAbono($id){

        $sql = "
            UPDATE 
                gestaoponto.solicitacaoabonos 
            SET status = '3' WHERE id = $id; 
        ";

        #echo "<pre>".$sql;exit;
        return $this->executarQueryBoleano($sql);
    }

    function verificaDadosEsqueciSenha($vet){

        $dtNasc = $vet['dtNasc'];
        $email = $vet['email'];
        $matricula = $vet['matricula'];

        $data_formatada = date('Y-m-d', strtotime(str_replace('/', '-', $dtNasc)));

        $sql = "
            select 
                u.id_usuario,
                u.email,
                u.nome
            from gestaoponto.usuario u

            where 1 = 1 and
            login = '$matricula' and
            email like '%$email%' and
            data_nascimento = '$data_formatada'
        ";

        #echo "<pre>" . $sql;exit;

        return $this->executarQuery($sql);
    }

    function alterarSenhaEsqueciSenha($id, $novaSenha){

        $sql = "

            update gestaoponto.usuario 
            set senha = '$novaSenha'
            where id_usuario = $id;
        ";

        #echo "<pre>" . $sql;exit;

        return $this->executarQueryBoleano($sql);

    }

    function atualizarDadosConta($vet, $id){

        $cpf = "'" . $vet['cpf'] . "'";
        $nome = "'" . $vet['nome'] . "'";
        $email = "'" . $vet['email'] . "'";
        $telefone = "'" . $vet['telefone'] . "'";
        $data_nascimento = date('Y-m-d', strtotime(str_replace('/', '-', $vet['dtNasc'])));

        $sql = "
            UPDATE usuario 
            SET 
                cpf = $cpf,
                nome = $nome,
                email = $email,
                telefone = $telefone,
                data_nascimento = '$data_nascimento'
            WHERE 
                id_usuario = $id;
        ";
        #echo "<pre>" . $sql;exit;
        return $this->executarQueryBoleano($sql);        
    }

    function verificaSenha($senha, $id){

        $sql = "
            select * from gestaoponto.usuario u
            where u.senha = '$senha' and
            u.id_usuario = $id
        ";

        #echo '<pre>' . $sql;exit;
        return $this->executarQuery($sql);        
    }

    function atualizarSenha($antiga, $nova, $id){

        session_start();

        $confSenha = $this->verificaSenha($antiga, $_SESSION['usuario']['id_usuario']);

        $arrayResposta = array();
        if($confSenha == ""){
            $arrayResposta = array('informacao' => 'ERROR');
            return $arrayResposta;
        }

        $sql = "
        UPDATE usuario 
            SET 
                senha = '$nova'
            WHERE 
                id_usuario = $id;  

        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQueryBoleano($sql);        
    }

    function distribuicaoUsuarios(){
    $sql = "
        SELECT 
                SUM(CASE WHEN ativo = 1 THEN 1 ELSE 0 END) AS usuarios_ativos,
                SUM(CASE WHEN ativo = 0 THEN 1 ELSE 0 END) AS usuarios_inativos
            FROM gestaoponto.usuario
        ";


        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);    
    }


    function totalRegistrosPonto(){

        $sql = "
            SELECT COUNT(*) AS total_registros_ponto
            FROM registros_ponto
        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);    
    }

    function totalAbonosPendentes(){

        $sql = "
            SELECT COUNT(*) AS total_abonos_pendentes
            FROM solicitacaoabonos
            WHERE status = 1
        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);    
    }

    function atrasoEntrada(){

        $sql = "
                SELECT count(CONCAT_WS(' - ', r.id, r.hora, u.nome)) AS qtd_atraso_na_entrada
                FROM registros_ponto AS r
                JOIN usuario AS u ON r.id_usuario = u.id_usuario
                WHERE TIME(r.hora) > '08:00:00';

        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);    
    }


    function getUsuarios(){

        $sql = "
            SELECT * from gestaoponto.usuario u
        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);    

    }

    function atualizarStatusUsuario($status, $id){

        $sql = "
        UPDATE usuario 
            SET 
                ativo = $status 
            WHERE 
                id_usuario = $id
        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQueryBoleano($sql);    
    }

    function inserirFerias($vet, $id_usuario){

        #echo '<pre>';print_r($vet);echo '</pre>';exit;

        $dtIni          = $vet['dataInicio'];
        $duracao        = $vet['duracao'];
        $adicionais     = $vet['adicionais'];
        $adiantamento   = $vet['adiantamento'];

        if($adicionais == ""){
            $adicionais = 'nao';
        }


        $sql = "

            INSERT INTO ferias
            (
                id_usuario, 
                data_inicio,
                duracao, 
                adiantamento_13, 
                dias_adicionais,
                status
            ) 


            VALUES 
                (
                    $id_usuario,
                    '$dtIni',
                    $duracao,
                    '$adiantamento',
                    '$adicionais',
                    '1'

                )

        ";


        #echo "<pre>" . $sql;exit;
        return $this->executarQueryBoleano($sql);    
    }

    function getFeriasPorUsuarios($id){

        $sql = "
         SELECT * from gestaoponto.ferias f
            join gestaoponto.usuario u
            on u.id_usuario = f.id_usuario
         where f.id_usuario = $id
        ";

        #echo "<pre>" . $sql;exit;
        return $this->executarQuery($sql);  

    }

    function getFeriasPendentes(){

        $sql = "
        SELECT * from gestaoponto.ferias f
            JOIN gestaoponto.usuario u
            on u.id_usuario = f.id_usuario
        where f.status = '1';
       ";

       #echo "<pre>" . $sql;exit;
       return $this->executarQuery($sql); 

    }












    

  
}

?>