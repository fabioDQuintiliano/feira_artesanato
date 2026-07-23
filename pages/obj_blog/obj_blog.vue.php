

<?php
    loadObj('cp_postagem');   
    $dados = array();
    $dados = \Sistema\Blog::getPostagens(0,2);
 
?>

<script>
var app = new Vue({
    el: '#blog',
    
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
