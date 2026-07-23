<?php
namespace Sistema;
use \DAO;

/**
 * Classe para gerenciamento de eventos no portal Agenda Cultural
 */
class Eventos
{
    /** Tipo de estabelecimento: Bares e Restaurantes */
    public const TIPO_BARES_RESTAURANTES = 0;

    /**
     * Retorna os eventos mais recentes ou filtrados
     * @param int $limite Quantidade máxima de eventos
     * @param string|array|null $tag Filtrar por uma ou mais tags (AND: o evento precisa ter todas). Aceita string, CSV ou array.
     * @param int $offset Deslocamento para paginação
     * @param string|null $data Filtrar por data (Y-m-d)
     * @param int|null $tipoEstabelecimento Filtrar por tipo do estabelecimento (ex.: 0 = Bares e Restaurantes)
     * @param int|null $excluirTipoEstabelecimento Exclui eventos de estabelecimentos com este tipo
     * @return array
     */
    static function getEventos(
        $limite = 10,
        $tag = null,
        $offset = 0,
        $data = null,
        $tipoEstabelecimento = null,
        $excluirTipoEstabelecimento = null
    ) {
        $lista = [];

        $dao = DAO::eventos();

        $tags = [];
        if (is_array($tag)) {
            $tags = array_values(array_filter(array_map('trim', $tag)));
        } elseif (is_string($tag) && $tag !== '') {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tag))));
        }

        if ($tags) {
            $tagIds = [];
            foreach ($tags as $tagNome) {
                $daoTag = DAO::tags()->_nome($tagNome)->_loadAll();
                if ($daoTag->size()) {
                    $tagIds[] = (int) $daoTag->id;
                }
            }
            if ($tagIds) {
                $ids = implode(',', $tagIds);
                $qtd = count($tagIds);
                $dao->_where("id IN (
                    SELECT evento FROM evento_tags
                    WHERE tag IN ($ids)
                    GROUP BY evento
                    HAVING COUNT(DISTINCT tag) = $qtd
                )");
            }
        }

        if ($tipoEstabelecimento !== null && $tipoEstabelecimento !== '') {
            $tipo = (int) $tipoEstabelecimento;
            $dao->_where("estabelecimento IN (
                SELECT id FROM estabelecimentos WHERE tipo = $tipo
            )");
        } elseif ($excluirTipoEstabelecimento !== null && $excluirTipoEstabelecimento !== '') {
            $tipoExcluir = (int) $excluirTipoEstabelecimento;
            $dao->_where("(estabelecimento IS NULL OR estabelecimento NOT IN (
                SELECT id FROM estabelecimentos WHERE tipo = $tipoExcluir
            ))");
        }

        if ($data) {
            // Se houver filtro por data específica (Y-m-d)
            $dao->_where("DATE(data_evento) = '$data'");
        } else {
            // Filtra eventos futuros (ou todos se preferir, aqui filtramos >= hoje)
            $hoje = date('Y-m-d H:i:s');
            $dao->_where("data_evento >= '$hoje'");
        }

        $dao->_loadAll("data_evento ASC LIMIT $limite OFFSET $offset");

        if ($dao->size()) {
            do {
                $lista[] = [
                    'id' => $dao->id,
                    'txtid' => $dao->txtid,
                    'titulo' => $dao->nome,
                    'descricao' => $dao->descricao,
                    'imagem' => self::normalizarImagem($dao->imagem),
                    'data' => date('d/m/Y', strtotime($dao->data_evento)),
                    'hora' => date('H:i', strtotime($dao->data_evento)),
                    'link' => $dao->link,
                    'local' => $dao->local,
                    'valor' => $dao->valor,
                    'faixa_etaria' => $dao->faixa_etaria,
                    'tags' => self::getTagsDoEvento($dao->id)
                ];
            } while ($dao->next());
        }

        return $lista;
    }

    /**
     * Conta eventos disponíveis conforme filtros
     * @param string|array|null $tag
     * @param string|null $data
     * @param int|null $tipoEstabelecimento
     * @param int|null $excluirTipoEstabelecimento
     * @return int
     */
    static function contarEventos(
        $tag = null,
        $data = null,
        $tipoEstabelecimento = null,
        $excluirTipoEstabelecimento = null
    ) {
        $where = [];

        $tags = [];
        if (is_array($tag)) {
            $tags = array_values(array_filter(array_map('trim', $tag)));
        } elseif (is_string($tag) && $tag !== '') {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tag))));
        }

        if ($tags) {
            $tagIds = [];
            foreach ($tags as $tagNome) {
                $daoTag = DAO::tags()->_nome($tagNome)->_loadAll();
                if ($daoTag->size()) {
                    $tagIds[] = (int) $daoTag->id;
                }
            }
            if ($tagIds) {
                $ids = implode(',', $tagIds);
                $qtd = count($tagIds);
                $where[] = "e.id IN (
                    SELECT evento FROM evento_tags
                    WHERE tag IN ($ids)
                    GROUP BY evento
                    HAVING COUNT(DISTINCT tag) = $qtd
                )";
            } else {
                return 0;
            }
        }

        if ($tipoEstabelecimento !== null && $tipoEstabelecimento !== '') {
            $tipo = (int) $tipoEstabelecimento;
            $where[] = "e.estabelecimento IN (
                SELECT id FROM estabelecimentos WHERE tipo = $tipo
            )";
        } elseif ($excluirTipoEstabelecimento !== null && $excluirTipoEstabelecimento !== '') {
            $tipoExcluir = (int) $excluirTipoEstabelecimento;
            $where[] = "(e.estabelecimento IS NULL OR e.estabelecimento NOT IN (
                SELECT id FROM estabelecimentos WHERE tipo = $tipoExcluir
            ))";
        }

        if ($data) {
            $data = preg_replace('/[^0-9\-]/', '', (string) $data);
            $where[] = "DATE(e.data_evento) = '$data'";
        } else {
            $hoje = date('Y-m-d H:i:s');
            $where[] = "e.data_evento >= '$hoje'";
        }

        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT COUNT(*) as total FROM eventos e $whereSql";
        $dao = DAO::doQuery($sql);

        return $dao->size() ? (int) $dao->total : 0;
    }

    /**
     * Retorna as tags vinculadas a um evento específico
     * @param int $eventoId
     * @return array
     */
    static function getTagsDoEvento($eventoId)
    {
        $tags = [];
        $sql = "SELECT t.nome 
                FROM tags t 
                INNER JOIN evento_tags et ON et.tag = t.id 
                WHERE et.evento = $eventoId";

        $dao = DAO::doQuery($sql);
        if ($dao->size()) {
            do {
                $tags[] = $dao->nome;
            } while ($dao->next());
        }

        return $tags;
    }

    /**
     * Retorna as tags mais populares para os filtros
     * @return array
     */
    static function getTagsPopulares($limite = 8)
    {
        $tags = [];
        $sql = "SELECT t.nome, COUNT(et.evento) as total 
                FROM tags t 
                INNER JOIN evento_tags et ON et.tag = t.id 
                GROUP BY t.id 
                ORDER BY total DESC 
                LIMIT $limite";

        $dao = DAO::doQuery($sql);
        if ($dao->size()) {
            do {
                $tags[] = $dao->nome;
            } while ($dao->next());
        }

        return $tags;
    }

    /**
     * Retorna eventos sugeridos com base nos interesses do usuário (OR entre tags).
     * Prioriza eventos que batem com mais interesses e, em seguida, a data mais próxima.
     *
     * @param string|array|null $interesses Tags de interesse (string CSV ou array)
     * @param int $limite Quantidade máxima (padrão 8)
     * @param int|null $excluirTipoEstabelecimento Exclui estabelecimentos deste tipo (ex.: bares)
     * @return array
     */
    static function getEventosPorInteresses(
        $interesses = null,
        $limite = 8,
        $excluirTipoEstabelecimento = null
    ) {
        $limite = max(1, (int) $limite);
        $tags = [];
        if (is_array($interesses)) {
            $tags = array_values(array_filter(array_map('trim', $interesses)));
        } elseif (is_string($interesses) && $interesses !== '') {
            $tags = array_values(array_filter(array_map('trim', explode(',', $interesses))));
        }

        if (!$tags) {
            return self::getEventos(
                $limite,
                null,
                0,
                null,
                null,
                $excluirTipoEstabelecimento
            );
        }

        $tagIds = [];
        foreach ($tags as $tagNome) {
            $daoTag = DAO::tags()->_nome($tagNome)->_loadAll();
            if ($daoTag->size()) {
                $tagIds[] = (int) $daoTag->id;
            }
        }

        if (!$tagIds) {
            return self::getEventos(
                $limite,
                null,
                0,
                null,
                null,
                $excluirTipoEstabelecimento
            );
        }

        $ids = implode(',', array_unique($tagIds));
        $hoje = date('Y-m-d H:i:s');
        $whereExtra = '';

        if ($excluirTipoEstabelecimento !== null && $excluirTipoEstabelecimento !== '') {
            $tipoExcluir = (int) $excluirTipoEstabelecimento;
            $whereExtra = " AND (e.estabelecimento IS NULL OR e.estabelecimento NOT IN (
                SELECT id FROM estabelecimentos WHERE tipo = $tipoExcluir
            ))";
        }

        $sql = "SELECT e.id, e.txtid, e.nome, e.descricao, e.imagem, e.data_evento,
                       e.link, e.local, e.valor, e.faixa_etaria,
                       COUNT(DISTINCT et.tag) AS matches
                FROM eventos e
                INNER JOIN evento_tags et ON et.evento = e.id
                WHERE et.tag IN ($ids)
                  AND e.data_evento >= '$hoje'
                  $whereExtra
                GROUP BY e.id, e.txtid, e.nome, e.descricao, e.imagem, e.data_evento,
                         e.link, e.local, e.valor, e.faixa_etaria
                ORDER BY matches DESC, e.data_evento ASC
                LIMIT $limite";

        $lista = [];
        $dao = DAO::doQuery($sql);
        if ($dao->size()) {
            do {
                $lista[] = [
                    'id' => $dao->id,
                    'txtid' => $dao->txtid,
                    'titulo' => $dao->nome,
                    'descricao' => $dao->descricao,
                    'imagem' => self::normalizarImagem($dao->imagem),
                    'data' => date('d/m/Y', strtotime($dao->data_evento)),
                    'hora' => date('H:i', strtotime($dao->data_evento)),
                    'link' => $dao->link,
                    'local' => $dao->local,
                    'valor' => $dao->valor,
                    'faixa_etaria' => $dao->faixa_etaria,
                    'tags' => self::getTagsDoEvento($dao->id)
                ];
            } while ($dao->next());
        }

        // Completa com próximos eventos se houver poucos matches
        if (count($lista) < $limite) {
            $faltam = $limite - count($lista);
            $idsExistentes = array_column($lista, 'id');
            $extras = self::getEventos(
                $faltam + count($idsExistentes),
                null,
                0,
                null,
                null,
                $excluirTipoEstabelecimento
            );
            foreach ($extras as $extra) {
                if (in_array($extra['id'], $idsExistentes, true)) {
                    continue;
                }
                $lista[] = $extra;
                if (count($lista) >= $limite) {
                    break;
                }
            }
        }

        return $lista;
    }

    /**
     * Retorna um evento específico pelo seu txtid (slug)
     * @param string $txtid
     * @return array|null
     */
    static function getEventoByTxtId($txtid)
    {
        $dao = DAO::eventos()->_txtid($txtid)->_loadAll();

        if ($dao->size()) {
            return [
                'id' => $dao->id,
                'txtid' => $dao->txtid,
                'titulo' => $dao->nome,
                'descricao' => $dao->descricao,
                'imagem' => self::normalizarImagem($dao->imagem),
                'data' => date('d/m/Y', strtotime($dao->data_evento)),
                'hora' => date('H:i', strtotime($dao->data_evento)),
                'link' => $dao->link,
                'local' => $dao->local,
                'valor' => $dao->valor,
                'faixa_etaria' => $dao->faixa_etaria,
                'tags' => self::getTagsDoEvento($dao->id)
            ];
        }

        return null;
    }

    /**
     * Aceita URL absoluta, caminho relativo ou só o nome do arquivo em images/upload.
     */
    private static function normalizarImagem($imagem): string
    {
        $imagem = trim((string) $imagem);
        if ($imagem === '' || strcasecmp($imagem, 'null') === 0) {
            return '/images/placeholder-event.jpg';
        }

        if (preg_match('#^(https?:)?//#i', $imagem) || str_starts_with($imagem, 'data:')) {
            return $imagem;
        }

        if (str_starts_with($imagem, '/')) {
            return rtrim(ROOT, '/') . $imagem;
        }

        return rtrim(ROOT, '/') . '/images/upload/' . ltrim($imagem, '/');
    }

    /**
     * Retorna os dias que possuem eventos em um determinado mês/ano
     * @param int $mes
     * @param int $ano
     * @return array Lista de datas strings 'Y-m-d'
     */
    static function getDatasComEventos($mes, $ano)
    {
        $datas = [];
        $dataInicio = "$ano-$mes-01 00:00:00";
        $dataFim = date("Y-m-t 23:59:59", strtotime($dataInicio));

        $sql = "SELECT DISTINCT DATE(data_evento) as data_dia 
                FROM eventos 
                WHERE data_evento BETWEEN '$dataInicio' AND '$dataFim'
                ORDER BY data_dia ASC";

        $dao = DAO::doQuery($sql);
        if ($dao->size()) {
            do {
                $datas[] = $dao->data_dia;
            } while ($dao->next());
        }

        return $datas;
    }
}
