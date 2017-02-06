UPDATE rhu_examen SET codigo_factura_fk = NULL;
UPDATE rhu_seleccion SET codigo_factura_fk = NULL;

/*Cartera*/
DELETE FROM car_recibo_detalle;
DELETE FROM car_recibo;
DELETE FROM car_nota_credito_detalle;
DELETE FROM car_nota_credito;
DELETE FROM car_nota_debito_detalle;
DELETE FROM car_nota_debito;
DELETE FROM car_cuenta_cobrar;
DELETE FROM car_cliente;

/*Turnos*/
DELETE FROM tur_costo_recurso_detalle;
DELETE FROM tur_costo_servicio;
DELETE FROM tur_simulacion_detalle;
DELETE FROM tur_novedad;
DELETE FROM tur_costo_recurso;
DELETE FROM tur_cierre_mes_servicio_detalle;
DELETE FROM tur_cierre_mes_servicio;
DELETE FROM tur_soporte_pago_detalle;
DELETE FROM tur_soporte_pago_inconsistencia;
DELETE FROM tur_soporte_pago;
DELETE FROM tur_soporte_pago_periodo;
DELETE FROM tur_factura_detalle;
DELETE FROM tur_factura_detalle_concepto;
DELETE FROM tur_factura;
DELETE FROM tur_programacion_detalle;
DELETE FROM tur_programacion;
DELETE FROM tur_pedido_detalle_recurso;
DELETE FROM tur_pedido_detalle;
DELETE FROM tur_pedido;
DELETE FROM tur_servicio_detalle_plantilla;
DELETE FROM tur_servicio_detalle_recurso;
DELETE FROM tur_servicio_detalle;
DELETE FROM tur_servicio;
DELETE FROM tur_cotizacion_otro;
DELETE FROM tur_cotizacion_detalle;
DELETE FROM tur_cotizacion;
/*DELETE FROM tur_recurso;*/

