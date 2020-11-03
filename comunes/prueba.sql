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
    id        bigserial     PRIMARY KEY
  , emp_no    numeric(4)    NOT NULL UNIQUE
  , apellidos varchar(255)  NOT NULL
  , salario   numeric(8, 2) NOT NULL
  , comision  numeric(8, 2)
  , fecha_alt timestamp
  , oficio    varchar(255)
  , jefe_id   bigint        REFERENCES emple (id)
  , depart_id bigint        NOT NULL REFERENCES depart (id)
);

-- Fixtures:

INSERT INTO depart (dept_no, dnombre, loc)
VALUES (10, 'CONTABILIDAD', 'SANLÚCAR')
     , (20, 'INVESTIGACIÓN', 'JEREZ')
     , (30, 'VENTAS', 'CHIPIONA')
     , (40, 'PRODUCCIÓN', 'TREBUJENA');

INSERT INTO emple (emp_no, apellidos, salario, comision, fecha_alt,
                   oficio, jefe_id, depart_id)
VALUES ('1111', 'GARCÍA', 2345.23, NULL, '2018-11-04 14:00:00',
        'PROGRAMADOR', NULL, 4)
     , ('2222', 'PÉREZ', 1804.87, 230.00, '2015-06-02 18:23:15',
        'VENDEDOR', NULL, 3)
     , ('3333', 'MARTÍNEZ', 1500.00, 200.00, '2016-02-02 14:00:00',
        'EMPLEADO', 2, 3);

-- Conversiones entre SQL y PHP:
-- NULL <=> null
-- bool <=> bool
-- entero (no numeric) <=> int (si cabe)
-- el resto <=> string

