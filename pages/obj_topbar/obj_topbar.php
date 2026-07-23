<div id="obj_topbar">
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <template v-if="dados.info.email"><i class='bx bxs-envelope mail-ico' ></i><a :href="'mailto:'+dados.info.email"  target="_blank">{{dados.info.email}}</a></template>
        <template v-if="dados.info.whatsapp"><i class="bx bxl-whatsapp phone-icon"></i><a :href="'https://api.whatsapp.com/send?phone=55'+apenas_numeros(dados.info.whatsapp)" target="_blank"> +55 {{dados.info.whatsapp}}</a></template>
      </div>
      <div class="social-links d-none d-md-block">
        
        <a v-if="dados.info.facebook"  target="_blank" :href="dados.info.facebook" class="facebook"><i class="bi bi-facebook"></i></a>
        <a v-if="dados.info.instagram"  target="_blank" :href="dados.info.instagram" class="instagram"><i class="bi bi-instagram"></i></a>
        <a v-if="dados.info.linkedin" target="_blank" :href="dados.info.linkedin" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
      </div>
    </div>
  </section>

</div>
