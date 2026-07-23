<div id="page_inicial">
  
  


   <div class="row animated fadeInUp faster "  v-if="carregado"  v-cloak>


   	

   		

   		<div class="col-sm-12 col-md-10 order-2 order-md-1 px-sm-0 px-md-1" v-if="dados.tarefas.length || dados.proximas.length">


   				
   			<div class="card mb-1" v-if="dados.tarefas.length">
	            <div class="card-header pb-0 px-3">
	              <h6 class="mb-0">Essa semana</h6>
	            </div>
	            <div class="card-body pt-4 p-3">

	              <ul class="list-group">
	                <li class="list-group-item border-0 p-0 m-0" v-for="item of dados.tarefas">
	                 	<cp_tarefa :item="item" @finaliza="finalizaTarefa"></cp_tarefa>
	                </li>
	              </ul>

	            </div>

	           
	        </div>
	        <div class="card" v-if="dados.proximas.length">
	            

	            <div class="card-header pb-0 px-3">
	              <h6 class="mb-0">Próximas</h6>
	            </div>
	            <div class="card-body pt-4 p-3">


	            	<!-- <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_PBgNop.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player> -->



	              <ul class="list-group">
	                <li class="list-group-item border-0 p-0 m-0" v-for="item of dados.proximas">
	                 	<cp_tarefa :item="item"></cp_tarefa>
	                </li>
	              
	              </ul>


			


	            </div>
	        </div>

   			
   		</div>
   		<div class="col-sm-12 col-md-2   order-1 order-md-2 mb-2" >

   			<div class="card  animated fadeInUp fast" v-if="dados.cronogramas == 0 && dados.projetos.length">
	           
	            <div class="card-body pt-4 p-3">

	            	<div class="text-center" >


		   				<p class="fs-6" >Já conhece nossa ferramenta para gerenciar seus cronogramas?</p>
		   				
			   			<lottie-player class="d-inline-block" src="ROOT/images/lottie/plan.json"  background="transparent"  speed="1"  style="width: 100%; height: 300px;"  loop  autoplay></lottie-player>

		   				<small >Vamos começar?</small>

			   			<p class="mt-3">
			   				<a href="ROOT/adm-home?item=29&new=1" class="btn btn-info"><i class="fas fa-plus"></i> Criar  cronograma</a>

			   			</p>
			   		</div>
	             	
	            </div>
	         </div>
	         <div class="card  animated fadeInUp fast" v-if="dados.cronogramas > 0 && dados.projetos.length">
	           
	            <div class="card-body pt-4 p-3">

	            	<div class="text-center" >


		   				
		   				
			   			<lottie-player class="d-inline-block" src="ROOT/images/lottie/plan.json"  background="transparent"  speed="1"  style="width: 100%; height: 300px;"  loop  autoplay></lottie-player>

		   				<small>Crie cronogramas para cada etapa de seus projetos.</small>

			   			<p class="mt-3">
			   				<a href="ROOT/adm-home?item=29&new=1" class="btn btn-info"><i class="fas fa-plus"></i> Criar  cronograma</a>

			   			</p>
			   		</div>
	             	
	            </div>
	         </div>

   			
   		</div>

   </div>

</div>
