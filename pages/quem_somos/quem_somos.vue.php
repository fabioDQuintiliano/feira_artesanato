<?php
  
    $dados = \Sistema\Equipe::get();
    $texto = getTextoHome(5,true);
  

   

?>

<script>
var app = new Vue({
    el: '#page_quem_somos',
    
    data: {
       
        dados: <?=json_encode($dados)?>,
        conteudo: `<?=$texto?>`,
     
      

    },
    mounted: function () {
       

        
    
    
    },
    methods:{
      
        apenas_numeros(string){
            var numsStr = string.replace(/[^0-9]/g,'');
            return parseInt(numsStr);
        }
    }
})
</script>
