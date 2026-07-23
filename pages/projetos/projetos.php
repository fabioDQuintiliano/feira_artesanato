<!--[CONTAINER-padrao]-->
<div id="page_blog">
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Projetos</h2>
          <ol>
            <li><a href="ROOT/">Home</a></li>
            <li><a href="ROOT/projetos">Projetos</a></li>
           
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

    <section class="portfolio" >
      <div class="container">

        <div class="section-title">
          <h2>Nossos Projetos</h2>
          <p><?=getTextoHome(3)?></p>
        </div>


        <div class="row" v-if="dados.length">

        

       
            <cp_projeto v-for="item of dados" :item="item"></cp_projeto>
          
         
       
        </div>
        
        <div class="row" >

          <div class="col col-xs-12">

            <cp_paginacao :page="page" :total="total" :rota="'projetos'" />
          
          </div>
       
        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
