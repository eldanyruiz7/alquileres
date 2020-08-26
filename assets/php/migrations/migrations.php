<?php
require_once ('../../connect/bd.php');
require_once ("../../connect/sesion.class.php");
$sesion = new sesion();
require_once ("../../connect/cerrarOtrasSesiones.php");
require_once ("../../connect/usuarioLogeado.php");
require_once ("../../php/funcionesVarias.php");
if( logueado($idSesion,$idUsuario,$mysqli) == false || $idSesion == false)
{
	header("Location: ../../../salir.php");
}
else
{

	require ("../usuario.class.php");
	require ("../query.class.php");
	$usuario 	= new usuario($idUsuario,$mysqli);
	$query 		= new Query();
	// echo $query->backup();
	$query ->dropTable("bitacora_eventos", 0);
	$query 	->createTable("bitacora_eventos", TRUE)
			->bigIncrements("id")
			->bigInt("idUsuario")
			->dateTimeCurrent("fecha")
			->varChar("ip", 30)
			->varChar("pantalla", 100)
			->varChar("descripcion", 500)
			->execute(TRUE);

	$query	->dropTable("tiposUsuarios", 0);
	$query	->createTable("tiposUsuarios", TRUE)
			->intIncrements("idTipoUsuario")
			->varChar("nombre", 15)
			->dateTimeCurrent("fechaCreacion")
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("cat_usuarios", 0);
	$query	->createTable("cat_usuarios", TRUE)
			->bigIncrements("idUsuario")
			->varChar("nombre", 200)
			->varChar("nickName", 30)
			->varChar("cntrsn", 100)
			->varChar("email", 40, TRUE)
			->int("tipo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("cat_estados", 0);
	$query	->createTable("cat_estados", TRUE)
			->intIncrements("idEstado")
			->varChar("nombre", 50)
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("cat_titulares", 0);
	$query	->createTable("cat_titulares", TRUE)
			->bigIncrements("idTitular")
			->varChar("nombre", 50)
			->varChar("apellidoPaterno", 50)
			->varChar("apellidoMaterno", 50, TRUE)
			->varChar("sexo",1)
			->int("idEstadoNacimiento")
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->dateTimeCurrent("fechaCreacion")
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("contratos", 0);
	$query	->createTable("contratos", TRUE)
			->bigIncrements("idContrato")
			->dateTime("fechaInicio")
			->int("mesesDuracion")
			->int("idInmueble")
			->decimal("montoRenta")
			->decimal("montoDeposito")
			->decimal("tasaInteresMoratorio")
			->text("textoContrato")
			->int("diasGraciaAtraso", FALSE, 0)
			->int("bDeposito")
			->int("activo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->execute(TRUE);

	$query	->dropTable("inmuebles", 0);
	$query	->createTable("inmuebles", TRUE)
			->intIncrements("idInmueble")
			->varChar("numero", 50)
			->varChar("numeroCorto", 10)
			->int("idTipoInmueble")
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->int("activo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->execute(TRUE);

	$query	->dropTable("tiposInmuebles", 0);
	$query	->createTable("tiposInmuebles", TRUE)
			->intIncrements("idTipoInmueble")
			->varChar("nombre", 50)
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("pagos", 0);
	$query	->createTable("pagos", TRUE)
			->bigIncrements("idPago")
			->bigInt("idContrato")
			->int("numeroPago")
			->int("idTipoPago")
			->dateTime("fechaPago", TRUE)
			->varChar("referenciaPago", 200, TRUE)
			->int("diasAtrasoMoratorio", FALSE, 0)
			->decimal("tasaInteresMoratorio")
			->decimal("monto")
			->decimal("montoAtraso")
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->int("activo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->execute(TRUE);

	$query	->dropTable("tiposPagos", 0);
	$query	->createTable("tiposPagos", TRUE)
			->intIncrements("idTipoPago")
			->varChar("nombre", 50)
			->int("activo", FALSE, 1)
			->execute(TRUE);

	$query	->dropTable("gastos", 0);
	$query	->createTable("gastos", TRUE)
			->bigIncrements("idGasto")
			->int("idTipoGasto")
			->int("idInmueble", TRUE)
			->decimal("monto")
			->varChar("observaciones", 200, TRUE)
			->dateTime("fechaGasto")
			->int("activo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->execute(TRUE);

	$query	->dropTable("tiposGastos", 0);
	$query	->createTable("tiposGastos", TRUE)
			->intIncrements("idTipoGasto")
			->varChar("nombre", 50)
			->bigInt("idUsuario")
			->bigInt("idUsuarioCancelacion", TRUE)
			->int("activo", FALSE, 1)
			->dateTimeCurrent("fechaCreacion")
			->execute(TRUE);

	$query	->dropTable("sesionescontrol", 0);
	$query	->createTable("sesionescontrol", TRUE)
			->bigIncrements("id")
			->dateTime("timestampentrada")
			->dateTime("timestampsalida", TRUE)
			->int("estado", FALSE, 1)
			->bigInt("usuario")
			->int("activo", FALSE, 1)
			->execute(TRUE);

	/*** INDEX Y FOREIGN KEYS */

	$query	->alterTable("bitacora_eventos")
			->index("idUsuario")
			->index("fecha")
			->execute(TRUE);
	$query	->alterTable("bitacora_eventos")
			->foreignKey("FK_bitacora_eventos_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->execute(TRUE);

	$query	->alterTable("tiposUsuarios")
			->index("idTipoUsuario")
			->index("nombre")
			->index("activo")
			->execute(TRUE);

	$query	->alterTable("cat_usuarios")
			->index("idUsuario")
			->index("nombre")
			->index("nickName")
			->index("tipo")
			->index("activo")
			->execute(TRUE);
	$query	->alterTable("cat_usuarios")
			->foreignKey("FK_cat_usuarios_TiposUsuarios00", "tipo", "tiposUsuarios", "idTipoUsuario")
			->execute(TRUE);

	$query	->alterTable("cat_titulares")
			->index("idTitular")
			->index("nombre")
			->index("apellidoPaterno")
			->index("apellidoMaterno")
			->index("idEstadoNacimiento")
			->index("activo")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->execute(TRUE);
	$query	->alterTable("cat_titulares")
			->foreignKey("FK_cat_titulares_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->foreignKey("FK_cat_titulares_cat_usuarios01", "idUsuarioCancelacion", "cat_usuarios", "idUsuario")
			->foreignKey("FK_cat_titulares_cat_estados00", "idEstadoNacimiento", "cat_estados", "idEstado")
			->execute(TRUE);

	$query	->alterTable("contratos")
			->index("idContrato")
			->index("fechaInicio")
			->index("mesesDuracion")
			->index("idInmueble")
			->index("activo")
			->index("fechaCreacion")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->execute(TRUE);
	$query	->alterTable("contratos")
			->foreignKey("FK_contratos_inmuebles00", "idInmueble", "inmuebles", "idInmueble")
			->foreignKey("FK_contratos_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->foreignKey("FK_contratos_cat_usuarios01", "idUsuarioCancelacion", "cat_usuarios", "idUsuario")
			->execute(TRUE);

	$query	->alterTable("inmuebles")
			->index("idInmueble")
			->index("numero")
			->index("numeroCorto")
			->index("idTipoInmueble")
			->index("activo")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->execute(TRUE);
	$query	->alterTable("inmuebles")
			->foreignKey("FK_inmuebles_tiposInmuebles00", "idTipoInmueble", "tiposInmuebles", "idTipoInmueble")
			->foreignKey("FK_inmuebles_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->foreignKey("FK_inmuebles_cat_usuarios01", "idUsuarioCancelacion", "cat_usuarios", "idUsuario")
			->execute(TRUE);

	$query	->alterTable("tiposInmuebles")
			->index("idTipoInmueble")
			->index("nombre")
			->index("activo")
			->execute(TRUE);

	$query	->alterTable("pagos")
			->index("idPago")
			->index("idContrato")
			->index("numeroPago")
			->index("idTipoPago")
			->index("fechaPago")
			->index("referenciaPago")
			->index("diasAtrasoMoratorio")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->index("activo")
			->execute(TRUE);
	$query	->alterTable("pagos")
			->foreignKey("FK_pagos_contratos00", "idContrato", "contratos", "idContrato")
			->foreignKey("FK_pagos_tiposPagos00", "idTipoPago", "tiposPagos", "idTipoPago")
			->foreignKey("FK_pagos_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->foreignKey("FK_pagos_cat_usuarios01", "idUsuarioCancelacion", "cat_usuarios", "idUsuario")
			->execute(TRUE);

	$query	->alterTable("tiposPagos")
			->index("idTipoPago")
			->index("nombre")
			->index("activo")
			->execute(TRUE);

	$query	->alterTable("gastos")
			->index("idGasto")
			->index("idTipoGasto")
			->index("idInmueble")
			->index("monto")
			->index("fechaGasto")
			->index("activo")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->execute(TRUE);
	$query	->alterTable("gastos")
			->foreignKey("FK_gastos_tiposGastos00", "idTipoGasto", "tiposGastos", "idTipoGasto")
			->foreignKey("FK_gastos_Inmuebles00", "idInmueble", "inmuebles", "idInmueble")
			->foreignKey("FK_gastos_cat_usuarios00", "idUsuario", "cat_usuarios", "idUsuario")
			->foreignKey("FK_gastos_cat_usuarios01", "idUsuarioCancelacion", "cat_usuarios", "idUsuario")
			->execute(TRUE);

	$query	->alterTable("tiposGastos")
			->index("idTipoGasto")
			->index("nombre")
			->index("activo")
			->index("idUsuario")
			->index("idUsuarioCancelacion")
			->index("fechaCreacion")
			->execute(TRUE);

	$query	->alterTable("sesionescontrol")
			->index("id")
			->index("timestampentrada")
			->index("timestampsalida")
			->index("estado")
			->index("usuario")
			->index("activo")
			->execute(TRUE);
	$query	->alterTable("sesionescontrol")
			->foreignKey("FK_sesionescontrol_cat_usuarios00", "usuario", "cat_usuarios", "idUsuario")
			->execute(TRUE);


/*** INSERTS INICIALIZACIÓN */

	$query 	->table("tiposUsuarios")
			->insert(array( "nombre" => "Administrador"), "s")
			->execute(TRUE);
	$query 	->table("tiposUsuarios")
			->insert(array( "nombre" => "Normal"), "s")
			->execute(TRUE);

	$query 	->table("tiposInmuebles")
			->insert(array( "nombre" => "Residencial"), "s")
			->execute(TRUE);
	$query 	->table("tiposInmuebles")
			->insert(array( "nombre" => "Comercial"), "s")
			->execute(TRUE);

	$query 	->table("tiposPagos")
			->insert(array( "nombre" => "Renta"), "s")
			->execute(TRUE);
	$query 	->table("tiposPagos")
			->insert(array( "nombre" => "Depósito"), "s")
			->execute(TRUE);

	$query 	->table("cat_usuarios")
			->insert(array( "nombre" 	=> "root",
							"nickName"	=> "system",
							"cntrsn"	=> '$2y$15$HkVCwVWLCdErC/pmqhXHwepVjIpP6TevqpMKosqRhzv5oOBG/dpvq',
							"email"		=> "gamb2006@gmail.com"), "ssss")->execute(TRUE);

	$query 	->table("tiposGastos")
			->insert(array(	"nombre"	=> "Predial",
							"idUsuario"	=> 1), "si")->execute(TRUE);
	$query 	->table("tiposGastos")
			->insert(array(	"nombre"	=> "Energía Eléctrica",
							"idUsuario"	=> 1), "si")->execute(TRUE);


 }
