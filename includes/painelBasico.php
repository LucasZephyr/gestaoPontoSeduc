<div class="col-lg-8"> <!-- inicio coluna a esquerda-->
    <div class="row">


        <div class="col-xxl-4 col-md-12">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Quantidade de batidas por mês</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>

                        <div class="ps-3">
                            <h6>
                                <?php 
                                    #numero de dias do mes atual
                                    $numero_dias = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                                    # 4 batidas por dia vezes o numeros de dias do mes
                                    echo $numero_dias * 4 . ' / ' . $numeroBatidasFeitaEsseMes[0]['quantidade_batidas'];
                                ?>
                                    batidas registradas
                            </h6>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Batidas Atrasadas</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>
                                <?php 
                                    echo $atrasoEntradaUser[0]['qtd_atraso_na_entrada'];
                                ?>
                                Batidas após o horário
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Hora Extra</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>

                            </h6>
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
            <h5 class="card-title">Solicitações dos abonos </span></h5>

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
                            //name: '',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            avoidLabelOverlap: false,
                            label: {
                                show: true,
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
                                show: true
                            },
                            data: [<?=$dadosBasico?>]
                        }]
                    });
                });
            </script>

        </div>
    </div><!-- FIM DASHBOARD -->
    </div><!-- fim coluna a direita -->