<?php
loadObj('cp_projeto');
?>
<script type="text/x-template" id="cp_lista_projetos">	
	<ul class="list-group">
        <li class="list-group-item border-0 p-0 m-0" v-for="item of projetos">
         	<cp_projeto :item="item"></cp_projeto>
        </li>
  
  	</ul> 


</script>

<script>
	Vue.component('cp_lista_projetos', {
		template: '#cp_lista_projetos',
		data: function(){
			var vm = this;
			return {
				projetos:[],
				hide:false
				
			};
		},
		props: {
			item:{}
		},
		mounted(){
			this.init();
		},
		methods:{

			init(){
				ajax_load_class("\\Sistema\\Projetos","getProjetosRescentes",{}).then((o)=>{
                        this.projetos = o;
                },(e)=>{
                    
                })
			},



			
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
