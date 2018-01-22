<?php 
    session_start();
    if(!isset($_SESSION['registered'])){
        header('Location: lindex.php');
        exit();
    }else{
        unset($_SESSION['registered']);
    }
    
    if(isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
    if(isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
    if(isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
    if(isset($_SESSION['fr_first_name'])) unset($_SESSION['fr_first_name']);
    if(isset($_SESSION['fr_last_name'])) unset($_SESSION['fr_last_name']);
    if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if(isset($_SESSION['fr_age'])) unset($_SESSION['fr_age']);
    if(isset($_SESSION['fr_phone_number'])) unset($_SESSION['fr_phone_number']);  
    if(isset($_SESSION['fr_rules'])) unset($_SESSION['fr_rules']);
    
    if(isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
    if(isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
    if(isset($_SESSION['e_first_name'])) unset($_SESSION['e_first_name']);
    if(isset($_SESSION['e_last_name'])) unset($_SESSION['e_last_name']);
    if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if(isset($_SESSION['e_age'])) unset($_SESSION['e_age']);
    if(isset($_SESSION['e_phone_number'])) unset($_SESSION['e_phone_number']);  
    if(isset($_SESSION['e_rules'])) unset($_SESSION['e_rules']);
    if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
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
        <link href="styles/welcome.css" type="text/css" rel="stylesheet"/>
        
        <!--Google font-->
        <link href="https://fonts.googleapis.com/css?family=Atma" rel="stylesheet">
        
        <!--JS-->
        <script src="js/index.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <nav>
            <h1>Dziękujemy za rejestracje w naszym serwisie!</h1> 
            <h4>Teraz możesz zalogować się na swoje konto</h4>        
            <a href="index.php"><button>Przejdź do strony głównej</button></a>
        </nav>
    </body>
</html>