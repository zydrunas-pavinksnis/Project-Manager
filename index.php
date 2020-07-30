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
       echo '<table border="1">';
       echo '<tr><th>id</th><th>name</th><th>surname</th></tr>';
       while($row = mysqli_fetch_assoc($result)) {

            echo '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["surname"].'</td</tr>';
            
            
        }

        echo '</table>';
    } else {
        echo "0 results";
    }

    mysqli_close($conn);
    ?>



    
</body>
</html>