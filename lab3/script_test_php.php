<?php
date_default_timezone_set('Asia/Novosibirsk');

$method=$_SERVER['REQUEST_METHOD'];

// Получение данных
if ($method === 'POST') {
    $q1 = $_POST['choice'] ?? ' ';
    $q2 = $_POST['checkOne'] ?? [];
    $q3 = $_POST['checkTwo'] ?? [];
}
else if ($method === 'GET') {
    $q1 = $_GET['choice'] ?? ' ';
    $q2 = $_GET['checkOne'] ?? [];
    $q3 = $_GET['checkTwo'] ?? [];
}
// Проверка правильных ответов
$score = 0;
$results = [];

// Вопрос 1
if ($q1 === '1') {
    $score++;
    $results[] = "Вопрос 1: Верно";
} else {
    $results[] = "Вопрос 1: Неверно";
}

// Вопрос 2
$q2Sum = array_sum($q2);
if ($q2Sum === 6) {
    $score++;
    $results[] = "Вопрос 2: Верно";
} else if ($q2Sum === 1 || $q2Sum === 5 || $q2Sum === 9){
	$score += 0.5;
    $results[] = "Вопрос 2: Почти верно";
} else $results[] = "Вопрос 2: Неверно";

// Вопрос 3
$q3Sum = array_sum($q3);
if ($q3Sum === 4) {
    $score++;
    $results[] = "Вопрос 3: Верно";
} else if ($q3Sum === 1 || $q3Sum === 3 || $q3Sum === 9){
	$score += 0.5;
    $results[] = "Вопрос 3: Почти верно";
} else $results[] = "Вопрос 3: Неверно";

// Дата
$date = date("d-m-Y H:i:s");

// Сохраняем результат в файл
$log = "Дата: $date\n" . implode("\n", $results) . "\nБаллов: $score из 3\n\n";
$log = mb_convert_encoding($log, 'UTF-8', 'auto');
file_put_contents("results.txt", $log, FILE_APPEND);

echo "<!DOCTYPE html>";
echo "<html lang='ru'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Результаты теста</title>";
echo "<style>
        body { font-family: Arial, sans-serif; }
        h2 { color: #333; }
        .result { margin-bottom: 10px; }
        .bar { width: 300px; height: 25px; background: #eee; border: 1px solid #ccc; }
        .bar-inner { height: 100%; background: green; }
      </style>";
echo "</head>";
echo "<body>";

echo "<h2>Результаты теста</h2>";
echo "<p><strong>Дата:</strong> $date</p>";
foreach ($results as $line) {
    echo "<p class='result'>$line</p>";
}
echo "<p><strong>Итого баллов:</strong> $score из 3</p>";

// Графическое представление	  
echo "<h3> График </h3>";
echo "<img src='generate_score_image.php?score=" . urlencode($score) . "' alt='График результата $score из 3'>";

echo "<p><a href='results.txt'>Показать результаты</a></p>";

echo "</body>";
echo "</html>";
?>