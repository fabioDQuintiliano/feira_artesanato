<?php

class Componente__auto_items_permissoes{

	function listagem($tabela,$id,$valor=null){

		echo $id;

	}

	function exibe($tabela,$valor=null,$PARAM=null){

		

	?>

		<script>

        $(function(){

            $(".first").click(function(){

                

                

                $(this).parent('tr').find('.checkbox').each(function(){

                    

                    if($(this).attr('checked')=='checked'){

                        $(this).attr('checked',false)	

                    }else{

                        $(this).attr('checked','checked')	

                    }

                })

                

            });

            

        })

        </script>

        <?php

        global $MAP;

        $value = unserialize($valor);

        ?>

        <table class="tableList" cellpadding="0" cellspacing="0" style="width:900px;">

        <?php

        $l = 0;

        $dados_p = $MAP['infoPages'];

        

        

        require_once('tables/_admin_permissoes.php');

        

        

		for($iPerm = 0;$iPerm<count($CONTENT_PERMISSOES);$iPerm++):

		

			$dado_p = $CONTENT_PERMISSOES[$iPerm];

			$link = $CONTENT_PERMISSOES[$iPerm]['valor'];

			$botoesAdicionais = $CONTENT_PERMISSOES[$iPerm]['botoes'];

            ?>

            <tr class="<?php echo(($l%2)==0?'odd':'even')?>">

                <td class="first" style="cursor:pointer;"><span class="item_permissao"><?php echo $dado_p['nome']?></span></td>

                <td width="80">

                	<label>

                    	<input type="checkbox" <?=(@in_array($link,$value['menu'])?'checked="checked"':'')?>  class="checkbox" name="menu[]" value="<?php echo $link?>"> Menu

                    </label>

                </td>

                <td width="80">

                	<label>

                    	<input type="checkbox" <?=(@in_array($link,$value['add'])?'checked="checked"':'')?>  class="checkbox" name="add[]" value="<?php echo $link?>"> Adicionar

                    </label>

                </td>

                <td width="80">

                	<label>

                    	<input type="checkbox" <?=(@in_array($link,$value['edit'])?'checked="checked"':'')?>   class="checkbox" name="edit[]" value="<?php echo $link?>"> Editar

                    </label>

                </td>

                <td width="80">

                    <label>

                        <input type="checkbox" <?=(@in_array($link,$value['list'])?'checked="checked"':'')?>  class="checkbox" name="list[]" value="<?php echo $link?>"> Listar

                    </label>

                </td>

                <td width="80">

                    <label>

                        <input type="checkbox" <?=(@in_array($link,$value['view'])?'checked="checked"':'')?>  class="checkbox" name="view[]" value="<?php echo $link?>"> Visualizar

                    </label>

                </td>

                <td width="80">

                    <label>

                        <input type="checkbox" <?=(@in_array($link,$value['del'])?'checked="checked"':'')?>   class="checkbox" name="del[]" value="<?php echo $link?>"> Deletar

                    </label>

                </td>

                

                 <td width="130">

                <?php

				if(is_array($botoesAdicionais))for($iBtsAdd = 0;$iBtsAdd <count($botoesAdicionais);$iBtsAdd++):

				?>

               

                    <label>

                        <input type="checkbox" <?=(@in_array($botoesAdicionais[$iBtsAdd]['valor'],$value['bt_adicional'])?'checked="checked"':'')?>   class="checkbox" name="bt_adicional[]" value="<?php echo $botoesAdicionais[$iBtsAdd]['valor']?>"> <?php echo $botoesAdicionais[$iBtsAdd]['nome']?>

                    </label>

               

                

				<?php

				endfor;

				?>

                 </td>

                

                

            </tr>

            <?php

            $l++;

		endfor;

		

		

        ?>

        </table>

<?php

	}

	

	

	function view($tabela,$valor=''){

		return $valor;

	}

}

?>