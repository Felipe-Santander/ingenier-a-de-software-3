<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['id']) || $_SESSION['tipo_cuenta'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background">
        <h2>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?> (Administrador)</h2>

        <!-- Menú de opciones SCRUD -->
        <div class="menu">
            <button onclick="showSection('crear')">Crear</button>
            <button onclick="showSection('consultar')">Consultar</button>
            <button onclick="showSection('guardar')">Guardar</button>
            <button onclick="showSection('modificar')">Modificar</button>
            <button onclick="showSection('eliminar')">Eliminar</button>
            <button onclick="cancelar()">Cancelar</button>
            <button onclick="location.href='logout.php'">Salir</button>
        </div>

        <!-- Secciones para cada funcionalidad SCRUD -->
        <div id="crear" class="section" style="display:none;">
            <h3>Crear Registro</h3>
            <form>
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="tipoCuenta">Tipo de Cuenta:</label>
                <select id="tipoCuenta" name="tipoCuenta" required>
                    <option value="admin">Administrador</option>
                    <option value="student">Estudiante</option>
                    <option value="parent">Padre de Familia</option>
                </select>

                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <button type="submit">Guardar</button>
            </form>
        </div>

        <div id="consultar" class="section" style="display:none;">
            <h3>Consultar Registros</h3>
            <!-- Aquí se mostrará la lista de registros en la base de datos -->
            <p>Consulta de registros en proceso...</p>
        </div>

        <div id="guardar" class="section" style="display:none;">
            <h3>Guardar Cambios</h3>
            <!-- Aquí iría la funcionalidad para guardar los cambios -->
            <p>Funcionalidad para guardar cambios en proceso...</p>
        </div>

        <div id="modificar" class="section" style="display:none;">
            <h3>Modificar Registro</h3>
            <!-- Aquí iría el formulario para modificar un registro existente -->
            <p>Funcionalidad para modificar registros en proceso...</p>
        </div>

        <div id="eliminar" class="section" style="display:none;">
            <h3>Eliminar Registro</h3>
            <!-- Aquí se implementará la funcionalidad de eliminación -->
            <p>Funcionalidad para eliminar registros en proceso...</p>
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
