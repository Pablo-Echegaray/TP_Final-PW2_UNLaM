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
    CONSTRAINT pk_partida PRIMARY KEY (id)
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
