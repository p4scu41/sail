<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Envio de Mensajes Usando SDK Mercacel</title>
</head>

<body>
<form action="command.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="480" border="0">
    <tr>
      <td width="62">Titulo:</td>
      <td width="408"><input name="Titulo" type="text" id="Titulo" value="Demo" /></td>
    </tr>
    <tr>
      <td>Para:</td>
      <td><input type="text" name="Para" id="Para" /> 
        (ej. 5559611745,5537845126)</td>
    </tr>
    <tr>
      <td>Mensaje:</td>
      <td><textarea name="Msg" cols="20" rows="7" id="Msg"></textarea></td>
    </tr>
    <tr>
      <td>Fecha:</td>
      <td><input name="Fecha" type="text" id="Fecha" value="2011-08-22" size="9" />
        (2010-01-01)</td>
    </tr>
    <tr>
      <td>Hora:</td>
      <td><input name="Hora" type="text" id="Hora" value="09:00" size="9" />
        (15:00)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnenviar" id="btnenviar" value="Enviar" /></td>
    </tr>
    <tr>
      <td colspan="2">Todos los campos son obligatorios</td>
    </tr>
  </table>
</form>
</body>
</html>