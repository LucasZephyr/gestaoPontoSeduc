function aprovar(botao){

	let dataId = botao.getAttribute('data-idAbono');

	let dados = {
		"id_abono": dataId,
        "aprovar": "true"
	}

	$.ajax({
        url: "ajax/aprovarAbono.php",
        data: dados,              
        cache: false,    
        dataType: "json",
        type: "POST",
        success: function(resp){
            
            if(resp.informacao == "SUCESSO"){
                Swal.fire({
                  title: 'SUCESSO',
                  text: 'Abono Aprovado!',
                  icon: 'success'
                });

                $("#card"+dataId).remove();
      
              }else{
      
                Swal.fire({
                  title: resp.informacao,
                  text: resp.text,
                  icon: 'error'
                });
      
              }
              
        },
        error: function(){
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'erro no processamento!',
                showConfirmButton: true
            });
        }              
    });
	
}



function reprovar(botao){

	let dataId = botao.getAttribute('data-idAbono');

	let dados = {
		"id_abono": dataId,
        "reprovar": "true"
	}

	$.ajax({
        url: "ajax/reprovarAbono.php",
        data: dados,              
        cache: false,    
        dataType: "json",
        type: "POST",
        success: function(resp){

            if(resp.informacao == "SUCESSO"){
                Swal.fire({
                  title: 'SUCESSO',
                  text: 'Abono Reprovado!',
                  icon: 'success'
                });

                $("#card"+dataId).remove();
      
              }else{
      
                Swal.fire({
                  title: resp.informacao,
                  text: resp.text,
                  icon: 'error'
                });
      
              }
            
        },
        error: function(){
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'erro no processamento!',
                showConfirmButton: true
            });
        }              
    });
	
}
