<?php

include "master/dbMaster.php";


if(isset($_GET["delId"])){

    $query = $db->prepare("DELETE FROM serviceproviders WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $_GET["delId"]
    ));


}


if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $id = $_SESSION['id'];


        $queryForMyServices = $db->query("SELECT * FROM serviceProviders WHERE user_id = " . $id, PDO::FETCH_ASSOC);



    } else {
        header('Location: login.php');
    }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profil</title>
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
</head>
<body class="animsition">

<?php

include("includes/header.php")

?>


<section class="section-content" style="padding-top:30px;background-color: #fbf4f9;min-height: 600px;">
    <div class="container">

        <div class="row">
            <aside class="col-md-3">
                <ul class="list-group">
                    <a class="list-group-item" href="profile.php"> Hesap Bilgileri </a>
                    <a class="list-group-item" href="addService.php"> Servis Ekle </a>
                    <a class="list-group-item active" href="#"> Servislerim </a>
                    <a class="list-group-item" href="login.php"> Çıkış Yap </a>
                </ul>
            </aside> <!-- col.// -->
            <main class="col-md-9">

                <article class="card  mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Servislerim </h5>

                        <div class="row">


                            <?php

                            if ($queryForMyServices->rowCount()) {
                                foreach ($queryForMyServices as $row) {




                                    $queryforspimages = $db->query("SELECT * FROM spimages WHERE sp_id = '{$row["id"]}'")->fetch(PDO::FETCH_ASSOC);

                                    print "<div class=\"col-md-4\">";
                                    print "<figure class=\"itemside  mb-4\">";
                                    print "<div class=\"aside\"><a href='productDetails.php?id=".$row["id"]."'><img style='width:100%' src=\"".$queryforspimages["photoUrl"]."\" class=\"border img-sm imageCorrect\"></a>";

                                    print "</div>";
                                    print "<figcaption class=\"info\">";
                                    print "<time class=\"text-muted\">".$row["vendor_name"]."</time>";
                                    print "</figcaption>";
                                    print "</figure>";
                                    print "<a class='btn btn-danger' style='width: 100%' href='myServices.php?delId=".$row["id"]."'> Servis Sil</a>";
                                    print "<a class='btn btn-success' style='width: 100%; margin-top:10px;' href='updateService.php?id=".$row["id"]."'> Düzenle</a>";
                                    print "</div>";
                                }
                            }

                            ?>

                        </div> <!-- row.// -->


                    </div> <!-- card-body .// -->
                </article> <!-- card.// -->


            </main> <!-- col.// -->
        </div>

    </div> <!-- container .//  -->
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

</body>
</html>
