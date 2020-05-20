<?php
include "master/dbMaster.php";
include_once "secret/User.php";



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use PHPMailer\PHPMailer\SMTP;


require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        header('Location: profile.php');
    }

}

if (isset($_POST["register"])) {

    $nameSurname = $_POST["user-name"] ;
    $plainPass = $_POST["user-password"];
    $password = md5($_POST["user-password"]);
    $repass = md5($_POST["user-repassword"]);
    $phone = $_POST["user-phone"];
    $email = $_POST["user-email"];
    $type = $_POST["user-type"];
    $address = $_POST["address"];

    $query = $db->prepare('SELECT * FROM users WHERE email = ?');
    $query->execute(array($email));
    $results = $query->fetch();


    if ($repass == $password) {
        if ($query->rowCount() <= 0) {

            $users = new Users();

            $lastUID = $users->createUser($email,$plainPass);



            $firebaseUser = array(''.$lastUID.'' =>
                array(
                    'avata' => 'default',
                    'email' => '' . $email . '',
                    'message' =>
                        array(
                            'idReceiver' => '0',
                            'idSender' => '0',
                            'text' => '',
                            'timestamp' => 0,
                        ),
                    'name' => '' . $nameSurname . '',
                    'status' =>
                        array(
                            'isOnline' => false,
                            'timestamp' => 1577273905967,
                        )
                )
            );


            $users->insert($firebaseUser);



            $queryInsert = $db->prepare("INSERT INTO users SET name_surname = ?, address = ?, email = ?, password = ?, phone = ?, user_type = ?, firebaseUID = ?, is_active = ?");
            $insert = $queryInsert->execute(array($nameSurname,$address, $email, $password, $phone, $type, $lastUID, 0));
            if ($insert) {

                        //Create a new PHPMailer instance



                $secretpassKey = time() . mt_rand();
        $mail = new PHPMailer;

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
       //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = 'ctisweddingvibes@gmail.com';

        //Password to use for SMTP authentication
        $mail->Password = 'ctis123456';

        //Set who the message is to be sent from
        $mail->setFrom('weddingvibesctis@gmail.com', 'WeddingVibes Admin');

        //Set who the message is to be sent to
        $mail->addAddress($email, 'Dear User');


        $mail->isHTML(true);  
        //Set the subject line
        $mail->Subject = 'User Activate';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        
        $mail->Body    = '<a href="http://localhost/wbson/login.php?activateUser='.$secretpassKey.'">Bağlantıdan hesabınızı aktive edebilirsiniz.</a>';
        $mail->AltBody = 'http://localhost/wbson/login.php?activateUser='.$secretpassKey;

        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: '. $mail->ErrorInfo;
        } else {


              //echo 'Message sent!';
             $queryActivateUser = $db->prepare("INSERT INTO activateuser SET email = ?, secretkey = ?");
            $insert = $queryActivateUser->execute(array(
                $email,
                $secretpassKey
            ));


                header("location: login.php?mustactivate");
            }
        }







             
        } else {
            header("location: register.php?userExist");
        }
    } else {
        header("location: register.php?pdm");
    }

    /*



    */

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kayıt Ol</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/themify/themify-icons.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/elegant-font/html-css/style.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->

    <style>
        .inputStyleCorrect {
            border: 1px solid rgba(0, 0, 0, .15) !important;
        }

        .custom-control-inputCorrect {
            border: 1px solid rgba(0, 0, 0, .15) !important;
        }

        .btn-bgCorrect {
            background-color: #e65540 !important;
            border-color: #e65540 !important;
        }
    </style>
</head>
<body class="animsition">

<!-- Header -->
<?php

include("includes/header.php");


?>


