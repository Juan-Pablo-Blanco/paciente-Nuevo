/* Se crea la tabla pacientes */
CREATE TABLE `pacientes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(40) NOT NULL,
    `apellido` VARCHAR(40) NOT NULL,
    `fecha_nacimiento` DATE NOT NULL,
    `telefono` VARCHAR(15) DEFAULT NULL,
    `adulto_responsable` VARCHAR(80) DEFAULT NULL,
    `motivo_consulta` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
);

/* Se crea la tabla turnos */
CREATE TABLE `turnos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `paciente_id` INT(11) NOT NULL,
    `fecha_turno` DATE NOT NULL,
    `hora_turno` TIME NOT NULL,
    `observaciones` TEXT DEFAULT NULL,
    `obra_social` VARCHAR(30) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_turno_paciente`
        FOREIGN KEY (`paciente_id`) REFERENCES `pacientes`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
);

/* Se crea la tabla roles */
CREATE TABLE `roles` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `rol` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`id`)
);

/* Se crea la tabla usuarios */
CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(50) NOT NULL,
    `nombre` VARCHAR(50) NOT NULL,
    `apellido` VARCHAR(50) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `password` CHAR(255) NOT NULL,
    `id_rol` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `usuario_unico` (`usuario`),
    CONSTRAINT `fk_usuario_rol`
        FOREIGN KEY (`id_rol`) REFERENCES `roles`(`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
);