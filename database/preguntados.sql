CREATE DATABASE IF NOT EXISTS preguntados;
       USE preguntados;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    edad INT(3) NOT NULL,
    sexo VARCHAR(50) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    ciudad VARCHAR(50) NOT NULL,
    mail VARCHAR(100) NOT NULL,
    contrasena VARCHAR(50) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    foto VARCHAR(100) NOT NULL,

    qr VARCHAR(200),
    codigo VARCHAR(100),
    verificado TINYINT(1) NOT NULL DEFAULT 0
);