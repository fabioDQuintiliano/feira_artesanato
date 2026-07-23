<!--[CONTAINER-padrao]-->
<div id="page_quem_somos">
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Conheça a VEK</h2>
          <ol>
            <li><a href="ROOT/">Home</a></li>
            <li><a href="ROOT/quem_somos">Quem Somos</a></li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

   

    <section class="blog" >
      <div class="container">

        <div class="section-title">
          <h2>Quem Somos</h2>
          <p><?=getTextoHome(5)?></p>
        </div>


        <div class="row" >

           
          <div class="col col-sm-12 col-md-12 " v-html="conteudo">

          
          </div>
       
        </div>

        <div class="section-title mt-5" v-if="dados && dados.length" data-aos="fade-up" data-aos-delay="100">
          <h2>Conheça nossa equipe</h2>
        </div>
        <div class="row">

           
          	<div class="col-sm-12 col-md-12 "  v-for="(item,index) in dados" data-aos="fade-up" data-aos-delay="200">
              
                <div class="row mb-5">

                	<div class="col-sm-12 col-md-4 col-xl-2    text-center">

		              	<div class="equipe-foto"  :style="{ backgroundImage: `url(${item.imagem})` }"></div>
		              	 <div class="social-itens mt-2">

			              	  <a v-if="item.whatsapp"  target="_blank" :href="'https://api.whatsapp.com/send?phone=55'+apenas_numeros(item.whatsapp)" class="mb-1 whatsapp socialPd"><i class="bi bi-whatsapp"></i></a>
				              <a v-if="item.instagram"  target="_blank" :href="item.instagram" class="mb-1 instagram socialPd"><i class="bi bi-instagram"></i></a>
				              <a v-if="item.linkedin" target="_blank" :href="item.linkedin" class="mb-1 linkedin socialPd"><i class="bi bi-linkedin"></i></i></a>
				              <a v-if="item.email" target="_blank"  :href="'mailto:'+item.email"   class="mb-1 email socialPd"><i class="bx bxs-envelope"></i></i></a>
			              </div>
		            </div>
	              	<div class="col-sm-12 col-md-8 col-xl-10">
	              		<div class="text-center text-md-start ">
			              <div class="titlePd">{{item.nome}}</div>
			              <div class="cargoPd">{{item.cargo}}</div>
			          	
			             

			          	</div>
			          	<div >
			          		<div v-html="item.descricao"></div>
			          	</div>
		          	</div>
		        </div>
	          
          	</div>
       
        </div>



      </div>
    </section><!-- End Counts Section -->

</div>
