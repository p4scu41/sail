<!-- <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script> -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<script type="text/javascript">
    $(document).ready(function() {
        setupCalendario('fecha_inicio');
        setupCalendario('fecha_fin');
        
        $('#tipo_paciente option:first').text('Todos');
    });
    
    function localizarMapa(){
        $('#formMapa').attr('action','?mod=map');
        $('#formMapa').submit();
    }
    
    function exportarKML(){
        $('#formMapa').attr('action','content/consultaKML.php');
        $('#formMapa').submit();
    }
</script>


<?php
$objHTML = new HTML();
$objSelects = new Select();

echo '<h2 align="center">GEOPOSICIONAMIENTO DE CASOS</h2>';

$objHTML->startForm('formMapa', '?mod=map', 'POST');

    $objHTML->startFieldset();

    $objSelects->SelectCatalogo('Tipo de Paciente', 'tipo_paciente', 'catTipoPaciente', $_POST['tipo_paciente']);
    $objHTML->inputText('', 'fecha_inicio', $_POST['fecha_inicio'], array('placeholder'=>'Inicio'));
    $objHTML->inputText('', 'fecha_fin', $_POST['fecha_fin'], array('placeholder'=>'Fin'));
    echo ' &nbsp; &nbsp; &nbsp; ';
    $objHTML->inputButton('localizar_mapa', 'Localizar en el mapa', array('onClick'=>'localizarMapa()'));
    echo ' &nbsp; &nbsp; &nbsp; ';
    $objHTML->inputButton('exportar_kml', 'Exportar a KML', array('onClick'=>'exportarKML()'));
    
    $objHTML->endFieldset();

$objHTML->endFormOnly();
?>

<div id="googleMap" style="width:750px;height:600px; margin:auto;"></div>

<script type="text/javascript">
    <?php // Si es de Chiapas
    if($_SESSION[EDO_USR_SESSION] == 7) {?>
        latitud  = 16.646718050971934;
        longitud = -92.6806640625;
        zoom = 8;
    <?php } // Si es otro estado
    else { ?>
        latitud  = 23.200961;
        longitud = -101.953125;
        zoom = 5;
    <?php }?>
    
    var myCenter = new google.maps.LatLng(latitud, longitud);

    function initialize()
    {
        var mapProp = {
          center: myCenter,
          zoom: zoom,
          mapTypeId: google.maps.MapTypeId.ROADMAP
          };

        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        
        /*var archivoKML = new google.maps.KmlLayer({
            //url: 'http://gmaps-samples.googlecode.com/svn/trunk/ggeoxml/cta.kml'
            url: 'http://cie.servehttp.com/lepra.chiapas/include/chiapas.kml'
            //url: 'http://cie.servehttp.com/lepra.chiapas/include/cta.kml'
        });
        
        archivoKML.setMap(map);*/
        /*var kmlLayer = new google.maps.KmlLayer({
                    url: 'http://cie.servehttp.com/lepra.chiapas/include/jurs.KML',
                    suppressInfoWindows: true,
                    map: map
                });*/
        
        <?PHP 
        if(!empty($_POST))
        {
            
            require_once('include/clases/KML.php');
            
            $archivoKML = new KML();
    
            $archivoKML->queryKML($_POST['tipo_paciente'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
            $matriz = $archivoKML->getMatriz();
            
            if($matriz == null){
                echo 'alert("No se econtraron casos");';
            } else {
				//print_r($matriz);
                foreach($matriz as $elemento)
                {
                    echo '
                    var pac_'.$elemento['id'].' = new google.maps.Marker({
                        position: new google.maps.LatLng('.$elemento['lat'].', '.$elemento['lon'].'),
                        map: map,
                        title: "'.$elemento['name'].'",
                        icon: "';
                    if($elemento['sexo'] == 1)
                        echo 'http://maps.google.com/mapfiles/kml/shapes/man.png';
                    else
                        echo 'http://maps.google.com/mapfiles/kml/shapes/woman.png';
                            echo '"
                    });
                    
                    var infWin_'.$elemento['id'].' = new google.maps.InfoWindow({
                            content : "'.str_replace(array("\r\n", "\n", "\r"), ' ', $elemento['description']).'"
                        });

                    google.maps.event.addListener(pac_'.$elemento['id'].', "click", function () {
                        infWin_'.$elemento['id'].'.open(map, pac_'.$elemento['id'].');
                    });
                    ';
                }
            }
        }
        ?>
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    
</script>