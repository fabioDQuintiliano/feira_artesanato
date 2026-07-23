<script type="text/x-template" id="cp_compartilhar">		
	<div class="d-flex  justify-content-end" data-aos="fade-up">

		<div class="d-flex flex-column">
			<div class="d-flex titulo_compartilhe">
				Compartilhe com seus amigos
			</div>
			<div class="d-flex cp_compartilhar flex-row ">
		     



		      <a :href="shareFace()" target="_blank"> <i class='bx bxl-facebook-circle bt_face' ></i></a>

		      <a :href="shareWhats()" target="_blank"> <i class='bx bxl-whatsapp bt_whats' ></i></a>
		      <a :href="shareLinkedin()" target="_blank"> <i class='bx bxl-linkedin bt_linkedin' ></i></i></a>


		      <a :href="shareTwitter()" target="_blank"> <i class='bx bxl-twitter bt_twitter' ></i></a>

		      <a :href="shareMail()" target="_blank"> <i class='bx bx-envelope bt_mail'></i></a>
		    </div>
	    </div>
    </div>

</script>

<script>
	Vue.component('cp_compartilhar', {
		template: '#cp_compartilhar',
		data: function(){
			var vm = this;
			return {
				url:''
			};
		},
		props: {
			

		
		},
		mounted(){
			this.url = window.location.href;
		},
		methods:{
		
			shareFace(){
				return  "https://www.facebook.com/sharer/sharer.php?u="+this.url;
			
			},
			shareWhats(){
				return "https://api.whatsapp.com/send?text="+this.url;
				
			},
			shareTwitter(){
				return "https://twitter.com/share?url="+this.url;
				
			},
			shareMail(){
				return "mailto:?&subject=&body="+this.url;
			},
			shareLinkedin(){
				return "https://www.linkedin.com/shareArticle?mini=true&url="+this.url;
			}

			
    	}
	  // options
	});
</script>

<style>
	.titulo_compartilhe{
		font-size: 14px;
	}
	.cp_compartilhar a{
		margin-right: 5px;
	}
	.cp_compartilhar i{
		font-size: 36px;
	}
	.cp_compartilhar{
		justify-content: flex-end;
	}
	.cp_compartilhar .bt_face{
		color: #1873EB;
	}
	.cp_compartilhar .bt_whats{
		color: #24CA62;
	}
	.cp_compartilhar .bt_twitter{
		color: #00A6E8;
	}
	.cp_compartilhar .bt_mail{
		color: #666666;
	}

</style>
