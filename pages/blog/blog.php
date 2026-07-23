<!--[CONTAINER-padrao]-->
<div id="page_blog">
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Blog</h2>
          <ol>
            <li><a href="ROOT/">Home</a></li>
            <li><a href="ROOT/blog">Blog</a></li>
           
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

   

    <section class="blog" >
      <div class="container">

        <div class="section-title">
          <h2>Blog</h2>
          <p><?=getTextoHome(4)?></p>
        </div>


        <div class="row" v-if="dados.length">

          <div class="col col-sm-12 col-md-6 d-flex" v-for="item of dados">

            <cp_postagem :item="item" />
          
          </div>
       
        </div>

        <div class="row" >

          <div class="col col-xs-12">

            <cp_paginacao :page="page" :total="total" :rota="'blog'" />
          
          </div>
       
        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
