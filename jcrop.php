<?php
include 'front_includes.php';
?>
<script src="script/jquery-1.9.0.js"></script>
<script src="script/jquery-migrate-1.0.0.js"></script>

<script type="text/javascript" src="script/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="script/jquery.color.js"></script>
<link href="css/jquery.Jcrop.css" rel="stylesheet" type="text/css" />

<script>
$(function(){
	
	$("#files_up").change(function(){
		$('#formCrop').submit();
	});	
});

</script>
<style>
#boxCrop{ float:left; clear:both; margin-top:20px;}
#imageCrop{}
#cropBtn{ position:absolute; right:10px; top:3px; background:#9de48f ; color:#fff; border:none; font-weight:bold; z-index:2; padding:5px 10px 5px 10px; border-radius:5px;}
.bt_style{background:#9de48f ; color:#fff; border:none; font-weight:bold; z-index:2; padding:5px 10px 5px 10px; border-radius:5px;}
#cropBtnCel{ color:#fff; border:none; font-weight:bold; z-index:2; padding:5px 10px 5px 10px; border-radius:5px; background:#ff5454; position:absolute;right:170px; top:5px;}
.myfileupload-buttonbar{ float:left; margin-bottom:5px; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;}
.myfileupload-buttonbar input
        {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            border: solid transparent;
            border-width: 0 0 100px 200px;
            opacity: 0.0;
            filter: alpha(opacity=0);
            -o-transform: translate(250px, -50px) scale(1);
            -moz-transform: translate(-300px, 0) scale(4);
            direction: ltr;
            cursor: pointer;
        }
        .myui-button
        {
            position: relative;
            cursor: pointer;
            text-align: center;
            overflow: visible;
            background-color: #45b9bd;
            overflow: hidden;
			padding:5px 5px 5px 5px ;
			color:#fff;
			font-weight:100;
			border-radius:3px;
        }
		

</style>

<form method="post" id="formCrop" enctype="multipart/form-data">
    
    
    <div class="myfileupload-buttonbar ">
        <label class="myui-button">
            <span ><strong>Selecionar arquivo</strong></span>
            <input type="file" name="arq" id="files_up"  />
        </label>
    </div>  
    
    
  
</form>


<?php
if(isset($_FILES['arq'])){
	$dados = $_FILES['arq'];
	if(substr($dados['type'],0,5)=='image'){
		$img = getimagesize($dados['tmp_name']);
		
		$nomeFile = md5(rand(0,99999).microtime()).'.'.str_replace('image/','',$dados['type']);
		
		$paramw = $_GET['w'];
		$paramh = $_GET['h'];
		
		$imgW = $img[0];
		$imgH = $img[1];
		
		
		$nH = $imgH*$paramw/$imgW;
		
		$nW = $paramw;
		
		if($nH<$paramh){
			$nW = $imgW*$paramh/$imgH;
			$nH = $paramh;
		}
		
		
		//exit;
		if(uploadnoagua($dados['tmp_name'], $nomeFile, $nW, 'images/upload/',$dados['type'])){
			
		?>
                

            <script>
			function updateCoords(c){
				x = c.x;
				y = c.y;
				w = c.w;
				h = c.h;
			};
			
			$(function(){
			  
				$("#cropBtn").click(function(){
					
					$.post('fn-cropImagem',{imagem:'<?php echo $nomeFile?>',w:w,h:h,x:x,y:y,view:'<?php echo $_GET['view']?>'},function(o){
						
						window.opener.reloadDiv('<img src="<?php echo ROOT;?>images/upload/view_<?php echo $nomeFile?>" /><input type="hidden" value="<?php echo $nomeFile?>" name="<?php echo $_GET['campo']?>" />','<?php echo $_GET['campo']?>');
						window.close();
					});

				});
				
			 
				$('#imageCrop').Jcrop({
					aspectRatio:<?php echo $paramw?> / <?php echo $paramh?>,
					bgColor:'#fff',
					setSelect:   [<?php echo $paramw?> , <?php echo $paramh?>,0, 0,],
					minSize:[<?php echo $paramw?> , <?php echo $paramh?>],
					onSelect: updateCoords
				});
			
			});
            </script>
            
            <input type="button" value="Recortar Imagem" id="cropBtn" />
            <div id="boxCrop">
            <img src="images/upload/<?php echo $nomeFile?>" id="imageCrop" />
            </div>
		<?php
		}
	}
}
?>