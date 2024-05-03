<?php
class date{

    function converterDataPadraoBrasileiro($padraoAmericano){
        $timestamp = strtotime($padraoAmericano);
        return date('d/m/Y', $timestamp);
    }

}
?>