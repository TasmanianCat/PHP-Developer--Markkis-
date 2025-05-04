<style>

  div {
    margin: 3rem;
  }

  h2 {
    margin: 3rem 3rem 0 3rem;
    color:brown;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  h3 {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: purple;
  }

  input {
    margin: 3rem;
    border: 1px solid green;
    padding: 1rem; width: 30rem;
    border-radius: 4px;
  }
  button {
    padding: 1rem;
    margin-left: 3rem;
  }

  p {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 1.3rem;
  }

  table {
    border: 1px solid purple;
    border-collapse: collapse;
  }

  th, td {
    border: 1px solid purple;
    padding: 8px 12px;
    text-align: center;
  }
</style>

<h2>Задание №1</h2>
<form action="#" method="POST">
  <input name="phoneNum" type="tel" placeholder="Введите номер">
  <button type="submit">Отправить</button>
</form>

<?php
/* THE TASK #1. Accept a number */
/* Do not show the error if the input is empty */
$phoneNumValue = "";

/* Show an entered phone number */
if (isset($_POST["phoneNum"])) {
  $phoneNumValue = $_POST["phoneNum"];
}

/* Create a phone number pattern */
$phoneNumPattern = "/\D/";

/* Count charachters in a string */
$phoneNumValueLength = strlen(preg_replace($phoneNumPattern, "", $phoneNumValue));

/* Show a message to a user */
$validPhoneNum = "Количество символов верное!";
$invalidPhoneNum = "Ошибка! Количество символов не верное.";

/* Modify the first symbol in a phone number */
$cleanedPhoneNum = preg_replace($phoneNumPattern, "", $phoneNumValue);
$firstPhoneNumChar = $cleanedPhoneNum[0] ?? "";
$userPhoneNum = $cleanedPhoneNum;

if (!empty($firstPhoneNumChar) && $userPhoneNum[0] === '8') {
  /* Replace the first symbol '8' with '+7' */
  $userPhoneNum = '+7' . substr($userPhoneNum, 1);
}

/* Format the phone number */
$formattedPhoneNum = '';

if (strpos($userPhoneNum, '+7') === 0 && strlen($userPhoneNum) === 12) {
  /* Remove '+7' temporarily to work with digits */
  $digitsOnly = substr($userPhoneNum, 2);

  $formattedPhoneNum = '+7 (' . substr($digitsOnly, 0, 3) . ') '
                              . substr($digitsOnly, 3, 3) . '-'
                              . substr($digitsOnly, 6, 2) . '-'
                              . substr($digitsOnly, 8, 2);
} else {
  /* If not matching, just show as is */
  $formattedPhoneNum = $userPhoneNum; 
}
?>

<div>
  <p>Введен номер телефона: <?php echo htmlspecialchars($phoneNumValue); ?></p>
  <p>Номер телефона (Только цифры): <?php echo $cleanedPhoneNum; ?></p>
  <p>Заменить '8' на '+7': <?php echo $userPhoneNum; ?></p>
  <p>Количество символов: <?php echo $phoneNumValueLength; ?></p>
  <p>Проверка: <?php
  if ($phoneNumValueLength === 11) {
    echo $validPhoneNum;
  } elseif ($phoneNumValueLength > 0) {
      echo $invalidPhoneNum;
  }
  ?></p>
  <p style="color: purple;">Номер телефона (Форматирован): <?php echo $formattedPhoneNum; ?></p>
</div>

<h2>Задание №2</h2>
<?php
/* THE TASK #2. Working with an Array */
/* Sort array data in ascending order */
$dataSource = file_get_contents('./users-data.json');

/* Decode the json dats into an array */
$array = json_decode($dataSource, true);

/* Sort the array by users age in ascending order */
usort($array, function ($a, $b) {
  return $a['age'] <=> $b['age'];
});

?>

<div>
  <p>Данные в JSON файле: <?php echo $dataSource; ?></p>
  <!-- Output the sorted array as JSON -->
  <p>Сортировка пользователей по возрасту: <?php echo json_encode($array, JSON_PRETTY_PRINT); ?></p>

  <!-- The table with the user data -->
  <h3>Таблица</h3>
  <table>
    <thead>
      <th>ID</th>
      <th>Имя</th>
      <th>Возраст</th>
    </thead>
    <tbody>
      <?php foreach ($array as $user): ?>
        <tr>
          <td><?= htmlspecialchars($user['id']) ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['age']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h2>Задание №3</h2>
<?php
/* Set a source */
$url = "https://jsonplaceholder.typicode.com/users";

/* Create a stream context with HTTP headers */
$options = [
  "http" => [
    "method" => "GET",
    "ignore_errors" => true
  ]
];

/* Fetch response from API */
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
/* echo $response; */

/* Check HTTP status code */
$statusLine = $http_response_header[0] ?? 'HTTP/1.1 500 Internal Server Error';
preg_match('{HTTP\/\S*\s(\d{3})}', $statusLine, $match);
$status = (int)$match[1];

if ($status === 200 && $response !== false) {
  $data = json_decode($response, true);
  ?>
  <div>
    <p>
      <?php foreach ($data as $user): ?>
        <span><?php echo "ID: " . htmlspecialchars($user['id']); ?></span>,
        <span><?php echo "Name: " . htmlspecialchars($user['name']); ?></span></br>
      <?php endforeach; ?>
    </p>
  </div>
  <?php
} else {
  /* Error message for non-200 responses */
  echo "
  <div>
    <p>Ошибка при получении данных. HTTP статус код: $status</p>
  </div>
  ";
}
?>

<h2>Задание №4</h2>
<form method="get" action="save_email.php">
  <input type="text" name="name" placeholder="Введите имя" required>
    <input type="email" name="email" placeholder="Введите email" required>
    <button type="submit">Сохранить Email</button>
</form>