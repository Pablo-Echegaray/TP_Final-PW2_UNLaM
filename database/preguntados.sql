CREATE DATABASE IF NOT EXISTS preguntados;
       USE preguntados;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    contrasena VARCHAR(50) NOT NULL,
    codigo VARCHAR(50) NOT NULL
);