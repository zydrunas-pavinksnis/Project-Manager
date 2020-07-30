<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darbuotojai projektai</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "projects";

    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id, name, surname FROM employees";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo  $row["id"]. " " . $row["name"]. " " . $row["surname"]. "<br>";
        }
    } else {
        echo "0 results";
    }

    mysqli_close($conn);
    ?>



    
</body>
</html>