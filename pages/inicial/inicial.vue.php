<?php
   
 //   loadObj('cp_tarefa');
 //   loadObj('cp_projeto');
    $dados = array();
   

    $dados['tarefas'] = [];
    $dados['proximas'] = [];
    $dados['projetos'] = [];

    //var_dump($dados);
?>

<script>
var app = new Vue({
    el: '#page_inicial',
    
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
        finalizaTarefa(tarefa){

        
            this.dados.tarefas.map((item,key)=>{
                if(item.id == tarefa.id){
                    this.dados.tarefas.splice(key,1)
                }
            })

            this.dados.proximas.map((item,key)=>{
                if(item.id == tarefa.id){
                    this.dados.tarefas.splice(key,1)
                }
            })        

            this.$forceUpdate();
        }

    }
})
</script>
