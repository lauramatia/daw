<?php
    //Codificación UTF-8 para  el fichero.
    header('Content-Type: text/html; charset=UTF-8');
    
    // Obtención de datos del formulario
    
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $mens = $_POST['mens'];
     
    // Creación de los campos del correo

    $para = 'lauramatia99@gmail.com';
    $asunto = 'Petición de 05-contacto';
    $mensaje = "Nombre: " . $nombre . " <br/> ";
    $mensaje .= "Número de teléfono: " .$telefono. "<br/> ";
    $mensaje .= "Correo: " .$correo. "<br/> ";
    $mensaje .= "Mensaje: " .$mens. "<br/> ";
  
    //Así podríamos construir una estructura completa de página HTML si
    //queremos que llegue con más forma
    
    $cabeceras = "MIME-Version: 1.0". "\r\n";
    $cabeceras .= "Content-type: text/html; charset=UTF-8"."\r\n";
    $cabeceras .= "From:emailtest@diw-lauramatia.000webhostapp.com";

    if (mail($para, $asunto, $mensaje, $cabeceras )){
        echo '<span style="font-size:1.3em;color:white;background-color:green;padding:5px;">Se acaba de enviar el 05-contacto, revisa tu bandeja de entrada o la de SPAM antes de ponerte nervioso...</span>';
		header('Location: ok-mail.html');
    } else {
        echo '<span style="font-size:1.3em;color:white;background-color:red;padding:5px;">Error al enviar mensaje</span>';
        header('Location: error-mail.html');
    }
?>    
    


