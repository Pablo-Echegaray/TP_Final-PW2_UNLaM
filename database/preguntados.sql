/*Creación Base de Datos*/

CREATE DATABASE IF NOT EXISTS preguntados;
       USE preguntados;
/*
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
);*/
/*Creación de Tablas*/

CREATE TABLE IF NOT EXISTS Usuarios (
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
    CONSTRAINT fk_usuario_sexo FOREIGN KEY (id_sexo) REFERENCES Sexos(id),
    CONSTRAINT unique_email UNIQUE (email),
    CONSTRAINT unique_nombre_usuario UNIQUE (nombre_usuario)
    CONSTRAINT check_year_birth CHECK (year_birth > 1920)
);

CREATE TABLE IF NOT EXISTS Usuarios_editores (
    id_Usuario INT,
    CONSTRAINT pk_usuario_editor PRIMARY KEY (id_Usuario),
    CONSTRAINT fk_usuario_editor FOREIGN KEY (id_Usuario) REFERENCES Usuarios(id)
);

CREATE TABLE IF NOT EXISTS Usuarios_admins (
    id_Usuario INT,
    CONSTRAINT pk_usuario_admin PRIMARY KEY (id_Usuario),
    CONSTRAINT fk_usuario_admin FOREIGN KEY (id_Usuario) REFERENCES Usuarios(id)
);

CREATE TABLE IF NOT EXISTS Jugadores (
    id_Usuario INT,
    CONSTRAINT pk_jugador PRIMARY KEY (id_Usuario),
    CONSTRAINT fk_jugador_usuario FOREIGN KEY (id_Usuario) REFERENCES Usuarios(id)
);

CREATE TABLE IF NOT EXISTS Partidas (
    id INT AUTO_INCREMENT,
    modo VARCHAR(50),
    id_dificultad INT NOT NULL,
    CONSTRAINT pk_partida PRIMARY KEY (id)
    CONSTRAINT fk_partida_dificultad FOREIGN KEY (id_dificultad) REFERENCES Dificultades(id)
);

CREATE TABLE IF NOT EXISTS Jugadores_partidas (
    id_Jugador INT NOT NULL,
    id_Partida INT NOT NULL,
    puntaje INT,
    CONSTRAINT pk_jugador_partida PRIMARY KEY (id_Jugador, id_Partida),
    CONSTRAINT fk_jugador_partida_jugador FOREIGN KEY (id_Jugador) REFERENCES Jugadores(id_Usuario),
    CONSTRAINT fk_jugador_partida_partida FOREIGN KEY (id_Partida) REFERENCES Partidas(id)
);

CREATE TABLE IF NOT EXISTS Preguntas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_dificultad INT NOT NULL,
    id_categoria INT NOT NULL,
    id_trampita INT,
    id_respuesta_correcta INT NOT NULL,
    CONSTRAINT pk_pregunta PRIMARY KEY (id),
    CONSTRAINT fk_pregunta_dificultad FOREIGN KEY (id_dificultad) REFERENCES Dificultades(id),
    CONSTRAINT fk_pregunta_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id),
    CONSTRAINT fk_pregunta_trampita FOREIGN KEY (id_trampita) REFERENCES Trampitas(id),
    CONSTRAINT fk_pregunta_respuesta_correcta FOREIGN KEY (id_respuesta_correcta) REFERENCES Respuestas_correctas(id)
);

CREATE TABLE IF NOT EXISTS Dificultades (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_dificultad PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Partidas_preguntas (
    id_Partida INT NOT NULL,
    id_Pregunta INT NOT NULL,
    CONSTRAINT pk_partida_pregunta PRIMARY KEY (id_Partida, id_Pregunta),
    CONSTRAINT fk_partida_pregunta_partida FOREIGN KEY (id_Partida) REFERENCES Partidas(id),
    CONSTRAINT fk_partida_pregunta_pregunta FOREIGN KEY (id_Pregunta) REFERENCES Preguntas(id)
);

CREATE TABLE IF NOT EXISTS Respuestas_correctas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_correcta PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_correcta_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id)
);

CREATE TABLE IF NOT EXISTS Categorias (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_categoria PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Respuestas_incorrectas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_incorrecta PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_incorrecta_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id)
);

CREATE TABLE IF NOT EXISTS Preguntas_respuestas_incorrectas (
    id_Pregunta INT NOT NULL,
    id_Respuesta_incorrecta INT NOT NULL,
    CONSTRAINT pk_pregunta_respuesta_incorrecta PRIMARY KEY (id_Pregunta, id_Respuesta_incorrecta),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_pregunta FOREIGN KEY (id_Pregunta) REFERENCES Preguntas(id),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_respuesta FOREIGN KEY (id_Respuesta_incorrecta) REFERENCES Respuestas_incorrectas(id)
);

