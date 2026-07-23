<?php

    $id = $url[1];

    if(!$id){
        myHeader("location:".ROOT);
    }


    $dadosCronograma = \Sistema\Cronograma::getShared($id);
    var_dump($dadosCronograma);


    if(!$dadosCronograma['cronograma']){
        myHeader("location:".ROOT);
    }
    $dados = $dadosCronograma;

  
?>

<script>
var app = new Vue({
    el: '#page_compartilhar',
    
    data: {
       
        code: "<?=addslashes($id)?>",
        carregado:false,
        dados:{}
      

    },
    mounted: function () {

        this.init();
       
        
    },
    methods:{
        init(){
          
        },
        compartilhar(){
            
            ajax_load_class("\\Sistema\\Cronograma","setCompartilhamento",{cronograma:this.code}).then((o)=>{
                console.log(o);
                this.dados.publico = o.publico?true:false;
                
            },(e)=>{
               
            })
        }
      

    }
})
</script>
