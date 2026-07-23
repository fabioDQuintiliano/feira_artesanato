<?php
    loadObj('cp_projeto');   
    loadObj('cp_paginacao');   
   // $page = intval($url[1]);
    $page = intval($url[1])-1;
    $dados = \Sistema\Projetos::getProjetosPage($page);
    $total = \Sistema\Projetos::paginacao();

?>

<script>
var app = new Vue({
    el: '#page_blog',
    
    data: {
       
        dados: <?=json_encode($dados)?>,
        total: <?=$total?>,
        page: <?=$page?>
      

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
