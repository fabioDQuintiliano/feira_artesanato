<script type="text/x-template" id="cp_tarefa">	
<div class="d-flex p-1 mb-1 bg-gray-100 border-radius-lg" v-if="!hide">	
	<div class="main-cp-tarefa " >
		<!-- 
	     <div class="d-flex flex-column">
		    <span class="mb-3 text-sm">{{item.titulo}}</span>
		    <span v-if="mostra_detalhes" class="mb-2 text-xs animated fadeIn descricao-tarefa" v-html="item.descricao"></span>

		</div> -->




	    <div class="d-flex flex-row">

			<div class=" border-0 d-flex align-items-center px-0 mb-2 flex-grow-1">

			  <div class="logo-projeto avatar me-1">
			    <img v-if="item.projeto.imagem" :src="item.projeto.imagem" alt="kal" class="border-radius-lg ">
			    <img v-else src="images/no-image.png" alt="kal" class="border-radius-lg ">
			  </div>
			  <div class="d-flex align-items-start flex-column justify-content-center">
			    <span class="mb-0 text-xs">{{item.projeto.nome}}</span>
			  </div>
			    
			</div>


		</div>
	    <div class="d-flex flex-row">

			<span class="badge badge-sm me-2" v-bind:class="{
				'bg-info':item.status == 'em_tempo',
				'bg-danger':item.status == 'atrasado',
				'bg-warning':item.status == 'alerta',
			}" v-if="item.data_final && item.data_final != '00/00/0000'">{{item.data_final}}</span>

			<span class="badge badge-sm bg-danger me-2" v-if="item.status == 'atrasado'">Atrasado</span>
			<span class="badge badge-sm bg-warning me-2" v-if="item.status == 'alerta'">Prazo se esgotando</span>
			<a v-if="item.cronograma" :href="'ROOT/adm-cronograma/'+item.cronograma.code"><span class="badge badge-sm bg-secondary" ><i class="fas fa-project-diagram"></i> {{item.cronograma.nome}}</span></a>
		
		</div>

		<div class="d-flex flex-row">

			<div class="d-flex flex-column">
			    <span class="mb-3 text-sm">{{item.titulo}}</span>
			</div>

		


			<div class="ms-auto text-end text-nowrap ">


				<template v-if="item.descricao">
				    <a v-if="!mostra_detalhes" class="btn btn-link text-info text-gradient p-0 px-1 mb-0" href="javascript:;" @click="mostra_detalhes = true"><i class="fas fa-eye me-1"></i>Mais</a>
				    <a v-if="mostra_detalhes"class="btn btn-link text-info text-gradient p-0 px-1 mb-0" href="javascript:;" @click="mostra_detalhes = false"><i class="fas fas fa-eye-slash me-1"></i>Menos</a>
				</template>


			    
			    <a @click="finalizar" class="btn btn-link text-info text-gradient p-0 px-1 mb-0" href="javascript:;"><i class="fas fa-check  me-1" aria-hidden="true"></i>Finalizar</a>

			    <a @click="editar" class="btn btn-link text-info text-gradient p-0 px-1 mb-0" href="javascript:;"><i class="fas fa-pencil-alt  me-1" aria-hidden="true"></i>Editar</a>



			</div>
		</div>

		<span v-if="mostra_detalhes" class="mb-2 text-xs animated fadeIn descricao-tarefa" v-html="item.descricao"></span>
	</div>
</div>



</script>

<script>
	Vue.component('cp_tarefa', {
		template: '#cp_tarefa',
		data: function(){
			var vm = this;
			return {
				mostra_detalhes:false,
				hide:false
				
			};
		},
		props: {
			item:{}
		},
		mounted(){
			//toast('teste')
		},
		methods:{
			abreItem(item){
				let url = 'ROOT/projeto/'+item.id+'/'+item.url;
				//window.location.href = url;

			},
			editar(){
				let url = 'adm-home?item=27&edit='+this.item.id+'&fromtask=1';
				window.location.href = url;

			},
			async finalizar(){
				
				try{
					let del = await confirma('Finalizar essa tarefa?');
					loadShow();

					let o = await ajax_load_class('\\Sistema\\Tarefas','finalizaTarefa',{p1:this.item.id});
					console.log(o)
					loadHide();

					if(o){
						toast('Tarefa finalizada.');
						this.hide = true;
						this.$emit('finaliza', this.item);
					}
				}catch(e){
					loadHide();
					console.log(e)
				}
			}

			
    	}
	  // options
	});
</script>

<style>
.logo-projeto img{
	border-radius: 15px;
}
.logo-projeto{
	width: 15px;
	height: 15px;

}
.main-cp-tarefa{
	width: 100%;
}
.descricao-tarefa *{
	font-size: 0.75rem !important;
}
.portfolio-item {
	cursor: pointer;
}
</style>
