<?php

    $id = $url[1];

    if(!$id){
        myHeader("location:".ROOT);
    }

  
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
            loadShow();
            ajax_load_class("\\Sistema\\Cronograma","getCronograma",{cronograma:this.code}).then((o)=>{
                this.dados = o;
                loadHide();
            },(e)=>{
                loadHide();
            })
        },
        compartilhar(){
            loadShow();
            ajax_load_class("\\Sistema\\Cronograma","setCompartilhamento",{cronograma:this.code}).then((o)=>{
                console.log(o);
                this.dados.publico = o.publico?true:false;
                setTimeout(()=>{

                    loadHide();
                },500)
            },(e)=>{
                loadHide();
            })
        }
      

    }
})
</script>