CREATE TABLE IF NOT EXISTS Trampitas (
    id INT AUTO_INCREMENT,
    precio DECIMAL(10, 2) NOT NULL,
    CONSTRAINT pk_trampita PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Jugadores_trampitas (
    id_Jugador INT NOT NULL,
    id_Trampita INT NOT NULL,
    CONSTRAINT pk_jugador_trampita PRIMARY KEY (id_Jugador, id_Trampita),
    CONSTRAINT fk_jugador_trampita_jugador FOREIGN KEY (id_Jugador) REFERENCES Jugadores(id_Usuario),
    CONSTRAINT fk_jugador_trampita_trampita FOREIGN KEY (id_Trampita) REFERENCES Trampitas(id)
);

CREATE TABLE IF NOT EXISTS Preguntas_reportadas (
    id INT AUTO_INCREMENT,
    motivo TEXT NOT NULL,
    CONSTRAINT pk_pregunta_reportada PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Reportes_de_preguntas (
    id_Reporte INT NOT NULL,
    id_Pregunta INT NOT NULL,
    id_Usuario INT NOT NULL,
    CONSTRAINT pk_reporte_de_pregunta PRIMARY KEY (id_Reporte, id_Pregunta, id_Usuario),
    CONSTRAINT fk_reporte_de_pregunta_reporte FOREIGN KEY (id_Reporte) REFERENCES Preguntas_reportadas(id),
    CONSTRAINT fk_reporte_de_pregunta_pregunta FOREIGN KEY (id_Pregunta) REFERENCES Preguntas(id),
    CONSTRAINT fk_reporte_de_pregunta_usuario FOREIGN KEY (id_Usuario) REFERENCES Usuarios(id)
);

CREATE TABLE IF NOT EXISTS Preguntas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    id_respuesta_correcta_sugerida INT NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT pk_pregunta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_pregunta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id),
    CONSTRAINT fk_pregunta_sugerida_respuesta FOREIGN KEY (id_respuesta_correcta_sugerida) REFERENCES Respuestas_correctas_sugeridas(id),
    CONSTRAINT fk_pregunta_sugerida_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

CREATE TABLE IF NOT EXISTS Respuestas_correctas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_correcta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_correcta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id)
);

CREATE TABLE IF NOT EXISTS Respuestas_incorrectas_sugeridas (
    id INT AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT pk_respuesta_incorrecta_sugerida PRIMARY KEY (id),
    CONSTRAINT fk_respuesta_incorrecta_sugerida_categoria FOREIGN KEY (id_categoria) REFERENCES Categorias(id)
);

CREATE TABLE IF NOT EXISTS Preguntas_y_respuestas_incorrectas_sugeridas (
    id_Pregunta_sugerida INT NOT NULL,
    id_Respuesta_incorrecta_sugerida INT NOT NULL,
    CONSTRAINT pk_pregunta_respuesta_incorrecta_sugerida PRIMARY KEY (id_Pregunta_sugerida, id_Respuesta_incorrecta_sugerida),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_sugerida_pregunta FOREIGN KEY (id_Pregunta_sugerida) REFERENCES Preguntas_sugeridas(id),
    CONSTRAINT fk_pregunta_respuesta_incorrecta_sugerida_respuesta FOREIGN KEY (id_Respuesta_incorrecta_sugerida) REFERENCES Respuestas_incorrectas_sugeridas(id)
);

