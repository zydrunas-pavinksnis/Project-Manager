<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager</title>
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
            <input type="hidden"  name="empl">
            <a href="#" onclick="this.parentNode.submit();">Employees</a>
        </form>
    </th><th>

        <form action="index.php" method="POST">
            <input type="hidden"  name="proj">
            <a href="#" onclick="this.parentNode.submit();">Projects</a>
        </form>
    </th><th style="text-align:right"><h2>Project Manager</h2></th></tr>
    </table>

    <br><br>    


    
    

    <?php
    
    // session_start();
    // $_SESSION['empl'] = 'empl';
    // $_POST['empl'] = $_SESSION['empl'];
    // session_abort  ();

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "projects";
    // $table = ;

    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    function drawEmplTable (){
        $sql = "SELECT employees.id, employees.name, actions.projectname
                FROM employees
                LEFT JOIN actions
                ON employees.project_id=actions.id";

        $result = mysqli_query($GLOBALS["conn"], $sql);
    
        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>employee id</th><th>name</th><th>project</th><th colspan="3">actions</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["projectname"].'</td>
                <td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="delemplid" value="'.$row["id"].'">
                    <input type="submit" value="delete employee">
                    </form>
                </td><td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="wantUpdateEmplid" value="'.$row["id"].'">
                    <input type="submit" value="update employee name">
                    </form>
                </td><td>assing project to employee</td></tr>';                
            }
            echo '</table>';
        } else {
            echo "0 results";
        }
        echo '<br><table><tr><td>add project</td><td>add employee</td></tr></table>';
    }

    function drawProjTable(){
        $sql = "SELECT actions.projectname, actions.id, GROUP_CONCAT(employees.name SEPARATOR ', ')
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";

        $result = mysqli_query($GLOBALS["conn"], $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>project id</th><th>project name</th><th>responsible employee(s)</th><th colspan="2">actions</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td>
                <td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="delprojid" value="'.$row["id"].'">
                    <input type="submit" value="delete project">
                    </form>
                </td><td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="wantUpdateProjid" value="'.$row["id"].'">
                    <input type="submit" value="update project name">
                    </form>                
                </td></tr>';                
            }
            echo '</table>';
        } else {
            echo "0 results";
        }
        echo '<br><table><tr><td>add project</td><td>add employee</td></tr></table>';
    }

    
    // <td>assing project to employee</td>

    
    
    if (isset($_POST['empl'])) {
        drawEmplTable();
    }


    if (isset($_POST['proj'])) {
        drawProjTable();
    }
    

    if (isset($_POST['delemplid'])) {
        $deleteid = $_POST['delemplid'];
        $sqldelete = "DELETE FROM employees WHERE `id` = $deleteid ";
        mysqli_query($conn, $sqldelete);
        drawEmplTable();                           
    }

    if (isset($_POST['delprojid'])) {
        $deleteid = $_POST['delprojid'];
        $sqldelete = "DELETE FROM actions WHERE `id` = $deleteid ";
        mysqli_query($conn, $sqldelete);
        drawProjTable();                           
    }


    if (isset($_POST['wantUpdateEmplid'])) {
        $updateid = $_POST['wantUpdateEmplid'];
        
        $sql = "SELECT employees.id, employees.name, actions.projectname
        FROM employees
        LEFT JOIN actions
        ON employees.project_id=actions.id";

        $result = mysqli_query($GLOBALS["conn"], $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>employee id</th><th>name</th><th>project</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                if ($updateid == $row["id"]) {
                    echo '<tr><td>'.$row["id"].'</td><td>
                        <form action="index.php" method="POST">                        
                            <input type="text"  name="updateemplname" value="'.$row["name"].'">
                            <input type="hidden"  name="updateemplid" value="'.$row["id"].'">
                            <input type="submit" value="submit update">
                        </form>
                    </td><td>'.$row["projectname"].'</td>
                    </tr>';
                    } else {
                        echo '<tr><td>'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["projectname"].'</td>
                    </tr>';    
                    }
            }
            echo '</table>';
        } else {
            echo "0 results";
        }
    }

    if (isset($_POST['updateemplname'])) {
        $emplid = $_POST['updateemplid'];
        $newemplname = $_POST['updateemplname'];
        $sqlupdate = "UPDATE `employees`
                      SET `name` = '$newemplname'
                      WHERE `id` = $emplid";
        mysqli_query($conn, $sqlupdate);
        drawEmplTable();
    }





    if (isset($_POST['wantUpdateProjid'])) {
        $updateid = $_POST['wantUpdateProjid'];
        
        $sql = "SELECT actions.projectname, actions.id, GROUP_CONCAT(employees.name SEPARATOR ', ')
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";

        $result = mysqli_query($GLOBALS["conn"], $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table>';
            echo '<tr><th>project id</th><th>project name</th><th>responsible employee(s)</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                if ($updateid == $row["id"]) {
                    echo '<tr><td>'.$row["id"].'</td><td>
                        <form action="index.php" method="POST">                        
                            <input type="text"  name="updateprojname" value="'.$row["projectname"].'">
                            <input type="hidden"  name="updateprojid" value="'.$row["id"].'">
                            <input type="submit" value="submit update">
                        </form>
                    </td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td></tr>';
                } else {
                    echo '<tr><td>'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td>
                </tr>';
                }
            }
            echo '</table>';
        } else {
            echo "0 results";
        }                             
    }

    if (isset($_POST['updateprojname'])) {
        $projid = $_POST['updateprojid'];
        $newprojname = $_POST['updateprojname'];
        $sqlupdate = "UPDATE `actions`
                      SET `projectname` = '$newprojname'
                      WHERE `id` = $projid";
        mysqli_query($conn, $sqlupdate);
        drawProjTable();
    }

    mysqli_close($conn);
    ?>



    
</body>
</html>