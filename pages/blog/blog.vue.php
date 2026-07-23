<?php
    loadObj('cp_postagem');   
    loadObj('cp_paginacao');   
    $page = intval($url[1])-1;
    $dados = \Sistema\Blog::getPostsPage($page);
    $total = \Sistema\Blog::paginacao();



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
