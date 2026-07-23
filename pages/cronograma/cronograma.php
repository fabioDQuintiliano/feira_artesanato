<div id="page_cronograma">
  
	<div class="item-salvo animated fadeIn" v-if="salvo">Informações salvas</div>

   <div class="row pb-5">


   		<div class="col-sm-2 task-side">

   			<div class="h-padrao item-task d-flex align-items-center justify-content-end pe-3" v-for="(tarefa,key) of tarefas">	
   				{{tarefa.id}}. 

   				<div class="container-imput">
               	<input type="text" v-model="tarefa.nome" class="ggWidth input-tarefa input-tarefa-titulo" />
            	</div>
               <i v-tooltip="{ content: 'Vincular tarefa' }" class="fas fa-link"></i><input type="text" v-model="tarefa.pai" @blur="initConeccao(tarefa)" class=" input-tarefa pai " />


               <i class="fas fa-trash-alt item-remove" @click="removeItem(tarefa)"></i>
   			</div>

   			<div class="mt-4">
   				<a @click="addNewTask()">
   					<i class="fas fa-plus"></i> Nova tarefa
   				</a>
   			</div>	


   			<div class="mt-4">
   				<a @click="salvar()">
   					<i class="fas fa-save"></i> Salvar alterações
   				</a>
   			</div>





   		</div>
   		<div class="col-sm-10 " id="scrollArea">

   			<div class="item-linha d-flex" >
	   			<div class="text-center item-day item-cro-header flex-column" v-for="(item,index) of datas" :ref="isHoje(item.data)?'hoje':''" v-bind:class="{'hoje':isHoje(item.data),'fim_semana':isFimSemana(item.data)}">
                  
	   				<div class="text-xxs">{{diaSemana(item.data)}}</div>
	   				<div>
	   					<h5 class="mb-0 pb-0">{{dia(item.data)}}</h5>
	   					<div class="text-xxs item-mes-ano">{{mes(item.data)}} {{ano(item.data)}}</div>
	   				</div>


	   			</div>


   			</div>

   			<div class="item-linha d-flex" v-for="(tarefa,key) of tarefas">



            

	   			<div class="item-day item-day-task h-padrao flex-column" v-for="(item,index) of datas" v-bind:class="{'hoje':isHoje(item.data),'fim_semana':isFimSemana(item.data)}">

	   				<!-- <div v-for="(t,c) of item.tarefas" v-if="t.id == tarefa.id" class="item-task-box" @mouseDown="startDragging()" >
	   				
	   					{{dividerPosition}}
	   				</div>
 -->
                  <draggable   :scroll-sensitivity="200" :force-fallback="true" :list="item.tarefas"  :group="{ name: 't'+tarefa.id}" handle=".handle" @start="startDrag()" @end="endDrag">
                    
                        <template  v-for="(t,c) of item.tarefas" v-if="t.id == tarefa.id"  >
                           
                           <cp_item_cronograma key="c" :item="t" @changeitem="atualizaItem" v-bind:class="{'arrastando':arrastando}"></cp_item_cronograma>
                        </template>
               
                  </draggable>
   	   				


	   			</div>

	   			
   			</div>



   			
   		</div>
   		

   </div>

</div>
