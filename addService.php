<?php

include "master/dbMaster.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $id = $_SESSION['id'];



        $query = $db->query("SELECT * FROM categories", PDO::FETCH_ASSOC);


    } else {
        header('Location: login.php');
    }

}

function reArrayFiles($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_key as $val) {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


if (isset($_POST["addService"])) {

    $productCategory = $_POST["productCategory"];
    $productDescription = $_POST["productDescription"];
    $productName = $_POST["productName"];
    $activated = 0;
    $longlang = $_POST["lat"].",".$_POST["lng"];
    $query = $db->prepare("INSERT INTO serviceproviders SET category_id = ?, description = ?, vendor_name = ?, user_id = ?, is_activated = ?, latlng = ?");
    $insert = $query->execute(array(
        $productCategory,
        $productDescription,
        $productName,
        $_SESSION['id'],
        $activated,
        $longlang 
    ));
    if ($insert) {

        $last_id = $db->lastInsertId();

        $img = $_FILES['productPhotos'];

        if (!empty($img)) {
            $img_desc = reArrayFiles($img);
            //print_r($img_desc);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], 'img_data/' . $newname);

                $newname = 'img_data/' . $newname;
                $query = $db->prepare("INSERT INTO spimages SET sp_id = ?, photoUrl = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $newname
                ));
                if ($insert) {
                    $lastIDInsertedImage = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }
        }

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

    <style>
        .inputStyleCorrect{border: 1px solid rgba(0,0,0,.15) !important;}
        .custom-control-inputCorrect{border: 1px solid rgba(0,0,0,.15) !important;}
        .btn-bgCorrect{
            background-color: #e65540 !important;
            border-color: #e65540 !important;
        }
    </style>
    <style type="text/css">
        #map{ width:700px; height: 500px; }
    </style>

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
                    <a class="list-group-item active" href="#"> Servis Ekle </a>
                    <a class="list-group-item" href="myServices.php"> Servislerim </a>
                    <a class="list-group-item" href="login.php"> Çıkış Yap </a>
                </ul>
            </aside> <!-- col.// -->
            <main class="col-md-9">

                <article class="card  mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Servis Ekle </h5>

                        <form action="" method="post" enctype='multipart/form-data'>

                            <div id="map"></div>
                            <input type="hidden" id="lat" name="lat" readonly="yes"><br>
                            <input type="hidden" id="lng" name="lng" readonly="yes">
                            <div class="form-group">
                                <label for="inputAddress">Servis Adı</label>
                                <input type="text" name="productName" class="form-control inputStyleCorrect" id="inputAddress"
                                       placeholder="Servis Adı" required>
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="inputAddress">Fiyat</label>
                                <input type="text" name="productPrice" class="form-control inputStyleCorrect" id="inputAddress"
                                       placeholder="Fiyat" required>
                            </div>
-->
                            <div class="form-group">
                                <label for="inputCategory">Kategori Seç</label>
                                <select id="inputCategory" name="productCategory" class="form-control inputStyleCorrect" required>
                                    <option value="">Kategori Seç...</option>
                                    <?php
                                    if ($query->rowCount()) {
                                        foreach ($query as $row) {
                                            print "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Ürün Açıklaması</label>
                                <textarea class="form-control inputStyleCorrect" name="productDescription" id="exampleFormControlTextarea1" rows="3" required></textarea>
                            </div>





                            <div class="form-group">
                                <label for="exampleFormControlFile1">Fotoğraf Seçiniz</label>
                                <input type="file" name="productPhotos[]" class="form-control-file"
                                       id="exampleFormControlFile1" multiple="multiple" required>
                            </div>


                            <input type="submit" name="addService" class="btn btn-primary btn-bgCorrect" value="Servis Ekle">
                        </form>


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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<script>

    //map.js

    //Set up some of our variables.
    var map; //Will contain map object.
    var marker = false; ////Has the user plotted their location marker?

    //Function called to initialize / create the map.
    //This is called when the page has loaded.
    function initMap() {

        //The center location of our map.
        var centerOfMap = new google.maps.LatLng(52.357971, -6.516758);

        //Map options.
        var options = {
            center: centerOfMap, //Set center.
            zoom: 7 //The zoom value.
        };

        //Create the map object.
        map = new google.maps.Map(document.getElementById('map'), options);

        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function(event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if(marker === false){
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function(event){
                    markerLocation();
                });
            } else{
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
        });
    }

    //This function will get the marker's current location and then add the lat/long
    //values to our textfields so that we can save the location.
    function markerLocation(){
        //Get location.
        var currentLocation = marker.getPosition();
        //Add lat and lng values to a field that we can save.
        document.getElementById('lat').value = currentLocation.lat(); //latitude
        document.getElementById('lng').value = currentLocation.lng(); //longitude
    }


    //Load the map when the page has finished loading.
    google.maps.event.addDomListener(window, 'load', initMap);

</script>


</body>
</html>
