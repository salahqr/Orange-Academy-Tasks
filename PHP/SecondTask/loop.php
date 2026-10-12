<?php
    $arr = array(0,1);

    for ($i=1; $i < 20; $i++) { 
        array_push($arr, ($arr[$i]+$arr[$i-1]));
    }

    $num = 1;
    for ($i = 1; $i <= 5; $i++) {

        for ($j = 1; $j <= $i; $j++) {
            echo $num . " ";
            $num++;
        }

        echo "<br>";
    }


for ($i = 1; $i <= 5; $i++) {

    for ($j = 1; $j <= $i; $j++) {
        echo chr(64 + $j) . " ";
    }

    echo "<br>";
}

for ($i = 4; $i >= 1; $i--) {

    for ($j = 1; $j <= $i; $j++) {
        echo chr(64 + $j) . " ";
    }

    echo "<br>";
}
?>

<!-- 
2. Write a PHP program to generate and display the first n lines of a Floyd triangle
According to Wikipedia Floyd's triangle is a right-angled triangular array of natural numbers, used in computer science
education. It is named after Robert Floyd. It is defined by filling the rows of the triangle with consecutive numbers, starting with
a 1 in the top left corner:
Sample output:
1
2 3
4 5 6
7 8 9 10
11 12 13 14 15

3. .Write a PHP program to print the following pattern. Expected Output: -->