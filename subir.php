<?php

if (isset($_FILES["archivo"])) {
    $nombreArchivo = $_FILES["archivo"]["name"];
    $tipoArchivo = $_FILES["archivo"]["type"];
    $tamanoArchivo = $_FILES["archivo"]["size"];
    $temporalArchivo = $_FILES["archivo"]["tmp_name"];

    // Validar el archivo
    if ($tipoArchivo != "image/png" && $tipoArchivo != "image/jpg" && $tipoArchivo != "image/jpeg") {
        echo "El archivo debe ser una imagen PNG, JPG o JPEG.";
        exit;
    }

    if ($tamanoArchivo > 1000000) {
        echo "El archivo no puede superar los 1MB.";
        exit;
    }

    // Mover el archivo a la carpeta destino
    $carpetaDestino = "./uploads/";
    move_uploaded_file($temporalArchivo, $carpetaDestino . $nombreArchivo);

    echo "El archivo se ha subido correctamente.";
} else {
    echo "No se ha seleccionado ningún archivo.";
}

?>
