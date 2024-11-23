<?php
session_start();

// Verificar si el usuario ha iniciado sesión y si es estudiante
if (!isset($_SESSION['id']) || $_SESSION['tipo_cuenta'] != 'student') {
    header("Location: index.php"); // Redirige a la página de inicio de sesión si no es estudiante
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pequeños Exploradores Matemáticos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos similares a los anteriores */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .background {
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }

        .menu, .options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .menu button, .options button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .menu button:hover, .options button:hover {
            transform: scale(1.1);
            background-color: #0056b3;
        }

        .scoreboard {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 15px;
            background-color: rgba(0, 123, 255, 0.1);
            border: 2px solid #007BFF;
            border-radius: 8px;
        }

        .scoreboard p {
            margin: 5px 0;
            font-size: 16px;
            color: #007BFF;
        }

        .game-container, .character-selection, .scenario {
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #007BFF;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .question {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .character-selection img {
            height: 100px;
            margin: 10px;
            cursor: pointer;
            border: 3px solid transparent;
            border-radius: 10px;
            transition: transform 0.3s, border-color 0.3s;
        }

        .character-selection img:hover {
            transform: scale(1.1);
            border-color: #007BFF;
        }

        #selected-character {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="background">
        <h2>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?> (Estudiante)</h2>

        <!-- Puntajes -->
        <div class="scoreboard">
            <p><strong>Puntos Correctos:</strong> <span id="correct-score">0</span></p>
            <p><strong>Puntos Erróneos:</strong> <span id="wrong-score">0</span></p>
        </div>

        <!-- Selección de personaje -->
        <div id="character-selection" class="character-selection">
            <h3>Elige tu personaje</h3>
            <div>
                <img src="nino.png" alt="Nino" onclick="selectCharacter('Nino')">
                <img src="monstruo.png" alt="Monstruo Matemático" onclick="selectCharacter('Monstruo Matemático')">
                <img src="blue.png" alt="Blue" onclick="selectCharacter('Blue')">
            </div>
            <p id="selected-character">No has seleccionado un personaje aún.</p>
            <button onclick="confirmCharacter()">Confirmar Personaje</button>
        </div>

        <!-- Escenario -->
        <div id="scenario" class="scenario" style="display:none;">
            <h3>Escenario: La Cueva</h3>
            <img id="scenario-image" src="cueva.jpg" alt="Cueva" style="width: 300px; height: auto; border-radius: 10px;">
        </div>


        <!-- Preguntas -->
        <div id="game" class="game-container" style="display:none;">
            <p id="question-text" class="question"></p>
            <div class="options" id="options-container"></div>
        </div>
    </div>

    <script>
        let correctScore = 0;
        let wrongScore = 0;
        let currentQuestionIndex = 0;
        let selectedCharacter = "";

        const questions = [
    {
        question: "¿Qué sigue en la secuencia? 2, 4, 6, 8",
        options: ["10", "5", "0", "1"],
        correct: 0
    },
    {
        question: "¿Cuánto es la mitad de 8?",
        options: ["4", "2", "6", "8"],
        correct: 0
    },
    {
        question: "¿Cuántas esquinas tiene un triángulo?",
        options: ["1", "3", "2", "5"],
        correct: 1
    },
    {
        question: "¿Cuál es el resultado de 5 + 4?",
        options: ["1", "5", "9", "Ninguna de las anteriores"],
        correct: 2
    },
    {
        question: "Hay cuatro galletas en el frasco. Te comes una. ¿Cuántas quedan en el frasco?",
        options: ["1", "2", "3", "6"],
        correct: 2
    },
    {
        question: "Rellena el número que falta. 7 - ? = 3",
        options: ["4", "2", "1", "5"],
        correct: 0
    },
    {
        question: "¿Qué sigue en la secuencia? 1, 1, 2, 3, 5, ...",
        options: ["6", "7", "8", "10"],
        correct: 2
    },
    {
        question: "¿Cuál es el resultado de 12 ÷ 4?",
        options: ["4", "2", "3", "6"],
        correct: 2
    },
    {
        question: "¿Cuánto es 15 - 7?",
        options: ["7", "8", "9", "10"],
        correct: 1
    },
    {
        question: "Un granjero tiene 24 gallinas y se escapan 8. ¿Cuántas le quedan?",
        options: ["16", "12", "18", "20"],
        correct: 0
    }
];


        function selectCharacter(character) {
            selectedCharacter = character;
            document.getElementById("selected-character").textContent = `Personaje seleccionado: ${character}`;
        }

        function confirmCharacter() {
            if (selectedCharacter === "") {
                alert("Por favor, selecciona un personaje antes de continuar.");
                return;
            }
            document.getElementById("character-selection").style.display = "none";
            document.getElementById("scenario").style.display = "block";
            document.getElementById("game").style.display = "block";
            loadQuestion();
        }

        function loadQuestion() {
            if (currentQuestionIndex >= questions.length) {
                alert("¡Felicidades! Has completado el juego.");
                return;
            }
            const questionData = questions[currentQuestionIndex];
            document.getElementById("question-text").textContent = questionData.question;
            const optionsContainer = document.getElementById("options-container");
            optionsContainer.innerHTML = "";
            questionData.options.forEach((option, index) => {
                const button = document.createElement("button");
                button.textContent = option;
                button.onclick = () => checkAnswer(index);
                optionsContainer.appendChild(button);
            });
        }

        function checkAnswer(selectedIndex) {
            const questionData = questions[currentQuestionIndex];
            if (selectedIndex === questionData.correct) {
                correctScore++;
                document.getElementById("correct-score").textContent = correctScore;
                alert("¡Correcto!");
            } else {
                wrongScore++;
                document.getElementById("wrong-score").textContent = wrongScore;
                alert("Incorrecto. Inténtalo de nuevo.");
            }
            currentQuestionIndex++;
            loadQuestion();
        }
    </script>
</body>
</html>
