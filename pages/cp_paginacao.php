<script type="text/x-template" id="cp_paginacao">		
	<div class=" d-flex">
      <div class="container">

        <div class="row no-gutters">
        

          <div class="col-sm-12 col-md-8  d-flex flex-row  " v-if="quantidade.length">

            
            <template  v-if="mostraInicio()">
              
              <div @click="abre(1)" class="item_paginacao" v-bind:class="{'marcado':checaAtivo(1)}">
                1
              </div>
              <div class="item_paginacao_neutro"> ... </div>

            </template>

            <div @click="abre(index+1)" v-for="(item, index) of quantidade" class="item_paginacao" v-if="mostra(index+1)"  v-bind:class="{'marcado':checaAtivo(index+1)}">
              {{index+1}}
            </div>

            <template  v-if="mostraTotal()">

              <div class="item_paginacao_neutro"> ... </div>
              <div  @click="abre(total)" class="item_paginacao" v-bind:class="{'marcado':checaAtivo(total)}">
                {{total}}
              </div>

            </template>

          </div>
        </div>
      </div>

    </div>

</script>

<script>
	Vue.component('cp_paginacao', {
		template: '#cp_paginacao',
		data: function(){
			var vm = this;
			return {
        quantidade:[],
        pagina:0
				
			};
		},
		props: {
      page:0,
			rota:'',
      total:{
        type: Number, // not 'number'
        required: false,
        default: 10
      }
		},
		mounted(){
			this.quantidade = Array.from({ length: this.total }, () => 1)

      this.pagina = this.page + 1;
		},
		methods:{
      mostra(p){


        if(p >= (this.pagina - 5) && p <= (this.pagina + 5) ){
          return true;
        }else{
          return false;
        }

      },
      mostraInicio(){
        console.log('*******>>',(this.pagina - 5))
        if((this.pagina - 5) > 1){
          return true;
        }else{
          return false;
        }
      },
      mostraTotal(){
        if(this.pagina < this.total - 5){
          return true;
        }else{
          return false;
        }
      },
      checaAtivo(p){
        console.log(p,this.pagina)
        if(p == 1 && this.pagina <= 0){
          return true;
        }
        return (p==this.pagina?true:false)

      },
      abre(p){
        let pag = 'ROOT/'+this.rota+'/'+p;
      
        window.location.href = pag;
      }
			
    }
	  // options
	});
</script>

<style>

.item_paginacao{
  padding:10px;
  margin: 10px 5px 10px 5px ; 
  background: #fff;
  border-radius: 4px;
  color: #428bca;
  cursor: pointer;
} 
.item_paginacao_neutro{
   padding:10px;
  margin: 10px 5px 10px 5px ; 
  background: #fff;
  color: #428bca;
}

.item_paginacao:hover,.marcado{
   background: #428bca;
   color: #fff;
 }
</style>
