<?php
    loadObj('cp_projeto');
    $dados = array();
    $dados = \Sistema\Projetos::getProjetos(0,6);
 
?>

<script>
var app = new Vue({
    el: '#obj_portfolio',
    
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
