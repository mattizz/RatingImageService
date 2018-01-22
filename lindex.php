<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    if(!isset($_SESSION['loged'])){
        header('Location: index.php');
        exit();
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=true">
        <meta charset="UTF-8">
        <title>Oceniacz</title>
        
        <!--css-->
        <link href="styles/lindex.css" type="text/css" rel="stylesheet"/>
        
        <!--Google font-->
        <link href="https://fonts.googleapis.com/css?family=Atma" rel="stylesheet">
        
        <!--JS-->
        <script src="js/index.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body>
        <nav>    
            <span>Zalogowany jako: <?php echo $_SESSION['user']; ?></span>
            <span id="t_rate">Twoja obecna ocena: 
                <?php 
                    try{
                        include_once 'connect.php';
                        $connection = new mysqli($host, $db_user, $db_password, $db_name);
                        if($connection->connect_errno!=0){
                            throw new Exception(mysqli_connect_errno());
                        }else{
                                $user_id = $_SESSION['id'];
                                $image_number = $_SESSION['image_number'];
                                
                                $result = $connection->query("SELECT * FROM rates WHERE user='$user_id' AND image='$image_number'");
                                if(!$result) throw new Exception ($connection->error);
                                $found_results = $result->num_rows;
                                
                                if($found_results>0){
                                    $row = $result->fetch_assoc();
                                    $user_rating = $row['rating'];
                                    echo $user_rating;
                                }else{
                                    echo "Nie glosowano";
                                }
                            $connection->close();
                        }
                     }catch (Exception $ex) {
                           echo "BŁAD<br />";
                           exit();
                     }
                ?>
            </span>
            <a href="logout.php"><button class="f_btn" id="logout_button">Wyloguj się</button></a>
            <a href="register.php"><button class="f_btn">Zarejestruj się</button></a>
            
        </nav>
        <div id="content">
            <h1>Oceń to zdjęcie</h1>
            <table>
                <tr>
                    <td><form action="plindex.php"><button class="btn">&lt;</button></form></td>
                    <td><img src="img/<?php echo $_SESSION['image_number']?>.jpg" alt="Zdjecie" id="img"/></td>
                    <td><form action="nlindex.php"><button class="btn">&gt;</button></form></td>
                </tr>
            </table>
            <div id="rate">
                <form action="" method="POST">
                    <label class="ratingButton"><input type="radio" name="rating" value="1">1</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="2">2</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="3">3</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="4">4</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="5">5</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="6">6</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="7">7</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="8">8</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="9">9</label>
                    <label class="ratingButton"><input type="radio" name="rating" value="10">10</label> 
                    <br />
                    <input type="submit" value="Prześlij ocenę" class="sf_btn"/>
                    <div id="rating">
                        <?php
                            if(isset($_SESSION['rating_message'])){
                                echo $_SESSION['rating_message'];
                                unset($_SESSION['rating_message']);
                            }
                        ?>
                    </div>
                </form>
            </div>
            <?php
                     include_once 'connect.php';
                     try{
                        $connection = new mysqli($host, $db_user, $db_password, $db_name);
                        if($connection->connect_errno!=0){
                            throw new Exception(mysqli_connect_errno());
                        }else{
                            if(isset($_POST['rating'])){
                                $user_id = $_SESSION['id'];
                                $user_rating = $_POST['rating'];
                                $image_number = $_SESSION['image_number'];
                                
                                $result = $connection->query("SELECT id_rate FROM rates WHERE user='$user_id' AND image='$image_number'");
                                if(!$result) throw new Exception ($connection->error);
                                $found_results = $result->num_rows;
                                
                                if($found_results>0){
                                    $result = $connection->query("UPDATE rates set rating='$user_rating' WHERE user='$user_id' AND image='$image_number'");
                                    if(!$result) throw new Exception ($connection->error);
                                    else{
                                        $_SESSION['rating_message'] = "Twoja ocena została zaktualizowana";
                                        header('Location: lindex.php');
                                    }
                                }else{
                                    $result = $connection->query("INSERT INTO rates VALUES(NULL,'$user_id', '$image_number', '$user_rating')");
                                    if(!$result) throw new Exception ($connection->error);
                                    else{
                                        $_SESSION['rating_message'] = "Głos został oddany";
                                        header('Location: lindex.php');
                                    }
                                }
                            }else{
                                $_SESSION['rating_message'] = '<span style="color: red">Proszę wybrać ocenę od 1 do 10<span>';
                            }
                            $connection->close();
                        }
                     }catch (Exception $ex) {
                           echo "BŁAD<br />";
                           exit();
                     }
                ?>
        </div>
    </body>
</html>