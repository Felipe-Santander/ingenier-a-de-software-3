<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirige al inicio de sesión
header("Location: index.php");
exit();
?>