<?php
   
    $dados = array();
    $dados = \Sistema\Site::getDepoimentos();

   
?>

<script>
var app = new Vue({
    el: '#obj_depoimentos',
    
    data: {
       
        dados: <?=json_encode($dados)?>
      

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
