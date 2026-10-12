<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form action="" method="GET">
        <input
            type="search"
            name="search"
            maxlength="50"
            id="search"
        >

        <button type="submit">Search</button>
    </form>

    <?php
        $search = $_GET["search"] ?? "";

        if ($search !== "") {
            echo "<p>Your search result is: "
                . htmlspecialchars($search)
                . "</p>";
        }
    ?>

</body>
</html>
