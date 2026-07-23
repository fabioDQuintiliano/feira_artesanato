<?php

global $INFO_QUERY_FILTRO,$MAP;

//$INFO_QUERY_FILTRO = array();





if(isset($_SESSION['INFO_QUERY_FILTRO'][$configTableList->id])){

	$INFO_QUERY_FILTRO = $_SESSION['INFO_QUERY_FILTRO'][$configTableList->id];

}





$INFO_QUERY_FILTRO['TABELA'] = $configTableList->id;

if(count($_POST)>0){

	

	foreach($_POST as $k=>$v):

		if($v != '' ){

			$INFO_QUERY_FILTRO[$k] = $v;

		}else{

			if($INFO_QUERY_FILTRO[$k] != '')

			unset($INFO_QUERY_FILTRO[$k]);

		}

		unset($_POST[$k]);

	endforeach;

	unset($_POST);

}





$_SESSION['INFO_QUERY_FILTRO'][$configTableList->id] = $INFO_QUERY_FILTRO;

//$_SESSION[$configTableList->id]['INFO_QUERY_FILTRO']['TABELA'] = $_GET[':item'];

?>





<div id="box_topo_container_filtro">

<form method="post" >

    <table cellpadding="0" cellspacing="0" class="tableFiltro">

    

        <?php

		$num_fil=0;

        $q = new Model;

		require("tables/def_".$MAP['def_file'].".php");

		if(!empty($MAP["filtro_adicional"])):

			

			foreach($MAP["filtro_adicional"] as $filtro_adicional):

			

				

				include 'admin/filtro/'.$filtro_adicional;

				$nFiltro = explode('__',str_replace('.php','',$filtro_adicional));

				

				$nameclass_filtro = executaFiltro_.$nFiltro[1];

				$filEXEC = new $nameclass_filtro;

				$nomeFiltro = $filEXEC->nomeFiltro();

				echo '<tr>';

				echo $filEXEC->input($nomeFiltro,$INFO_QUERY_FILTRO[$nomeFiltro]);

				echo '</tr>';	

				$num_fil++;

						

			endforeach;

			

		endif;

		

		

		

		$fil = $TABLE_DEF_INPUT;

	
		

		if($fil && count($fil)>0)

        foreach($fil as $filtro):

		if($filtro['exb_filtro']!=1)continue;

		

		

		$num_fil++

        ?>

        <tr>

            <th><?php echo $filtro['nome']?></th>

            <td >

                

                <?php

                if($filtro['type'] == 'select'):

					global $MAP,$listapar;

					if(in_array($MAP['tabela'].'----'.$filtro['campo_tabela'],$listapar)):

					?>	

						<script>

                        $(function(){

                            $("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>").keyup(function(){

                                val = $(this).val();

                                

                                $.post('<?=ROOT?>fn-carrega_lista',{p0:'<?=$MAP['tabela']?>',p1:'<?=$filtro['campo_tabela']?>',p2:val},function(a){

                                    

                                    

                                    $("#<?php echo  $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>").html(a);

                                    

                                    $(".select_auto").click(function(){

                                        $("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id'])?>").val($(this).attr('valor'));

                                        $("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>").val($(this).attr('nome'))

                                        $("#<?php echo $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>").html('');

                                    })

                                    

                                        

                                })

                            })	

                        })

                        </script>  

                        

                        <input type="text"  name="<?php echo $filtro['campo_tabela']?>_LSTP" id="<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>" class="autocomplete_lista_input" value="<?php echo $INFO_QUERY_FILTRO[$filtro['campo_tabela'].'_LSTP'] ?>"  autocomplete="off"/>

                        <input type="hidden"  name="<?php echo $filtro['campo_tabela']?>" id="<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id'])?>" value="<?php echo $INFO_QUERY_FILTRO[$filtro['campo_tabela']]?>" />

                        

                        <div class="list_auto_comp" id="<?php echo  $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>">

                       </div>

                       

					<?php

					else:



                ?>

                        <select name="<?php echo $filtro['campo_tabela']?>">

                            <option value="">Selecione...</option>

                            <?php

                            if($filtro['caracteristica'] == 2):

                                $dados_join = $q->read($filtro['join_tabela']);

                                foreach($dados_join as $list_join):

                                    if($list_join[$filtro['join_chave_extrangeira']] == $INFO_QUERY_FILTRO[$filtro['campo_tabela']]){

                                        $sele = 'selected="selected"';	

                                    }else{

                                        $sele = '';

                                    }

                                

                                    echo '<option '.$sele.' value="'.$list_join[$filtro['join_chave_extrangeira']].'">'.$list_join[$filtro['join_campo_exibido']].'</option>';

                                endforeach;

                            else:

                                if(!empty($filtro['valor'])):

                                    $dados_select = explode(',',$filtro['valor']);

                                    for($i = 0; $i<count($dados_select); $i++):

                                    

                                        if($i == $INFO_QUERY_FILTRO[$filtro['campo_tabela']] && $INFO_QUERY_FILTRO[$filtro['campo_tabela']] != ''){

                                            $sele = 'selected="selected"';	

                                        }else{

                                            $sele = '';

                                        }

                                        

                                        echo '<option '.$sele.' value="'.$i.'">'.$dados_select[$i].'</option>';

                                    

                                    endfor;

                                endif;

                            endif;

                            ?>

                        </select>

                <?php

					endif;

                else:

			

					if($li['caracteristica'] == 2):

						$dados_join = $q->read($filtro['join_tabela']);

						foreach($dados_join as $list_join):

							?>

							<div class="system_item_join">

							<input type="<?php echo $filtro['type']?>"  name="<?php echo $filtro['campo_tabela']?>[]" value="" /><?php echo $list_join[$li['join_campo_exibido']];?>

							</div>

							<?php

						endforeach;

					else:

						if($filtro['mascara'] == 'data'):

				

						?>

							de <input type="text" name="de_<?php echo $filtro['campo_tabela']?>" value="<?php echo $INFO_QUERY_FILTRO['de_'.$filtro['campo_tabela']]?>" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" /> ate <input type="text" name="ate_<?php echo $filtro['campo_tabela']?>" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" value="<?php echo $INFO_QUERY_FILTRO['ate_'.$filtro['campo_tabela']]?>"  /> 

						<?php

				

						else:

						?>

							<input type="text" name="<?php echo $filtro['campo_tabela']?>" value="<?php echo $INFO_QUERY_FILTRO[$filtro['campo_tabela']]?>" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" />

						<?php

						endif;

                	endif;

				endif;

                ?>

                

            </td>

        </tr>

        <?php

        endforeach;

        ?>

        

        <?php

        if($num_fil>0):

		?>

        <tr>

            

            <td colspan="2" style="border:none;"><input type="submit" value="Filtrar" class="input_filtro" style="float:right;" /></td>

        </tr>

    	<?php

        endif;

		?>

    </table>

