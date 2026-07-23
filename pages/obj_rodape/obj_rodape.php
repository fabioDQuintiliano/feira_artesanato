<div id="obj_rodape">
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="ROOT/images/logo_branco.png" class="logo_footer" />
          </div>

         <!--  <div class="col-lg-2 col-md-6 footer-links">
            <h4>Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto active" href="ROOT/">Home</a></li>
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="ROOT/#services">Serviços</a></li>
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="ROOT/quem_somos">Quem somos</a></li>
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="ROOT/projetos">Projetos</a></li>
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="ROOT/blog">Blog</a></li>
             
              <li><i class="bx bx-chevron-right"></i><a class="nav-link scrollto" href="ROOT/#contact">Contato</a></li>
            </ul>
          </div>
 -->
          <div class="col-lg-3 col-md-6 footer-links">
            <h4></h4>
            <ul>
             <!--  <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li> -->
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-info">
            <h3>Fale com a gente</h3>
            <p>
            
              <strong>Whatsapp:</strong> <a :href="'https://api.whatsapp.com/send?phone=55'+apenas_numeros(dados.info.whatsapp)" target="_blank"> +55 {{dados.info.whatsapp}}</a><br>
              <strong>Email:</strong> <a :href="'mailto:'+dados.info.email"  target="_blank">{{dados.info.email}}</a><br>
            </p>
            <div class="social-links mt-3">
      

              <a v-if="dados.info.facebook"  target="_blank" :href="dados.info.facebook" class="facebook"><i class="bi bi-facebook"></i></a>
              <a v-if="dados.info.instagram"  target="_blank" :href="dados.info.instagram" class="instagram"><i class="bi bi-instagram"></i></a>
              <a v-if="dados.info.linkedin" target="_blank" :href="dados.info.linkedin" class="linkedin"><i class="bi bi-linkedin"></i></i></a>



            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      
      <div class="credits">
        © 2018-<?=date('Y')?> VEK. Todos os Direitos Reservados.
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/mamba-one-page-bootstrap-template-free/ -->
      <!--   Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
      </div>
    </div>
  </footer><!-- End Footer -->

</div>
