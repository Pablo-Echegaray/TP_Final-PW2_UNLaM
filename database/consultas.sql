/*Obtener cada pregunta con su respectiva respuesta correcta*/
SELECT p.id, p.descripcion, rc.descripcion
FROM preguntados.preguntas p
INNER JOIN preguntados.respuestas_correctas rc ON p.id_respuesta_correcta = rc.id;

/*Obtener cada pregunta son sus respectivas respuestas incorrectas*/
SELECT p.id, p.descripcion, ri.descripcion
FROM preguntados.preguntas p
INNER JOIN preguntas_respuestas_incorrectas pri ON pri.id_pregunta = p.id
INNER JOIN respuestas_incorrectas ri ON ri.id = pri.id_respuesta_incorrecta;

/*Obtener pregunta de una partida específica*/
SELECT pp.id_partida, pp.id_pregunta, pre.descripcion
FROM preguntados.partidas_preguntas pp
INNER JOIN preguntados.preguntas pre ON pp.id_pregunta = pre.id;