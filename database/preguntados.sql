/*Creación Base de Datos*/

CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

/*Creación de Tablas*/

CREATE TABLE IF NOT EXISTS usuarios(
    id INT AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    year_birth INT NOT NULL,
    sexo VARCHAR(50) NOT NULL,
    ciudad VARCHAR(50) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(50) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL,
    foto VARCHAR(100) NOT NULL,
    rol VARCHAR(10) NOT NULL, /* J - E - A */
    activo INT NOT NULL, /* 0 = inactivo | 1 = activo*/
    qr VARCHAR(255), /* ni idea*/
    entregadas INT NOT NULL, /* 100 -> valor por default*/  /* hit / entregadas */
    hit INT NOT NULL, /* 50 -> valor por default*/
    CONSTRAINT pk_usuario PRIMARY KEY (id),
    CONSTRAINT unique_email UNIQUE (email),
    CONSTRAINT unique_nombre_usuario UNIQUE (nombre_usuario)
    );

CREATE TABLE IF NOT EXISTS partidas(
    id INT AUTO_INCREMENT,
    modo VARCHAR(50) NOT NULL, /* single player | multiplayer */
    estado VARCHAR(50) NOT NULL, /* playing | finished*/
    CONSTRAINT pk_partida PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS jugadores_partidas(
    id_jugador INT NOT NULL,
    id_partida INT NOT NULL,
    puntaje INT,
    CONSTRAINT pk_jugador_partida PRIMARY KEY (id_jugador, id_partida),
    CONSTRAINT fk_jugador_partida_jugador FOREIGN KEY (id_jugador) REFERENCES usuarios(id),
    CONSTRAINT fk_jugador_partida_partida FOREIGN KEY (id_partida) REFERENCES partidas(id)
    );

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_categoria PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS preguntas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    estado VARCHAR(20) NOT NULL, /* activa - reportada - sugerida*/
    entregadas INT NOT NULL, /* 100 -> valor por default*/
    hit INT NOT NULL, /* 50 -> valor por default*/
    id_categoria INT NOT NULL,
    CONSTRAINT pk_pregunta PRIMARY KEY (id),
    CONSTRAINT fk_pregunta_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)
    );

CREATE TABLE IF NOT EXISTS respuestas(
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(255) NOT NULL,
    estado INT NOT NULL, /* 0 = incorrecta | 1= correcta*/
    id_pregunta INT NOT NULL,
    CONSTRAINT pk_respuesta PRIMARY KEY (id),
    CONSTRAINT fk_pregunta FOREIGN KEY (id_pregunta) REFERENCES preguntas(id)
    );

CREATE TABLE IF NOT EXISTS partidas_preguntas (
    id_Partida INT NOT NULL,
    id_Pregunta INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_partida_pregunta PRIMARY KEY (id_partida, id_pregunta),
    CONSTRAINT fk_partida_pregunta_partida FOREIGN KEY (id_partida) REFERENCES partidas(id),
    CONSTRAINT fk_partida_pregunta_pregunta FOREIGN KEY (id_pregunta) REFERENCES preguntas(id)
    );

/*INSERTS*/

INSERT INTO usuarios (nombre, apellido, year_birth, sexo, ciudad, pais, email, password, nombre_usuario, foto, rol, activo, entregadas, hit, qr) VALUES
    ('Pablo', 'Echegaray', 1995, 'Masculino', 'Buenos Aires', 'Argentina', 'pablo.echegaray@example.com', 'password123', 'pabloE', 'public/image/perfil_sin_foto.jpg', 'A', 1, 100, 50, 'QR1'),
    ('Micaela', 'Mendez', 2002, 'Femenino', 'Buenos Aires', 'Argentina','micaela.mendez@example.com', 'password456', 'micaM', 'public/image/perfil_sin_foto.jpg', 'A', 1, 100, 50, 'QR2'),
    ('Pablo', 'Rocha', 2002, 'Masculino', 'Buenos Aires', 'Argentina','pablo.rocha@example.com', 'password789', 'pabloR', 'public/image/perfil_sin_foto.jpg', 'A',1, 100, 50, 'QR3'),
    ('Regina', 'Sanchez', 2002, 'Femenino', 'Buenos Aires', 'Argentina','regina.sanchez@example.com', 'password101', 'reginaS', 'public/image/perfil_sin_foto.jpg', 'A', 1, 100, 50, 'QR4'),
    ('Carlos', 'González', 1992, 'No especificado', 'Lima', 'Perú', 'carlos.gonzalez@example.com', 'password112', 'carlosg92', 'public/image/perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR5');

