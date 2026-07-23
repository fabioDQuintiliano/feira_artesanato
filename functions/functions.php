<?php
function base64_to_jpeg($base64_string, $output_file)
{
    $dir = dirname($output_file);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException('Pasta de upload indisponível: ' . $dir);
    }

    $ifp = fopen($output_file, 'wb');
    if ($ifp === false) {
        throw new RuntimeException('Não foi possível gravar a imagem em: ' . $output_file);
    }

    $data = explode(',', $base64_string, 2);
    $binario = base64_decode($data[1] ?? '', true);
    if ($binario === false) {
        fclose($ifp);
        throw new RuntimeException('Imagem em base64 inválida.');
    }

    fwrite($ifp, $binario);
    fclose($ifp);

    return $output_file;
}
function token($device)
{
    return sha1($device . 'vek' . (date('d') * 1));
}
function validatoken($info)
{
    $token = $info->token;
    $device = $info->device;
    if (!$token || !$device) {
        return false;
    }
    $codToken = token($device);
    if ($codToken == $token) {
        return true;
    } else {
        return false;
    }

}
function timestamp($data)
{
    return strtotime($data) * 1000;
}
function get_fcontent($url)
{


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;

}

function imageUrl($imagem)
{



    if (is_file('images/upload/' . $imagem)) {
        return ROOT . 'images/upload/' . $imagem;
    }
}

function getImagem($imagem, $thumb = false, $gerethumb = false)
{
    $nome = false;
    if ($thumb) {
        $nome = $imagem;
        $imagem = 'thumb_' . $imagem;
    }


    if (is_file('images/upload/' . $imagem)) {
        return ROOT . 'images/upload/' . $imagem;
    } else {
        if ($gerethumb) {
            if (is_file('images/upload/' . $nome)) {

                $image = new \Gumlet\ImageResize('images/upload/' . $nome);
                $image->crop(320, 320, true);
                $image->save('images/upload/' . $imagem);
                return ROOT . 'images/upload/' . $imagem;
            }
        }
    }
    return false;
}

function curl_load($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $html = curl_exec($ch);


    curl_close($ch);

    return $html;
}



function salvaImagem($img, $name = '')
{

    $getImage = file_get_contents($img);
    if ($getImage) {

        file_put_contents('images/upload/' . $name, $getImage);

        return $name;
    }
    return false;
}


function numWhatsap($num)
{
    return "550" . str_replace(array(' ', '(', ')', '-'), '', $num);
}

function ajax_load_class_function($classe, $funcao, $params = [])
{

    if ($_SESSION['user_id'] || strpos($classe, 'Eventos') !== false || 1 == 1) {
        $params = json_decode($params);
        $listparams = array();
        foreach ($params as $key => $value) {
            $listparams[] = $value;
        }
        $aux = new $classe();
        $dados = call_user_func_array(array($aux, $funcao), $listparams);
        return json_encode($dados);
    } else {
        return 'nao logado';
    }
}

function getConfiguracao($chave)
{

    $dao = DAO::System_config()->_loadAll();
    if ($dao->size()) {

        return $dao->{$chave};
    }
    return false;

}




function retornaLink($link)
{
    if ($link && $link != '') {

        if (strrpos($link, 'http://') >= 0 || strrpos($link, 'https://') >= 0) {
            return $link;
        }
        return 'http://' . $link;
    }
    return false;
}

function tratalink($base, $link)
{

    if ($link && trim($link) != '') {

        if (strrpos($link, 'http://') !== false || strrpos($link, 'https://') !== false) {
            return $link;
        } else {
            return $base . $link;
        }
    }

    return '';
}
function linkInstagram($link)
{
    if (substr($link, 0, 1) == '@') {
        $link = substr($link, 1, strlen($link));
    }

    return tratalink('https://instagram.com/', $link);
}
function linkFacebook($link)
{
    return tratalink('https://facebook.com/', $link);
}
function linkLinkedin($link)
{
    return tratalink('https://www.linkedin.com/in/', $link);
}

function execPost($postData)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        // set url, timeouts, encoding headers etc.
        CURLOPT_URL => 'https://graph.facebook.com/v22.0/584980888034389/messages',
        // ...
    ));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer EAAHp9jiz6hABOwEgZBM1yiBrtsy6PjmF7cZAcA1S7wxEyLavdrMMM8CaXBpWhI2zacdwmcIiI0Dpr5jZCIKwCjDjN9YnJdCOiqvU5zrA2htWY1hYdREiiE2qoWcg9bffLYRZBRodfJinY31zlVDeF1YNN7tpSCePcJyvZCCToWjmZA81AmHUrN6Mlhh61k4tfoiwZDZD',
        'Content-Type: application/json'
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);



    return $response;
}

function getInfo($campo)
{
    $dao = DAO::System_config()->_id(1)->_loadAll();

    return $dao->{$campo};
}