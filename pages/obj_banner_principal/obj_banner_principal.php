
  <div id="obj_banner_principal">
  <section id="hero">

    <div class="hero-container">
      <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <ol v-show="total > 1" class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

          <!-- Slide 1 -->
          <div class="carousel-item " v-for="(item,index) of dados.banners"   :style="{ backgroundImage: `url(${item.imagem})` }"  v-bind:class="{'active':index == 0}">
            <div class="carousel-container">
              <div class="carousel-content container">
                <h2 class="animate__animated animate__fadeInDown"><span>{{item.titulo}}</span></h2>
                <p class="animate__animated animate__fadeInUp" v-if="item.descricao">{{item.descricao}}</p>
                <a :href="item.link" v-if="item.link" class="btn-get-started animate__animated animate__fadeInUp scrollto">Ler mais</a>
              </div>
            </div>
          </div>

         

        </div>

        <a v-show="total > 1" class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>
        <a v-show="total > 1" class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

      </div>
    </div>
  </section><!-- End Hero -->
</div>
