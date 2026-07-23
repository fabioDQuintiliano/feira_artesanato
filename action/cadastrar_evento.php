<?php

header('Content-Type: application/json; charset=utf-8');

$ERROR = false;
$ERROR_MSG = '';
$INFO = [];
$MSG = '';

try {
    $body = json_decode(file_get_contents('php://input'));

    if (!$body) {
        throw new Exception('Dados não recebidos.');
    }

    $nome = trim((string) ($body->nome ?? ''));
    $descricao = trim((string) ($body->descricao ?? ''));
    $data_evento = trim((string) ($body->data ?? ''));
    $hora_evento = trim((string) ($body->hora ?? ''));
    $local = trim((string) ($body->local ?? ''));
    $valor = trim((string) ($body->valor ?? ''));
    $faixa_etaria = trim((string) ($body->faixa_etaria ?? ''));
    $imagem_base64 = (string) ($body->imagem ?? '');
    $tags_brutas = trim((string) ($body->tags ?? ''));

    if ($nome === '') {
        throw new Exception('O nome do evento é obrigatório.');
    }
    if ($data_evento === '' || !preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data_evento)) {
        throw new Exception('Informe uma data válida no formato DD/MM/AAAA.');
    }
    if ($hora_evento === '' || !preg_match('/^\d{2}:\d{2}$/', $hora_evento)) {
        $hora_evento = '00:00';
    }

    $dataBanco = date2banco($data_evento);
    if (!strtotime($dataBanco . ' ' . $hora_evento . ':00')) {
        throw new Exception('Data ou hora inválida.');
    }

    $urlImagem = null;
    if ($imagem_base64 !== '' && str_contains($imagem_base64, 'base64,')) {
        $dirUpload = __DIR__ . '/../images/upload';
        if (!is_dir($dirUpload) && !mkdir($dirUpload, 0775, true) && !is_dir($dirUpload)) {
            throw new Exception('Não foi possível criar a pasta de upload.');
        }

        $nomeImagem = md5(uniqid((string) microtime(true), true)) . '.png';
        $caminhoImagem = $dirUpload . '/' . $nomeImagem;
        base64_to_jpeg($imagem_base64, $caminhoImagem);

        if (!is_file($caminhoImagem)) {
            throw new Exception('Falha ao salvar a imagem do evento.');
        }

        $urlImagem = rtrim(ROOT, '/') . '/images/upload/' . $nomeImagem;
    }

    $criadoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? 1);
    if ($criadoPor <= 0) {
        $criadoPor = 1;
    }

    $dao = DAO::eventos();
    $dao->txtid = gen_uuid();
    $dao->nome = $nome;
    $dao->descricao = $descricao !== '' ? $descricao : null;
    $dao->data_evento = $dataBanco . ' ' . $hora_evento . ':00';
    $dao->local = $local !== '' ? $local : null;
    $dao->valor = $valor !== '' ? $valor : null;
    $dao->faixa_etaria = $faixa_etaria !== '' ? $faixa_etaria : null;
    $dao->imagem = $urlImagem;
    $dao->created_by = $criadoPor;
    $dao->last_edit_by = $criadoPor;
    $dao->created_on = date('Y-m-d H:i:s');

    $eventoId = $dao->save();

    if (!$eventoId) {
        throw new Exception('Erro ao salvar o evento.');
    }

    if ($tags_brutas !== '') {
        $tags = array_unique(array_filter(array_map('trim', explode(',', $tags_brutas))));
        foreach ($tags as $tagName) {
            $tagName = mb_substr($tagName, 0, 50, 'UTF-8');
            if ($tagName === '') {
                continue;
            }

            $daoTag = DAO::tags()->_nome($tagName)->_loadAll();
            if ($daoTag->size()) {
                $tagId = $daoTag->id;
            } else {
                $newTag = DAO::tags();
                $newTag->nome = $tagName;
                $tagId = $newTag->save();
            }

            if ($tagId) {
                $vinc = DAO::evento_tags();
                $vinc->evento = $eventoId;
                $vinc->tag = $tagId;
                $vinc->id = 0;
                $vinc->save();
            }
        }
    }

    $INFO = ['id' => (int) $eventoId, 'txtid' => $dao->txtid];
    $MSG = 'Evento cadastrado com sucesso!';
} catch (Throwable $e) {
    $ERROR = true;
    $ERROR_MSG = $e->getMessage();
}

echo json_encode([
    'error' => $ERROR,
    'error_msg' => $ERROR_MSG,
    'data' => $INFO,
    'msg' => $MSG,
], JSON_UNESCAPED_UNICODE);
exit;
