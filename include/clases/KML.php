<?php

//require_once('../include/var_global.php');
require_once('Helpers.php');

class KML {
    public $matriz;
    public $contenido;
    private $help;
    
    public function __construct(){
        $this->help = new Helpers();
    }
    
    public function doKML() {
$this->contenido = '<kml xmlns="http://www.opengis.net/kml/2.2" 
    xmlns:gx="http://www.google.com/kml/ext/2.2" 
    xmlns:kml="http://www.opengis.net/kml/2.2" 
    xmlns:atom="http://www.w3.org/2005/Atom">
    
    <Document>
        <Style id="highlightPlacemark">
            <IconStyle>
                <Icon>
                    <href>http://maps.google.com/mapfiles/kml/paddle/red-stars.png</href>
                </Icon>
            </IconStyle>
        </Style>';
        if($this->matriz != null)
        {
            foreach($this->matriz as $elemento)
            {
        $this->contenido .= '
            <Placemark id="'.$elemento['id'].'">
                <name>'.$elemento['name'].'</name>
                <description>
                   <![CDATA[
                       '.$elemento['description'].'
                   ]]>
                </description>
                <Point>
                    <coordinates>'.($elemento['lon']-rand(0.10000111, 0.90000999)).','.($elemento['lat']+rand(0.10000111, 0.90000999)).',0</coordinates>
                </Point>
                <LookAt>
                    <altitude>0</altitude>
                    <heading>0</heading>
                    <tilt>0</tilt>
                    <gx:altitudeMode>relativeToSeaFloor</gx:altitudeMode>
                </LookAt>
                <styleUrl>#highlightPlacemark</styleUrl>
            </Placemark>';
            }
        }
$this->contenido .= '
    </Document>
</kml>';
    }
    
    public function getKML() {
        header('Content-Disposition: attachment; filename="pacientesLepra.kml"');
        header('Content-type: application/vnd.google-earth.kml+xml');
        echo $this->contenido;
    }
    
    public function getMatriz() {
        return $this->matriz;
    }
    
    public function queryKML($tipoPaciente, $fechaInicio, $fechaFin) {
        $this->matriz = null;

		if ($_SESSION[EDO_USR_SESSION] == 0) {
			$query = 'SELECT [pacientes].[cveExpediente]
                ,([pacientes].[nombre]+\' \'+[pacientes].[apellidoPaterno]+\' \'+[pacientes].[apellidoMaterno]) as nombre
                ,[pacientes].[idCatTipoPaciente]
                ,[pacientes].[sexo]
                ,[pacientes].[fechaNacimiento]
                ,([pacientes].[calle]+\' \'+[pacientes].[noExterior]) AS direccion
                ,[pacientes].[colonia]
                ,[pacientes].[idCatLocalidad]
                ,[pacientes].[idCatMunicipio]
                ,[pacientes].[fechaDiagnostico] 
                ,[pacientes].[fechaNotificacion]
                ,[pacientes].[idCatUnidadReferido]
                ,[pacientes].[idCatUnidadTratante]
                ,[diagnostico].[idCatClasificacionLepra]
                ,[catLocalidad].[lat_dec]
                ,[catLocalidad].[lon_dec]
			FROM [pacientes], [catLocalidad], [diagnostico]
			WHERE [catLocalidad].[idCatEstado]=[pacientes].[idCatEstado] AND
                [catLocalidad].[idCatMunicipio]=[pacientes].[idCatMunicipio] AND
                [catLocalidad].[idCatLocalidad]=[pacientes].[idCatLocalidad] AND
                [diagnostico].[idPaciente] = [pacientes].[idPaciente]';	
		} else {
			$query = 'SELECT [pacientes].[cveExpediente]
                ,([pacientes].[nombre]+\' \'+[pacientes].[apellidoPaterno]+\' \'+[pacientes].[apellidoMaterno]) as nombre
                ,[pacientes].[idCatTipoPaciente]
                ,[pacientes].[sexo]
                ,[pacientes].[fechaNacimiento]
                ,([pacientes].[calle]+\' \'+[pacientes].[noExterior]) AS direccion
                ,[pacientes].[colonia]
                ,[pacientes].[idCatLocalidad]
                ,[pacientes].[idCatMunicipio]
                ,[pacientes].[fechaDiagnostico] 
                ,[pacientes].[fechaNotificacion]
                ,[pacientes].[idCatUnidadReferido]
                ,[pacientes].[idCatUnidadTratante]
                ,[diagnostico].[idCatClasificacionLepra]
                ,[catLocalidad].[lat_dec]
                ,[catLocalidad].[lon_dec]
			FROM [pacientes], [catLocalidad], [diagnostico]
			WHERE [catLocalidad].[idCatEstado]=[pacientes].[idCatEstado] AND
                [catLocalidad].[idCatMunicipio]=[pacientes].[idCatMunicipio] AND
                [catLocalidad].[idCatLocalidad]=[pacientes].[idCatLocalidad] AND
                [diagnostico].[idPaciente] = [pacientes].[idPaciente] AND
                [catLocalidad].[idCatEstado] = '.$_SESSION[EDO_USR_SESSION];
        }

        if($tipoPaciente != 0)
            $query .= ' AND [pacientes].[idCatTipoPaciente] = '.$tipoPaciente.' ';
        
        if(!empty($fechaInicio) || !empty($fechaFin))
            $query .= ' AND [pacientes].[fechaDiagnostico] BETWEEN \''.formatFechaObj($fechaInicio,'Y-m-d').'\' AND \''.formatFechaObj($fechaFin,'Y-m-d').'\' ';
        
        $result = ejecutaQuery($query);

        while($registro = devuelveRowAssoc($result)){
            $descripcion = '<table>
                            <tr><td colspan=\'2\'><h3>'.htmlentities($registro['nombre']).'</h3></td></tr>
                            <tr><td>Expediente</td><td>'.$registro['cveExpediente'].'</td></tr>
                            <tr><td>Fecha Nacimiento</td><td>'.formatFechaObj($registro['fechaNacimiento']).'</td></tr>
                            <tr><td>Direcci&oacute;n</td><td>'.$registro['direccion'].'</td></tr>
                            <tr><td>Colonia</td><td>'.htmlentities($registro['colonia']).'</td></tr>
                            <tr><td>Fecha Notificaci&oacute;n</td><td>'.formatFechaObj($registro['fechaNotificacion']).'</td></tr>
                            <tr><td>Fecha Di&aacute;gnostico</td><td>'.formatFechaObj($registro['fechaDiagnostico']).'</td></tr>
                            <tr><td>Unidad</td><td>'.($registro['idCatUnidadTratante'] ? $registro['idCatUnidadTratante']: $registro['idCatUnidadReferido']).' '.htmlentities($this->help->getNameUnidad($registro['idCatUnidadTratante'] ? $registro['idCatUnidadTratante']: $registro['idCatUnidadReferido'])).'</td></tr>
                        </table>';
            $this->matriz[] = array(
                    'id'=>$registro['cveExpediente'],
                    'name'=>$registro['nombre'],
                    'description'=>$descripcion,
                    'lon'=>($registro['lon_dec']-rand(0.10000111, 0.90000999)),
                    'lat'=>($registro['lat_dec']+rand(0.10000111, 0.90000999)),
                    'sexo'=>$registro['sexo']
                );
        }
    }
}
?>