/*Este insert debería hacerse cuando se crea una partida y el usuario elike si va a jugar 'single player' o 'multiplayer'.*/
/*
INSERT INTO partidas (modo, estado) VALUES
('Single Player', 'finished'),
('Single Player', 'finished'),
('Single Player', 'finished'),
('Single Player', 'finished'),
('Single Player', 'finished'),
('Single Player', 'playing');
*/

/*Este insert se se hace cuando finaliza la partida, y se le pasa el id de la misma, el id del jugador y el puntaje final obtenido.*/
/*
INSERT INTO jugadores_partidas (id_Jugador, id_Partida, puntaje) VALUES
(1, 1, 100),
(2, 2, 80),
(3, 3, 90),
(4, 4, 110),
(1, 5, 10),
(5, 6, 30);
*/
INSERT INTO categorias (descripcion) VALUES
('Geografía'),
('Literatura'),
('Deportes'),
('Ciencia'),
('Historia'),
('Cultura General');

INSERT INTO preguntas (descripcion, estado, entregadas, hit, id_categoria) VA
   ('¿Cuál es la capital de Francia?', 'activa', 100, 50, 1),
    ('¿Quién escribió "Don Quijote"?', 'activa', 100, 50, 2),
    ('¿Cuál es el equipo que más veces ganó la UEFA Champions League?', 'activa', 100, 50, 3),
    ('¿En qué año llegó el hombre a la luna?', 'activa', 100, 50, 5),
    ('¿Cuál es el elemento químico con símbolo "O"?','activa', 100, 50, 4),
    ('¿Cuál es el río más largo del mundo?','activa', 100, 50, 1),
    ('¿Qué ciudad es conocida como la Gran Manzana?','activa', 100, 50, 1),
    ('¿Quién pintó la Mona Lisa?','activa', 100, 50, 6),
    ('¿Quién escribió "Cien años de soledad"?', 'activa', 100, 50, 2),
    ('¿Cuál es el deporte más popular del mundo?', 'activa', 100, 50, 3),
    ('¿En qué año se fundó la FIFA?', 'activa', 100, 50, 3),
    ('¿Cuál es la fórmula química del agua?', 'activa', 100, 50, 4),
    ('¿Quién descubrió la penicilina?', 'activa', 100, 50, 6),
    ('¿Cuál es la capital de Japón?', 'activa', 100, 50, 1),
    ('¿Quién escribió "Hamlet"?', 'activa', 100, 50, 2),
    ('¿Cuál es el planeta más cercano al sol?', 'activa', 100, 50, 4),
    ('¿Quién es conocido como el padre de la física moderna?', 'activa', 100, 50, 4),
    ('¿Cuál es el océano más grande del mundo?', 'activa', 100, 50, 1),
    ('¿Qué país es el mayor productor de café?', 'activa', 100, 50, 1),
    ('¿Quién escribió "La Divina Comedia"?', 'activa', 100, 50, 2),
    ('¿Quién ganó el primer Mundial de Fútbol?', 'activa', 100, 50, 3),
    ('¿Qué científico propuso la teoría de la relatividad?', 'activa', 100, 50, 4),
    ('¿Cuál es el símbolo químico del oro?', 'activa', 100, 50, 4),
    ('¿Cuál es la montaña más alta del mundo?', 'activa', 100, 50, 1),
    ('¿Qué país tiene la mayor población del mundo?', 'activa', 100, 50, 1),
    ('¿Quién es el autor de "1984"?', 'activa', 100, 50, 2),
    ('¿Cuál es el país con más medallas olímpicas?', 'activa', 100, 50, 3),
    ('¿Qué elemento tiene el símbolo Na?', 'activa', 100, 50, 4),
    ('¿Cuál es el animal terrestre más rápido del mundo?', 'activa', 100, 50, 6);

