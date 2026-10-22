<?php

$ans = "";

for ($i = 1; $i <= 10; $i++) {

    $ans .= $i . '-';

}

$ans = substr($ans, 0, -1);

echo $ans . "<br>";


$ans = 0;

for ($i = 1; $i <= 30; $i++) {

    $ans += $i;

}

echo $ans . "<br>";


$letters = array('a', 'b', 'c', 'd', 'e');

for ($i = 0; $i < 5; $i++) {

    for ($j = 0; $j < 5; $j++) {

        if ($j < 5 - $i - 1) {
            echo 'a';
        } else {
            echo $letters[$i];
        }

    }

    echo "<br>";
}


echo "<br>";


for ($i = 0; $i < 5; $i++) {

    for ($j = 0; $j < 5; $j++) {

        if ($j < 5 - $i - 1) {
            echo 1;
        } else {
            echo $i;
        }

    }

    echo "<br>";
}


echo "<br>";


for ($i = 1; $i <= 5; $i++) {

    for ($j = 1; $j <= 5; $j++) {

        if ($i == $j) {
            echo $i;
        } else {
            echo 0;
        }

    }

    echo "<br>";
}


echo "<br>";


function factorial($num) {

    $ans = 1;

    for ($i = 1; $i <= $num; $i++) {
        $ans *= $i;
    }

    return $ans;
}

echo factorial(5);

echo "<br><br>";


// Multiplication table

?>

<table border="1" cellpadding="3px" cellspacing="0px">

<?php for ($i = 1; $i <= 6; $i++) { ?>

    <tr>

        <?php for ($j = 1; $j <= 6; $j++) { ?>

            <td>
                <?php echo $i . "*" . $j . "=" . ($i * $j); ?>
            </td>

        <?php } ?>

    </tr>

<?php } ?>

</table>