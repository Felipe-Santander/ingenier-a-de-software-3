<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es padre de familia
if (!isset($_SESSION['id']) || $_SESSION['tipo_cuenta'] != 'parent') {
    header("Location: index.php"); // Redirige a la página de inicio de sesión si no es padre de familia
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Padre de Familia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background">
        <h2>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?> (Padre de Familia)</h2>

        <!-- Menú de opciones SCRUD -->
        <div class="menu">
            <button onclick="showSection('consultar')">Consultar</button>
            <button onclick="cancelar()">Cancelar</button>
            <button onclick="location.href='logout.php'">Salir</button>
        </div>

        <!-- Secciones para cada funcionalidad SCRUD -->
        <div id="consultar" class="section" style="display:none;">
            <h3>Consultar Información</h3>
            <p>Aquí podrás consultar información relacionada con tu cuenta o tu hijo/a.</p>
            <!-- Agregar aquí la funcionalidad para mostrar los registros relevantes para el padre de familia -->
        </div>
    </div>

    <script>
        // Mostrar la sección correspondiente según el botón SCRUD que se seleccione
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(sectionId).style.display = 'block';
        }

        // Función para cancelar y ocultar todas las secciones SCRUD
        function cancelar() {
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.style.display = 'none');
        }
    </script>
</body>
</html>
