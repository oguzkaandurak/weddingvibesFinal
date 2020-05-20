
<?php 
include "master/dbMaster.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $id = $_SESSION['id'];
        $userType = $_SESSION['user-type'];
        $userAddress = $_SESSION['user-address'];

        $userPhone = $_SESSION['user-phone'];
        $query = $db->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute(array($id));
        $results = $query->fetch();
    } else {
        header('Location: login.php');
    }

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>İletişim</title>
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
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/lightbox2/css/lightbox.min.css">
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

<?php

include("includes/header.php")

?>


<section class="section-content" style="padding-top:30px; background-color: #fbf4f9;min-height: 600px;">
   <div class="container">
            <div class="row">
                <div class="col-md-6 p-b-30">
                    <div class="p-r-20 p-r-0-lg">
                        <div class="contact-map size21" id="google_map" data-map-x="39.871343" data-map-y="32.764510" data-scrollwhell="0" data-draggable="1"></div>
                    </div>
                </div>

                <div class="col-md-6 p-b-30">
                    <h1>Adres</h1>
                    <span>Universiteler Mah. Bilkent Universitesi, Doğu Kampüs, C Binası. Ankara </span><br><br>
                    <h1>Telefon</h1>
                    <span>05320000000</span><br><br>
                    <h1>Mail</h1>
                    <span>weddingvibesctis@gmail.com</span><br><br>

                </div>
            </div>
        </div>
</section>

<?php
include("includes/footer.php")

?>


<!-- Back to top -->
<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
</div>

<!-- Container Selection1 -->
<div id="dropDownSelect1"></div>


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
</script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
<script type="text/javascript" src="js/slick-custom.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/lightbox2/js/lightbox.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.block2-btn-addcart').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
        $(this).on('click', function () {
            swal(nameProduct, "is added to cart !", "success");
        });
    });

    $('.block2-btn-addwishlist').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
        $(this).on('click', function () {
            swal(nameProduct, "is added to wishlist !", "success");
        });
    });
</script>

<!--===============================================================================================-->
<script src="js/main.js"></script>


<script>

    $("#editProfile").click(function () {


        $("#profileShowArticle").css("display", "none");
        $('#profileEditArticle').css("display", "block");


    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes"></script>
    <script src="js/map-custom.js"></script>
</body>
</html>
