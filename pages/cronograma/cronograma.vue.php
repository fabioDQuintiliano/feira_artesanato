<?php
    loadObj('cp_item_cronograma');

    $id = $url[1];

    if(!$id){
        myHeader("location:".ROOT);
    }


    $dadosCronograma = \Sistema\Cronograma::get($id);
 //   var_dump($dadosCronograma);


    if(!$dadosCronograma['cronograma']){
        myHeader("location:".ROOT);
    }
    $dados = $dadosCronograma;
   
    // $dados['tarefas'] = \Sistema\Tarefas::get(false,'hoje');
    // $dados['proximas'] = \Sistema\Tarefas::get(false,'proximas');
    // $dados['projetos'] = \Sistema\Projetos::getProjetosRescentes();

    //var_dump($dados);
?>

<script>
var app = new Vue({
    el: '#page_cronograma',
    
    data: {
       
        data: new Date(),
        diasExibe: 90,

        datas:[],
        
        tarefas:<?=json_encode($dados['tarefas'])?>,
        dividerPosition:50,
        dados: <?=json_encode($dados)?>,
        arrastando:false,
        linhas:[],
        timeout:null,
        salvo:false,
        pode_salvar:true
      

    },
    mounted: function () {
       
        $('body').addClass('body-hide-nav');



        if(this.dados.tarefas.length){
           // this.tarefas = this.dados.tarefas;
        }else{
            this.addNewTask();
        }
        /*  
      
       this.tarefas.push({
        id:1,
        nome:'Iniciar cronograma',
        inicio:'2022-09-26',
        termino:'2022-10-30'
       })
       this.tarefas.push({
        id:2,
        nome:'Avaliar o layout',
        inicio:'2022-10-26',
        termino:'2022-10-26',
       });
       this.tarefas.push({
        id:3,
        pai:1,
        nome:'Entrega final',
        inicio:'2022-10-28',
        termino:'2022-10-28',
       });*/


        this.tarefas.map((t,i)=>{
            let d = this.sqlToDate(t.inicio);
            if(d.getTime() < this.data.getTime()){
                this.data = d;
            }
        })
        this.data = this.addDias(this.data,-2);


        this.geraDiasList();
        this.setTaskDias();
        this.ajustaInicioTarefasFilhos();

        window.addEventListener("resize", this.atualizaTodosPontos);
        window.addEventListener("scroll", this.atualizaTodosPontos);
        document.getElementById('scrollArea').addEventListener("scroll", this.atualizaTodosPontos);

        setTimeout(()=>{

        this.scroll_hoje()
        },200)

        
    },
    methods:{
        clone(a){
            return JSON.parse(JSON.stringify(a));
        },
        resetOrder(){
            let linha = 1;
            this.tarefas.map((item,key)=>{

                if(item.id != linha){
                    this.tarefas.map((item2,key2)=>{
                        if(item2.pai == item.id){
                            item2.pai = linha;
                        }
                    })
                    if(!item.oldkey){
                        item.oldkey = this.clone(item.id);
                    }
                    item.id = linha;
                    if(item.pai == linha){
                        item.pai = '';
                    }

                }
                linha++;
            })
        },
        removeItem(item){
            this.tarefas.map((i,key)=>{
                if(item.id == i.id){

                    this.tarefas.map((item2,key2)=>{
                        if(item2.pai == item.id){
                            item2.pai = '';
                        }
                    })

                    if(this.linhas[item.id] && this.linhas[item.id].length){
                        this.linhas[item.id].map((l,j)=>{
                            console.log(l,j)
                            if(this.linhas[item.id][j]){

                                this.linhas[item.id][j].remove();
                                this.linhas[item.id][j] = null;
                            }
                        })

                    }

                    if(this.linhas && this.linhas.length){
                        this.linhas.map((l,j)=>{
                            if(this.linhas[j][item.id]){
                                this.linhas[j][item.id].remove()
                                this.linhas[j][item.id] = null;
                            }
                        })
                    }


                    this.tarefas.splice(key,1);
                    this.resetOrder();
                }
            })
            this.geraDiasList();
            this.setTaskDias();


        },
        salvar(hideLoad=false){

            if(this.pode_salvar){

               // this.pode_salvar = false;

                if(this.timeout){
                    clearTimeout(this.timeout);
                    this.timeout = null;
                }
                this.timeout = setTimeout(()=>{
                    if(!hideLoad){
                        loadShow();
                    }
                    ajax_load_class("\\Sistema\\Cronograma","salvar",{cronograma:this.dados.cronograma.txtid,tarefas:this.tarefas}).then((o)=>{
                        this.salvo = true;
                        this.tem_que_salvar = false;
                        this.pode_salvar = true;

                        setTimeout(()=>{
                            this.salvo = false;
                        },2000)
                        loadHide();
                    },(e)=>{
                        loadHide();
                    })
                },800)
            }

        },
        addNewTask(){

            let evento = {
                id:this.tarefas.length+1,
                nome:'',
                inicio:this.dateToSql(new Date()),
                termino:this.dateToSql(new Date()),
            }
            //console.log(evento)
            this.tarefas.push(evento);
            this.geraDiasList();
            this.setTaskDias();

        },
        atualizaTodosPontos(){
            this.linhas.map((linha,k)=>{
                linha.map((line)=>{
                    if(line)
                    line.position();
                })
            })

        },
        geraDiasList(){
            let lista = [];
            for(let i=0;i<=this.diasExibe;i++){
                lista.push({
                    data:this.addDias(this.data,i),
                    tarefas:[]
                })
            }   
            this.datas = lista;
            //console.log(this.datas);

        },

        initConeccao(item){
           // console.log(item,this.linhas);
            let tem = this.tarefas.filter((it,kt)=>{
                if(it.id == item.pai){
                    return true;
                }else{
                    return false;
                }
            })

            if(tem.length == 0){
                item.pai = '';
            }

            if(item.pai == item.id){
                item.pai = '';
            }

            if(item.pai != item.id){
                this.linhas.map((i,j)=>{ 
                    
                    i.map((v,k)=>{
                        if(k == item.id){
                            if(this.linhas[j][k]){
                                this.linhas[j][k].remove();
                                 this.linhas[j][k] = null
                            }
                        }
                    })
                   
                })
                this.ajustaInicioTarefasFilhos();
                

                    this.conecta(item.pai, item.id);
                
            }else{
                item.pai='';
            }
        },
        conecta(um,dois){
          //  console.log('++++',um,dois,this.linhas)
            if(document.getElementById('taskidr'+um)){


            if(!this.linhas[um]){
                 this.linhas[um] = []    
            }
            if(this.linhas[um] && this.linhas[um][dois]){
                this.linhas[um][dois].remove()
                this.linhas[um][dois] = null
            }else{
               
            }

            this.linhas[um][dois] = new LeaderLine(
              document.getElementById('taskidr'+um),
              document.getElementById('taskidl'+dois),
              {color: '#aaa', size: 3,startPlug:'disc',endPlug:'disc',startSocketGravity:40, endSocketGravity:40}
            );
            }

        },
        conectaTarefas(){
            if(this.tarefas.length){
                this.tarefas.map((item,k)=>{
                    if(item.pai){
                        this.conecta(item.pai,item.id);
                    }

                })
            }
        },
        setTaskDias(){
            this.datas.map((item,key)=>{
                this.tarefas.map((task,chave)=>{

                 //       console.log('++++++++++',item.data ,this.sqlToDate(task.data))
                    if(item.data.getTime() == this.sqlToDate(task.inicio).getTime()){
                        let dias = dateDif.dateDiff(task.inicio, task.termino);
                        dias = (dias*1);
                        task.dias = dias;
                        item.tarefas.push(task);
                    }

                })

            })
           // this.conectaTarefas();
            setTimeout(()=>{
                this.conectaTarefas();
            },100)
            setTimeout(()=>{
                this.conectaTarefas();
            },500)

           // console.log(this.datas);
        },
        endDrag(i,j){
            //tarefa foi arrastada
            this.arrastando = false;
            this.datas.map((d,k)=>{
                d.tarefas.map((item,index)=>{
                    let inicio = d.data;
                    let termino = this.addDias(inicio,item.dias);
                    item.inicio = this.dateToSql(inicio);
                    item.termino = this.dateToSql(termino);
                })

            })

            this.ajustaInicioTarefasFilhos();
            this.conectaTarefas();
           
        },
        atualizaItem(item){
            //console.log('Atualizando',item)
            let inicio=this.sqlToDate(item.inicio);
        
            let dias = item.dias*1;
            let novaDataFinal = this.addDias(inicio,dias);
            novaDataFinal = this.dateToSql(novaDataFinal);
          //  console.log(novaDataFinal);
            item.termino = novaDataFinal;


            this.tarefas.map((i,chave)=>{
                if(item.id == i.id){
                    i=item;
                }
            })
            

            this.ajustaInicioTarefasFilhos();
            this.geraDiasList();
            this.setTaskDias();
            //this.conectaTarefas();
           // console.log(this.tarefas);
        },
        ajustaInicioTarefasFilhos(){

            this.tarefas.map((item,key)=>{
                this.tarefas.map((i,chave)=>{
                    if(item.id == i.pai){
                        let fimPai = this.sqlToDate(item.termino)
                        let iniFilho = this.sqlToDate(i.inicio)
                        if(fimPai.getTime()  >= iniFilho.getTime()){
                            
                            let ini = this.addDias(fimPai,1);
                            i.inicio = this.dateToSql(ini);
                            this.atualizaItem(i);
                        }
                    }
                })
            })
        },
        isFimSemana(data){
            let d = data.getDay();
            if(d == 0 || d == 6){
                return true;
            }else{
                return false;
            }
        },
        diaSemana(data){
            var semana = ["DOM.", "SEG.", "TER.", "QUA.", "QUI.", "SEX.", "SÁB."];
            return semana[data.getDay()];
        },
        dia(data){
           
            return data.getDate();
        },
        mes(data){
            var meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago","Set","Out","Nov","Dez"];
            
            return meses[data.getMonth()];
        },
        ano(data){
           
            return data.getFullYear();
        },
        sqlToDate(d){
            var dateParts = d.split("-");
            var jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2));
            return jsDate;
        },
        isHoje(data){
            if(this.dateToSql(data) == this.dateToSql(new Date())){
                return true;
            }else{
                return false;
            }
        },
        dateToSql(d){

            var date = [
                d.getFullYear(),
                ('00' + (d.getMonth()*1+1)).slice(-2),
                ('00' + (d.getDate()*1)).slice(-2)
            ].join('-');

          //  console.log(date,d.getFullYear(),d.getMonth(),d.getDate());
            return date;
           
        },
        addDias(date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);

            let noTime = new Date(result.getFullYear(), result.getMonth(), result.getDate());

            return noTime;
        },
        startDrag(){
           // console.log('START');
            this.arrastando = true;
        },
        scroll_hoje() {
          const el = this.$refs.hoje;
         // console.log(el)
          if(el && el[0]){
            el[0].scrollIntoView({behavior: 'smooth',inline:'center'});
          }
        }
      

    },
    watch: {
        tarefas: {
            handler: function (after, before) {
                //console.log('alterou tarefa AFTER',after.length)
                //console.log('alterou tarefa BEFORE  ',before.length)
                this.tem_que_salvar = true;
                this.salvar(true)
            },
            deep: true,
            immediate: false 
        }
    }
})

</script>
