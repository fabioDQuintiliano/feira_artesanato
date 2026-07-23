<!--[CONTAINER-padrao]-->
<div id="page_postagem">
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Blog</h2>
          <ol>
            <li><a href="ROOT/">Home</a></li>
            <li><a href="ROOT/blog">Blog</a></li>
            <li>Postagem</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->
   

    <section class="blog" >
      <div class="container">

        <div >
          <h2>{{dados.post.titulo}}</h2>
         <div v-if="dados.post.tempo_leitura"><span class="tempo_leitura">Tempo de leitura {{dados.post.tempo_leitura}} minutos</span><span class="data-postagem">{{dados.post.data}}</span></div>
        </div>


        <div class="row mt-5" >

          <div data-aos="fade-up" >
                    
            <div class="col col-sm-12 d-flex">

               <p v-html="dados.post.conteudo"></p>
            
            </div>
          </div>
        </div>
       
        
        <div class="row autor" v-if="dados.post.autor" data-aos="fade-up">
            
            <div class="col col-sm-12 col-md-5 mb-5">

              <div class="icon-box autor-box" data-aos="fade-up" >
                <div v-if="!dados.post.autor.imagem" class="icon"><i class='bx bxs-face'></i></div>
  
                <div v-if="dados.post.autor.imagem" class="icon item-icon-avatar"  :style="{ backgroundImage: `url(${dados.post.autor.imagem})` }"></div>

                <h4 class="title">{{dados.post.autor.nome}}</h4>
                
                <div class="autor-item-text animate__animated animate__fadeIn">
                
                  <p class="description" v-html="dados.post.autor.resumo"></p>
                </div>
              </div>
            </div>
            <div class="col col-sm-12 col-md-7 ">

              <cp_compartilhar>

             </div>
         
        </div>


        <div class="section-title mt-5" v-if="dados.relacionados && dados.relacionados.length" data-aos="fade-up" data-aos-delay="400">
          <h2>Posts relacionados</h2>
        </div>

        <div class="row "  v-if="dados.relacionados && dados.relacionados.length" data-aos="fade-up" data-aos-delay="500">
          <div class="col col-sm-12 col-md-6 d-flex" v-for="itemRelacionados of dados.relacionados">
            <cp_postagem :item="itemRelacionados" />
          </div>
        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
