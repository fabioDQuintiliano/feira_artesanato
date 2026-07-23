<script type="text/x-template" id="cp_projeto">		
	<div class="cp-projeto-item border-0 d-flex align-items-center px-0 mb-2 flex-grow-1">
	  	<div class="cp-logo-projeto avatar me-1">
	    	<img v-if="item.imagem" :src="item.imagem" alt="kal" class="border-radius-lg ">
	    	<img v-else src="ROOT/images/no-image.png" alt="kal" class="border-radius-lg ">
	  	</div>
	    <div class="d-flex align-items-start flex-row justify-content-center flex-grow-1  text-truncate">
	    	<div class="mb-0 text-xs flex-grow-1  text-truncate">{{item.nome}}</div>


			<a @click="addItem" data-bs-toggle="tooltip" data-bs-placement="top" title="Nova Tarefa" class="btn btn-link text-info text-gradient p-0 px-3 mb-0" href="javascript:;"><i class="fas fa-plus  me-2" aria-hidden="true"></i></a>
	  	</div>
	    
	</div>



</script>

<script>
	Vue.component('cp_projeto', {
		template: '#cp_projeto',
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
			addItem(){
				let url = 'ROOT/adm-home?item=27&new=1&projeto='+this.item.id;
				window.location.href = url;

			}

			
    	}
	  // options
	});
</script>

<style>
.cp-projeto-item{

}
.cp-logo-projeto img{
	border-radius: 15px;
	border:solid 1px #ccc;
}
.cp-logo-projeto{
	width: 25px;
	height: 25px;
	

}
</style>
