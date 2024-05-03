<?php
$tempo = 7200; #duas horas
session_set_cookie_params($tempo);
session_start();

if(!$_SESSION['usuario']['logado'] == 'sim'){
    header("Location: login.php");
}

require '../classes/sql.class.php';
$sql = new SQL();

$ferias = $sql->getFeriasPorUsuarios($_SESSION['usuario']['id_usuario']);
#echo '<pre>';print_r($ferias);exit;


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Ver Ferias Solic </title>

    <style>
        .custom-list {
            list-style: none;
            padding-left: 0; 
        }
        .custom-list li::before {
            content: "●";
        }
        .custom-list .pendente::before {
            color: gray;
        }
        .custom-list .aprovado::before {
            color: blue;
        }
        .custom-list .reprovado::before {
            color: red; 
        }
    </style>
    
    <?php include '../includes/cabecalho.php'?>
</head>

<body>

    <?php include '../includes/navBar.php'?>

    <?php include '../includes/menuLateral.php'?>


    <!-- Inicio menu Principal-->
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Servidores</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">inicio</a></li>
                    <li class="breadcrumb-item">Servidores</li>
                    <li class="breadcrumb-item active">Ver F&eacute;rias Solicitadas</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-11"> <!-- inicio coluna a esquerda-->
                    <div class="row">

                    <div class="card">
                        <h5 class="card-header">Legenda dos status abaixo:</h5>

                        <div class="row">
                        <?php 
                        if($ferias){ ?>

                        <div class="container ms-4 mt-3">
                            <ul class="custom-list">
                                <li class="pendente">&nbsp;Pendente</li>
                                <li class="aprovado">&nbsp;Aprovado</li>
                                <li class="reprovado">&nbsp;Reprovado</li>
                            </ul>
                        </div>



                        <?php  foreach($ferias as $feria){ 
                                if($feria['status'] == 1){
                                    $status = 'Pendente';
                                    $cor = 'secondary';

                                }elseif($feria['status'] == 2){
                                    $status = 'Aprovado';
                                    $cor = 'primary';

                                }else{
                                    $status = 'Reprovado';
                                    $cor = 'danger';
                                }
                        ?>

                            <div class="card col-md-5 ms-5">
                                <div class="card-body">
                                    <h5 class="card-title">Férias : <?=$feria['nome']?></h5>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Data de Início:</th>
                                                <td><?=date('d/m/Y', strtotime($feria['data_inicio']))?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Duração (em dias):</th>
                                                <td><?=$feria['duracao']?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Adiantamento 13º:</th>
                                                <td><?=$feria['adiantamento_13']?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Dias Adicionais:</th>
                                                <td><?=$feria['dias_adicionais']?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <button class="btn btn-<?=$cor?>"><?=$status?></button>
                                    
                                </div>
                            </div>

                        <?php 
                            } #foreach

                            }else{ #if ?>

                                <div class="card col-md-5 ms-5 mt-5">
                                <div class="card-body">
                                    <h5 class="card-title">Sem Férias</h5>
                                    
                                </div>
                            </div

                        <?php
                            } 
                        ?>
                        </div>

                    </div>
                        
                    </div>
                </div>
            </div>
        </section>

    </main><!-- FIM DO MENU PRINCIPAL -->

    <!-- ======= RODAPÉ ======= -->
    <?php include "../includes/rodape.php";?>

</body>

</html>