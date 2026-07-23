<div id="blog">
   

    <section class="blog section-bg" v-if="dados.length">
      <div class="container">

        <div class="section-title">
          <h2>Blog</h2>
          <p><?=getTextoHome(4)?></p>
        </div>


        <div class="row">
          <div class="col col-sm-12 col-md-6 d-flex" v-for="item of dados">
            <cp_postagem :item="item" />
          </div>
        </div>
        <div class="row">

          <div class="col col-sm-12 d-flex justify-content-center">
            <a href="ROOT/blog" class="btn-all">Confira todas as postagens</a>
          </div>
        </div>


      </div>
    </section><!-- End Counts Section -->

</div>
