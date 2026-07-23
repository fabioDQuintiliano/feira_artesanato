<?php
    $banners = \Sistema\Site::getBanners();
    $dados = array();
    $dados['banners'] = $banners;
?>

<script>
var app = new Vue({
    el: '#obj_banner_principal',
    
    data: {
       
        dados: <?=json_encode($dados)?>,
        total:0

    },
    mounted: function () {
        this.total = this.dados.banners.length;

      
    
    
    },
    methods:{
      
    
    }
})
</script>
