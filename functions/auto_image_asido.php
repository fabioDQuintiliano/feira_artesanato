<?php
function resizeImage($imagem_origem,$w='',$h='',$imagem_destino='',$STRETCH = false){

	$image = new \ImageResize($imagem_origem);
	
//	$i1 = asido::image($imagem_origem, $imagem_destino);

	if($w != '' && $h == ''){

		$image->resizeToWidth($w);

	}

	if($w == '' && $h != ''){

		$image->resizeToHeight($h);

	}

	if($w != '' && $h != ''){

		

		if($STRETCH == true){

			$image->resize($w, $h, true);

		}else{

			$image->resizeToBestFit($w, $h);

		}

	}

	$image->save($imagem_destino);

	

}

function marcaImage($imagem_origem,$imagem_destino=''){

	asido::driver('gd');

	$imagem_destino = $imagem_destino != ''?$imagem_destino:$imagem_origem;	

	

	$i1 = asido::image($imagem_origem, $imagem_destino);

	$watermark = 'images/logo_marcadagua.png';

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_TOP_LEFT);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_TOP_CENTER);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_TOP_RIGHT);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_MIDDLE_LEFT);

	Asido::watermark($i1, $watermark, ASIDO_WATERMARK_MIDDLE_CENTER);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_MIDDLE_RIGHT);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_BOTTOM_LEFT);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_BOTTOM_CENTER);

	//Asido::watermark($i1, $watermark, ASIDO_WATERMARK_BOTTOM_RIGHT);

	$i1->save(ASIDO_OVERWRITE_ENABLED);

	

}

function cropImage($imagem_origem,$imagem_destino='',$largura='',$altura=''){

	$imagem_destino = $imagem_destino != ''?$imagem_destino:$imagem_origem;	

	asido::driver('gd');

	

	$i1 = asido::image($imagem_origem, $imagem_destino);

	//Asido::Crop($i1, 0, 0, 300, 300);

	Asido::Crop($i1, 0, 0, $largura, $altura);

	$i1->save(ASIDO_OVERWRITE_ENABLED);

	
}

?>