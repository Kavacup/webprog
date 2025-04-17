<?php
$score = isset($_GET['score']) ? (float)$_GET['score'] : 0;
$maxScore = 3; 

$width = 250; // Ширина изображения
$height = 40; // Высота изображения
$padding = 5; // Отступы внутри

// --- Создание изображения ---
$image = imagecreatetruecolor($width, $height);

// --- Определение цветов ---
$bgColor = imagecolorallocate($image, 240, 240, 240); // Светло-серый фон
$barBgColor = imagecolorallocate($image, 200, 200, 200); // Цвет фона под полоской
$barColor = imagecolorallocate($image, 34, 139, 34);   // Зеленый цвет полоски (ForestGreen)
$borderColor = imagecolorallocate($image, 100, 100, 100); // Цвет рамки
$textColor = imagecolorallocate($image, 0, 0, 0);       // Черный цвет текста


// Заливаем фон
imagefill($image, 0, 0, $bgColor);

// Рисуем фон для полоски прогресса
$barAreaWidth = $width - (2 * $padding);
$barAreaHeight = $height - (2 * $padding);
imagefilledrectangle(
    $image,
    $padding,                            // x1
    $padding,                            // y1
    $padding + $barAreaWidth,            // x2
    $padding + $barAreaHeight,           // y2
    $barBgColor
);

// Рассчитываем и рисуем саму полоску прогресса
if ($score > 0) {
    $scoreBarWidth = (int)(($score / $maxScore) * $barAreaWidth);
    if ($scoreBarWidth > 0) {
        imagefilledrectangle(
            $image,
            $padding,                         // x1
            $padding,                         // y1
            $padding + $scoreBarWidth,        // x2
            $padding + $barAreaHeight,        // y2
            $barColor
        );
    }
}

// Рисуем рамку вокруг области полоски
imagerectangle(
    $image,
    $padding,                            // x1
    $padding,                            // y1
    $padding + $barAreaWidth -1,         // x2 ( -1 чтобы влезло )
    $padding + $barAreaHeight -1,        // y2 ( -1 чтобы влезло )
    $borderColor
);

// Добавляем текст с результатом
$text = sprintf("Results: %.1f / %.0f", $score, $maxScore); // Форматируем текст
$font = 4; // Встроенный шрифт GD (1-5)
$textWidth = imagefontwidth($font) * strlen(utf8_decode($text)); // Ширина текста 
$textHeight = imagefontheight($font);
$textX = (int)(($width - $textWidth) / 2);   // Центрируем по горизонтали
$textY = (int)(($height - $textHeight) / 2); // Центрируем по вертикали
imagestring($image, $font, $textX, $textY, utf8_decode($text), $textColor); // Рисуем текст

// --- Вывод изображения ---
header("Content-type: image/png"); // Сообщаем браузеру, что это PNG-изображение
imagepng($image); // Выводим готовое изображение

// --- Освобождение памяти ---
imagedestroy($image);

?>