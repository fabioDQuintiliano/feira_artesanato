-- Schema do site 2º Encontro de Ceramistas (Arceburgo-MG)
-- Tabelas: expositores, expositores_fotos, programacao

CREATE TABLE IF NOT EXISTS `expositores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `nome` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `resumo` VARCHAR(400) DEFAULT NULL,
  `descricao` TEXT NOT NULL,
  `categoria` VARCHAR(80) DEFAULT NULL,
  `grupo` VARCHAR(40) NOT NULL DEFAULT 'artesao',
  `logo` VARCHAR(255) DEFAULT NULL,
  `foto_destaque` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(120) DEFAULT NULL,
  `whatsapp` VARCHAR(40) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_expositores_txtid` (`txtid`),
  UNIQUE KEY `uk_expositores_slug` (`slug`),
  KEY `idx_expositores_ativo_ordem` (`ativo`, `ordem`),
  KEY `idx_expositores_grupo` (`grupo`, `ativo`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `expositores_fotos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expositor_id` INT UNSIGNED NOT NULL,
  `arquivo` VARCHAR(255) NOT NULL,
  `legenda` VARCHAR(255) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expositores_fotos_expositor` (`expositor_id`, `ordem`),
  CONSTRAINT `fk_expositores_fotos_expositor`
    FOREIGN KEY (`expositor_id`) REFERENCES `expositores` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `programacao` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `dia` DATE NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fim` TIME DEFAULT NULL,
  `local` VARCHAR(180) DEFAULT NULL,
  `categoria` VARCHAR(60) DEFAULT NULL,
  `icone` VARCHAR(40) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_programacao_txtid` (`txtid`),
  KEY `idx_programacao_dia_hora` (`ativo`, `dia`, `hora_inicio`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
