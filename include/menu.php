<?php 
switch ($_SESSION[TIPO_USR_SESSION]) {
	case 1: // Administrador
	?>
	<ul>
	    <li <?PHP if($_GET['mod']=='ini') echo 'class="active"';?> ><a href="?mod=ini">Inicio</a></li>
        <?php if($_SESSION[EDO_USR_SESSION] != 0) { ?>
		<li <?PHP if($_GET['mod']=='cap') echo 'class="active"';?> ><a href="?mod=cap">Capturar</a></li>
        <?php } ?>
		<li <?PHP if($_GET['mod']=='bus') echo 'class="active"';?> ><a href="?mod=bus">Buscar</a></li>
		<li <?PHP if($_GET['mod']=='rep') echo 'class="active"';?> ><a href="?mod=rep">Reportes</a></li>
        <li <?PHP if($_GET['mod']=='indIndx') echo 'class="active"';?> ><a href="?mod=indIndx">Indicadores</a></li>
        <li <?PHP if($_GET['mod']=='val') echo 'class="active"';?> ><a href="?mod=val">Validaci&oacute;n</a></li>
        <li <?PHP if($_GET['mod']=='usrs') echo 'class="active"';?> ><a href="?mod=usrs">Usuarios</a></li>
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
        <?php if($_SESSION[EDO_USR_SESSION] != 0) { ?>
		<li <?PHP if($_GET['mod']=='cap') echo 'class="active"';?> ><a href="?mod=cap">Capturar</a></li>
        <?php } ?>
		<li <?PHP if($_GET['mod']=='bus') echo 'class="active"';?> ><a href="?mod=bus">Buscar</a></li>
        <li <?PHP if($_GET['mod']=='ane') echo 'class="active"';?> ><a href="?mod=ane">Anexos</a></li>
        <?PHP if($_SESSION[EDO_USR_SESSION]==7){ ?>
        <li <?PHP if($_GET['mod']=='her') echo 'class="active"';?> ><a href="?mod=her">Herramientas</a></li>
        <?PHP } ?>
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
    case 5: // Control de calidad
	?>
	<ul>
		<li <?PHP if($_GET['mod']=='calIni') echo 'class="active"';?> ><a href="?mod=calIni">Inicio</a></li>
		<li <?PHP if($_GET['mod']=='help') echo 'class="active"';?> ><a href="?mod=help">Ayuda</a></li>
	</ul>
	<?php
	break;
	
	default:
		echo 'ERROR: Tipo de Usuario no definido';
	break;
}
?>