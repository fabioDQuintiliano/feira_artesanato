<?php
    loadObj('cp_projeto');   
    loadObj('cp_compartilhar');   
    $id_projeto = $url[1];
    $postagem = \Sistema\Projetos::getProjeto($id_projeto);
   
    $dados['projeto'] = $postagem;
    $dados['relacionados'] = \Sistema\Projetos::getProjetosRelacionados($postagem['id'],$postagem['categoria']['id']);
   

?>

<script>
var app = new Vue({
    el: '#page_projeto',
    
    data: {
       
        dados: <?=json_encode($dados)?>
      

    },
    mounted: function () {
       
    },
    methods:{
      

    }
})
</script>