INSERT INTO respuestas (descripcion, estado, id_pregunta) VALUES
('Londres', 0, 1),
('París', 1, 1),
('Roma', 0, 1),
('Berlín', 0, 1),
('Lope de Vega', 0, 2),
('Luis de Góngora', 0, 2),
('Francisco de Quevedo', 0, 2),
('Miguel de Cervantes', 1, 2),
('Real Madrid', 1, 3),
('FC Barcelona', 0, 3),
('Manchester United', 0, 3),
('Bayern Múnich', 0, 3),
('1970', 0, 4),
('1969', 1, 4),
('1968', 0, 4),
('1971', 0, 4),
('Oro', 0, 5),
('Osmio', 0, 5),
('Oxígeno', 1, 5),
('Oganesón', 0, 5),
('Amazonas', 1, 6),
('Nilo', 0, 6),
('Misisipi', 0, 6),
('Yangtsé', 0, 6),
('Los Ángeles', 0, 7),
('Chicago', 0, 7),
('Nueva York', 1, 7),
('San Francisco', 0, 7),
('Leonardo da Vinci', 1, 8),
('Michelangelo', 0, 8),
('Raphael', 0, 8),
('Donatello', 0, 8),
('Mario Vargas Llosa', 0, 9),
('Julio Cortázar', 0, 9),
('Jorge Luis Borges', 0, 9),
('Gabriel García Márquez', 1, 9),
('Baloncesto', 0, 10),
('Fútbol', 1, 10),
('Críquet', 0, 10),
('Tenis', 0, 10),
('1904', 1, 11),
('1920', 0, 11),
('1896', 0, 11),
('1910', 0, 11),
('CO2', 0, 12),
('O2', 0, 12),
('H2', 0, 12),
('H2O', 1, 12),
('Louis Pasteur', 0, 13),
('Marie Curie', 0, 13),
('Alexander Fleming', 1, 13),
('Joseph Lister', 0, 13),
('Osaka', 0, 14),
('Kioto', 0, 14),
('Hiroshima', 0, 14),
('Tokio', 1, 14),
('William Shakespeare', 1, 15),
('Christopher Marlowe', 0, 15),
('Ben Jonson', 0, 15),
('Thomas Kyd', 0, 15),
('Venus', 0, 16),
('Mercurio', 1, 16),
('Marte', 0, 16),
('Júpiter', 0, 16),
('Isaac Newton', 0, 17),
('Galileo Galilei', 0, 17),
('Albert Einstein', 1, 17),
('Niels Bohr', 0, 17),
('Océano Atlántico', 0, 18),
('Océano Pacífico', 1, 18),
('Océano Índico', 0, 18),
('Océano Ártico', 0, 18),
('Colombia', 0, 19),
('Vietnam', 0, 19),
('Etiopía', 0, 19),
('Brasil', 1, 19),
('Dante Alighieri', 1, 20),
('Giovanni Boccaccio', 0, 20),
('Francesco Petrarca', 0, 20),
('Ludovico Ariosto', 0, 20),
('Brasil', 0, 21),
('Uruguay', 1, 21),
('Alemania', 0, 21),
('Italia', 0, 21),
('Albert Einstein', 1, 22),
('Isaac Newton', 0, 22),
('Niels Bohr', 0, 22),
('Galileo Galilei', 0, 22),
('Ag', 0, 23),
('Au', 1, 23),
('Pt', 0, 23),
('Pb', 0, 23),
('K2', 0, 24),
('Kangchenjunga', 0, 24),
('Monte Everest', 1, 24),
('Lhotse', 0, 24),
('India', 0, 25),
('Estados Unidos', 0, 25),
('Indonesia', 0, 25),
('China', 1, 25),
('George Orwell', 1, 26),
('Aldous Huxley', 0, 26),
('Ray Bradbury', 0, 26),
('Kurt Vonnegut', 0, 26),
('Rusia', 0, 27),
('Estados Unidos', 1, 27),
('China', 0, 27),
('Alemania', 0, 27),
('Nitrógeno', 0, 28),
('Neón', 0, 28),
('Sodio', 1, 28),
('Níquel', 0, 28),
('Guepardo', 1, 29),
('Leopardo', 0, 29),
('Dogo Argentino', 0, 29),
('León', 0, 29);

/* Este insert deberia hacerse cuando se crea una partida y updetear cada vez que se entrega una pregunta*/
/*
INSERT INTO partidas_preguntas (id_Partida, id_Pregunta) VALUES
(1, 1);
*/