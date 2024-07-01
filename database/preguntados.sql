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
    codigo_verificacion VARCHAR(50),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_usuario PRIMARY KEY (id),
    CONSTRAINT unique_email UNIQUE (email),
    CONSTRAINT unique_nombre_usuario UNIQUE (nombre_usuario)
    );

CREATE TABLE IF NOT EXISTS partidas(
    id INT AUTO_INCREMENT,
    modo VARCHAR(50) NOT NULL, /* single player | multiplayer */
    estado VARCHAR(50) NOT NULL, /* playing | finished*/
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

CREATE TABLE IF NOT EXISTS google_maps_persistence (
    id INT AUTO_INCREMENT,
    id_user INT DEFAULT NULL,
    city VARCHAR(150) DEFAULT NULL,
    country VARCHAR(20) DEFAULT NULL,
    lat FLOAT(10,6) DEFAULT NULL,
    lng FLOAT(10,6) DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_maps PRIMARY KEY (id),
    CONSTRAINT fk_usuario_map FOREIGN KEY (id_user) REFERENCES usuarios(id)
);

/*INSERTS*/

INSERT INTO usuarios (nombre, apellido, year_birth, sexo, ciudad, pais, email, password, nombre_usuario, foto, rol, activo, entregadas, hit, qr, fecha_creacion) VALUES
    ('Pablo', 'Echegaray', 1995, 'Masculino', 'Buenos Aires', 'Argentina', 'pablo.echegaray@example.com', 'password123', 'pabloE', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR1', '2023-12-01 12:00:00'),
    ('Micaela', 'Mendez', 2002, 'Femenino', 'Buenos Aires', 'Argentina', 'micaela.mendez@example.com', 'password456', 'micaM', 'perfil_sin_foto.jpg', 'A', 1, 100, 50, 'QR2', '2023-12-02 10:30:00'),
    ('Pablo', 'Rocha', 2002, 'Masculino', 'Buenos Aires', 'Argentina', 'pablo.rocha@example.com', 'password789', 'pabloR', 'perfil_sin_foto.jpg', 'E', 1, 100, 50, 'QR3', '2023-12-03 15:45:00'),
    ('Regina', 'Sanchez', 2002, 'Femenino', 'Buenos Aires', 'Argentina', 'regina.sanchez@example.com', 'password101', 'reginaS', 'perfil_sin_foto.jpg', 'A', 1, 100, 50, 'QR4', '2023-12-04 08:00:00'),
    ('Carlos', 'González', 1992, 'No especificado', 'Lima', 'Perú', 'carlos.gonzalez@example.com', 'password112', 'carlosg92', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR5', '2024-01-01 14:20:00'),
    ('José', 'González', 1995, 'Masculino', 'Ciudad de México', 'México', 'jose.gonzalez@example.com', 'password123', 'joseG', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR16', '2024-01-10 09:30:00'),
    ('Ana', 'Martín', 2010, 'Femenino', 'Buenos Aires', 'Argentina', 'ana.martin@example.com', 'passwordabc', 'anaM', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR21', '2024-01-20 10:00:00'),
    ('Fernanda', 'López', 1980, 'Femenino', 'Bogotá', 'Colombia', 'fernanda.lopez@example.com', 'password456', 'fernandaL', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR17', '2024-02-05 14:45:00'),
    ('Alejandro', 'Martínez', 1975, 'Masculino', 'Santiago', 'Chile', 'alejandro.martinez@example.com', 'password789', 'alejandroM', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR18', '2024-03-03 11:00:00'),
    ('Valeria', 'Fernández', 1990, 'Femenino', 'Lima', 'Perú', 'valeria.fernandez@example.com', 'password101', 'valeriaF', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR19', '2024-04-15 16:20:00'),
    ('Carlos', 'Sánchez', 2005, 'No especificado', 'San José', 'Costa Rica', 'carlos.sanchez@example.com', 'password112', 'carlosS', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR20', '2024-05-08 08:30:00'),
    ('Miguel', 'Hernández', 1995, 'Masculino', 'Quito', 'Ecuador', 'miguel.hernandez@example.com', 'passwordjkl', 'miguelH', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR24', '2024-05-13 16:45:00'),
    ('Laura', 'Gutiérrez', 1983, 'Femenino', 'Medellín', 'Colombia', 'laura.gutierrez@example.com', 'password123', 'lauraG', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR26', '2024-06-28 10:00:00'),
    ('Jorge', 'Pérez', 1990, 'Masculino', 'Buenos Aires', 'Argentina', 'jorge.perez@example.com', 'password456', 'jorgeP', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR27', '2024-07-01 14:30:00'),
    ('María', 'López', 1978, 'Femenino', 'Lima', 'Perú', 'maria.lopez@example.com', 'password789', 'mariaL', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR28', '2024-07-01 08:45:00'),
    ('Roberto', 'Sánchez', 1985, 'Masculino', 'Santiago', 'Chile', 'roberto.sanchez@example.com', 'password101', 'robertoS', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR29', '2024-07-02 12:00:00'),
    ('Ana', 'Paredes', 1980, 'Femenino', 'Madrid', 'España', 'ana.martinez@example.com', 'password123', 'anaP', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR30', '2024-07-03 09:00:00'),
    ('Lucía', 'Pérez', 1970, 'Femenino', 'Montevideo', 'Uruguay', 'lucia.perez@example.com', 'passwordghi', 'luciaP', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR23', '2024-07-07 14:30:00'),
    ('Sara', 'Díaz', 1988, 'Femenino', 'Santiago', 'Chile', 'sara.diaz@example.com', 'passwordmno', 'saraD', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR25', '2024-07-10 18:00:00'),
    ('Elena', 'López', 1988, 'Femenino', 'Bogotá', 'Colombia', 'elena.lopez@example.com', 'password789', 'elenaL', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR32', '2024-07-10 11:00:00'),
    ('Elena', 'Fernandez', 1988, 'Femenino', 'Bogotá', 'Colombia', 'elena.fernandez@example.com', 'password789', 'elenaF', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR32', '2024-07-10 11:00:00'),
    ('Javier', 'Gómez', 1955, 'Masculino', 'Madrid', 'España', 'javier.gomez@example.com', 'passworddef', 'javierG', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR22', '2024-07-14 12:15:00'),
    ('María', 'González', 1983, 'Femenino', 'Santiago', 'Chile', 'maria.gonzalez@example.com', 'password112', 'mariaG', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR34', '2024-07-17 10:30:00'),
    ('Pedro', 'Rodríguez', 1992, 'Masculino', 'Buenos Aires', 'Argentina', 'pedro.rodriguez@example.com', 'password101', 'pedroR', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR33', '2024-07-21 14:00:00'),
    ('Juan', 'García', 1975, 'Masculino', 'Ciudad de México', 'México', 'juan.garcia@example.com', 'password456', 'juanG', 'perfil_sin_foto.jpg', 'J', 1, 100, 50, 'QR31', '2024-07-25 15:30:00');

/*Este insert debería hacerse cuando se crea una partida y el usuario elike si va a jugar 'single player' o 'multiplayer'.*/
INSERT INTO partidas (modo, estado, fecha_creacion) VALUES
('Single Player', 'finished', '2024-01-15'),
('Single Player', 'finished', '2024-02-20'),
('Single Player', 'finished', '2024-03-10'),
('Single Player', 'finished', '2024-03-25'),
('Single Player', 'finished', '2024-04-05'),
('Single Player', 'finished', '2024-05-12'),
('Single Player', 'finished', '2024-06-03'),
('Single Player', 'finished', '2024-06-15'),
('Single Player', 'finished', '2024-06-28'),
('Single Player', 'finished', '2024-07-05'),
('Single Player', 'finished', '2024-07-12');

/*Este insert se se hace cuando finaliza la partida, y se le pasa el id de la misma, el id del jugador y el puntaje final obtenido.*/
INSERT INTO jugadores_partidas (id_Jugador, id_Partida, puntaje) VALUES
(1, 1, 100),
(5, 2, 80),
(6, 3, 90),
(7, 4, 110),
(1, 5, 10),
(8, 6, 150),
(9, 7, 30),
(6, 8, 90),
(7, 9, 10),
(1, 10, 180),
(5, 11, 30);

INSERT INTO categorias (descripcion) VALUES
    ('Geografía'),
    ('Literatura'),
    ('Deportes'),
    ('Ciencia'),
    ('Historia'),
    ('Cultura General');

INSERT INTO preguntas (descripcion, estado, entregadas, hit, id_categoria, fecha_creacion) VALUES
    ('¿Cuál es la capital de Francia?', 'activa', 100, 70, 1, '2023-11-01 08:00:00'),
    ('¿Quién escribió "Don Quijote"?', 'activa', 100, 50, 2, '2023-11-10 10:00:00'),
    ('¿Cuál es el equipo que más veces ganó la UEFA Champions League?', 'activa', 100, 50, 3, '2023-11-20 12:00:00'),
    ('¿En qué año llegó el hombre a la luna?', 'activa', 100, 70, 5, '2023-12-01 14:00:00'),
    ('¿Cuál es el elemento químico con símbolo "O"?', 'activa', 100, 50, 4, '2023-12-10 16:00:00'),
    ('¿Cuál es el río más largo del mundo?', 'activa', 100, 50, 1, '2023-12-20 18:00:00'),
    ('¿Qué ciudad es conocida como la Gran Manzana?', 'activa', 100, 70, 1, '2024-01-01 20:00:00'),
    ('¿Quién pintó la Mona Lisa?', 'activa', 100, 50, 6, '2024-01-10 22:00:00'),
    ('¿Quién escribió "Cien años de soledad"?', 'activa', 100, 70, 2, '2024-01-20 08:00:00'),
    ('¿Cuál es el deporte más popular del mundo?', 'activa', 100, 50, 3, '2024-02-01 10:00:00'),
    ('¿En qué año se fundó la FIFA?', 'activa', 100, 50, 3, '2024-02-10 12:00:00'),
    ('¿Cuál es la fórmula química del agua?', 'activa', 100, 70, 4, '2024-02-20 14:00:00'),
    ('¿Quién descubrió la penicilina?', 'activa', 100, 70, 6, '2024-03-01 16:00:00'),
    ('¿Cuál es la capital de Japón?', 'activa', 100, 50, 1, '2024-03-10 18:00:00'),
    ('¿Quién escribió "Hamlet"?', 'activa', 100, 50, 2, '2024-03-20 20:00:00'),
    ('¿Cuál es el planeta más cercano al sol?', 'activa', 100, 70, 4, '2024-04-01 22:00:00'),
    ('¿Quién es conocido como el padre de la física moderna?', 'activa', 100, 50, 4, '2024-04-10 08:00:00'),
    ('¿Cuál es el océano más grande del mundo?', 'activa', 100, 50, 1, '2024-04-20 10:00:00'),
    ('¿Qué país es el mayor productor de café?', 'activa', 100, 70, 1, '2024-05-01 12:00:00'),
    ('¿Quién escribió "La Divina Comedia"?', 'activa', 100, 50, 2, '2024-05-10 14:00:00'),
    ('¿Quién ganó el primer Mundial de Fútbol?', 'activa', 100, 70, 3, '2024-05-20 16:00:00'),
    ('¿Qué científico propuso la teoría de la relatividad?', 'activa', 100, 50, 4, '2024-06-01 18:00:00'),
    ('¿Cuál es el símbolo químico del oro?', 'activa', 100, 50, 4, '2024-06-10 20:00:00'),
    ('¿Cuál es la montaña más alta del mundo?', 'activa', 100, 50, 1, '2024-06-20 08:00:00'),
    ('¿Qué país tiene la mayor población del mundo?', 'activa', 100, 70, 1, '2024-06-25 10:00:00'),
    ('¿Quién es el autor de "1984"?', 'activa', 100, 50, 2, '2024-06-30 12:00:00'),
    ('¿Cuál es el país con más medallas olímpicas?', 'activa', 100, 50, 3, '2024-06-30 14:00:00'),
    ('¿Qué elemento tiene el símbolo Na?', 'activa', 100, 50, 4, '2024-06-30 16:00:00'),
    ('¿Cuál es el animal terrestre más rápido del mundo?', 'activa', 100, 70, 6, '2024-06-30 18:00:00'),
    ('¿Quién fue el ganador de las NBA Finals 2024?', 'activa', 100, 30, 3, '2024-06-30 18:10:00'),
    ('¿Cuáles fueron las dos selecciones clasificadas a cuartos de final de la Copa América 2024 por el grupo A?', 'activa', 100, 50, 3, '2024-06-30 18:20:00'),
    ('¿Cuándo fue la última vez que el Club Atlético Boca Juniors se consagró campeón de la CONMEBOL Libertadores?', 'activa', 100, 40, 3, '2024-06-30 18:30:00'),
    ('¿Cuántas veces se consagró campeón del mundo el Club Atlético Boca Juniors en su historia?', 'activa', 100, 70, 3, '2024-06-30 18:40:00'),
    ('¿Cuál es el significado de la sigla POO en el hámbito del desarrollo de Software?', 'activa', 100, 70, 4, '2024-06-30 18:41:00'),
    ('Cuando hablamos de MVC en el hámbito del desarrollo de Software, estamos hablando de...', 'activa', 100, 70, 4, '2024-06-30 18:42:00');

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
    ('León', 0, 29),
    ('Chicago Bulls', 0, 30),
    ('Timberwolves', 0, 30),
    ('Boston Celtics', 1, 30),
    ('Dallas Maverics', 0, 30),
    ('Brasil y Jamaica', 0, 31),
    ('Argentina y Chile', 0, 31),
    ('Argentina y Perú', 0, 31),
    ('Argentina y Canada', 1, 31),
    ('CONMEBOL Libertadores 2018', 0, 32),
    ('CONMEBOL Libertadores 2014', 0, 32),
    ('CONMEBOL Libertadores 2007', 1, 32),
    ('CONMEBOL Libertadores 2003', 0, 32),
    ('Se consagró 3 veces campeón del mundo', 1, 33),
    ('Se consagró 2 veces campeón del mundo', 0, 33),
    ('Se consagró 1 veces campeón del mundo', 0, 33),
    ('Nunca ha podido lograrlo', 0, 33),
    ('Programación Ordinal Objetiva', 0, 34),
    ('Programación Orientada a Objetivos', 0, 34),
    ('Programación Oriental Orientada', 0, 34),
    ('Programación Orientada a Objetos', 1, 34),
    ('Un patrón que separa la aplicación en tres componentes interconectados: Model, View, Capa', 0, 35),
    ('Un patrón que separa la aplicación en tres componentes interconectados: Model, View, Controller', 1, 35),
    ('Un modo de verificar nuestro código: Muy, Verficado, Chequeado', 0, 35),
    ('Un modo de testear nuestras aplicaciones: Más, Verficado, Chequeado', 0, 35);


/*id INT AUTO_INCREMENT,
    id_user INT NOT NULL,
    description VARCHAR(100) NOT NULL,
    city VARCHAR(150) NOT NULL,
    country VARCHAR(20) NOT NULL,
    lat FLOAT(10,6) DEFAULT NULL,
    lng FLOAT(10,6) DEFAULT NULL,
  -34.792035526341564, -58.4904372303487*/
INSERT INTO google_maps_persistence (id_user, city, country, lat, lng) VALUES
    (1, 'Monte Grande', 'Argentina', -34.792035526341564, -58.4904372303487);

/* Este insert deberia hacerse cuando se crea una partida y updetear cada vez que se entrega una pregunta*/
/*
INSERT INTO partidas_preguntas (id_Partida, id_Pregunta) VALUES
(1, 1);
*/