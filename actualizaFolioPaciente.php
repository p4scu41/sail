<?php 
require_once('include/var_global.php');
require_once('include/bdatos.php');
require_once('include/funciones.php');
require_once('include/log.php');
require_once('include/fecha_hora.php');

$connectionBD = conectaBD();

$result = ejecutaQuery('SELECT ROW_NUMBER() OVER(ORDER BY [idPaciente] ASC) AS folio, [idPaciente],[catUnidad].[idCatEstado] FROM [pacientes],[catUnidad] WHERE [pacientes].[idCatUnidadTratante] = [catUnidad].[idCatUnidad] ORDER BY [idPaciente]');

while($fila = devuelveRowAssoc($result)) {
    ejecutaQuery("UPDATE [pacientes] SET [folioRegistro] = 'LEP".str_pad($fila['idCatEstado'],2,'0',STR_PAD_LEFT).str_pad($fila['folio'],5,'0',STR_PAD_LEFT)."' WHERE [idPaciente] = ".$fila['idPaciente']);
    
}

closeConexion();

?>