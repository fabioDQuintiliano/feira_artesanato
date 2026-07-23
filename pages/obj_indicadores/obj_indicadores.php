<div id="obj_indicadores">
    <!-- ======= Counts Section ======= -->
    <section class="counts section-bg" v-if="dados.length">
      <div class="container">

        <div class="row">

          <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" v-for="(item,index) of dados"  :data-aos-delay="200*index">
            <div class="count-box">
             <!--  <i class='bx bx-happy-beaming' style="color: #c042ff;"></i> -->
              <span data-purecounter-start="0" :data-purecounter-end="item.valor" data-purecounter-duration="1" class="purecounter"></span>
              <p>{{item.indicador}}</p>
            </div>
          </div>

       

        </div>

      </div>
    </section><!-- End Counts Section -->

</div>
