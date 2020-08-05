<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees & Projects</title>
    <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

    <table><tr><th>
        <form action="index.php" method="POST">
            <input type="hidden"  name="empl" value="employees">
            <a href="#" onclick="this.parentNode.submit();">Employees</a>
        </form>
    </th><th>

        <form action="index.php" method="POST">
            <input type="hidden"  name="proj" value="actions">
            <a href="#" onclick="this.parentNode.submit();">Projects</a>
        </form>
    </th></tr>
    </table>


    <br><br>



     

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "projects";

    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    $sql = "SELECT actions.projectname, actions.id, group_concat(employees.name)
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";

    $result = mysqli_query($conn, $sql);

    var_dump($result);

    if (mysqli_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>project id</th><th>projectname</th><th>employees.project_id</th><th></th></tr>';
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr><td>'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["group_concat(employees.name)"].'</td><td></td></tr>';                
        }
        echo '</table>';
    } else {
        echo "0 results";
    }



    if (isset($_POST['empl'])) {
        $tableName = $_POST['empl'];
                    
        $sql = "SELECT id, name, surname FROM $tableName ";
        

        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>employee id</th><th>name</th><th>surname</th><th>project</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["surname"].'</td><td></td></tr>';                
            }
            echo '</table>';
        } else {
            echo "0 results";
        }    
    }

    if (isset($_POST['proj'])) {
        $tableName = $_POST['proj'];
                    
        $sql = "SELECT id, projectname FROM $tableName ";
        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>project id</th><th>project name</th><th>responsible empoyee(s)</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>'.$row["id"].'</td><td>'.$row["projectname"].'</td><td></td></tr>';                
            }
            echo '</table>';
        } else {
            echo "0 results";
        }    
    }

    

    mysqli_close($conn);
    ?>



    
</body>
</html>