-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-07-2020 a las 08:31:02
-- Versión del servidor: 8.0.18
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `db_tiendavirtual`
--

DROP TABLE IF EXISTS ROL;
CREATE TABLE IF NOT EXISTS ROL
(
   ROL_ID          bigint(20)                             NOT NULL AUTO_INCREMENT,
   ROL_NOMBRE      varchar(50) COLLATE utf8mb4_swedish_ci NOT NULL,
   ROL_DESCRIPCION text        COLLATE utf8mb4_swedish_ci NOT NULL,
   ROL_STATUS      int(11)                                NOT NULL DEFAULT '1',
   PRIMARY KEY (ROL_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;


DROP TABLE IF EXISTS PERSONA;
CREATE TABLE IF NOT EXISTS PERSONA
(
   PER_ID               bigint(20)                              NOT NULL AUTO_INCREMENT,
   PER_IDENTIFICACION   varchar(30)  COLLATE utf8mb4_swedish_ci NOT NULL,
   PER_NOMBRE           varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL,
   PER_APELLIDOS        varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL,
   PER_TELEFONO         bigint(20)                              NOT NULL,
   PER_EMAIL            varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL,
   PER_PASSWORD         varchar(75)  COLLATE utf8mb4_swedish_ci NOT NULL,
   PER_NIT              varchar(20)  COLLATE utf8mb4_swedish_ci NULL,
   PER_NOMBRE_FISCAL    varchar(100) COLLATE utf8mb4_swedish_ci NULL,
   PER_DIRECCION_FISCAL varchar(100) COLLATE utf8mb4_swedish_ci NULL,
   PER_TOKEN            varchar(80)  COLLATE utf8mb4_swedish_ci NULL,
   PER_ROL_ID           bigint(20)                              NOT NULL,
   PER_DATECREATED      datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PER_STATUS           int(11)                                 NOT NULL DEFAULT '1',
   PRIMARY KEY (PER_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE PERSONA
   ADD CONSTRAINT FK_PER_ROL FOREIGN KEY (PER_ROL_ID)
      REFERENCES ROL (ROL_ID) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS MODULO;
CREATE TABLE IF NOT EXISTS MODULO
(
   MOD_ID          bigint(20)                             NOT NULL AUTO_INCREMENT,
   MOD_TITULO      varchar(50) COLLATE utf8mb4_swedish_ci NOT NULL,
   MOD_DESCRIPCION text        COLLATE utf8mb4_swedish_ci NOT NULL,
   MOD_STATUS      int(11)                                NOT NULL DEFAULT '1',
   PRIMARY KEY (MOD_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

DROP TABLE IF EXISTS PERMISOS;
CREATE TABLE IF NOT EXISTS PERMISOS
(
   PRM_ID     bigint(20) NOT NULL AUTO_INCREMENT,
   PRM_ROL_ID bigint(20) NOT NULL,
   PRM_MOD_ID bigint(20) NOT NULL,
   PRM_R      int(11)    NOT NULL DEFAULT '0',
   PRM_W      int(11)    NOT NULL DEFAULT '0',
   PRM_U      int(11)    NOT NULL DEFAULT '0',
   PRM_D      int(11)    NOT NULL DEFAULT '0',
   PRIMARY KEY (PRM_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE PERMISOS
   ADD CONSTRAINT FK_PRM_ROL FOREIGN KEY (PRM_ROL_ID)
      REFERENCES ROL (ROL_ID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE PERMISOS
   ADD CONSTRAINT FK_PRM_MOD FOREIGN KEY (PRM_MOD_ID)
      REFERENCES MODULO (MOD_ID) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS CATEGORIA;
CREATE TABLE IF NOT EXISTS CATEGORIA
(
   CAT_ID          bigint(20)                              NOT NULL AUTO_INCREMENT,
   CAT_NOMBRE      varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL,
   CAT_DESCRIPCION text         COLLATE utf8mb4_swedish_ci NOT NULL,
   CAT_DATECREATED datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP,
   CAT_STATUS      int(11)                                 NOT NULL DEFAULT '1',
  PRIMARY KEY (CAT_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

DROP TABLE IF EXISTS PRODUCTO;
CREATE TABLE IF NOT EXISTS PRODUCTO
(
   PRO_ID          bigint(20)                               NOT NULL AUTO_INCREMENT,
   PRO_CAT_ID      bigint(20)                               NOT NULL,
   PRO_CODIGO      varchar(30)   COLLATE utf8mb4_swedish_ci NOT NULL,
   PRO_NOMBRE      varchar(100)  COLLATE utf8mb4_swedish_ci NOT NULL,
   PRO_DESCRIPCION text          COLLATE utf8mb4_swedish_ci NOT NULL,
   PRO_PRECIO      decimal(11,2)                            NOT NULL,
   PRO_STOCK       int(11)                                  NOT NULL,
   PRO_IMAGEN      varchar(100)  COLLATE utf8mb4_swedish_ci NOT NULL,
   PRO_DATECREATED datetime                                 NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRO_STATUS      int(11)                                  NOT NULL DEFAULT '1',
   PRIMARY KEY (PRO_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE PRODUCTO
   ADD CONSTRAINT FK_PRO_CAT FOREIGN KEY (PRO_CAT_ID)
      REFERENCES CATEGORIA (CAT_ID) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS PEDIDO;
CREATE TABLE IF NOT EXISTS PEDIDO
(
   PED_ID        bigint(20)    NOT NULL AUTO_INCREMENT,
   PED_PER_ID    bigint(20)    NOT NULL,
   PED_FECHA     datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PED_MONTO     decimal(11,2) NOT NULL,
   PED_TIPO_PAGO bigint(20)    NOT NULL,
   PED_STATUS    int(11)       NOT NULL DEFAULT '1',
   PRIMARY KEY (PED_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE PEDIDO
   ADD CONSTRAINT FK_PED_PER FOREIGN KEY (PED_PER_ID)
      REFERENCES PERSONA (PER_ID) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS DETALLE_PEDIDO;
CREATE TABLE IF NOT EXISTS DETALLE_PEDIDO
(
   DPE_ID       bigint(20)    NOT NULL AUTO_INCREMENT,
   DPE_PED_ID   bigint(20)    NOT NULL,
   DPE_PRO_ID   bigint(20)    NOT NULL,
   DPE_PRECIO   decimal(11,2) NOT NULL,
   DPE_CANTIDAD int(11)       NOT NULL,
  PRIMARY KEY (DPE_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE DETALLE_PEDIDO
   ADD CONSTRAINT FK_DPE_PED FOREIGN KEY (DPE_PED_ID)
      REFERENCES PEDIDO (PED_ID) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE DETALLE_PEDIDO
   ADD CONSTRAINT FK_DPE_PRO FOREIGN KEY (DPE_PRO_ID)
      REFERENCES PRODUCTO (PRO_ID) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS DETALLE_TEMP;
CREATE TABLE IF NOT EXISTS DETALLE_TEMP
(
   DTE_ID       bigint(20)                              NOT NULL AUTO_INCREMENT,
   DTE_PRO_ID   bigint(20)                              NOT NULL,
   DTE_PRECIO   decimal(11,2)                           NOT NULL,
   DTE_CANTIDAD int(11)                                 NOT NULL,
   DTE_TOKEN    varchar(100) COLLATE utf8mb4_swedish_ci NOT NULL,
  PRIMARY KEY (DTE_ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

ALTER TABLE DETALLE_TEMP
   ADD CONSTRAINT FK_DTE_PRO FOREIGN KEY (DTE_PRO_ID)
      REFERENCES PRODUCTO (PRO_ID) ON DELETE CASCADE ON UPDATE CASCADE;