/* Recurso humano */
DELETE FROM rhu_visita;
DELETE FROM rhu_provision;
DELETE FROM rhu_provision_periodo;
DELETE FROM rhu_soporte_pago_horario_detalle;
DELETE FROM rhu_soporte_pago_horario;
DELETE FROM rhu_permiso;
DELETE FROM rhu_soporte_pago_horario;
DELETE FROM rhu_horario_acceso;
DELETE FROM rhu_horario_periodo;
DELETE FROM rhu_desempeno_detalle;
DELETE FROM rhu_desempeno;
DELETE FROM rhu_capacitacion_detalle;
DELETE FROM rhu_capacitacion_nota;
DELETE FROM rhu_capacitacion;
DELETE FROM rhu_empleado_informacion_interna;
DELETE FROM rhu_seleccion_requisicion_aspirante;
DELETE FROM rhu_aspirante;
DELETE FROM rhu_embargo;
DELETE FROM rhu_requisito_detalle;
DELETE FROM rhu_requisito;
DELETE FROM rhu_supervigilancia_parafiscales;
DELETE FROM ad_archivo;
DELETE FROM rhu_pago_examen_detalle;
DELETE FROM rhu_pago_examen;
DELETE FROM rhu_pago_banco_detalle;
DELETE FROM rhu_pago_banco;
DELETE FROM rhu_proyeccion;
DELETE FROM rhu_credito_pago;
DELETE FROM rhu_ingreso_base;
DELETE FROM rhu_cambio_salario;
DELETE FROM rhu_factura_detalle;
DELETE FROM rhu_factura;
DELETE FROM rhu_sso_aporte;
DELETE FROM rhu_sso_periodo_empleado;
DELETE FROM rhu_sso_periodo_detalle;
DELETE FROM rhu_seleccion_entrevista;
DELETE FROM rhu_seleccion_referencia;
DELETE FROM rhu_seleccion_prueba;
DELETE FROM rhu_seleccion_visita;
DELETE FROM rhu_seleccion;
DELETE FROM rhu_seleccion_requisito;
DELETE FROM rhu_pago_adicional;
DELETE FROM rhu_servicio_cobrar;
DELETE FROM rhu_pago_detalle;
DELETE FROM rhu_pago;
DELETE FROM rhu_incapacidad_pago_detalle;
DELETE FROM rhu_incapacidad_pago;
DELETE FROM rhu_incapacidad;
DELETE FROM rhu_licencia;
DELETE FROM rhu_credito_pago;
DELETE FROM rhu_programacion_pago_detalle;
DELETE FROM rhu_programacion_pago;
DELETE FROM rhu_liquidacion_adicionales;
DELETE FROM rhu_liquidacion;
DELETE FROM rhu_vacacion_credito;
DELETE FROM rhu_vacacion_adicional;
DELETE FROM rhu_vacacion;
DELETE FROM rhu_contrato_sede;
DELETE FROM rhu_contrato_prorroga;
DELETE FROM rhu_traslado_salud;
DELETE FROM rhu_traslado_pension;
DELETE FROM rhu_disciplinario_descargo;
DELETE FROM rhu_disciplinario;
DELETE FROM rhu_cambio_tipo_contrato;
DELETE FROM rhu_contrato;
DELETE FROM rhu_credito;
DELETE FROM rhu_dotacion_detalle;
DELETE FROM rhu_dotacion;
DELETE FROM rhu_acreditacion;
DELETE FROM rhu_empleado_estudio;
DELETE FROM rhu_empleado_familia;
DELETE FROM rhu_accidente_trabajo;
/*DELETE FROM rhu_empleado;*/
DELETE FROM rhu_examen_restriccion_medica_detalle;
DELETE FROM rhu_examen_restriccion_medica;
DELETE FROM rhu_examen_detalle;
DELETE FROM rhu_examen;
DELETE FROM rhu_sede;
/*DELETE FROM rhu_centro_costo;*/
DELETE FROM ctb_registro;
DELETE FROM ctb_registro_exportar;

UPDATE `rhu_configuracion` SET anio_actual = 2017, vr_salario = 737717, vr_auxilio_transporte = 83140;
UPDATE `rhu_cierre_anio` SET estado_cerrado = 0;
UPDATE `rhu_sso_periodo` SET estado_generado = 0, estado_cerrado = 0;

UPDATE `rhu_centro_costo` SET `fecha_ultimo_pago` = '2016-12-30', `fecha_ultimo_pago_programado` = '2016-12-30', `fecha_ultimo_pago_prima` = '2016-12-30', `fecha_ultimo_pago_cesantias` = '2016-12-30' WHERE codigo_centro_costo_pk = 1;
UPDATE `rhu_centro_costo` SET `fecha_ultimo_pago` = '2016-12-30', `fecha_ultimo_pago_programado` = '2016-12-30', `fecha_ultimo_pago_prima` = '2016-12-30', `fecha_ultimo_pago_cesantias` = '2016-12-30' WHERE codigo_centro_costo_pk = 2;
UPDATE `rhu_centro_costo` SET `fecha_ultimo_pago` = '2016-12-30', `fecha_ultimo_pago_programado` = '2016-12-30', `fecha_ultimo_pago_prima` = '2016-12-30', `fecha_ultimo_pago_cesantias` = '2016-12-30' WHERE codigo_centro_costo_pk = 3;

insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('1','1','1','3','1','1','1','1','1','1','0',NULL,'17','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('2','2','1','3','1','1','1','1','1','1','0',NULL,'17','1','3','2239','2017-01-01','2017-01-03','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('3','3','1','3','1','1','1','1','1','1','0',NULL,'23','1','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('4','4','1','1','1','1','1','1','1','1','0',NULL,'22','2','3','2239','2017-01-17','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','0','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0','1','0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('5','5','1','3','1','1','1','1','1','1','0',NULL,'24','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'R123','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('6','6','1','1','1','1','1','1','1','1','0',NULL,'17','1','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('7','7','1','3','1','1','1','1','1','1','0',NULL,'22','1','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('8','8','1','3','1','1','1','1','1','1','0',NULL,'22','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('9','9','1','3','1','1','1','1','1','1','0',NULL,'17','1','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('10','10','1','1','1','1','1','1','1','1','0',NULL,'17','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'R123','VIGILANTE','VARIABLE','737717','737717','1',NULL,'1','2016-12-30','2016-12-30','2016-12-30','2016-12-30','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('11','11','1','2','1','1','1','1','1','1','0',NULL,'12','2','3','2239','2017-01-01','2017-01-05','2017-09-01',NULL,NULL,'001','ADMINISTRATIVO','HORARIO FIJO','1300000','1300000','1',NULL,'1','2017-01-05','2017-01-05','2017-01-05','2017-01-05','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('12','12','1','2','1','1','1','1','1','1','0',NULL,'17','5','3','2239','2017-01-01','2017-01-09','2017-09-01',NULL,NULL,'001','ADMINISTRATIVO','HORARIO FIJO','737717','737717','1',NULL,'1','2017-01-09','2017-01-09','2017-01-09','2017-01-09','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('13','13','1','2','1','1','1','1','1','1','0',NULL,'17','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'001','ADMINISTRATIVO','HORARIO FIJO','950000','950000','1',NULL,'1','2017-01-01','2017-01-01','2017-01-01','2017-01-01','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
insert into `rhu_contrato` (`codigo_contrato_pk`, `codigo_empleado_fk`, `codigo_tipo_tiempo_fk`, `codigo_centro_costo_fk`, `codigo_contrato_tipo_fk`, `codigo_clasificacion_riesgo_fk`, `codigo_cargo_fk`, `codigo_tipo_pension_fk`, `codigo_tipo_salud_fk`, `codigo_tipo_cotizante_fk`, `codigo_subtipo_cotizante_fk`, `codigo_motivo_terminacion_contrato_fk`, `codigo_entidad_salud_fk`, `codigo_entidad_pension_fk`, `codigo_entidad_caja_fk`, `codigo_ciudad_contrato_fk`, `fecha`, `fecha_desde`, `fecha_hasta`, `fecha_prorroga_inicio`, `fecha_prorroga_final`, `numero`, `cargo_descripcion`, `horario_trabajo`, `vr_salario`, `vr_salario_pago`, `estado_activo`, `comentarios`, `indefinido`, `fecha_ultimo_pago_cesantias`, `fecha_ultimo_pago_vacaciones`, `fecha_ultimo_pago_primas`, `fecha_ultimo_pago`, `estado_liquidado`, `factor`, `factor_horas_dia`, `salario_integral`, `codigo_usuario`, `ibp_cesantias_inicial`, `codigo_salario_tipo_fk`, `codigo_ciudad_labora_fk`, `ibp_recargo_nocturno_inicial`, `estado_terminado`, `codigo_usuario_termina`, `codigo_entidad_cesantia_fk`, `ibp_primas_inicial`, `limitarHoraExtra`, `comentarios_terminacion`, `vr_devengado_pactado`, `codigo_contrato_clase_fk`, `turno_fijo_ordinario`, `secuencia`, `codigo_contrato_grupo_fk`) values('14','14','1','2','1','1','1','1','1','1','0',NULL,'22','2','3','2239','2017-01-01','2017-01-01','2017-09-01',NULL,NULL,'R123','ADMINISTRATIVO','HORARIO FIJO','2100000','2100000','1',NULL,'1','2017-01-01','2017-01-01','2017-01-01','2017-01-01','0','8','8','0','marioestrada','0','1','2239','0','0',NULL,'1','0','0',NULL,'0',NULL,'0',NULL,NULL);
