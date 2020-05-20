<?php
include "master/dbMaster.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';


require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';


if (!isset($_SESSION)) {
    ob_start();
    session_start();
}

if (isset($_SESSION["user-mail"])) {
    session_destroy();
    header("Location:login.php");
}


if (isset($_POST['forgetPassword'])) {

    $userEmail = $_POST['user-mail'];

    $request = $db->prepare("SELECT * FROM users where email=:mail");
    $request->execute(array(
        'mail' => $userEmail

    ));

    echo $response = $request->rowCount();


    $res = $request->fetch();


    if ($response == 1) {

        $secretpassKey = time() . mt_rand() ;


                //Create a new PHPMailer instance
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
        $mail->addAddress($userEmail, 'Dear User');


        $mail->isHTML(true);  
        //Set the subject line
        $mail->Subject = 'Password Reset';

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        
        $mail->Body    = '<a href="http://localhost/wbson/passwordReset.php?secKey='.$secretpassKey.'">Bağlantıdan parolanızı yenileyebilirsiniz.</a>';
        $mail->AltBody = 'http://localhost/wbson/passwordReset.php?secKey='.$secretpassKey;

        //send the message, check for errors
        if (!$mail->send()) {
            echo 'Mailer Error: '. $mail->ErrorInfo;
        } else {


             $queryDel = $db->prepare("DELETE FROM passreset WHERE email = :email");
            $delete = $queryDel->execute(array(
                'email' => $userEmail
            ));
            //echo 'Message sent!';
             $query = $db->prepare("INSERT INTO passreset SET email = ?, secretpasskey = ?");
            $insert = $query->execute(array(
                $userEmail,
                $secretpassKey
            ));

            header("Location: forgetPassword.php?res");
        }




       

       


        
        //header("Location:profile.php");
        exit;


    } else {

        //header("Location:login.php?res=no");
        exit;
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Parolamı Unuttum</title>
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
</head>
<body class="animsition">

<!-- Header -->
<?php

include("includes/header.php")

?>

<!-- Title Page -->
<section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: url(images/heading-pages-05.jpg);">
    <h2 class="l-text2 t-center">
        Parolamı Unuttum
    </h2>
</section>

<section class="section-conten padding-y" style="min-height:600px;background-color: #fbf4f9;padding-top:100px;">

    <!-- ============================ COMPONENT LOGIN   ================================= -->
    <div class="card mx-auto" style="max-width: 380px; ">
        <div class="card-body">
            <h4 class="card-title mb-4">Parolamı Unuttum</h4>
            <form method="post" action="">

                <?php
                if (isset($_GET["res"])){

                    print "<div class=\"alert alert-success\" role=\"alert\">Mail adresinizi kontrol ediniz.</div>";

                }

                ?>

                <div class="form-group">
                    <input name="user-mail" style="border: 1px solid rgba(0,0,0,.15) !important;" class="form-control"
                           placeholder="E-mail" type="text" required>
                </div> <!-- form-group// -->
                <div class="form-group">
                    <input type="submit" name="forgetPassword" class="btn btn-primary btn-block" value="Parolamı Unuttum"
                           style="background-color: #e65540">
                </div> <!-- form-group// -->
            </form>
        </div> <!-- card-body.// -->
    </div> <!-- card .// -->

    <p class="text-center mt-4"><a href="register.php">Giriş Yap</a></p>
    <br><br>
    <!-- ============================ COMPONENT LOGIN  END.// ================================= -->


</section>


<!-- Footer -->

<?php

include("includes/footer.php");

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
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>

</body>
</html>
