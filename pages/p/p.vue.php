<?php
    loadObj('cp_postagem');   
    loadObj('cp_compartilhar');   
    $url_postagem = $url[1];
    $postagem = \Sistema\Blog::getPostagem($url_postagem);
    $dados['post'] = $postagem;

    $relacionados = false;
    if($postagem && $postagem['id']){
        $relacionados = \Sistema\Blog::getPostsRelacionados($postagem['id']);
    }else{
        header("location:".ROOT.'404');
    }
    $dados['relacionados'] = $relacionados;
   

?>

<script>
var app = new Vue({
    el: '#page_postagem',
    
    data: {
       
        dados: <?=json_encode($dados)?>
      

    },
    mounted: function () {
       
    },
    methods:{
      

    }
})
</script>
