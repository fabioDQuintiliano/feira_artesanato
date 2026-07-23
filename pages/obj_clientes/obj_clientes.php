<div id="obj_clientes">
   

    <section class="clients" v-if="dados.length">
      <div class="container">

        <div class="section-title">
          <h2>Nossos Clientes</h2>
          <p><?=getTextoHome(1)?></p>
        </div>


        <div class="row">




          <div class="d-flex justify-content-center">
            <template v-for="item of dados">
              <div class="item-client" data-aos="fade-up">
                <a :href="item.link" target="_blank"> <img :src="item.imagem" /></a>
              </div>
            </template>
          </div>

       

        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
