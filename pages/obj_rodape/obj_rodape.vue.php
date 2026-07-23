<?php
   
    $dados = array();
    $dados['info'] = \Sistema\Site::getInfo();
?>

<script>
var app = new Vue({
    el: '#obj_rodape',
    
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
