<?php

include "master/dbMaster.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $userId = $_SESSION['id'];



        $queryForCategories = $db->query("SELECT * FROM categories", PDO::FETCH_ASSOC);


    } else {
        header('Location: login.php');
    }

}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $query = $db->prepare('SELECT * FROM serviceproviders WHERE id = ?');
    $query->execute(array($id));
    $results = $query->fetch();

    $map = explode(",", $results["latlng"]);



    $datesResult = "";

    $datesArr = array();
    $queryForDates = $db->query("SELECT * FROM spdates WHERE sp_id = '{$id}'", PDO::FETCH_ASSOC);
    if ( $queryForDates->rowCount() ){
        foreach( $queryForDates as $row ){
            array_push($datesArr,$row["dateData"]);

        }
    }


    //print_r($datesArr);

    $newArray = array();
    //print $datesResult;
    foreach($datesArr as $value) {
        $newArray[] = date('d-m-Y', strtotime($value));
    }

    sort($newArray);


    //print_r($newArray);
    //echo "selam ".$results["user_id"];


    foreach($newArray as $value) {
        $datesResult .= $value.", ";
    }

    $datesResult = substr($datesResult, 0, -2);

    //echo $datesResult;


    //print_r($resultsForUser["firebaseUID"]);

    //$firebase=$resultsForUser["firebaseUID"];
    $queryForImages = $db->query("SELECT * FROM spimages WHERE sp_id = " . $id, PDO::FETCH_ASSOC);



    $queryForUserAppointments = $db->query("SELECT * FROM userappointments WHERE is_active = 0 AND sp_id = ". $id, PDO::FETCH_ASSOC);


}

if(isset($_GET["delPhotoId"])){

    $query = $db->prepare("DELETE FROM spimages WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $_GET["delPhotoId"]
    ));


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


if (isset($_POST["updateService"])) {

    $productCategory = $_POST["productCategory"];
    $productDescription = $_POST["productDescription"];
    $productName = $_POST["productName"];
    $activated = $_POST["is_activated"];
    $lastSPID = $_POST["lastSPID"];
    $dates = $_POST["productDates"];
    $datesExploded = explode(",", $dates);
    $longlang = $_POST["lat"].",".$_POST["lng"];
    $query = $db->prepare("UPDATE serviceproviders SET category_id = :category_id, description = :description, vendor_name = :vendor_name, latlng = :latlng WHERE id = :id");
    $insert = $query->execute(array(
        "category_id" => $productCategory,
        "description" => $productDescription,
        "vendor_name" => $productName,
        "id" => $lastSPID,
        "latlng" =>$longlang

    ));

    if (count($datesExploded) > 0) {

        $query = $db->prepare("DELETE FROM spdates WHERE sp_id = :id");
        $delete = $query->execute(array(
            'id' => $lastSPID
        ));


        foreach ($datesExploded as $row) {

            $query = $db->prepare("INSERT INTO spdates SET sp_id = ?, dateData = ?");
            $insert = $query->execute(array(
                $lastSPID,
                $row
            ));

        }
    }


    if ($insert) {



        $img = $_FILES['productPhotos'];

        if ($_FILES["profilePhotos"]["error"] == 4) {
            $img_desc = reArrayFiles($img);
            //print_r($img_desc);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], 'img_data/' . $newname);

                $newname = 'img_data/' . $newname;
                $query = $db->prepare("INSERT INTO spimages SET sp_id = ?, photoUrl = ?");
                $insert = $query->execute(array(
                    $lastSPID,
                    $newname
                ));
                if ($insert) {
                    $lastIDInsertedImage = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }
        }

    }

    header("Location: updateService.php?id=".$lastSPID);

}





if (isset($_GET["applyAppointment"])) {

    $idAppointment = $_GET["applyAppointment"];
    $lastSPID = $_GET["id"];
    $is_activated = 1;

    $queryForAppUs = $db->prepare("UPDATE userappointments SET is_active = :is_activated WHERE id = :id");
    $update = $queryForAppUs->execute(array(
        "is_activated" => $is_activated,
        "id" => $idAppointment
    ));
    if ($update) {
        //print "güncelleme başarılı!";

        $queryForGettingAppointmentDate = $db->prepare('SELECT * FROM userappointments WHERE id = ?');
        $queryForGettingAppointmentDate->execute(array($idAppointment));
        $resultsForGettingAppointmentDate = $queryForGettingAppointmentDate->fetch();



        $queryForUpdatingSPDate = $db->prepare("INSERT INTO spdates SET sp_id = ?, dateData = ?");
        $insert = $queryForUpdatingSPDate->execute(array(
            $lastSPID,
            $resultsForGettingAppointmentDate["dateData"]
        ));

        header('Location: updateService.php?id='.$lastSPID);
    }
}

if (isset($_GET["removeAppointment"])) {

    $idAppointment = $_GET["removeAppointment"];


    $lastSPID = $_GET["id"];

    $query = $db->prepare("DELETE FROM userappointments WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $idAppointment
    ));

    header('Location: updateService.php?id='.$lastSPID);
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="includes/jquery-ui.multidatespicker.css">




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


