<?php

    $dados = array();
  
?>

<script>
var app = new Vue({
    el: '#page_home',
    
    data: {
       
        dados: <?=json_encode($dados)?>,
        carregado:false
      

    },
    mounted: function () {
        setTimeout(()=>{
            this.carregado = true;
        },500)
    },
    methods:{
        

    }
})
</script>
