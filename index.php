<?php 
    session_start();
    $_SESSION['image_number'] = 1;
    if(isset($_SESSION['loged'])&&($_SESSION['loged']==true)){
        header('Location: lindex.php');
        exit();
    }
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=true"/>
        <meta charset="UTF-8">
        <title>Oceniacz</title>
        
        <!--css-->
        <link href="styles/index.css" type="text/css" rel="stylesheet"/>
        
        <!--Google font-->
        <link href="https://fonts.googleapis.com/css?family=Atma" rel="stylesheet">
        
        <!--JS-->
        <script src="js/index.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <nav>    
            <?php 
                if(isset($_SESSION['error'])) echo $_SESSION['error'];
            ?>
             <button id="login_button" onclick="login()" class="f_btn">Zaloguj się</button>
             <a href="register.php"><button class="f_btn">Zarejestruj się</button></a>
             <div id="login_panel">
                 <form action="" method="POST">
                     <label for="login">Login</label><input type="text" name="login" id="login"/><br />
                     <label for="password">Password</label><input type="password" name="password" id="passowrd"/><br />
                     <input type="submit" class="f_btn" name="submit" value="Login"/>
                 </form>
             </div> 
            
        </nav>
        <div id="content">
            <div id="image">
                <img src="img/1.jpg" alt="Zdjecie"/>
            </div>
            <div id="rate">
                <h1>Zaloguj się aby móc głosować</h1>
            </div>
        </div>
        <?php
            require_once "connect.php";
            $connection = @new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno!=0){
                echo 'Error: '.$connection->connect_errno;
            }
            else{
                    $login = filter_input(INPUT_POST, 'login');
                    $password = filter_input(INPUT_POST, 'password');
                    
                    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

                    if($result = @$connection->query(sprintf("SELECT * FROM users WHERE login='%s'", 
                            mysqli_real_escape_string($connection, $login))))
                            {
                        $foundUser = $result->num_rows;
                        if($foundUser>0){
                            $row = $result->fetch_assoc();
                            if(password_verify($password, $row['password'])){
                                $_SESSION['loged'] = true;
                                $_SESSION['id'] =  $row['id_user'];
                                $_SESSION['user'] = $row['login']; 
                                unset($_SESSION['error']);
                                $result->close();
                                header('Location: lindex.php ');
                            }
                            else{
                                $_SESSION['error'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
                                header('Location: index.php');
                            }
                        }else{
                            if(isset($_POST['submit'])){
                                $_SESSION['error'] = '<span style="color:red">Nieprawidlowy login lub haslo!</span>';
                                header('Location: index.php');
                            }
                        }
                    }
                $connection->close();
            }
        ?>
    </body>
</html>