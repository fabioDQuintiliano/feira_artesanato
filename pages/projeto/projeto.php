<!--[CONTAINER-padrao]-->
<div id="page_projeto">
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Projetos</h2>
          <ol>
            <li><a href="ROOT/">Home</a></li>
            <li><a href="ROOT/projetos">Projetos</a></li>
            <li v-if="dados.projeto && dados.projeto.id">{{dados.projeto.titulo}}</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->
   

    <section class="portfolio" v-if="dados.projeto && dados.projeto.id">
      <div class="container">

        <div >
          <h2>{{dados.projeto.titulo}}</h2>
         <div v-if="dados.projeto.tempo_leitura"><span class="tempo_leitura">Tempo de leitura {{dados.projeto.tempo_leitura}} minutos</span><span class="data-postagem">{{dados.projeto.data}}</span></div>
        </div>


        <div class="row mt-5" data-aos="fade-up">

        
                    
            <div class="col-sm-12 col-md-4 ">

               <img :src="dados.projeto.imagem" class="imagem_projeto" />
            
            </div>
            <div class="col-sm-12 col-md-8 ">

               <p v-html="dados.projeto.descricao"></p>
            
            </div>
          
        </div>
       
        
        <div class="row autor" v-if="dados.projeto.autor" data-aos="fade-up">
            
            <div class="col col-sm-12 col-md-5 mb-5">

              <div class="icon-box autor-box" data-aos="fade-up" >
                <div v-if="!dados.projeto.autor.imagem" class="icon"><i class='bx bxs-face'></i></div>
  
                <div v-if="dados.projeto.autor.imagem" class="icon item-icon-avatar"  :style="{ backgroundImage: `url(${dados.projeto.autor.imagem})` }"></div>

                <h4 class="title">{{dados.projeto.autor.nome}}</h4>
                
                <div class="autor-item-text animate__animated animate__fadeIn">
                
                  <p class="description" v-html="dados.projeto.autor.resumo"></p>
                </div>
              </div>
            </div>
            <div class="col col-sm-12 col-md-7 ">

              <cp_compartilhar>

             </div>
         
        </div>


        <div class="section-title mt-5" v-if="dados.relacionados && dados.relacionados.length" data-aos="fade-up" data-aos-delay="400">
          <h2>Projetos relacionados</h2>
        </div>

        <div class="row "  v-if="dados.relacionados && dados.relacionados.length" data-aos="fade-up" data-aos-delay="500">
          <template v-for="itemRelacionados of dados.relacionados">
            <cp_projeto :item="itemRelacionados" />
          </template>
        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