<section class="section-content" style="padding-top:30px">
    <div class="container">

        <div class="row">
            <aside class="col-md-3">
                <ul class="list-group">
                    <a class="list-group-item" href="profile.php"> Hesap Bilgileri </a>
                    <a class="list-group-item" href="#"> Servis Ekle </a>
                    <a class="list-group-item active" href="myServices.php"> Servislerim </a>
                    <a class="list-group-item" href="login.php"> Çıkış Yap </a>
                </ul>
            </aside> <!-- col.// -->
            <main class="col-md-9">






                <article class="card  mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Servis Güncelle </h5>


                        <div class="card-body">
                            <h5 class="card-title mb-4">Fotoğraflar </h5>

                            <div class="row">


                                <?php

                                if ($queryForImages->rowCount()) {
                                    foreach ($queryForImages as $row) {

                                        print "<div class=\"col-md-4\">";
                                        print "<figure class=\"itemside  mb-4\">";
                                        print "<div class=\"aside\"><img style='width:100%' src=\"".$row["photoUrl"]."\" class=\"border img-sm imageCorrect\">";
                                        print "</div>";
                                        print "</figure>";
                                        print "<a class='btn btn-danger' style='width: 100%' href='updateService.php?id=".$_GET["id"]."&delPhotoId=".$row["id"]."'>Fotoğrafı Sil</a>";
                                        print "</div>";
                                    }
                                }

                                ?>

                            </div> <!-- row.// -->


                        </div>



                        <form action="" method="post" enctype='multipart/form-data'>




                            <div class="form-group">
                                <label for="inputAddress">Servis Adı</label>
                                <input type="text" name="productName" class="form-control inputStyleCorrect" id="inputAddress"
                                       placeholder="Servis Adı" value="<?php echo $results["vendor_name"];?>" required>
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="inputAddress">Fiyat</label>
                                <input type="text" name="productPrice" class="form-control inputStyleCorrect" id="inputAddress"
                                       placeholder="Fiyat" required>
                            </div>
-->

                            <div class="form-group">
                                <label for="inputAddress">Tarih</label>
                                <input type="text" name="productDates" class="form-control inputStyleCorrect" id="productDates"
                                       placeholder="Kapanacak Tarihleri Seçin" value="<?php echo $datesResult ?>">
                            </div>

                            <div class="form-group">
                                <label for="inputCategory">Kategori Seç</label>
                                <select id="inputCategory" name="productCategory" class="form-control inputStyleCorrect" required>
                                    <option value="">Kategori Seç...</option>
                                    <?php
                                    if ($queryForCategories->rowCount()) {
                                        foreach ($queryForCategories as $row) {

                                            if ($row['id'] == $results["category_id"])
                                                print "<option value='" . $row['id'] . "' selected>" . $row['category'] . "</option>";
                                            else
                                                print "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Ürün Açıklaması</label>
                                <textarea class="form-control inputStyleCorrect" name="productDescription" id="exampleFormControlTextarea1" rows="3" required><?php echo $results["vendor_name"];?></textarea>
                            </div>





                            <div class="form-group">
                                <label for="exampleFormControlFile1">Fotoğraf Seçiniz</label>
                                <input type="file" name="productPhotos[]" class="form-control-file"
                                       id="exampleFormControlFile1" multiple="multiple">
                            </div>



                            <div id="map"></div>
                            <input type="hidden" id="lat" name="lat" readonly="yes" value="<?php echo($map[0]); ?>"><br>
                            <input type="hidden" id="lng" name="lng" readonly="yes" value="<?php echo($map[1]); ?>">




                            <input type="hidden" name="is_activated" value="<?php echo $results["is_activated"];?>">
                            <input type="hidden" name="lastSPID" value="<?php echo $results["id"];?>">

                            <input type="submit" name="updateService" class="btn btn-primary btn-bgCorrect" value="Servis Güncelle">
                        </form>


                    </div> <!-- card-body .// -->
                </article> <!-- card.// -->


                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bekleyen Randevular</h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Randevu Tarihi</th>
                                <th scope="col">Kullanıcı Adı</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            if ($queryForUserAppointments->rowCount()) {
                                $counter = 1;



                                foreach ($queryForUserAppointments as $row) {

                                    $queryAppointmentUser = $db->prepare('SELECT * FROM users WHERE id = ?');
                                    $queryAppointmentUser->execute(array($row["user_id"]));
                                    $resultAppointmentUser = $queryAppointmentUser->fetch();


                                    print "<tr>";
                                    print "<th scope=\"row\">" . $counter . "</th>";
                                    //print "<td><img src=\"" . $row['photoUrl'] . "\" style=\"max-height: 60px\"></td>";
                                    print "<td>" . $row['dateData'] . "</td>";
                                    print "<td>" . $resultAppointmentUser['name_surname'] . "</td>";
                                    print "<td><a href=\"updateService.php?id=" . $_GET["id"] . "&applyAppointment=".$row["id"]."\"><i class=\"fas fa-edit\" style=\"padding-right: 10px;\"></i></a><a href=\"updateService.php?id=" . $_GET["id"] . "&removeAppointment=".$row["id"]."\"><i class=\"fas fa-times\"></i></a></td>";
                                    print "</tr>";
                                    $counter++;
                                }
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

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
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="includes/jquery-ui.multidatespicker.js"></script>

<script type="text/javascript">
    $( function() {

        $( "#productDates" ).multiDatesPicker({

            dateFormat: "dd-mm-yy",
            minDate:0,
            monthNames: [ "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık" ],
            dayNamesMin: [ "Pa", "Pt", "Sl", "Ça", "Pe", "Cu", "Ct" ],
            firstDay:1
        });


    } );
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
        var centerOfMap = new google.maps.LatLng(<?php echo $results["latlng"];?>);

        //Map options.
        var options = {
            center: centerOfMap, //Set center.
            zoom: 17 //The zoom value.
        };

        //Create the map object.
        map = new google.maps.Map(document.getElementById('map'), options);


        marker = new google.maps.Marker({
                    position: centerOfMap,
                    map: map,
                    draggable: true //make it draggable
                });
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
