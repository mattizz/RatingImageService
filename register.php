<?php 
    session_start();
    unset($_SESSION['error']);
    if(isset($_POST['email'])){
        $all_OK = true;
        
        //Walidacja formularza
        $login = $_POST['login'];
        
        //----------------- LOGIN -----------------
        //Login length
        if((strlen($login)<3) || (strlen($login)>20)){
            $all_OK = false;
            $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
        }
        
        //Signs
        if(ctype_alnum($login)==false){
            $all_OK = false;
            $_SESSION['e_login'] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
        }
        
        //----------------- PASSWORD -----------------
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        
        //Length password
        if((strlen($password1)<8) || (strlen($password1>20))){
             $all_OK = false;
             $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków";
        }
        
        //Equals two passwords
        if($password1!=$password2){
            $all_OK = false;
            $_SESSION['e_password'] = "Podane hasła nie są identyczne";
        }
        
        $password_hashed = password_hash($password1, PASSWORD_DEFAULT);
        
        //----------------- FIRST NAME -----------------
        $first_name = $_POST['first_name'];
        
        if((strlen($first_name)<2) || (strlen($first_name)>25)){
            $all_OK = false;
            $_SESSION['e_first_name'] = "Podaj poprawne imie";
        }
        
        if(!preg_match("/^[a-zA-Z'-]+$/",$first_name)){
            $all_OK = false;
            $_SESSION['e_first_name'] = "Podaj poprawne imie";
        }
        
        //----------------- LAST NAME -----------------
        $last_name = $_POST['last_name'];
        
        if((strlen($last_name)<2) || (strlen($last_name)>25)){
            $all_OK = false;
            $_SESSION['e_last_name'] = "Podaj poprawne nazwisko";
        }
        
        if(!preg_match("/^[a-zA-Z'-]+$/",$last_name)){
            $all_OK = false;
            $_SESSION['e_last_name'] = "Podaj poprawne nazwisko";
        }
        
        //----------------- EMAIL -----------------
        $email = $_POST['email'];
        $emailSafe = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if((filter_var($emailSafe, FILTER_VALIDATE_EMAIL)==false) || ($emailSafe!=$email)){
            $all_OK = false;
            $_SESSION['e_email'] = "Podaj poprawny email";
        }
        
        //----------------- AGE -----------------
        $age = $_POST['age'];
        
        if(!preg_match("/^[0-9]{1,2}$|^1[0-4][0-9]$/", $age)){
            $all_OK = false;
            $_SESSION['e_age'] = "Podaj prawidłowy wiek";
        }
        
        //----------------- PHONE NUMBER -----------------
        $phone_number = $_POST['phone_number'];
        
        if(!preg_match("/^[0-9]{9}$/", $phone_number)){
            $all_OK = false;
            $_SESSION['e_phone_number'] = "Podaj poprawny numer";
        }
        
        //----------------- CHECKBOX -----------------
        if(!isset($_POST['rules'])){
            $all_OK = false;
            $_SESSION['e_rules'] = "Potwierdz akceptacje regulaminu";
        }
        
        //----------------- CAPTCHA -----------------
        $secret = "6Lfq-EAUAAAAAK-otix7m4gXFVuylbXJW_CJkw4q";
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        
        $answer = json_decode($check);
        
        if($answer->success==false){
            $all_OK = false;
            $_SESSION['e_bot'] = "Potwierdz, że nie jesteś botem";
        }
        
        $_SESSION['fr_login'] = $login;
        $_SESSION['fr_password1'] = $password1;
        $_SESSION['fr_password2'] = $password2;
        $_SESSION['fr_first_name'] = $first_name;
        $_SESSION['fr_last_name'] = $last_name;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_age'] = $age;
        $_SESSION['fr_phone_number'] = $phone_number;
        if(isset($_POST['rules'])){
            $_SESSION['fr_rules'] = true;
        }
        
         //----------------- AFTER ALL VALIDATIONS -----------------
        require_once 'connect.php';
        
        mysqli_report(MYSQLI_REPORT_STRICT); //Rzucaj exceptions zamiast warningów
        
        try{
             $connection = new mysqli($host, $db_user, $db_password, $db_name);
             if($connection->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }else{
                $result1 = $connection->query("SELECT id_user FROM users where login='$login'"); 
                if(!$result1) throw new Exception ($connection->error);
                $num_found_logins = $result1->num_rows;
                if($num_found_logins>0){
                    $all_OK=false;
                    $_SESSION['e_login'] = "Podany login jest zajęty";
                }
                
                $result2 = $connection->query("SELECT id_user FROM users where email='$email'");
                if(!$result2) throw new Exception ($connection->error);
                $num_found_emails = $result2->num_rows;
                if($num_found_emails>0){
                     $all_OK=false;
                     $_SESSION['e_email'] = "Ten email jest już wykorzystywany";
                }
                if($all_OK==true){
                    if($connection->query("INSERT INTO users VALUES(NULL, '$login', '$password_hashed', '$first_name', '$last_name', '$email', '$age', '$phone_number')")){
                        $_SESSION['registered'] = true;
                        header('Location: welcome.php');
                    }else{
                        throw new Exception($connection->error);
                    }
                }
        
                $connection->close();
            }
        } catch (Exception $ex) {
            echo '<span style="color:red">Błąd serwera. Prosimy o rejestracje w innym terminie.<span>';
            echo '<br /> Wyjątek: '.$ex;
        }
        
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
        <title>Rejestracja</title>
        
        <!--css-->
        <link href="styles/register.css" type="text/css" rel="stylesheet"/>
        
        <!--Google font-->
        <link href="https://fonts.googleapis.com/css?family=Atma" rel="stylesheet">
        
        <!--JS-->
        <script src="js/index.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <nav>    
            <a href="index.php"><button class="f_btn">Powrót na stronę główną</button></a>
        </nav>
        <div id="form_register">
            <h1>Formularz rejestracji<h1/>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>
                            <h3>Dane do logowania</h3>
                            <label for="login">Login:</label><input type="text" name="login" id="login" value="<?php
                                 if(isset($_SESSION['fr_login'])){
                                     echo $_SESSION['fr_login'];
                                     unset($_SESSION['fr_login']);
                                 }                                   
                            ?>"/><div></div>
                            <?php 
                                if(isset($_SESSION['e_login'])){
                                    echo '<div class="error">'.$_SESSION['e_login'].'</div>';
                                    unset($_SESSION['e_login']);
                                }                                   
                            ?>
                            <label for="password1">Hasło:</label><input type="password" name="password1" id="password1" value="<?php
                                 if(isset($_SESSION['fr_password1'])){
                                     echo $_SESSION['fr_password1'];
                                     unset($_SESSION['fr_password1']);
                                 }                                   
                            ?>"/><div></div>
                            <?php 
                                if(isset($_SESSION['e_password'])){
                                    echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                                    unset($_SESSION['e_password']);
                                }                                   
                            ?>
                            <label for="password2">Powtórz hasło:</label><input type="password" name="password2" id="password2" value="<?php
                                 if(isset($_SESSION['fr_password2'])){
                                     echo $_SESSION['fr_password2'];
                                     unset($_SESSION['fr_password2']);
                                 }                                   
                            ?>"/><div></div>
                        </td> 
                        <td>
                             <h3>Dane podstawowe</h3>
                             <label for="first_name">Imie:</label><input type="text" name="first_name" id="first_name" value="<?php
                                 if(isset($_SESSION['fr_first_name'])){
                                     echo $_SESSION['fr_first_name'];
                                     unset($_SESSION['fr_first_name']);
                                 }                                   
                            ?>"/><div></div>
                             <?php 
                                if(isset($_SESSION['e_first_name'])){
                                    echo '<div class="error">'.$_SESSION['e_first_name'].'</div>';
                                    unset($_SESSION['e_first_name']);
                                }                                   
                            ?>
                             <label for="last_name">Nazwisko:</label><input type="last_name" name="last_name" id="last_name" value="<?php
                                 if(isset($_SESSION['fr_last_name'])){
                                     echo $_SESSION['fr_last_name'];
                                     unset($_SESSION['fr_last_name']);
                                 }                                   
                            ?>"/><div></div>
                             <?php 
                                if(isset($_SESSION['e_last_name'])){
                                    echo '<div class="error">'.$_SESSION['e_last_name'].'</div>';
                                    unset($_SESSION['e_last_name']);
                                }                                   
                            ?>
                             <label for="email">Email:</label><input type="email" name="email" id="email" value="<?php
                                 if(isset($_SESSION['fr_email'])){
                                     echo $_SESSION['fr_email'];
                                     unset($_SESSION['fr_email']);
                                 }                                   
                            ?>"/><div></div>
                             <?php 
                                if(isset($_SESSION['e_email'])){
                                    echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                                    unset($_SESSION['e_email']);
                                }                                   
                            ?>
                             <label for="age">Wiek:</label><input type="text" name="age" id="age" maxlength="3" value="<?php
                                 if(isset($_SESSION['fr_age'])){
                                     echo $_SESSION['fr_age'];
                                     unset($_SESSION['fr_age']);
                                 }                                   
                            ?>"/><div></div>
                             <?php 
                                if(isset($_SESSION['e_age'])){
                                    echo '<div class="error">'.$_SESSION['e_age'].'</div>';
                                    unset($_SESSION['e_age']);
                                }                                   
                             ?>
                             <label for="phone_number">Numer telefonu:</label><input type="text" name="phone_number" id="phone_number" maxlength="9" value="<?php
                                 if(isset($_SESSION['fr_phone_number'])){
                                     echo $_SESSION['fr_phone_number'];
                                     unset($_SESSION['fr_phone_number']);
                                 }                                   
                            ?>"/><div></div>
                              <?php 
                                if(isset($_SESSION['e_phone_number'])){
                                    echo '<div class="error">'.$_SESSION['e_phone_number'].'</div>';
                                    unset($_SESSION['e_phone_number']);
                                }                                   
                             ?>
                        </td>
                    </tr>
                <table
                <br/><br/>
                <label><input type="checkbox" name="rules" <?php  
                if(isset($_SESSION['fr_rules'])){
                    echo "checked";
                    unset($_SESSION['fr_rules']);
                }
                ?>/>Akceptuję regulamin<br /></label><div></div>
                <?php 
                                if(isset($_SESSION['e_rules'])){
                                    echo '<div class="error">'.$_SESSION['e_rules'].'</div>';
                                    unset($_SESSION['e_rules']);
                                }                                   
               ?>
                <div class="g-recaptcha" data-sitekey="6Lfq-EAUAAAAABBjuPuQEon_YkwXLalWaZmroGkN"></div><br/>
                <?php 
                                if(isset($_SESSION['e_bot'])){
                                    echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                                    unset($_SESSION['e_bot']);
                                }                                   
               ?>
                <input type="submit" value="Załóż konto" id="submitBTN"/>
            </form>
        </div>      
    </body>
</html>