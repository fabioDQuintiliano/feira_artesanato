<div id="obj_depoimentos">
   

    <section class="depoimentos section-bg" v-if="dados.length">
      <div class="container">

        <div class="section-title">
          <h2>Quem Acredita</h2>
          <p><?=getTextoHome(2)?></p>
        </div>


        <div class="row">


        <div  class="carousel slide" data-bs-ride="carousel">

        
          <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item item-depoimento"  v-for="(item,index) of dados" v-bind:class="{'active':index == 0}">
              <div class="carousel-container">
                <div class="carousel-content container">




                  <div class="icon-box depoimento-box" data-aos="fade-up" data-aos-delay="100">
                    <div v-if="!item.imagem" class="icon"><i class="bx bx-happy-heart-eyes"></i></div>
      
                    <div v-if="item.imagem" class="icon item-icon-avatar"  :style="{ backgroundImage: `url(${item.imagem})` }"></div>


                    <h4 class="title">{{item.nome}}</h4>
                    <p class="cargo" v-if="item.cargo">{{item.cargo}}</p>
                    <div class="depoimento-item-text animate__animated animate__fadeIn">
                      <i class='bx bxs-quote-alt-left'></i>
                      <p class="description" v-html="item.depoimento"></p>
                    </div>
                  </div>





                 
                </div>
              </div>
            </div>

           

          </div>
       

        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
