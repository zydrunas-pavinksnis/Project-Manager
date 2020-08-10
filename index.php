<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager</title>
    <style>
        body {background-color: #F2FFED;}

        table {
        font-family: verdana, arial, sans-serif;
        color: #551A8B;
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        text-align: left;
        padding: 8px;
        }

        tr:nth-child(odd) {
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
    <br>      
     

    <?php

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "projects";
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    function drawEmplTable (){
        $sql = "SELECT employees.id, employees.name, employees.project_id, actions.projectname
                FROM employees
                LEFT JOIN actions
                ON employees.project_id=actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql);
               
        echo   '<table><tr><td>
                    <form action="index.php" method="POST">                        
                    <input type="text"  name="addempl" placeholder="name surname">
                    <input type="submit" value="add new employee">
                    </form>
                </td></tr></table>';
    
        if (mysqli_num_rows($result) > 0) {
            echo '<table><tr><th style="text-align:center">employee id</th><th>name</th><th>project</th><th colspan="3">actions</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["projectname"].'</td>
                <td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="delemplid" value="'.$row["id"].'">
                    <input type="submit" value="remove employee">
                    </form>
                </td><td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="wantUpdateEmplid" value="'.$row["id"].'">
                    <input type="submit" value="update employee data">
                    </form>
                </td><td>';
                    if ($row["project_id"] == NULL) {
                        echo '<form action="index.php" method="POST">
                        <input type="hidden"  name="wantAssignProj" value="'.$row["id"].'">
                        <input type="submit" value="assing employee to project">
                        </form>';
                    } else {
                        echo 'is assigned to project';
                    }
                echo '</td></tr>';                
            }
            echo '</table>';
        } else {
            echo '<table><tr></tr><tr><td>Employees list empty.  Add new employee.</td></tr></table>';
        }
    }


    function drawProjTable(){
        $sql = "SELECT actions.projectname, actions.id, GROUP_CONCAT(employees.name SEPARATOR ', ')
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql);

        echo   '<table><tr><td>
                    <form action="index.php" method="POST">                        
                    <input type="text"  name="addproj" placeholder="project name">
                    <input type="submit" value="add new project">
                    </form>                    
                </td></tr></table>';

        if (mysqli_num_rows($result) > 0) {
            echo '<table><tr><th style="text-align:center">project id</th><th>project name</th><th>responsible employee(s)</th><th colspan="3">actions</th></tr>';
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td>
                <td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="delprojid" value="'.$row["id"].'">
                    <input type="submit" value="remove project">
                    </form>
                </td><td>
                    <form action="index.php" method="POST">
                    <input type="hidden"  name="wantUpdateProjid" value="'.$row["id"].'">
                    <input type="submit" value="update project data">
                    </form>                
                </td><td>';                
                    if ($row["GROUP_CONCAT(employees.name SEPARATOR ', ')"] !== NULL) {
                        echo '  <form action="index.php" method="POST">
                                    <input type="hidden"  name="wantDissEmpl" value="'.$row["id"].'">
                                    <input type="submit" value="dismiss employee from project">
                                </form>';
                    } else {
                        echo 'no employees assigned';
                    }
                echo '</td></tr>';                
            }
            echo '</table>';
        } else {
            echo '<table><tr></tr><tr><td>Projects list empty.  Add new project.</td></tr></table>';
        }
    }
    ob_start();
    drawEmplTable();

    if (isset($_POST['empl'])) {
        ob_get_clean();
        drawEmplTable();
    }
    
    if (isset($_POST['proj'])) {
        ob_get_clean();
        drawProjTable();    
    }

    if (isset($_POST['delemplid'])) {
        $deleteid = $_POST['delemplid'];
        $sqldelete = "  DELETE FROM employees
                        WHERE `id` = $deleteid ";
        mysqli_query($conn, $sqldelete);
        ob_get_clean();
        drawEmplTable();                           
    }

    if (isset($_POST['delprojid'])) {
        $deleteid = $_POST['delprojid'];
        $sqldelete = "  DELETE FROM actions
                        WHERE `id` = $deleteid ";
        mysqli_query($conn, $sqldelete);
        ob_get_clean();
        drawProjTable();                           
    }

    if (isset($_POST['addempl'])) {
        $newempl = $_POST['addempl'];
        $sqlupdate = "  INSERT INTO employees 
                        VALUES (NULL, '$newempl', NULL);";
        mysqli_query($conn, $sqlupdate);
        ob_get_clean();
        drawEmplTable();
    }

    if (isset($_POST['addproj'])) {
        $newproj = $_POST['addproj'];
        $sqlupdate = "  INSERT INTO actions
                        VALUES (NULL, '$newproj');";
        mysqli_query($conn, $sqlupdate);
        ob_get_clean();
        drawProjTable();
    }


    if (isset($_POST['wantUpdateEmplid'])) {
        ob_get_clean();
        $updateid = $_POST['wantUpdateEmplid'];
        $sql = "SELECT employees.id, employees.name, actions.projectname
                FROM employees
                LEFT JOIN actions
                ON employees.project_id=actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql);

        echo   '<table><tr><td></td></tr></table>
                <table><tr><th style="text-align:center">employee id</th><th>name</th><th>project</th></tr>';
        while($row = mysqli_fetch_assoc($result)) {
            if ($updateid == $row["id"]) {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>
                    <form action="index.php" method="POST">                        
                        <input type="text"  name="updateemplname" value="'.$row["name"].'">
                        <input type="hidden"  name="updateemplid" value="'.$row["id"].'">
                        <input type="submit" value="submit update">
                    </form>
                </td><td>'.$row["projectname"].'</td></tr>';
            } else {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["projectname"].'</td></tr>';    
            }
        }
        echo '</table>';         
    }

            if (isset($_POST['updateemplname'])) {
                $emplid = $_POST['updateemplid'];
                $newemplname = $_POST['updateemplname'];
                $sqlupdate = "  UPDATE `employees`
                                SET `name` = '$newemplname'
                                WHERE `id` = $emplid";
                mysqli_query($conn, $sqlupdate);
                ob_get_clean();
                drawEmplTable();
            }


    if (isset($_POST['wantUpdateProjid'])) {
        ob_get_clean();
        $updateid = $_POST['wantUpdateProjid'];
        $sql = "SELECT actions.projectname, actions.id, GROUP_CONCAT(employees.name SEPARATOR ', ')
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql);

        echo   '<table><tr><td></td></tr></table>
                <table><tr><th style="text-align:center">project id</th><th>project name</th><th>responsible employee(s)</th></tr>';
        while($row = mysqli_fetch_assoc($result)) {
            if ($updateid == $row["id"]) {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>
                    <form action="index.php" method="POST">                        
                        <input type="text"  name="updateprojname" value="'.$row["projectname"].'">
                        <input type="hidden"  name="updateprojid" value="'.$row["id"].'">
                        <input type="submit" value="submit update">
                    </form>
                </td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td></tr>';
            } else {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td></tr>';
            }
        }
        echo '</table>';                                 
    }

            if (isset($_POST['updateprojname'])) {
                $projid = $_POST['updateprojid'];
                $newprojname = $_POST['updateprojname'];
                $sqlupdate = "  UPDATE `actions`
                                SET `projectname` = '$newprojname'
                                WHERE `id` = $projid";
                mysqli_query($conn, $sqlupdate);
                ob_get_clean();
                drawProjTable();
            }


    if (isset($_POST['wantAssignProj'])) {
        ob_get_clean();
        $assignid = $_POST['wantAssignProj'];
        $sql = "SELECT employees.id, employees.name, employees.project_id, actions.projectname
                FROM employees
                LEFT JOIN actions
                ON employees.project_id=actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql);

        $sql2 ="SELECT actions.id, actions.projectname
                FROM actions";
        $result2 = mysqli_query($GLOBALS["conn"], $sql2);        

        echo   '<table><tr><td></td></tr></table>
                <table><tr><th style="text-align:center">employee id</th><th>name</th><th>project</th></tr>';
        while($row = mysqli_fetch_assoc($result)) {
            if ($assignid == $row["id"]) {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["name"].'</td><td>
                    <form action="index.php" method="POST">
                        <select name="assignprojid">';
                            while($row2 = mysqli_fetch_assoc($result2)) {
                            echo '<option value="'.$row2["id"].'">'.$row2["projectname"].'</option>';}
                echo   '</select>                       
                        <input type="hidden"  name="assignemplid" value="'.$row["id"].'">
                        <input type="submit" value="assign to project">
                    </form></td></tr>';
            } else {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["name"].'</td><td>'.$row["projectname"].'</td></tr>';    
            }
        }
        echo '</table>';        
    }

            if (isset($_POST['assignprojid'])) {
                $assignemplid = $_POST['assignemplid'];
                $assignprojid = $_POST['assignprojid'];
                $sqlupdate = "  UPDATE `employees`
                                SET `project_id` = $assignprojid
                                WHERE `id` = $assignemplid ";
                mysqli_query($conn, $sqlupdate);
                ob_get_clean();
                drawEmplTable();
            }


    if (isset($_POST['wantDissEmpl'])) {
        ob_get_clean();
        $updateid = $_POST['wantDissEmpl'];
        $sql = "SELECT actions.projectname, actions.id, GROUP_CONCAT(employees.name SEPARATOR ', ')
                FROM actions
                LEFT JOIN employees
                ON actions.id=employees.project_id
                GROUP BY actions.id";
        $result = mysqli_query($GLOBALS["conn"], $sql); 

        echo   '<table><tr><td></td></tr></table>
                <table><tr><th style="text-align:center">project id</th><th>project name</th><th>responsible employee(s)</th></tr>';
        while($row = mysqli_fetch_assoc($result)) {
            if ($updateid == $row["id"]) {
                $rowid = $row["id"];

                $sql2 = "   SELECT employees.id, employees.name, employees.project_id
                            FROM employees
                            WHERE project_id = $rowid ";
                $result2 = mysqli_query($GLOBALS["conn"], $sql2);

                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>
                    <form action="index.php" method="POST">
                        <select name="disemplid">';
                            while($row2 = mysqli_fetch_assoc($result2)) {
                            echo '<option value="'.$row2["id"].'">'.$row2["name"].'</option>';}
                echo   '</select>            
                        <input type="submit" value="dismiss employee">
                    </form></td></tr>';
            } else {
                echo '<tr><td style="text-align:center">'.$row["id"].'</td><td>'.$row["projectname"].'</td><td>'.$row["GROUP_CONCAT(employees.name SEPARATOR ', ')"].'</td></tr>';
            }
        }
        echo '</table>';                                   
    }

            if (isset($_POST['disemplid'])) {
                $disemplid = $_POST['disemplid'];
                $sqlupdate = "  UPDATE `employees`
                                SET `project_id` = NULL
                                WHERE `id` = $disemplid ";
                mysqli_query($conn, $sqlupdate);
                ob_get_clean();
                drawProjTable();
            }

    mysqli_close($conn);
    ?>

    <table><tr><td style="text-align:right">Copyright: Zydrunas Pavinksnis, 2020 anno Domini</td></tr></table>    
</body>
</html>