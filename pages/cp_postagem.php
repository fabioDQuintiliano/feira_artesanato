<script type="text/x-template" id="cp_postagem">		
	<div class="postagem d-flex" data-aos="fade-up">
      <div class="container">

        <div class="row no-gutters">
          <div class="col-sm-12 col-md-4 video-box">
            <img :src="item.imagem" class="img-fluid" alt="">
          
          </div>

          <div class="col-sm-12 col-md-8  d-flex flex-column about-content">

        
            <div >
              
              <span class="data-postagem">{{item.data}}</span>
              <h5 class="title"><a :href="'ROOT/p/'+item.url">{{item.titulo}}</a></h5>
              <div v-if="item.tempo_leitura"><span class="tempo_leitura">Tempo de leitura {{item.tempo_leitura}} minutos</span></div>
              <p class="description" v-html="item.previa"></p>

              <div class="text-right">
                <a :href="'ROOT/p/'+item.url">Continuar lendo...</a>
              </div>
            </div>


          </div>
        </div>
      </div>

    </div>

</script>

<script>
	Vue.component('cp_postagem', {
		template: '#cp_postagem',
		data: function(){
			var vm = this;
			return {
				
			};
		},
		props: {
			item:{}
		},
		mounted(){
			
		},
		methods:{

			
    	}
	  // options
	});
</script>

<style>


</style>
