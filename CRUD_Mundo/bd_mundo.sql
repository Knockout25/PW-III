CREATE DATABASE IF NOT EXISTS bd_mundo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_mundo;

CREATE TABLE continentes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL,
 populacao BIGINT NOT NULL DEFAULT 0,
 area DECIMAL(15,2) NOT NULL DEFAULT 0,
 total_paises INT NOT NULL DEFAULT 0
);

CREATE TABLE governantes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 partido_politico VARCHAR(150),
 data_nascimento DATE,
 idade INT,
 data_inicio_mandato DATE,
 data_final_mandato DATE
);

CREATE TABLE paises (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 continente_id INT NOT NULL,
 populacao BIGINT NOT NULL DEFAULT 0,
 area DECIMAL(15,2) NOT NULL DEFAULT 0,
 idioma VARCHAR(100),
 governante_id INT NULL,
 clima VARCHAR(100),
 regime_politico VARCHAR(100),
 moeda VARCHAR(100),
 CONSTRAINT fk_pais_continente FOREIGN KEY (continente_id) REFERENCES continentes(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_pais_governante FOREIGN KEY (governante_id) REFERENCES governantes(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE cidades (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 pais_id INT NOT NULL,
 populacao BIGINT NOT NULL DEFAULT 0,
 area DECIMAL(15,2) NOT NULL DEFAULT 0,
 clima VARCHAR(100),
 governante_id INT NULL,
 data_fundacao DATE,
 CONSTRAINT fk_cidade_pais FOREIGN KEY (pais_id) REFERENCES paises(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_cidade_governante FOREIGN KEY (governante_id) REFERENCES governantes(id) ON UPDATE CASCADE ON DELETE SET NULL
);

DELIMITER $$

DROP TRIGGER IF EXISTS trg_continentes_bi$$
DROP TRIGGER IF EXISTS trg_continentes_bu$$
DROP TRIGGER IF EXISTS trg_paises_bi$$
DROP TRIGGER IF EXISTS trg_paises_bu$$
DROP TRIGGER IF EXISTS trg_paises_ai$$
DROP TRIGGER IF EXISTS trg_paises_au$$
DROP TRIGGER IF EXISTS trg_paises_ad$$
DROP TRIGGER IF EXISTS trg_cidades_ai$$
DROP TRIGGER IF EXISTS trg_cidades_au$$
DROP TRIGGER IF EXISTS trg_cidades_ad$$

CREATE TRIGGER trg_continentes_bi
BEFORE INSERT ON continentes
FOR EACH ROW
BEGIN
 SET NEW.populacao = 0;
 SET NEW.total_paises = 0;
END$$

CREATE TRIGGER trg_continentes_bu
BEFORE UPDATE ON continentes
FOR EACH ROW
BEGIN
 SET NEW.populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = OLD.id);
 SET NEW.total_paises = (SELECT COUNT(*) FROM paises WHERE continente_id = OLD.id);
END$$

CREATE TRIGGER trg_paises_bi
BEFORE INSERT ON paises
FOR EACH ROW
BEGIN
 SET NEW.populacao = 0;
END$$

CREATE TRIGGER trg_paises_bu
BEFORE UPDATE ON paises
FOR EACH ROW
BEGIN
 SET NEW.populacao = (SELECT COALESCE(SUM(populacao), 0) FROM cidades WHERE pais_id = OLD.id);
END$$

CREATE TRIGGER trg_paises_ai
AFTER INSERT ON paises
FOR EACH ROW
BEGIN
 UPDATE continentes
 SET total_paises = (SELECT COUNT(*) FROM paises WHERE continente_id = NEW.continente_id),
	 populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = NEW.continente_id)
 WHERE id = NEW.continente_id;
END$$

CREATE TRIGGER trg_paises_au
AFTER UPDATE ON paises
FOR EACH ROW
BEGIN
 UPDATE continentes
 SET total_paises = (SELECT COUNT(*) FROM paises WHERE continente_id = OLD.continente_id),
	 populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = OLD.continente_id)
 WHERE id = OLD.continente_id;
 UPDATE continentes
 SET total_paises = (SELECT COUNT(*) FROM paises WHERE continente_id = NEW.continente_id),
	 populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = NEW.continente_id)
 WHERE id = NEW.continente_id;
END$$

CREATE TRIGGER trg_paises_ad
AFTER DELETE ON paises
FOR EACH ROW
BEGIN
 UPDATE continentes
 SET total_paises = (SELECT COUNT(*) FROM paises WHERE continente_id = OLD.continente_id),
	 populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = OLD.continente_id)
 WHERE id = OLD.continente_id;
END$$

CREATE TRIGGER trg_cidades_ai
AFTER INSERT ON cidades
FOR EACH ROW
BEGIN
 UPDATE paises
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM cidades WHERE pais_id = NEW.pais_id)
 WHERE id = NEW.pais_id;
 UPDATE continentes c
 JOIN paises p ON p.continente_id = c.id
 SET c.populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = c.id)
 WHERE p.id = NEW.pais_id;
END$$

CREATE TRIGGER trg_cidades_au
AFTER UPDATE ON cidades
FOR EACH ROW
BEGIN
 UPDATE paises
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM cidades WHERE pais_id = OLD.pais_id)
 WHERE id = OLD.pais_id;
 UPDATE paises
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM cidades WHERE pais_id = NEW.pais_id)
 WHERE id = NEW.pais_id;
 UPDATE continentes
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = OLD.continente_id)
 WHERE id = OLD.continente_id;
 UPDATE continentes
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = NEW.continente_id)
 WHERE id = NEW.continente_id;
END$$

CREATE TRIGGER trg_cidades_ad
AFTER DELETE ON cidades
FOR EACH ROW
BEGIN
 UPDATE paises
 SET populacao = (SELECT COALESCE(SUM(populacao), 0) FROM cidades WHERE pais_id = OLD.pais_id)
 WHERE id = OLD.pais_id;
 UPDATE continentes c
 JOIN paises p ON p.continente_id = c.id
 SET c.populacao = (SELECT COALESCE(SUM(populacao), 0) FROM paises WHERE continente_id = c.id)
 WHERE p.id = OLD.pais_id;
END$$

DELIMITER ;

UPDATE paises p
SET p.populacao = (SELECT COALESCE(SUM(c.populacao), 0) FROM cidades c WHERE c.pais_id = p.id);

UPDATE continentes c
SET c.populacao = (SELECT COALESCE(SUM(p.populacao), 0) FROM paises p WHERE p.continente_id = c.id),
	c.total_paises = (SELECT COUNT(*) FROM paises p WHERE p.continente_id = c.id);