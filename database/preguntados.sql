CREATE DATABASE preguntados;
USE preguntados;

CREATE TABLE `usuario` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `name` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
    `pass` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
    `codigo` varchar(24) COLLATE utf8mb4_general_ci NOT NULL,
    `verificado` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
