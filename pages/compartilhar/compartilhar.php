<!--[CONTAINER-master-site]-->
<div id="page_compartilhar">
    


   <div class="row animated fadeInUp faster " v-cloak>


      <div class="col-sm-12 col-md-12  align-items-center flex-column " >

       

          <div class="card"style="transition: all 0.7s ease 0s;">
             
              <div class="card-body pt-4 p-3">

            <div class="text-center" >


              
              
            
                  <lottie-player class="d-inline-block" src="ROOT/images/lottie/share.json"  background="transparent"  speed="1"  style="width: 200px; height: 200px;"  loop  autoplay></lottie-player>

                  <p class="fs-4" >Compartilhar Cronograma - <b>{{dados.nome}}</b></p>


                  


                  <template v-if="dados.publico">


                    <p><i class="fas fa-share-alt"></i> <b>Este cronograma é publico.</b> Você pode cancelar o compartilhamento deste cronograma a qualquer momento.</p>


                    <div class="alert alert-light text-left" role="alert" style="width:fit-content; display: inline-block;">
                      Utilize o seguinte link para compartilhar este cronograma:<br />
                      <b><a :href="'ROOT/share/'+code" target="_blank">ROOT/share/{{code}}</a></b>
                    </div>

                    <div>
                    <a @click="compartilhar" class="btn btn-secondary"><i class="fas fa-share-alt"></i> &nbsp;Cancelar compartilhamento</a>
                  </div>
                  </template>
                  <template v-else>
                    <div><i class="fas fa-lightbulb"></i> Compartilhar seus cronogramas é uma maneira simples de manter seus clientes sempre atualizados sobre andamento de seus projetos.</div>
                  
                    <p>Qualquer pessoa com o link poderá visualizar este cronograma. Deseja continuar?</p>
                  

                    <p >
                      <a @click="compartilhar" class="btn btn-info"><i class="fas fa-share-alt"></i> &nbsp;Gerar link de compartilhamento</a>

                    </p>
                  </template>
                

          
            </div>
          </div>
        
        </div>

      </div>

      
   </div>


</div>
