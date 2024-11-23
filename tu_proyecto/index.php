<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema SCRUD</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Fondo de la página -->
    <div class="background">

        <!-- Procesamiento de inicio de sesión y registro en PHP -->
        <?php
        session_start();

        // Configuración de conexión a la base de datos
        $servername = "localhost";
        $username = "root"; // Cambiar si tienes otro usuario en XAMPP
        $password = ""; // Cambiar si tienes una contraseña configurada
        $dbname = "scrud_db";
        
        // Crear la conexión
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Comprobar conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Manejar registro de usuario
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
            $nombreCompleto = $_POST['fullname'];
            $usuario = $_POST['newUsername'];
            $correo = $_POST['email'];
            $contraseña = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
            $tipoCuenta = $_POST['accountType'];
            
            $sql = "INSERT INTO usuarios (nombre_completo, usuario, correo, contraseña, tipo_cuenta) VALUES ('$nombreCompleto', '$usuario', '$correo', '$contraseña', '$tipoCuenta')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p>Registro exitoso. Ahora puedes iniciar sesión.</p>";
            } else {
                echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }

        // Manejar inicio de sesión
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
            $usernameOrEmail = $_POST['username'];
            $password = $_POST['password'];
            
            $sql = "SELECT * FROM usuarios WHERE (usuario = '$usernameOrEmail' OR correo = '$usernameOrEmail')";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['contraseña'])) {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['nombre_completo'] = $user['nombre_completo'];
                    $_SESSION['tipo_cuenta'] = $user['tipo_cuenta'];

                    // Redirigir según el tipo de cuenta
                    if ($user['tipo_cuenta'] === 'admin') {
                        header("Location: admin.php");
                    } elseif ($user['tipo_cuenta'] === 'student') {
                        header("Location: estudiante.php");
                    } elseif ($user['tipo_cuenta'] === 'parent') {
                        header("Location: padre_familia.php");
                    }
                    exit();
                } else {
                    echo "<p>Contraseña incorrecta. Inténtalo de nuevo.</p>";
                }
            } else {
                echo "<p>No se encontró el usuario. Verifica tus datos.</p>";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["resetPassword"])) {
            $correo = $_POST['email'];
            echo "<p>Se ha enviado un enlace de recuperación al correo: $correo</p>";
        }

        $conn->close();
        ?>

        <!-- Iniciar Sesión -->
        <div id="login-section">
            <h2>Iniciar Sesión</h2>
            <form id="loginForm" method="POST" action="">
                <label for="username">Usuario o Correo:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit" name="login">Iniciar Sesión</button>
                <p><a href="#" onclick="showSection('reset-password')">¿Olvidaste tu contraseña?</a></p>
                <p><a href="#" onclick="showSection('register-section')">Crear Cuenta</a></p>
            </form>
        </div>

        <!-- Crear Cuenta -->
        <div id="register-section" style="display:none;">
            <h2>Crear Nueva Cuenta</h2>
            <form id="registerForm" method="POST" action="">
                <label for="fullname">Nombre Completo:</label>
                <input type="text" id="fullname" name="fullname" required>

                <label for="newUsername">Usuario:</label>
                <input type="text" id="newUsername" name="newUsername" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>

                <label for="newPassword">Contraseña:</label>
                <input type="password" id="newPassword" name="newPassword" required>

                <label for="accountType">Tipo de Cuenta:</label>
                <select id="accountType" name="accountType" required>
                    <option value="admin">Administrador</option>
                    <option value="student">Estudiante</option>
                    <option value="parent">Padre de Familia</option>
                </select>

                <button type="submit" name="register">Registrar</button>
                <button type="button" onclick="showSection('login-section')">Cancelar</button>
            </form>
        </div>

        <!-- Recuperar Contraseña -->
        <div id="reset-password" style="display:none;">
            <h2>Recuperar Contraseña</h2>
            <form id="resetForm" method="POST" action="">
                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit" name="resetPassword">Enviar enlace de recuperación</button>
                <button type="button" onclick="showSection('login-section')">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.getElementById("login-section").style.display = "none";
            document.getElementById("register-section").style.display = "none";
            document.getElementById("reset-password").style.display = "none";
            document.getElementById(sectionId).style.display = "block";
        }
    </script>
</body>
</html>
