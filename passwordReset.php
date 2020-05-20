<?php
include "master/dbMaster.php";


if (!isset($_SESSION)) {
    ob_start();
    session_start();
}

if (isset($_SESSION["user-mail"])) {
    session_destroy();
    header("Location:login.php");
}






if (isset($_POST['passwordReset'])) {

    $userPassword = $_POST['user-password'];
    $secretpasskey = "";

    if ($_GET["secKey"]) {
        $secretpasskey = $_GET["secKey"];

    }else{
         header("Location: login.php");
    }


    $request = $db->prepare("SELECT * FROM passreset where secretpasskey=:secretpasskey");
    $request->execute(array(
        'secretpasskey' => $secretpasskey

    ));

    echo $response = $request->rowCount();


    $res = $request->fetch();

    print_r($res);

    if ($response == 1) {

        $idAppointment = $_GET["applyAppointment"];
        $lastSPID = $_GET["id"];
        $is_activated = 1;

        $queryForAppUs = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
        $update = $queryForAppUs->execute(array(
            "password" => md5($userPassword),
            "email" => $res["email"]
        ));
        if ($update) {

         $query = $db->prepare("DELETE FROM passreset WHERE email = :id");
            $delete = $query->execute(array(
                'id' => $res["email"]
            ));

        }
        
        header("Location: login.php");
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
    <title>Parolamı Yenile</title>
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
            <h4 class="card-title mb-4">Parolamı Yenile</h4>
            <form method="post" action="">


                <div class="form-group">
                    <input name="user-password" style="border: 1px solid rgba(0,0,0,.15) !important;"
                           class="form-control" placeholder="Parola" type="password" required>
                </div> <!-- form-group// -->

                <div class="form-group">
                    <input type="submit" name="passwordReset" class="btn btn-primary btn-block" value="Parolamı Yenile"
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