</form>

</div><!-- box_topo_container_filtro -->





<?php

//MONTA A QUERY DA BUSCA.

$QUERY_FILTRO = " (1=1) ";



//echo count($INFO_QUERY_FILTRO);

if(count($INFO_QUERY_FILTRO)>1):

	

	if(!empty($MAP['filtro_adicional'])):

	

		foreach($MAP['filtro_adicional'] as $filtro_adicional):

		

			//include 'admin/filtro/'.$filtro_adicional;

			$nFiltro = explode('__',str_replace('.php','',$filtro_adicional));

			

			

			

			$nameclass_filtro = executaFiltro_.$nFiltro[1];

			$filEXEC = new $nameclass_filtro;

			

			

			$nFunction = 'executaFiltro_'.$nFiltro[1];

			$nomeFiltro = $filEXEC->nomeFiltro();

			$QUERY_FILTRO .= $filEXEC->query($INFO_QUERY_FILTRO[$nomeFiltro]);

						

		endforeach;

		

	endif;

	

	foreach($fil as $filtro):

		

		if($filtro['exb_filtro']!=1)continue;



		if(($INFO_QUERY_FILTRO[$filtro['campo_tabela']] != '' || ($INFO_QUERY_FILTRO['de_'.$filtro['campo_tabela']] != '' &&

		 $INFO_QUERY_FILTRO['ate_'.$filtro['campo_tabela']] != '')) && ($INFO_QUERY_FILTRO[$filtro['campo_tabela']]!='0,00')):	

			// se for um item de select

			 if($filtro['type'] == 'select' && $li['caracteristica'] == 2):

			 

				$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." IN(SELECT ".$filtro['join_chave_extrangeira']." FROM ".$filtro['join_tabela']." WHERE ".$filtro['join_campo_exibido']." = '".$INFO_QUERY_FILTRO[$filtro['campo_tabela']]."')";

			 

			 elseif($filtro['type'] == 'select' && $li['caracteristica'] != 2):

			

				$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." = '".$INFO_QUERY_FILTRO[$filtro['campo_tabela']]."'";

			

			 elseif($filtro['type'] != 'select' && $li['caracteristica'] == 2):

			 

				$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." IN (".implode(',',$INFO_QUERY_FILTRO[$filtro['campo_tabela']]).")";

						

			 else:

			

				 //query para cada tipo de mascara

				 if($filtro['mascara'] == 'data'):

					

					$QUERY_FILTRO .= " AND ( ".$filtro['campo_tabela']." BETWEEN '".date2banco($INFO_QUERY_FILTRO['de_'.$filtro['campo_tabela']])."' AND '".date2banco($INFO_QUERY_FILTRO['ate_'.$filtro['campo_tabela']])."' )";

					

				 elseif($filtro['mascara'] == 'decimal'):

				 

					$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." LIKE '%".substr(dinheiroToFloat($INFO_QUERY_FILTRO[$filtro['campo_tabela']]),0,-3)."%'";

				 

				 else:

	

					$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." LIKE '%".($INFO_QUERY_FILTRO[$filtro['campo_tabela']])."%'";

				 

				 endif;

				

			endif;

		endif;

	endforeach;

	

	

endif;



?>







