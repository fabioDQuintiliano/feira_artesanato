<!--[CONTAINER-container-admin]-->

<!--[PAGETITLE-Administracao]-->

<!--[PAGEDESCRIPTION-Descrição da página]-->

<!--[PAGEKEYWORDS-]-->

<?php

if(!logado_no_perfil(2)){

exit();	

}

?>

    

    

    

   <div class="row_admin">

        <div class="left_row_admin">

        	<div class="nome_page_atual">Minhas Fotos</div><!-- nome_page_atual -->

                            

                <a href="http://localhost/lovemodel/adm-home?item=23&new=1">

                    <div class="bt_add"> 

                        Adicionar Fotos

                    </div>

                </a>



            </div><!-- left_row_admin -->

        

    </div> 

    

    

    <div class="container_full">

    

    



		<?php

        $fts = DB::read("fotos");

		$fts->pessoa = $_SESSION['user_id'];

		$fts->load();

		

		if($fts->size()>0){

			do{

				

				?>

				<div class="item-foto-admin" style="background-image:url(ROOT/images/upload/view_<?php echo $fts->imagem?>)">

					<div class="opcsFoto delOpc">

                    	<a onclick="conf('Tem certeza que deseja deletar este item?',function(){wait(); location.href = 'ROOT/action-delete_global?item=23&reg=<?php echo $fts->id?>'});">

                    		<img src="ROOT/images/del_ico.png" />

                    	</a>

                    </div>

					<div class="opcsFoto editOpc">

                        <a href="ROOT/adm-home?item=23&edit=<?php echo $fts->id?>">

                            <img src="ROOT/images/edit_ico.png" />

                        </a>

                    </div>

				</div><!--  item-foto-admin -->

				<?php

				

			}while($fts->next());

		}else{

            echo "<h3>Nenhuma foto adicionada.</h3>";
        }

		?>

        

    </div><!-- container_full -->