<!-- content page -->
<section class="section-content padding-y" style="background-color: #fbf4f9;padding-top:40px">

    <!-- ============================ COMPONENT REGISTER   ================================= -->
    <div class="card mx-auto" style="max-width:520px; margin-top:40px;">
        <article class="card-body">
            <header class="mb-4"><h4 class="card-title">Kayıt Ol</h4></header>
            <form action="" method="post">
                <?php

                if (isset($_GET["userExist"])) {

                    print "<div class=\"alert alert-danger\" role=\"alert\">Girdiğini E-mail ile kayıtlı bir kullanıcı bulunmakta.</div>";

                }
                if (isset($_GET["pdm"])) {

                    print "<div class=\"alert alert-danger\" role=\"alert\">Girdiğiniz parolalar eşleşmiyor.</div>";

                }


                ?>


                <div class="form-row">
                    <div class="col form-group">
                        <label id="changableName">Ad Soyad*</label>
                        <input type="text" name="user-name" class="form-control inputStyleCorrect" placeholder=""
                               required>
                    </div> <!-- form-group end.// -->

                </div> <!-- form-row end.// -->
                <div class="form-group">
                    <label>Email*</label>
                    <input type="email" name="user-email" class="form-control inputStyleCorrect" placeholder=""
                           required>
                    <small class="form-text text-muted">Email adresinizi kimseyle paylaşmayacağız.</small>
                </div> <!-- form-group end.// -->
                <div class="form-group">
                    <label>Telefon*</label>
                    <input type="text" name="user-phone" class="form-control inputStyleCorrect" placeholder="" required>
                    <small class="form-text text-muted ">Telefon numaranızı kimseyle paylaşmayacağız.</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Şifre*</label>
                        <input class="form-control inputStyleCorrect pass" id="user-password" name="user-password"
                               type="password" required>
                    </div> <!-- form-group end.// -->
                    <div class="form-group col-md-6 ">
                        <label>Şifre Tekrar*</label>
                        <input class="form-control inputStyleCorrect pass" id="user-repassword" name="user-repassword"
                               type="password" required>
                    </div> <!-- form-group end.// -->
                    <div id="msg"></div>
                </div>
                <div class="form-group">
                    <label>Üyelik Tipi*</label>
                    <select name="user-type" id="user-type" class="form-control" required>
                        <option value="">Üyelik Tipi Seçiniz</option>
                        <option value="1">Müşteri</option>
                        <option value="2">Servis Sağlayıcı</option>
                    </select>
                </div>
                <div class="form-group" id="addressDiv" style="display: none">
                    <label for="exampleFormControlTextarea1">Adres*</label>
                    <textarea class="form-control inputStyleCorrect" name="address" id="address" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" name="register" class="btn btn-primary btn-block btn-bgCorrect"
                           value="Kayıt Ol">
                </div> <!-- form-group// -->
                <div class="form-group">
                    <div class="custom-control-label"> Kayıt olarak <a href="#">kullanım şartlarını</a> kabul ediyorum
                    </div>
                </div> <!-- form-group end.// -->
            </form>
        </article><!-- card-body.// -->
    </div> <!-- card .// -->
    <p class="text-center mt-4">Hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
    <br><br>
    <!-- ============================ COMPONENT REGISTER  END.// ================================= -->


</section>


<!-- Footer -->
<?php

include("includes/footer.php")

?>

<!-- Back to top -->
<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
</div>

<!-- Container Selection -->
<div id="dropDownSelect1"></div>
<div id="dropDownSelect2"></div>


<!--===============================================================================================-->
<script type="text/javascript" src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/bootstrap/js/popper.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/select2/select2.min.js"></script>
<script type="text/javascript">
    $(".selection-1").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect1')
    });

    $(".selection-2").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect2')
    });


    $(".pass").on("propertychange change keyup paste input", function () {

        if ($("#user-password").val() != $("#user-repassword").val()) {
            $("#msg").html("Şifreler Eşleşmiyor").css("color", "red");
        } else {
            $("#msg").html("Şifreler Eşleşti").css("color", "green");
        }

    });


    $("#user-type").change(function () {
        var selectedOption = $(this)[0].selectedIndex;

        if (selectedOption == 1) {
            $("#addressDiv").css("display", "none");
            $('#address').prop('required', false);
            $('#changableName').html("Ad Soyad*");
        } else if (selectedOption == 2) {
            $("#addressDiv").css("display", "block");
            $('#address').prop('required', true);
            $('#changableName').html("Firma Adı*");
        } else if (selectedOption == 0) {
            $("#addressDiv").css("display", "none");
            $('#address').prop('required', false);
            $('#changableName').html("Ad Soyad*");
        }
    });
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>

