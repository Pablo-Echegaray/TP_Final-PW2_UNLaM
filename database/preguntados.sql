/*Creación Base de Datos*/

CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

/*Creación de Tablas*/

CREATE TABLE IF NOT EXISTS Sexos (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_sexo PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    year_birth INT NOT NULL,
    id_sexo INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(50) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL,
    foto VARCHAR(100) NOT NULL,
    qr VARCHAR(255),
    CONSTRAINT pk_usuario PRIMARY KEY (id),
    CONSTRAINT fk_usuario_sexo FOREIGN KEY (id_sexo) REFERENCES sexos(id),
    CONSTRAINT unique_email UNIQUE (email),
    CONSTRAINT unique_nombre_usuario UNIQUE (nombre_usuario)
);

CREATE TABLE IF NOT EXISTS usuarios_editores (
    id_usuario INT,
    CONSTRAINT pk_usuario_editor PRIMARY KEY (id_usuario),
    CONSTRAINT fk_usuario_editor FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS usuarios_admins (
    id_usuario INT,
    CONSTRAINT pk_usuario_admin PRIMARY KEY (id_usuario),
    CONSTRAINT fk_usuario_admin FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS jugadores (
    id_usuario INT,
    CONSTRAINT pk_jugador PRIMARY KEY (id_usuario),
    CONSTRAINT fk_jugador_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS dificultades (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_dificultad PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS partidas (
    id INT AUTO_INCREMENT,
    modo VARCHAR(50),
    id_dificultad INT NOT NULL,
    CONSTRAINT pk_partida PRIMARY KEY (id),
    CONSTRAINT fk_partida_dificultad FOREIGN KEY (id_dificultad) REFERENCES dificultades(id)
);

CREATE TABLE IF NOT EXISTS jugadores_partidas (
    id_jugador INT NOT NULL,
    id_partida INT NOT NULL,
    puntaje INT,
    CONSTRAINT pk_jugador_partida PRIMARY KEY (id_jugador, id_partida),
    CONSTRAINT fk_jugador_partida_jugador FOREIGN KEY (id_jugador) REFERENCES jugadores(id_usuario),
    CONSTRAINT fk_jugador_partida_partida FOREIGN KEY (id_partida) REFERENCES partidas(id)
);

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_categoria PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS trampitas (
    id INT AUTO_INCREMENT,
    precio DECIMAL(10, 2) NOT NULL,
    CONSTRAINT pk_trampita PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS respuestas_correctas (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(255) NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_correcta PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_correcta_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)
    );

CREATE TABLE IF NOT EXISTS preguntas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_dificultad INT NOT NULL,
    id_categoria INT NOT NULL,
    id_trampita INT,
    id_respuesta_correcta INT NOT NULL,
    CONSTRAINT pk_pregunta PRIMARY KEY (id),
    CONSTRAINT fk_pregunta_dificultad FOREIGN KEY (id_dificultad) REFERENCES dificultades(id),
    CONSTRAINT fk_pregunta_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    CONSTRAINT fk_pregunta_trampita FOREIGN KEY (id_trampita) REFERENCES trampitas(id),
    CONSTRAINT fk_pregunta_respuesta_correcta FOREIGN KEY (id_respuesta_correcta) REFERENCES respuestas_correctas(id)
);

CREATE TABLE IF NOT EXISTS partidas_preguntas (
    id_Partida INT NOT NULL,
    id_Pregunta INT NOT NULL,
    CONSTRAINT pk_partida_pregunta PRIMARY KEY (id_partida, id_pregunta),
    CONSTRAINT fk_partida_pregunta_partida FOREIGN KEY (id_partida) REFERENCES partidas(id),
    CONSTRAINT fk_partida_pregunta_pregunta FOREIGN KEY (id_pregunta) REFERENCES preguntas(id)
);

CREATE TABLE IF NOT EXISTS respuestas_incorrectas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_incorrecta PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_incorrecta_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS preguntas_respuestas_incorrectas (
    id_pregunta INT NOT NULL,
    id_respuesta_incorrecta INT NOT NULL,
    CONSTRAINT pk_pregunta_respuesta_incorrecta PRIMARY KEY (id_pregunta, id_respuesta_incorrecta),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_pregunta FOREIGN KEY (id_pregunta) REFERENCES preguntas(id),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_respuesta FOREIGN KEY (id_respuesta_incorrecta) REFERENCES respuestas_incorrectas(id)
);


CREATE TABLE IF NOT EXISTS jugadores_trampitas (
    id_jugador INT NOT NULL,
    id_trampita INT NOT NULL,
    CONSTRAINT pk_jugador_trampita PRIMARY KEY (id_jugador, id_trampita),
    CONSTRAINT fk_jugador_trampita_jugador FOREIGN KEY (id_jugador) REFERENCES jugadores(id_usuario),
    CONSTRAINT fk_jugador_trampita_trampita FOREIGN KEY (id_trampita) REFERENCES trampitas(id)
);

CREATE TABLE IF NOT EXISTS preguntas_reportadas (
    id INT AUTO_INCREMENT,
    motivo TEXT NOT NULL,
    CONSTRAINT pk_pregunta_reportada PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS reportes_de_preguntas (
    id_reporte INT NOT NULL,
    id_pregunta INT NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT pk_reporte_de_pregunta PRIMARY KEY (id_reporte, id_pregunta, id_usuario),
    CONSTRAINT fk_reporte_de_pregunta_reporte FOREIGN KEY (id_reporte) REFERENCES preguntas_reportadas(id),
    CONSTRAINT fk_reporte_de_pregunta_pregunta FOREIGN KEY (id_pregunta) REFERENCES preguntas(id),
    CONSTRAINT fk_reporte_de_pregunta_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS respuestas_correctas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_correcta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_correcta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)
    );

CREATE TABLE IF NOT EXISTS preguntas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    id_respuesta_correcta_sugerida INT NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT pk_pregunta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_pregunta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    CONSTRAINT fk_pregunta_sugerida_respuesta FOREIGN KEY (id_respuesta_correcta_sugerida) REFERENCES respuestas_correctas_sugeridas(id),
    CONSTRAINT fk_pregunta_sugerida_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS respuestas_incorrectas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_incorrecta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_incorrecta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS preguntas_y_respuestas_incorrectas_sugeridas (
    id_pregunta_sugerida INT NOT NULL,
    id_respuesta_incorrecta_sugerida INT NOT NULL,
    CONSTRAINT pk_pregunta_respuesta_incorrecta_sugerida PRIMARY KEY (id_pregunta_sugerida, id_respuesta_incorrecta_sugerida),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_sugerida_pregunta FOREIGN KEY (id_pregunta_sugerida) REFERENCES preguntas_sugeridas(id),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_sugerida_respuesta FOREIGN KEY (id_respuesta_incorrecta_sugerida) REFERENCES respuestas_incorrectas_sugeridas(id)
);

CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_pais PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS ciudades (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_ciudad PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS usuarios_ciudades_paises (
    id_usuario INT NOT NULL,
    id_ciudad INT NOT NULL,
    id_pais INT NOT NULL,
    CONSTRAINT pk_usuario_ciudad_pais PRIMARY KEY (id_usuario, id_ciudad, id_pais),
    CONSTRAINT fk_usuario_ciudad_pais_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    CONSTRAINT fk_usuario_ciudad_pais_ciudad FOREIGN KEY (id_ciudad) REFERENCES ciudades(id),
    CONSTRAINT fk_usuario_ciudad_pais_pais FOREIGN KEY (id_pais) REFERENCES paises(id)
);

/*INSERTS*/

INSERT INTO sexos (descripcion) VALUES
('Femenino'),
('Masculino'),
('No especificado');

INSERT INTO usuarios (nombre, apellido, year_birth, id_sexo, email, password, nombre_usuario, foto, qr) VALUES
('Pablo', 'Echegaray', 1995, 2, 'pablo.echegaray@example.com', 'password123', 'pabloE', 'public/image/perfil_sin_foto.jpg', 'QR1'),
('Micaela', 'Mendez', 2000, 1, 'micaela.mendez@example.com', 'password456', 'micaM', 'public/image/perfil_sin_foto.jpg', 'QR2'),
('Pablo', 'Rocha', 2000, 2, 'pablo.rocha@example.com', 'password789', 'pabloR', 'public/image/perfil_sin_foto.jpg', 'QR3'),
('Regina', 'Sanchez', 2000, 1, 'regina.sanchez@example.com', 'password101', 'reginaS', 'public/image/perfil_sin_foto.jpg', 'QR4'),
('Carlos', 'González', 1992, 3, 'carlos.gonzalez@example.com', 'password112', 'carlosg92', 'public/image/perfil_sin_foto.jpg', 'QR5');

INSERT INTO usuarios_editores (id_Usuario) VALUES
(1),
(2),
(3),
(4);

INSERT INTO usuarios_admins (id_Usuario) VALUES
(1),
(2),
(3),
(4);

INSERT INTO jugadores (id_Usuario) VALUES
(1),
(2),
(3),
(4),
(5);

INSERT INTO dificultades (descripcion) VALUES
('Fácil'),
('Intermedio'),
('Difícil');

INSERT INTO partidas (modo, id_dificultad) VALUES
('Single Player', 1),
('Single Player', 2),
('Single Player', 3),
('Single Player', 1),
('Cooperative', 1);

INSERT INTO jugadores_partidas (id_Jugador, id_Partida, puntaje) VALUES
(1, 1, 100),
(1, 2, 200),
(1, 3, 150),
(2, 4, 180),
(3, 5, 170),
(4, 5, 180);

INSERT INTO categorias (descripcion) VALUES
('Geografía'),
('Literatura'),
('Deportes'),
('Ciencia'),
('Historia'),
('Química'),
('Cultura General');

INSERT INTO respuestas_correctas (descripcion, id_categoria) VALUES
('París', 1),
('Miguel de Cervantes', 2),
('Real Madrid', 3),
('Amazonas', 1),
('1969', 5),
('Oxígeno', 6),
('Nueva York', 1),
('Leonardo da Vinci', 2),
('Gabriel García Márquez', 2),
('Fútbol', 3),
('1904', 3),
('H2O', 6),
('Alexander Fleming', 6),
('Tokio', 1),
('William Shakespeare', 2),
('Mercurio', 5),
('Albert Einstein', 6),
('Océano Pacífico', 1),
('Brasil', 1),
('Dante Alighieri', 2),
('Uruguay', 3),
('Au', 6),
('Monte Everest', 1),
('China', 1),
('George Orwell', 2),
('Estados Unidos', 3),
('Sodio', 6),
('Guepardo', 1);

INSERT INTO respuestas_incorrectas (descripcion, id_categoria) VALUES
('Londres', 1),
('Buenos Aires', 1),
('Gabriel García Márquez', 2),
('Domingo Faustino Sarmiento', 2),
('Barcelona FC', 3),
('Bayer Munich', 3),
('Nilo', 1),
('Río de la Plata', 1),
('1970', 5),
('2000', 5),
('Carbono', 6),
('Nitrógeno', 6),
('Nilo', 1),
('Yangtsé', 1),
('Los Ángeles', 1),
('Michelangelo', 2),
('Vincent van Gogh', 2),
('Mario Vargas Llosa', 2),
('Charles Dickens', 2),
('Baloncesto', 3),
('Cricket', 3),
('1914', 3),
('1924', 3),
('CO2', 6),
('O2', 6),
('Marie Curie', 6),
('Isaac Newton', 6),
('Beijing', 1),
('Seúl', 1),
('J. K. Rowling', 2),
('Fyodor Dostoevsky', 2),
('Venus', 5),
('Saturno', 5),
('Stephen Hawking', 6),
('Océano Atlántico', 1),
('Océano Índico', 1),
('Colombia', 1),
('Vietnam', 1),
('Virgilio', 2),
('Jorge Luis Borges', 2),
('Italia', 3),
('Alemania', 3),
('Plomo', 6),
('Hierro', 6),
('K2', 1),
('Kangchenjunga', 1),
('India', 1),
('Estados Unidos', 1),
('Aldous Huxley', 2),
('Ray Bradbury', 2),
('Rusia', 3),
('China', 3),
('Potasio', 6),
('Calcio', 6),
('Tigre', 1),
('León', 1),
('Baseball', 3),
('Júpiter', 4),
('Denali', 1),
('Lhotse', 1),
('Argentina', 3),
('Perro', 1);

INSERT INTO trampitas (precio) VALUES
(9.99),
(19.99),
(29.99);

INSERT INTO preguntas (descripcion, id_dificultad, id_categoria, id_trampita, id_respuesta_correcta) VALUES
('¿Cuál es la capital de Francia?', 1, 1, NULL, 1),
('¿Quién escribió "Don Quijote"?', 2, 2, NULL, 2),
('¿Cuál es el equipo que más veces ganó la UEFA Champions League?', 2, 3, NULL, 3),
('¿En qué año llegó el hombre a la luna?', 3, 5, 3, 5),
('¿Cuál es el elemento químico con símbolo "O"?', 1, 6, NULL, 6),
('¿Cuál es el río más largo del mundo?', 1, 1, NULL, 4),
('¿Qué ciudad es conocida como la Gran Manzana?', 1, 1, NULL, 7),
('¿Quién pintó la Mona Lisa?', 2, 2, NULL, 8),
('¿Quién escribió "Cien años de soledad"?', 2, 2, NULL, 9),
('¿Cuál es el deporte más popular del mundo?', 1, 3, NULL, 10),
('¿En qué año se fundó la FIFA?', 2, 3, NULL, 11),
('¿Cuál es la fórmula química del agua?', 1, 6, NULL, 12),
('¿Quién descubrió la penicilina?', 2, 6, NULL, 13),
('¿Cuál es la capital de Japón?', 1, 1, NULL, 14),
('¿Quién escribió "Hamlet"?', 2, 2, NULL, 15),
('¿Cuál es el planeta más cercano al sol?', 1, 4, NULL, 16),
('¿Quién es conocido como el padre de la física moderna?', 3, 6, NULL, 17),
('¿Cuál es el océano más grande del mundo?', 1, 1, NULL, 18),
('¿Qué país es el mayor productor de café?', 1, 1, NULL, 19),
('¿Quién escribió "La Divina Comedia"?', 2, 2, NULL, 20),
('¿Quién ganó el primer Mundial de Fútbol?', 2, 3, NULL, 21),
('¿Qué científico propuso la teoría de la relatividad?', 3, 6, NULL, 17),
('¿Cuál es el símbolo químico del oro?', 1, 6, NULL, 22),
('¿Cuál es la montaña más alta del mundo?', 1, 1, NULL, 23),
('¿Qué país tiene la mayor población del mundo?', 1, 1, NULL, 24),
('¿Quién es el autor de "1984"?', 2, 2, NULL, 25),
('¿Cuál es el país con más medallas olímpicas?', 2, 3, NULL, 26),
('¿Qué elemento tiene el símbolo Na?', 1, 6, NULL, 27),
('¿Cuál es el animal más rápido del mundo?', 1, 1, NULL, 28);

INSERT INTO partidas_preguntas (id_Partida, id_Pregunta) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2,1);

INSERT INTO preguntas_respuestas_incorrectas (id_Pregunta, id_Respuesta_incorrecta) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 9),
(4, 10),
(5, 11),
(5, 12),
(6, 7),
(6, 8),
(6, 1),
(7, 1),
(7, 2),
(7, 15),
(8, 3),
(8, 4),
(8, 16),
(9, 16),
(9, 17),
(9, 18),
(10, 20),
(10, 21),
(10, 57),
(11, 9),
(11, 10),
(11, 22),
(12, 11),
(12, 24),
(12, 25),
(13, 26),
(13, 27),
(13, 34),
(14, 14),
(14, 28),
(14, 29),
(15, 30),
(15, 31),
(15, 4),
(16, 32),
(16, 33),
(16, 58),
(17, 26),
(17, 27),
(17, 34),
(18, 35),
(18, 36),
(18, 8),
(19, 37),
(19, 38),
(19, 47),
(20, 49),
(20, 50),
(20, 3),
(21, 51),
(21, 52),
(21, 42),
(22, 34),
(22, 26),
(22, 27),
(23, 53),
(23, 54),
(23, 43),
(24, 46),
(24, 59),
(24, 60),
(25, 47),
(25, 48),
(25, 38),
(26, 49),
(26, 50),
(26, 4),
(27, 51),
(27, 52),
(27, 61),
(28, 53),
(28, 54),
(28, 44),
(29, 55),
(29, 56),
(29, 62);

INSERT INTO jugadores_trampitas (id_Jugador, id_Trampita) VALUES
(1, 1),
(2, 2),
(3, 1);

INSERT INTO preguntas_reportadas (motivo) VALUES
('Incorrecta'),
('Inapropiada'),
('Mal redactada');

INSERT INTO reportes_de_preguntas (id_Reporte, id_Pregunta, id_Usuario) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 3);

INSERT INTO respuestas_correctas_sugeridas (descripcion, id_categoria) VALUES
('Roma', 1),
('Gabriel García Márquez', 2),
('Baseball', 3),
('Pacífico', 4),
('1939', 5),
('Hidrógeno', 6);

INSERT INTO respuestas_incorrectas_sugeridas (descripcion, id_categoria) VALUES
('Milán', 1),
('Mario Vargas Llosa', 2),
('Football Americano', 3),
('Atlántico', 4),
('1940', 5),
('Helio', 6);

INSERT INTO preguntas_sugeridas (descripcion, id_categoria, id_respuesta_correcta_sugerida, id_usuario) VALUES
('¿Cuál es la capital de Italia?', 1, 1, 1),
('¿Quién escribió "Cien años de soledad"?', 2, 2, 2),
('¿Qué deporte jugó profesionalente Michael Jordan además de Basket?', 3, 3, 1),
('¿Cuál es el océano más grande del mundo?', 4, 4, 3),
('¿En qué año comenzó la Segunda Guerra Mundial?', 5, 5, 4),
('¿Cuál es el elemento químico con símbolo "H"?', 6, 6, 5);


INSERT INTO preguntas_y_respuestas_incorrectas_sugeridas (id_Pregunta_sugerida, id_Respuesta_incorrecta_sugerida) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

INSERT INTO paises (descripcion) VALUES
('Argentina'),
('Brasil'),
('Chile'),
('Colombia'),
('Perú');

INSERT INTO ciudades (descripcion) VALUES
('Buenos Aires'),
('Sao Paulo'),
('Santiago'),
('Bogotá'),
('Lima');

INSERT INTO usuarios_ciudades_paises (id_usuario, id_ciudad, id_pais) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 5, 5);