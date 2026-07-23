<?php
class Componente__gerador_de_parcelas{
	public function listagem($tabela,$id,$valor=null){
		return  $valor;
	}
	public function exibe($tabela,$valor=null,$PARAM=null){
?>
		<script>
        $(function(){
                
          
            $("#GP_gerar_parcelas").click(function(){
                
                
                valorPagamento = dinheiro2float($('.valor_total').val());
                valorEntrada = dinheiro2float($('#GP_parcelas_pago').val());
                vencimento1 = $("#GP_vendimento_parcela_1").val();
                numParcelas = $("#GP_parcelas_numero_parcelas").val();
                linhaParcela = $("#lista_parcelas_content").html();
                
                
                console.log(valorPagamento);
                if(valorEntrada>0){
                    
                    valorPagamento-=valorEntrada;
                }
                
                valorparcelas = (valorPagamento/numParcelas);
        
                if(vencimento1 == "" || vencimento1 == '00/00/0000'){
                    alerta('Preencha o vencimento da primeira parcela.');
                    
                }else if(valorPagamento <= 0){
                    
                    alerta('É necessário um valor válido.');
                }else{
                    
                    $("#parcelas_lista").show();
                    $("#exibe_parcelas").html('');
                    
                    $(".GP_opc_dis_parcelas").hide();
                    $(".GP_opc_dis_parcelas_cancela").show();
                    $(".GP_opc_dis_add_parcela").show();
                    
                    contaParcelas = 0;
                    if(valorEntrada>0){
                        
                        classe = "lista_parcelas_divide_odd";	
                        
                        parcela = linhaParcela.replace(/Parcela __X/gi,"Entrada").replace(/__X/gi,0).replace(/__class__/gi,classe);
                        
                        $("#exibe_parcelas").append(parcela);
                        
                        $('.dataMask').setMask('date');
                        $('.decimalMask').setMask('decimal');
                        
                        
                        $('#parcelas_vencimento0').val(vencimento1);
                        $("#parcelas_valor0").val(number_format(valorEntrada,2,',','.'));
                        $("#parcelas_pago0").trigger('click');
                        
                        //valorPagamento = valorPagamento-valorEntrada;
                    }
                    contaParcelas = 1;
                    while(contaParcelas<=numParcelas){
                        l = contaParcelas % 2;
                        if(l == 0){
                            classe = "lista_parcelas_divide_odd";	
                        }else{
                            classe = "lista_parcelas_divide_even";
                        }
                        
                        parcela = linhaParcela.replace(/__X/gi,contaParcelas).replace(/__class__/gi,classe);
                        
                        $("#exibe_parcelas").append(parcela);
                        
                        
                        
                        $('.dataMask').setMask('date');
                        $('.decimalMask').setMask('decimal');
                        
                        dataVencimento = addMes(vencimento1,(contaParcelas-1));
                        //alert(dataVencimento);
                        
                        if(contaParcelas == numParcelas){
                            
                            parceAtual = duasCasas(valorparcelas) * (contaParcelas - 1);
                            
                            valorparcelas = valorPagamento - parceAtual;
                        }
                        
                        $("#parcelas_vencimento"+contaParcelas).val(dataVencimento);
                        $("#parcelas_valor"+contaParcelas).val(number_format(valorparcelas,2,',','.'));
                        
                        
                        contaParcelas++;
                    }
                }
            });
            
            //var contaParcelas = contaParcelas;
            $("#GP_gerar_parcelas_add").click(function(){
                
                //contaParcelas = contaParcelas+1;
                linhaParcela = $("#lista_parcelas_content").html();
                
                l = contaParcelas % 2;
                if(l == 0){
                    classe = "lista_parcelas_divide_odd";	
                }else{
                    classe = "lista_parcelas_divide_even";
                }
                
                parcela = linhaParcela.replace(/__X/gi,contaParcelas).replace(/__class__/gi,classe);
                
                $("#exibe_parcelas").append(parcela);
                        
                $('.dataMask').setMask('date');
                $('.decimalMask').setMask('decimal');
                contaParcelas++;
                //alert(contaParcelas);
                    
            });
            
            
            $("#GP_gerar_parcelas_refazer").click(function(){
                
                //conf('Tem certeza que deseja refazer as parcelas?','','refazParcela');
                conf('Tem certeza que deseja refazer as parcelas?',function(){
                    refazParcela();
                    });
                
            });
            
        });
        
        function refazParcela(){
            $("#parcelas_lista").hide();
            $("#exibe_parcelas").html('');
            
            $(".GP_opc_dis_parcelas").show();
            $(".GP_opc_dis_parcelas_cancela").hide();
            $(".GP_opc_dis_add_parcela").hide();
            
            return false;
        }
        
        </script>
        
        <div class="titulo_ger_par">
        <label>Gerador de parcelas <span class="leg_parcelas"></span></label>
        </div><!-- titulo_ger_par -->
        <div class="body_ger_par">
        <?php
        global $url,$MAP;
        
        
        if($MAP['id'] == 28){
            //$MAP['tabela'] = 'fechar_hospedagem';	
        }
        
        if($_GET[':edit'] != ""):
            $q = new Model;
            $dItem = $q->read($MAP['tabela'],"id = '".$_GET[':edit']."'");
            $dFat = $q->read("fatura","cod_referencia = '".$_GET[':edit']."' AND referencia = '".$MAP['tabela']."'");
        
            if(count($dFat)>0):
            ?>
            <script>
            $(function(){
                
                $(".GP_opc_dis_parcelas").hide();
                $(".GP_opc_dis_parcelas_cancela").show();
                $(".GP_opc_dis_add_parcela").show();
                contaParcelas = <?php echo $dItem[0]['numero_de_parcelas'] + 1?>;
                
            })
            </script>
            <?php
            endif;
        
        endif;
        ?>
        <div class="system_item_form GP_opc_dis_parcelas">
            <input type="hidden" name="GP_parcelas_list_gera_posupsave" value="1" />
            <label for="despesa_forma_pagamento">Vencimento da 1º parcela</label>
            <br>
            <input type="text"  style="width:80px; margin-right:5px;" class="mask_type_data" value="<?php echo substr($dItem[0]['vencimento'],8,2).'/'.substr($dItem[0]['vencimento'],5,2).'/'.substr($dItem[0]['vencimento'],0,4)?>"  name="GP_vendimento_parcela_1" id="GP_vendimento_parcela_1" />
        </div>
        
        <div class="system_item_form GP_opc_dis_parcelas">
            <label for="despesa_forma_pagamento">Conta Bancária</label>
            <br>
            <select name="GP_parcelas_contas_bancarias">
            <option value="">Selecione...</option>
                <?php
                $contas_bancarias = $q->read("contas_bancarias");
                foreach($contas_bancarias as $contas_bancarias_lista):
                    echo '<option '.selected($contas_bancarias_lista['id'],$dItem[0]['conta']).' value="'.$contas_bancarias_lista['id'].'">'.$contas_bancarias_lista['banco'].'</option>';
                endforeach;
                ?>
            
            </select>
        </div>
        
        <div class="system_item_form GP_opc_dis_parcelas">
            <label for="despesa_forma_pagamento">Forma de Pagamento</label>
            <br>
            <select name="GP_parcelas_formas_de_pagamento" id="GP_parcelas_formas_de_pagamento">
            
                <?php
                $tipos_pagamento = $q->read("formas_de_pagamento");
                foreach($tipos_pagamento as $tipo_pagamento):
                    echo '<option '.selected($tipo_pagamento['id'],$dItem[0]['forma_de_pagamento']).' value="'.$tipo_pagamento['id'].'">'.$tipo_pagamento['forma_de_pagamento'].'</option>';
                endforeach;
                ?>
            
            </select>
        </div>
        
        
        
        <div class="system_item_form GP_opc_dis_parcelas">
            <label for="GP_parcelas_numero_parcelas">Quantidade de parcelas</label>
            <br>
            <select name="GP_parcelas_numero_parcelas" id="GP_parcelas_numero_parcelas">
            
                <?php
                $tipos_pagamento = $q->read("formas_de_pagamento");
                for($i = 1;$i<=24;$i++):
                    echo '<option '.selected($i,$dItem[0]['numero_de_parcelas']).' value="'.$i.'">'.$i.'</option>';
                endfor;
                ?>
            
            </select>
        </div>
        
        <div class="system_item_form GP_opc_dis_parcelas">
            <label for="GP_parcelas_pago">Entrada (Valor já pago)</label>
            <br>
            <input type="text" name="GP_parcelas_pago" id="GP_parcelas_pago" class="mask_type_decimal GP_parcelas_pago" style="width:80px;" value="<?php echo floatToDinheiro2($dItem[0]['valor_entrada']);?>">
            
        
        </div>
        
        
        <div class="system_item_form GP_opc_dis_parcelas">
            <input type="button" value="Gerar as parcelas" id="GP_gerar_parcelas" style="margin-top:20px;" />
        </div>
        
        <div class="system_item_form GP_opc_dis_parcelas_cancela" style="display:none;" >
            <input type="button" value="Refazer todas as parcelas" id="GP_gerar_parcelas_refazer" style="margin-top:20px;" />
        </div>
        
        
        <div class="system_item_form GP_opc_dis_add_parcela" style="display:none;" >
            <input type="button" value="Adicionar parcela" id="GP_gerar_parcelas_add" style="margin-top:20px;" />
        </div>
        
        
        
        <div id="parcelas_lista" style="display:block;">
            <label for="despesa_forma_pagamento">Parcelas</label>
            <div id="exibe_parcelas">
            
                <?php
                /*-----------------------------------------------------------------------------------------------------------------------------------------------------------*/
                if(count($dFat)>0):
                    $lin = 0;
                    
                    foreach($dFat as $item):
                    $lia = $lin%2;
                    ?>
                        <div class="lista_parcelas_divide <?php echo (($lia == 0)?'lista_parcelas_divide_odd':'lista_parcelas_divide_even')?>">
                            
                            
                            <script>
                            $(function(){
                                $('#parcelas_pago<?php echo $item['parcela']?>').live('change',function(){
                                    
                                        if($(this).attr('checked')){
                                            $(".parcelas_data_pagamento_box<?php echo $item['parcela']?>").show();
                                        }else{
                                            $(".parcelas_data_pagamento_box<?php echo $item['parcela']?>").hide();
                                        }
                                    
                                    })
                                
                                
                            })
                            </script>
                            
                            <div class="system_item_form " style="width:100px;">
                                <br />
                                <label for="parcelas_parcela<?php echo $item['parcela']?>"><?php echo ($item['parcela']==0?'Entrada':'Parcela '.$item['parcela'])?></label>
                                <input type="hidden" value="<?php echo $item['parcela']?>"  class="GP_num" name="GPar_parcelas_parcela_num<?php echo $item['parcela']?>" />
                               
                            </div>
                            <div class="system_item_form ">
                                <label for="parcelas_vencimento<?php echo $item['parcela']?>">Vencimento</label>
                                <br>
                                <input type="text" class="dataMask" name="GPar_parcelas_vencimento<?php echo $item['parcela']?>" id="parcelas_vencimento<?php echo $item['parcela']?>" style="width:80px;" value="<?php echo substr($item['data_vencimento'],8,2).'/'.substr($item['data_vencimento'],5,2).'/'.substr($item['data_vencimento'],0,4)?>" />
                            </div>
                            
                            
                            <div class="system_item_form ">
                                <label for="parcelas_valor<?php echo $item['parcela']?>">Valor</label>
                                <br>
                                <input type="text" name="GPar_parcelas_valor<?php echo $item['parcela']?>" class="decimalMask" value="<?php echo floatToDinheiro2($item['valor'])?>"  id="parcelas_valor<?php echo $item['parcela']?>" />
                            </div>
                            
                    
                            <div class="system_item_form ">
                                <label for="parcelas_tipo_pagamento<?php echo $item['parcela']?>">Forma de Pagamento</label>
                                <br>
                                <select name="GPar_parcelas_tipo_pagamento<?php echo $item['parcela']?>">
                                
                                    <?php
                                    $topos_pagamento = $q->read("formas_de_pagamento");
                                    foreach($topos_pagamento as $tipo_pagamento):
                                        echo '<option '.selected($tipo_pagamento['id'],$item['forma_pagamento']).' value="'.$tipo_pagamento['id'].'">'.$tipo_pagamento['forma_de_pagamento'].'</option>';
                                    endforeach;
                                    ?>
                                
                                </select>
                            </div>
                    
                    
                            <div class="system_item_form ">
                                <label for="parcelas_pago<?php echo $item['parcela']?>">Pago</label>
                                <br>
                                <input type="checkbox" name="GPar_parcelas_pago<?php echo $item['parcela']?>" value="1"  <?php echo(($item['pago'] == 1)?' checked="checked" ':'')?>  id="parcelas_pago<?php echo $item['parcela']?>" style="margin-top:12px;" />
                            </div>
                            
                            
                            <div class="system_item_form  parcelas_data_pagamento_box<?php echo $item['parcela']?>" <?php echo(($item['pago'] > 0)?'style="display:block;"" ':'style="display:none;"')?> >
                                <label for="parcelas_data_pagamento<?php echo $item['parcela']?>">Data Pagamento</label>
                                <br>
                                <input type="text"  style="width:80px;"  name="GPar_parcelas_data_pagamento<?php echo $item['parcela']?>" id="parcelas_data_pagamento<?php echo $item['parcela']?>" class="dataMask" <?php echo(($item['pago'] != 0)?'value="'.substr($item['data_pagamento'],8,2).'/'.substr($item['data_pagamento'],5,2).'/'.substr($item['data_pagamento'],0,4).'" ': 'value="'.date("d/m/Y").'"')?> />
                                
                                
                            </div>
                            
                            
                        </div><!-- lista_parcelas_divide -->
                    <?php
                    $lin++;
                    
                    endforeach;
                endif;
                ?>
            
            </div>
        </div><!-- parcelas_lista -->
        
        <div id="lista_parcelas_content" style="display:none;">
            <div class="lista_parcelas_divide __class__">
                
                
                <script>
                $(function(){
                    $('#parcelas_pago__X').live('change',function(){
                        
                            if($(this).attr('checked')){
                                $(".parcelas_data_pagamento_box__X").show();
                            }else{
                                $(".parcelas_data_pagamento_box__X").hide();
                            }
                        
                        })
                    
                    
                })
                </script>
                
                <div class="system_item_form " style="width:100px;">
                    <br />
                    <label for="parcelas_parcela__X">Parcela __X</label>
                    <input type="hidden" value="__X" class="GP_num" name="GPar_parcelas_parcela_num__X" />
                   
                </div>
                <div class="system_item_form ">
                    <label for="parcelas_vencimento__X">Vencimento</label>
                    <br>
                    <input type="text" class="dataMask" name="GPar_parcelas_vencimento__X" id="parcelas_vencimento__X" style="width:80px;" />
                </div>
                
                
                <div class="system_item_form ">
                    <label for="parcelas_valor__X">Valor</label>
                    <br>
                    <input type="text" name="GPar_parcelas_valor__X" class="decimalMask"  id="parcelas_valor__X" />
                </div>
                
        
                <div class="system_item_form ">
                    <label for="parcelas_tipo_pagamento__X">Forma de Pagamento</label>
                    <br>
                    <select name="GPar_parcelas_tipo_pagamento__X">
                    
                        <?php
                        $topos_pagamento = $q->read("formas_de_pagamento");
                        foreach($topos_pagamento as $tipo_pagamento):
                            echo '<option value="'.$tipo_pagamento['id'].'">'.$tipo_pagamento['forma_de_pagamento'].'</option>';
                        endforeach;
                        ?>
                    
                    </select>
                </div>
        
        
                <div class="system_item_form ">
                    <label for="parcelas_pago__X">Pago</label>
                    <br>
                    <input type="checkbox" name="GPar_parcelas_pago__X" value="1" id="parcelas_pago__X" style="margin-top:12px;" />
                </div>
                
                
                <div class="system_item_form  parcelas_data_pagamento_box__X" style="display:none;">
                    <label for="parcelas_data_pagamento__X">Data Pagamento</label>
                    <br>
                    <input type="text"  style="width:80px;"  name="GPar_parcelas_data_pagamento__X" id="parcelas_data_pagamento__X" class="dataMask" value="<?php echo date("d/m/Y")?>" />
                </div>
                
                
            </div><!-- lista_parcelas_divide -->
        </div><!-- lista_parcelas_content -->
        
            
        </div><!-- body_ger_par -->


<?php
	}
}
?>
