<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    if(isset($_SESSION['image_number'])){
        $_SESSION['image_number']--;
        if($_SESSION['image_number']<1) $_SESSION['image_number']=15;
        header('Location: lindex.php');
        exit();
    }
    if(!isset($_SESSION['loged'])){
        header('Location: index.php');
        exit();
    }
?>
