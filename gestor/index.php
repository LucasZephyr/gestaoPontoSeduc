<?php
$tempo = 7200; #duas horas
session_set_cookie_params($tempo);
session_start();
if(!$_SESSION['usuario']['logado'] == 'sim'){
    header("Location: login.php");
}

require '../classes/sql.class.php';
$sql = new SQL();



$grafico1 = $sql->distribuicaoUsuarios();
$usuarios_ativos = $grafico1[0]['usuarios_ativos'];
$usuarios_inativos = $grafico1[0]['usuarios_inativos'];


$totalFeriasSolic = $sql->totalFeriasSolic();

$dados = '{"value": ' . $usuarios_ativos . ', "name": "Usuários Ativos"}, {"value": ' . $usuarios_inativos . ', "name": "Usuários Inativos"}';


$registros_ponto = $sql->totalRegistrosPonto();
$abonos_pendentes = $sql->totalAbonosPendentes();
$atrasoEntrada = $sql->atrasoEntrada();
#echo '<pre>';print_r($atrasoEntrada);echo '</pre>';exit;

#echo "<pre>";print_r($_SESSION);exit


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Gestor</title>
    
    <?php include '../includes/cabecalho.php'?>
</head>

<body>

    <?php include '../includes/navBar.php'?>
    <?php include '../includes/menuLateral.php'?>


    <!-- Inicio menu Principal-->
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Gr&aacute;ficos</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">inicio</a></li>
                    <!-- <li class="breadcrumb-item active">Dashboard</li> -->
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8"> <!-- inicio coluna a esquerda-->
                    <div class="row">

                        <!-- Início do Painel de Registros de Ponto -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total de Registros de Ponto</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-up-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $registros_ponto[0]['total_registros_ponto']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim do Painel de Registros de Ponto -->

                        <!-- Início do Painel de Abonos Pendentes -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Solicitações de Abonos Pendentes</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-up-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $abonos_pendentes[0]['total_abonos_pendentes']; ?></h6>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim do Painel de Abonos Pendentes -->

                        <!-- Inicio do Painel de Informações dos Usuários -->
                        <div class="col-xxl-4 col-xl-12">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Usu&aacute;rios Atrasados</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>Total de Usu&aacute;rios: <?php echo $atrasoEntrada[0]['qtd_atraso_na_entrada']; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim do Painel de Informaca dos usuarios -->


                        <!-- Inicio do Painel de Ferias Solicitadas -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Número de Ferias Solicitadas</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-arrow-up-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?=$totalFeriasSolic[0]['ferias']?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>







                    </div>
                </div><!-- fim coluna a esquerda -->

                <!-- inicio coluna a direita-->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="card-title"> Rela&ccedil;&atilde;o de usu&aacute;rios ativos e inativos </span></h5>

                            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#trafficChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'nome editavel aqui',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [<?=$dados?>]
                                        }]
                                    });
                                });
                            </script>

                        </div>
                    </div><!-- FIM DASHBOARD -->
                </div><!-- fim coluna a direita -->

            </div>
        </section>

    </main><!-- FIM DO MENU PRINCIPAL -->

    <!-- ======= RODAPÉ ======= -->
    <?php include "../includes/rodape.php";?>

</body>

</html>

 <?php if($_SESSION['usuario']['primeiro_acesso'] == '1'){?>
    <div class="modal" tabindex="-1" id="modalPrimeiroAcesso" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bem-Vindo ao seu primeiro acesso: Atualize seus dados</h5>
            </div>
            <div class="modal-body">
                <form id="formPrimeiroAcesso" class="row">
                    <div class="col-md-6">
                        <label for="" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?=$_SESSION['usuario']['nome']?>">
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">E-MAIL</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?=$_SESSION['usuario']['email']?>">
                    </div>

                    <div class="col-md-4">
                        <label for="" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="13" value="<?=$_SESSION['usuario']['telefone']?>">
                    </div>

                    <div class="col-md-4">
                        <label for="validationCustom02" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="<?=$_SESSION['usuario']['cpf']?>">
                    </div>

                    <div class="col-md-4">
                        <label for="" class="form-label">Sua nova senha</label>
                        <input type="text" class="form-control" id="senha" name="senha">
                    </div>

                    <div class="col-md-12">
                        <p id="senhaMSG"></p>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="atualizarDadosPrimeiroAcesso">Atualizar</button>
            </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    $(document).ready(function() {

        $("#modalPrimeiroAcesso").modal('show');

        $('#modalPrimeiroAcesso').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#modalPrimeiroAcesso').on('hide.bs.modal', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#modalPrimeiroAcesso').hasClass('show')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });

        $('#modalPrimeiroAcesso .modal-content').on('click', function(e) {
            e.stopPropagation();
        });

        $('#atualizarDadosPrimeiroAcesso').click(function() {

            var nome = $('#nome').val();
            var cpf = $('#cpf').val();
            var email = $('#email').val();
            var senha = $('#senha').val();
            var telefone = $('#telefone').val();

            if (nome.trim() === '' || cpf.trim() === '' || email.trim() === '' || senha.trim() === '' || telefone.trim() === '') {
                Swal.fire({ text: "Por favor, preencha todos os campos", icon: "info" });

            } else {

                //INICIO DAS VALIDAÇÕES
                var senha = $('#senha').val();
                var senhaMSG = $('#senhaMSG');
                
                if (senha.length < 10) {
                    console.log('dsa');
                    senhaMSG.html('<p style="color: red">A senha deve ter pelo menos 10 caracteres.</p>');
                    return false;
                }


                if (!/[A-Z]/.test(senha)) {
                    senhaMSG.html('<p style="color: red">A senha deve ter pelo menos 1 letra maiúscula.</p>');
                    return false;
                }

                if (!/[a-z]/.test(senha)) {
                    senhaMSG.html('<p style="color: red">A senha deve ter pelo menos 1 letra minúscula.</p>');
                    return false;
                }

                if (!/[0-9]/.test(senha)) {
                    senhaMSG.html('<p style="color: red">A senha deve ter pelo menos 1 número.</p>');
                    return false;
                }
            

                senhaMSG.text('');
                //FIM DAS VALIDACOES

                let formularioPrimeiroAcesso = document.getElementById("formPrimeiroAcesso");
                let formDataPrimeiroAcesso = new FormData(formularioPrimeiroAcesso);

                $.ajax({
                    url: "ajax/alterarDadosPrimeiroAcesso.php",
                    data: formDataPrimeiroAcesso,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    type: "POST",
                    success: function(resp){
                        
                        if(resp.informacao == "SUCESSO"){
                            Swal.fire({
                                icon: 'success',
                                title: 'Atualizado!',
                                text: 'Seus dados estão atualizados.'
                            });

                            location.reload();


                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops..',
                                text: 'Erro ao atualizar dados'
                            }); 
                        
                        
                        }

                    },
                    error: function(){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops..',
                            text: 'Erro na Requisação'
                        });

                        return false;
                    }              
                });



            }
        });








    });
</script>