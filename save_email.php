<?php
function saveEmailToDatabase($name, $email) {
    $host = 'localhost';
    /* Username in Laragon */
    $user = 'root';
    /* The password in Laragon is blank */
    $pass = '';
    /* The database name in phpMyAdmin */
    $db = 'test_db';

    /* Connect to MySQL */
    $conn = new mysqli($host, $user, $pass, $db);

    /* Check the connection */
    if ($conn->connect_error) {
        die("Соединение не удалось: " . $conn->connect_error);
    }

    /* Trim and validate */
    $name = trim($name);
    $email = trim($email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Формат электронной почты Invalid.");
    }

    /* Enable exception mode for better error handling */
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        /* Prepare and bind */
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);

        /* Execute and show success */
        $stmt->execute();
        echo "Email адрес успешно сохранен.";

        $stmt->close();
        $conn->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
          echo "Email уже существует. Попробуйте другой.";
        } else {
          echo "Ошибка: " . $e->getMessage();
        }
    }
}

/* Check if the URL includes a query parameter. Trigger only if both name and email are provided */
// 
if (isset($_GET['name']) && isset($_GET['email'])) {
    saveEmailToDatabase($_GET['name'], $_GET['email']);
} else {
    echo "Пожалуйста, укажите имя и email адрес.";
}
?>