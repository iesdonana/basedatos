--------------------------
-- Base de datos PRUEBA --
--------------------------

-- Esquema:

DROP TABLE IF EXISTS depart CASCADE;

CREATE TABLE depart
(
    id        bigserial    PRIMARY KEY
  , dept_no   numeric(2)   NOT NULL UNIQUE
  , dnombre   varchar(255) NOT NULL
  , loc       varchar(255)
);

DROP TABLE IF EXISTS emple CASCADE;

CREATE TABLE emple
(
    id bigserial PRIMARY KEY
  , apellidos ...
  , depart_id bigint NOT NULL REFERENCES depart (id)
)

-- Fixtures:

INSERT INTO depart (dept_no, dnombre, loc)
VALUES (10, 'CONTABILIDAD', 'SANLÚCAR')
     , (20, 'INVESTIGACIÓN', 'JEREZ')
     , (30, 'VENTAS', 'CHIPIONA')
     , (40, 'PRODUCCIÓN', 'TREBUJENA');

-- Conversiones entre SQL y PHP:
-- NULL <=> null
-- bool <=> bool
-- entero (no numeric) <=> int (si cabe)
-- el resto <=> string

