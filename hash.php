<?php
// La contraseña que querés usar
$password = "juan1234";

// Generar el hash
$hash = password_hash($password, PASSWORD_BCRYPT);

// Mostrarlo para copiarlo en la base de datos
echo $hash;

?>