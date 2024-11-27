create table if not exists rol(
    id_rol INT NOT NULL comment 'id del rol',
    tipo VARCHAR(20) NOT NULL comment 'tipo de rol',
    primary key(id_rol)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists usuarios(
    usuario VARCHAR(10) NOT NULL comment'Guarda el nombre identificador del usuario',
    nombre VARCHAR(20) NOT NULL comment'Guarda el nombre real del usuario',
    apellido VARCHAR(20) NOT NULL comment'Guarda el apellido del usuario',
    correo VARCHAR(30) NOT NULL comment'Guarda el correo del usuario',
    contrasena VARCHAR(30) NOT NULL comment'Guarda la contrasenia del usuario',
    rol INT NOT NULL comment'Tipo de rol del usuario',
    primary key(usuario),
    FOREIGN KEY (rol) REFERENCES rol(id_rol)
    ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists direcciones(
    id_direccion INT AUTO_INCREMENT NOT NULL,
    id_usuario varchar(10) NOT NULL comment 'Almacena el id del usuario que tiene la direccion',
    calle VARCHAR(30) NOT NULL comment'Guarda nombre de la calle del usuario',
    colonia VARCHAR(30)  NOT NULL comment'Guarda nombre de la colonia del usuario',
    cod_postal INT(5)  NOT NULL comment'Guarda codigo postal del usuario',
    numero INT(30)  NOT NULL comment'Guarda numero de casa del usuario',
    estado VARCHAR(30) NOT NULL comment'Guarda nombre del estado del usuario',
    ciudad VARCHAR(30) NOT NULL comment'Guarda nombre de la ciudad del usuario',
    primary key(id_direccion),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(usuario)
    ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists pasteles(
    id_pastel int NOT NULL comment'Guarda el numero identificador del pastel',
    nombreP VARCHAR(20) NOT NULL comment'Guarda el nombre del pastel',
    descripcion TEXT NOT NULL comment'Guarda una descripcion de lo que contiene el pastel',
    precio double  NOT NULL comment'Guarda el precio del pastel',
    categoria varchar(20) NOT NULL Comment 'Guarda la categoria del producto',
    visualizacion varchar(15) NOT NULL comment'Se alamacena visualizar o ocultar dependiendo si se desea visualizar el pastel',
    primary key(id_pastel)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT(11) NOT NULL AUTO_INCREMENT comment'Guarda el numero identificador del pedido',
    user_id VARCHAR(10) NOT NULL comment'Guarda el numero identificador del usuario con el que esta relacionado ek pedido',
    fecha_actual DATE NOT NULL comment'Guarda la fecha en la que se realiza el pedido',
    estatus VARCHAR(30) NOT NULL comment'Guarda el estatus del pedido',
    Total DOUBLE NOT NULL comment'Guarda el precio total del pedido',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES usuarios(usuario)
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS pedido_articulos (
    id INT(11) NOT NULL AUTO_INCREMENT comment'Guarda el id de la descripcion del pedido',
    pedido_id INT(11) NOT NULL comment'Guarda el numero identificador del pedido',
    pastel_id int NOT NULL comment'Guarda el numero identificador del pastel con el que se relaciona el pedido',
    fecha_entrega DATE NOT NULL comment'Guarda la fecha de entrega del pastel',
    color VARCHAR(30) comment'Guarda el color del pastel',
    texto VARCHAR(50) comment'Guarda el texto que tendra del pastel',
    PRIMARY KEY (id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    FOREIGN KEY (pastel_id) REFERENCES pasteles(id_pastel)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists recetas(
   id int(11) not null auto_increment comment'Guarda el id de la receta',
   nombre varchar(20) NOT NULL comment'Guarda el nombre de la receta',
   receta text  NOT NULL comment 'Guarda la receta del pastel ',
   usuario_id VARCHAR(10) NOT NULL  comment'Guarda el usuario',
   primary key(id),
   FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario)
   ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists comentarios(
    id int NOT NULL auto_increment primary key  comment 'Guarda el numero identificador del comentario',
    idUsuario VARCHAR(10) NOT NULL comment'Guarda el numero identificador del usuario que hizo el comentario',
    comentario text NOT NULL comment'Guarda el comentario realizado por el usuario',
    satisfaccion int not null comment 'Guarda la satisfaccion del usuario con el servicio(1-5)',
    FOREIGN KEY (idUsuario) REFERENCES usuarios(usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists resenias(
    id int NOT NULL auto_increment primary key comment 'Guarda el numero identificador de la reseña',
    idUsuario VARCHAR(10) NOT NULL comment'Guarda el numero identificador del usuario que hizo la reseña',
    idpastel INT NOT NULL comment 'Guarda el id del pastel',
    resenias text NOT NULL comment'Guarda la reseña realizada por el usuario',
    FOREIGN KEY (idUsuario) REFERENCES usuarios(usuario)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    FOREIGN KEY (idpastel) REFERENCES pasteles(id_pastel)
    ON UPDATE CASCADE
    ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS bitacora_usuarios;
CREATE TABLE bitacora_usuarios (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario_id VARCHAR(30) NOT NULL comment 'Almacena el id del usuario',
    accion VARCHAR(50) NOT NULL comment 'Almacena la accion realizada por el usuario',
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS bitacora_recetas;
CREATE TABLE bitacora_recetas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    receta_id VARCHAR(30) NOT NULL comment 'Almacena el id de la receta',
    usuario_id VARCHAR(30) NOT NULL comment 'Almacena el id del usuario',
    accion VARCHAR(50) NOT NULL comment 'Almacena la accion realizada por el usuario',
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS bitacora_pedidos;
CREATE TABLE bitacora_pedidos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL comment 'Almacena el id del pedido',
    accion VARCHAR(50) NOT NULL comment 'Almacena si el pedido fue entregado o cancelado',
    total DOUBLE NOT NULL comment 'Alamacena el total del pedido',
    fecha DATE NOT NULL comment 'Almacena la fecha en que fue creado el pedido'
);

DROP TABLE IF EXISTS bitacora_pasteles;
CREATE TABLE IF NOT EXISTS bitacora_pasteles (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreP VARCHAR(30) NOT NULL comment 'Almacena el nombre del pastel',
    fecCompra  DATE NOT NULL comment 'Almacena la fecha de compra del pastel',
    Precio DOUBLE NOT NULL comment 'Almacena el el precio del pastel'
);


Insert into pasteles(id_pastel,nombreP,descripcion,precio,visualizacion) values (1,'Cupcake','Esquisito cupcake personalizado con el texto de tu preferecia',45,'visualizar');
Insert into pasteles(id_pastel,nombreP,descripcion,precio,visualizacion) values (2,'Pastel Mediano','Esquisito cupcake personalizado con el texto de tu preferecia',85,'visualizar');
Insert into pasteles(id_pastel,nombreP,descripcion,precio,visualizacion) values (3,'Pastel Grande','Esquisito cupcake personalizado con el texto de tu preferecia',150,'visualizar');

Insert into rol(id_rol,tipo) values (1,'admin');
Insert into rol(id_rol,tipo) values (2,'gerente ');
Insert into rol(id_rol,tipo) values (3,'cliente');

Insert into usuarios(usuario,nombre,apellido,correo,contrasena,rol) values ('admin','Administrador','Administrador','admin@gmail.com','12345',1);
