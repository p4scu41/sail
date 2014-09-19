<?php 
switch ($_SESSION[TIPO_USR_SESSION]) {
	case 1: // Administrador
	?>
	<ul>
	    <li <?PHP if($_GET['mod']=='ini') echo 'class="active"';?> ><a href="?mod=ini">Inicio</a></li>
		<li <?PHP if($_GET['mod']=='bus') echo 'class="active"';?> ><a href="?mod=bus">Buscar</a></li>
		<li <?PHP if($_GET['mod']=='cap') echo 'class="active"';?> ><a href="?mod=cap">Capturar</a></li>
		<li <?PHP if($_GET['mod']=='rep') echo 'class="active"';?> ><a href="?mod=rep">Reportes</a></li>
        <li <?PHP if($_GET['mod']=='ind') echo 'class="active"';?> ><a href="?mod=ind">Indicadores</a></li>
        <li <?PHP if($_GET['mod']=='val') echo 'class="active"';?> ><a href="?mod=val">Validaci&oacute;n</a></li>
        <li <?PHP if($_GET['mod']=='ane') echo 'class="active"';?> ><a href="?mod=ane">Anexos</a></li>
        <li <?PHP if($_GET['mod']=='her') echo 'class="active"';?> ><a href="?mod=her">Herramientas</a></li>
		<li <?PHP if($_GET['mod']=='help') echo 'class="active"';?> ><a href="?mod=help">Ayuda</a></li>
	</ul>
	<?php
	break;
	
	case 2: // Medico
	?>
	<ul>
	    <li <?PHP if($_GET['mod']=='ini') echo 'class="active"';?> ><a href="?mod=ini">Inicio</a></li>
		<li <?PHP if($_GET['mod']=='bus') echo 'class="active"';?> ><a href="?mod=bus">Buscar</a></li>
		<li <?PHP if($_GET['mod']=='cap') echo 'class="active"';?> ><a href="?mod=cap">Capturar</a></li>
        <li <?PHP if($_GET['mod']=='ane') echo 'class="active"';?> ><a href="?mod=ane">Anexos</a></li>
        <li <?PHP if($_GET['mod']=='her') echo 'class="active"';?> ><a href="?mod=her">Herramientas</a></li>
		<li <?PHP if($_GET['mod']=='help') echo 'class="active"';?> ><a href="?mod=help">Ayuda</a></li>
	</ul>
	<?php
	break;
	
	case 3: // Laboratorista
	?>
	<ul>
	    <li <?PHP if($_GET['mod']=='labIni') echo 'class="active"';?> ><a href="?mod=labIni">Inicio</a></li>
		<li <?PHP if($_GET['mod']=='labBus') echo 'class="active"';?> ><a href="?mod=labBus">Buscar</a></li>
		<li <?PHP if($_GET['mod']=='help') echo 'class="active"';?> ><a href="?mod=help">Ayuda</a></li>
	</ul>
	<?php
	break;
    
    case 4: // Recepcion Muestra
	?>
	<ul>
	    <li <?PHP if($_GET['mod']=='recepIni') echo 'class="active"';?> ><a href="?mod=recepIni">Inicio</a></li>
        <li <?PHP if($_GET['mod']=='recepBus') echo 'class="active"';?> ><a href="?mod=recepBus">Buscar</a></li>
		<li <?PHP if($_GET['mod']=='help') echo 'class="active"';?> ><a href="?mod=help">Ayuda</a></li>
	</ul>
	<?php
	break;
	
	default:
		echo 'ERROR: Tipo de Usuario no definido';
	break;
}
?>