CREATE TABLE IF NOT EXISTS Paises (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_pais PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Ciudades (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_ciudad PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Usuarios_ciudades_paises (
    id_Usuario INT NOT NULL,
    id_Ciudad INT NOT NULL,
    id_pais INT NOT NULL,
    CONSTRAINT pk_usuario_ciudad_pais PRIMARY KEY (id_Usuario, id_Ciudad, id_pais),
    CONSTRAINT fk_usuario_ciudad_pais_usuario FOREIGN KEY (id_Usuario) REFERENCES Usuarios(id),
    CONSTRAINT fk_usuario_ciudad_pais_ciudad FOREIGN KEY (id_Ciudad) REFERENCES Ciudades(id),
    CONSTRAINT fk_usuario_ciudad_pais_pais FOREIGN KEY (id_pais) REFERENCES Paises(id)
);

CREATE TABLE IF NOT EXISTS Sexos (
    id INT AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT pk_sexo PRIMARY KEY (id)
);

/*INSERTS*/

INSERT INTO Usuarios (nombre, apellido, year_birth, id_sexo, email, password, nombre_usuario, foto, qr) VALUES
('Pablo', 'Echegaray', 1995, 2, 'pablo.echegaray@example.com', 'password123', 'pabloE', 'public/image/perfil_sin_foto.jpg', 'QR1'),
('Micaela', 'Mendez', 2000, 1, 'micaela.mendez@example.com', 'password456', 'micaM', 'public/image/perfil_sin_foto.jpg', 'QR2'),
('Pablo', 'Rocha', 2000, 2, 'pablo.rocha@example.com', 'password789', 'pabloR', 'public/image/perfil_sin_foto.jpg', 'QR3'),
('Regina', 'Sanchez', 2000, 1, 'regina.sanchez@example.com', 'password101', 'reginaS', 'public/image/perfil_sin_foto.jpg', 'QR4'),
('Carlos', 'González', 1992, 3, 'carlos.gonzalez@example.com', 'password112', 'carlosg92', 'public/image/perfil_sin_foto.jpg', 'QR5');

INSERT INTO Usuarios_editores (id_Usuario) VALUES
(1),
(2),
(3),
(4);

INSERT INTO Usuarios_admins (id_Usuario) VALUES
(1),
(2),
(3),
(4);

INSERT INTO Jugadores (id_Usuario) VALUES
(1),
(2),
(3),
(4),
(5);

INSERT INTO Partidas (modo, id_dificultad) VALUES
('Single Player', 1),
('Cooperative', 2);

INSERT INTO Jugadores_partidas (id_Jugador, id_Partida, puntaje) VALUES
(1, 1, 100),
(1, 2, 200),
(1, 3, 150),
(2, 4, 180),
(3, 5, 170);

INSERT INTO Preguntas (descripcion, id_dificultad, id_categoria, id_trampita, id_respuesta_correcta) VALUES
('¿Cuál es la capital de Francia?', 1, 1, NULL, 1),
('¿Quién escribio "Don Quijote"?', 2, 2, NULL, 2),
('¿Cuál es el equipo que más veces ganó la UEFA Champions League?', 2, 3, NULL, 3),
('¿En qué año llegó el hombre a la luna?', 3, 5, 3, 5),
('¿Cuál es el elemento químico con símbolo "O"?', 1, 6, NULL, 6);

INSERT INTO Dificultades (descripcion) VALUES
('Fácil'),
('Intermedio'),
('Difícil');

INSERT INTO Categorias (descripcion) VALUES
('Geografía'),
('Literatura'),
('Deportes'),
('Geografía')
('Ciencia'),
('Historia'),
('Química')
('Cultura General');

INSERT INTO Respuestas_correctas (descripcion, id_categoria) VALUES
('París', 1),
('Miguel de Cervantes', 2),
('Real Madrid', 3)
('Amazonas', 4),
('1969', 5),
('Oxígeno', 6);

INSERT INTO Respuestas_incorrectas (descripcion, id_categoria) VALUES
('Londres', 1),
('Buenos Aires', 1)
('Gabriel García Márquez', 2),
('Barcelona', 3),
('Bayer Munich', 3),
('Nilo', 4),
('Río de la Plata', 4),
('1970', 5),
('2000', 5),
('Carbono', 6),
('Nitrógeno', 6);


INSERT INTO Partidas_preguntas (id_Partida, id_Pregunta) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2,1);

INSERT INTO Preguntas_respuestas_incorrectas (id_Pregunta, id_Respuesta_incorrecta) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 4),
(3, 5);

INSERT INTO Trampitas (precio) VALUES
(9.99),
(19.99),
(29.99);

INSERT INTO Jugadores_trampitas (id_Jugador, id_Trampita) VALUES
(1, 1),
(2, 2),
(3, 1);

INSERT INTO Preguntas_reportadas (motivo) VALUES
('Incorrecta'),
('Inapropiada'),
('Mal redactada');

INSERT INTO Reportes_de_preguntas (id_Reporte, id_Pregunta, id_Usuario) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 3, 3);

INSERT INTO Preguntas_sugeridas (descripcion, id_categoria, id_respuesta_correcta_sugerida, id_usuario) VALUES
('¿Cuál es la capital de Italia?', 1, 1, 1),
('¿Quién escribió "Cien años de soledad"?', 2, 2, 2),
('¿Qué deporte jugó profesionalente Michael Jordan además de Basket?', 3, 3, 1)
('¿Cuál es el océano más grande del mundo?', 4, 4, 3),
('¿En qué año comenzó la Segunda Guerra Mundial?', 5, 5, 4),
('¿Cuál es el elemento químico con símbolo "H"?', 6, 6, 5);

INSERT INTO Respuestas_correctas_sugeridas (descripcion, id_categoria) VALUES
('Roma', 1),
('Gabriel García Márquez', 2),
('Baseball', 3)
('Pacífico', 4),
('1939', 5),
('Hidrógeno', 6);

INSERT INTO Respuestas_incorrectas_sugeridas (descripcion, id_categoria) VALUES
('Milán', 1),
('Mario Vargas Llosa', 2),
('Football Americano', 3)
('Atlántico', 4),
('1940', 5),
('Helio', 6);

INSERT INTO Preguntas_y_respuestas_incorrectas_sugeridas (id_Pregunta_sugerida, id_Respuesta_incorrecta_sugerida) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5)
(6, 6);

INSERT INTO Paises (descripcion) VALUES
('Argentina'),
('Brasil'),
('Chile'),
('Colombia'),
('Perú');

INSERT INTO Ciudades (descripcion) VALUES
('Buenos Aires'),
('Sao Paulo'),
('Santiago'),
('Bogotá'),
('Lima');

INSERT INTO Usuarios_ciudades_paises (id_Usuario, id_Ciudad, id_pais) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 5, 5);

INSERT INTO Sexos (descripcion) VALUES
('Femenino'),
('Maasculino'),
('No especificado');

















