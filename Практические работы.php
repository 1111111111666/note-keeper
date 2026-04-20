<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Практические работы по PHP</title>
</head>
<body>

<?php
echo "========== ЗАДАНИЯ 1-1, 1-2, 1-3, 1-4 ==========<br><br>";

// 1-1: типы переменных
echo "1-1 задание<br>";
$num_int = 52;
$num_float = 6.99;
$text = "прив";
$flag = true;
$arr_data = [1, 2, 3];
echo "тип \$num_int: " . gettype($num_int) . "<br>";
echo "тип \$num_float: " . gettype($num_float) . "<br>";
echo "тип \$text: " . gettype($text) . "<br>";
echo "тип \$flag: " . gettype($flag) . "<br>";
echo "тип \$arr_data: " . gettype($arr_data) . "<br><br>";

// 1-2: арифметика
echo "1-2 задание<br>";
$num1 = 22;
$num2 = 30;
$result = $num1 + $num2;
$result2 = $num1 * $num2;
echo "сумма: $num1 + $num2 = $result<br>";
echo "умножение: " . $num1 . " * " . $num2 . " = " . $result2 . "<br><br>";

// 1-3: конкатенация
echo "1-3 задание<br>";
$str1 = "дикий ";
$str2 = "огурец";
$concatenated = $str1 . $str2;
echo $concatenated . "<br><br>";

// 1-4: тернарный оператор
echo "1-4 задание<br>";
$a = 10;
$b = 20;
echo "исследование тернарного оператора с переменными a = $a и b = $b:<br>";
$result1 = ($a == $b) ? "равны" : "не равны";
echo "1. a == b: $result1<br>";
$result2 = ($a > $b) ? "a больше b" : "a не больше b";
echo "2. a > b: $result2<br>";
$result3 = ($a < $b) ? "a меньше b" : "a не меньше b";
echo "3. a < b: $result3<br>";
$result4 = ($a === $b) ? "строго равны" : "не строго равны";
echo "4. a === b: $result4<br>";
echo "<br>изменяем значения: a = 20, b = 20 <br>";
$a = 20;
$b = 20;
$result5 = ($a == $b) ? "равны" : "не равны";
echo "5. a == b: $result5<br>";
$result6 = ($a === $b) ? "строго равны" : "не строго равны";
echo "6. a === b: $result6<br>";
echo "<br>исследование с разными типами: a = 20 (число), b = '20' (строка)<br>";
$a = 20;
$b = "20";
$result7 = ($a == $b) ? "нестрогое сравнение: равны" : "нестрогое сравнение: не равны";
echo "7. $result7<br>";
$result8 = ($a === $b) ? "строгое сравнение: равны" : "строгое сравнение: не равны";
echo "8. $result8<br><br>";

echo "========== ЗАДАНИЯ 2-1, 2-2, 2-3 ==========<br><br>";

// 2-1: возраст
echo "2-1 задание<br>";
$age = 25;
echo "Вам: $age лет<br>Вы ";
if ($age >= 1 && $age <= 17) {
    echo "Слишком молод";
} elseif ($age >= 18 && $age <= 35) {
    echo "Счастливчик";
} else {
    echo "Не повезло";
}
echo "<br><br>";

// 2-2: чётные числа до 100, кратные 5
echo "2-2 задание<br>";
$mass = [];
for ($i = 1; $i <= 100; $i++) {
    if ($i % 2 == 0) {
        $mass[] = $i;
    }
}
foreach ($mass as $number) {
    if ($number % 5 == 0) {
        echo $number . " ";
    }
}
echo "<br><br>";

// 2-3: ассоциативный массив
echo "2-3 задание<br>";
$mass2 = [
    "Name" => "Няшка<br>",
    "Address" => "ул.Крутая, д.1<br>",
    "Phone" => "+7 (999) 999-99-99<br>",
    "Mail" => "ogurchik@example.com"
];
foreach ($mass2 as $element => $znach) {
    echo "$element: $znach\n";
}
echo "<br><br>";

echo "========== ЗАДАНИЯ 3-1 – 3-11 ==========<br><br>";

// 3-1: strtoupper
echo "3-1 задание: " . strtoupper('php') . "<br>";

// 3-2: ucfirst
echo "3-2 задание: " . ucfirst('london') . "<br>";

// 3-3: lcfirst / strtolower
echo "3-3 задание: " . lcfirst('London') . " (lcfirst), " . strtolower('London') . " (strtolower)<br>";

// 3-4: strlen
$str34 = 'html css php';
echo "3-4 задание: длина строки '$str34' = " . strlen($str34) . "<br>";

// 3-5: проверка пароля
$password = "mypass123";
$len = strlen($password);
echo "3-5 задание: пароль '$password' (длина: $len) - ";
if ($len > 5 && $len < 10) {
    echo "пароль подходит<br>";
} else {
    echo "нужно придумать другой пароль<br>";
}

// 3-6: проверка .png
$str36 = "mur.png";
echo "3-6 задание: строка '$str36' ";
if (substr($str36, -4) == '.png') {
    echo "заканчивается на .png → да<br>";
} else {
    echo "не заканчивается на .png → нет<br>";
}

// 3-7: замена точек на дефисы
$str37 = '31.12.2013';
echo "3-7 задание: было: '$str37', стало: '" . str_replace('.', '-', $str37) . "'<br>";

// 3-8: замена a->1, b->2, c->3
$str38 = "abcbcbcabbb";
$replacements = ['a' => '1', 'b' => '2', 'c' => '3'];
echo "3-8 задание: было: '$str38', стало: '" . strtr($str38, $replacements) . "'<br>";

// 3-9: удалить все цифры
$str39 = '1a2b3c4b5d6e7f8g9h0';
$result39 = str_replace(range(0, 9), '', $str39);
echo "3-9 задание: было: '$str39', стало: '$result39'<br>";

// 3-10: позиция первой буквы 'b'
$str310 = 'abc abc abc';
$firstPos = strpos($str310, 'b');
echo "3-10 задание: в строке '$str310' первая буква 'b' на позиции: $firstPos<br>";

// 3-11: позиция последней буквы 'b'
$str311 = 'abc abc abc';
$lastPos = strrpos($str311, 'b');
echo "3-11 задание: в строке '$str311' последняя буква 'b' на позиции: $lastPos<br>";
?>

</body>
</html>