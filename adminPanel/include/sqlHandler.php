<?php
include_once "dbconnection.php";


//Add Product

if (isset($_POST["addProduct"])) {

    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productCategory = $_POST["productCategory"];
    $productDescription = $_POST["productDescription"];
    $productCode = $_POST["productCode"];
    $sizeType = $_POST["sizeType"];
    $checkboxChecked = 0;

    if (isset($_POST["promoteProduct"])) {
        $checkboxChecked = 1;
    } else {
        $checkboxChecked = -1;
    }


    $query = $db->prepare("INSERT INTO products SET productName = ?, productCode = ?, productPrice = ?, productCategory = ?, productDescription = ?, sizeType = ?, promoted = ?");
    $insert = $query->execute(array(
        $productName,
        $productCode,
        $productPrice,
        $productCategory,
        $productDescription,
        $sizeType,
        $checkboxChecked
    ));
    if ($insert) {
        $last_id = $db->lastInsertId();
        if ("" == trim($_POST['xs']) &&
            "" == trim($_POST['s']) &&
            "" == trim($_POST['m']) &&
            "" == trim($_POST['l']) &&
            "" == trim($_POST['xl']) &&
            "" == trim($_POST['xxl']) &&
            "" == trim($_POST['3xl']) &&
            "" == trim($_POST['4xl']) &&
            "" == trim($_POST['5xl'])) {

            //default
            $count = count($_POST["sizeName"]);
            for ($i = 0; $i < $count; $i++) {
                $query = $db->prepare("INSERT INTO productsize SET productID = ?, sizeDescription = ?, stockInformation = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $_POST["sizeName"][$i],
                    $_POST["sizeStock"][$i]
                ));
                if ($insert) {
                    $lastIDSize = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }


        } else {
            //eu
            $sizeName = array("XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL");
            $sizeStock = array($_POST["xs"], $_POST["s"], $_POST["m"], $_POST["l"], $_POST["xl"], $_POST["xxl"], $_POST["3xl"], $_POST["4xl"], $_POST["5xl"]);


            $count = count($sizeName);
            for ($i = 0; $i < $count; $i++) {
                $query = $db->prepare("INSERT INTO productsize SET productID = ?, sizeDescription = ?, stockInformation = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $sizeName[$i],
                    $sizeStock[$i]
                ));
                if ($insert) {
                    $lastIDSize = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }
        }

        $img = $_FILES['productPhotos'];

        if (!empty($img)) {
            $img_desc = reArrayFiles($img);
            //print_r($img_desc);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], 'productImages/' . $newname);

                $newname = 'productImages/' . $newname;
                $query = $db->prepare("INSERT INTO productimages SET productID = ?, photoUrl = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $newname
                ));
                if ($insert) {
                    $lastIDInsertedImage = $db->lastInsertId();
                    //print "insert işlemi başarılı!";

                    $tempArr = getimagesize($newname);
                    //print_r($tempArr[1]/960);
                    $resizeRatio = $tempArr[1]/960;
                    $newWidth = $tempArr[0]/$resizeRatio;
                    $newHeight = 960;
                    resize_image($newname,$newWidth,$newHeight);
                }
            }
        }

    }

}


//Update Product


if (isset($_POST["updateProduct"])) {

    $productName = $_POST["productName"];
    $productPrice = $_POST["productPrice"];
    $productCategory = $_POST["productCategory"];
    $productDescription = $_POST["productDescription"];
    $productCode = $_POST["productCode"];
    $sizeType = $_POST["sizeType"];
    $last_id = $_POST["productID"];

    $checkboxChecked = 0;
    if (isset($_POST["promoteProduct"])) {
        $checkboxChecked = 1;
    } else {
        $checkboxChecked = -1;
    }
    $query = $db->prepare("UPDATE products SET productName = :productName, productCode = :productCode, productPrice = :productPrice, productCategory = :productCategory, productDescription = :productDescription, sizeType = :sizeType, promoted = :promoted WHERE id = :id");
    $update = $query->execute(array(
        "productName" => $productName,
        "productCode" => $productCode,
        "productPrice" => $productPrice,
        "productCategory" => $productCategory,
        "productDescription" => $productDescription,
        "sizeType" => $sizeType,
        "promoted" => $checkboxChecked,
        "id" => $last_id
    ));

    if ($update == 1) {
        $query = $db->prepare("DELETE FROM productsize WHERE productID = :id");
        $delete = $query->execute(array(
            'id' => $last_id
        ));
        if ("" == trim($_POST['xs']) &&
            "" == trim($_POST['s']) &&
            "" == trim($_POST['m']) &&
            "" == trim($_POST['l']) &&
            "" == trim($_POST['xl']) &&
            "" == trim($_POST['xxl']) &&
            "" == trim($_POST['3xl']) &&
            "" == trim($_POST['4xl']) &&
            "" == trim($_POST['5xl'])) {

            //default
            $count = count($_POST["sizeName"]);
            for ($i = 0; $i < $count; $i++) {
                $query = $db->prepare("INSERT INTO productsize SET productID = ?, sizeDescription = ?, stockInformation = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $_POST["sizeName"][$i],
                    $_POST["sizeStock"][$i]
                ));
                if ($insert) {
                    $lastIDSize = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }


        } else {
            //eu
            $sizeName = array("XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL");
            $sizeStock = array($_POST["xs"], $_POST["s"], $_POST["m"], $_POST["l"], $_POST["xl"], $_POST["xxl"], $_POST["3xl"], $_POST["4xl"], $_POST["5xl"]);


            $count = count($sizeName);
            for ($i = 0; $i < $count; $i++) {
                $query = $db->prepare("INSERT INTO productsize SET productID = ?, sizeDescription = ?, stockInformation = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $sizeName[$i],
                    $sizeStock[$i]
                ));
                if ($insert) {
                    $lastIDSize = $db->lastInsertId();
                    //print "insert işlemi başarılı!";
                }
            }
        }

        $img = $_FILES['productPhotos'];




        if ($img["error"][0] != 4) {
            $img_desc = reArrayFiles($img);
            //print_r($img_desc);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], 'productImages/' . $newname);

                $newname = 'productImages/' . $newname;
                $query = $db->prepare("INSERT INTO productimages SET productID = ?, photoUrl = ?");
                $insert = $query->execute(array(
                    $last_id,
                    $newname
                ));
                if ($insert) {
                    $lastIDInsertedImage = $db->lastInsertId();

                    $tempArr = getimagesize($newname);
                    //print_r($tempArr[1]/960);

                    $resizeRatio = $tempArr[1]/960;
                    $newWidth = $tempArr[0]/$resizeRatio;
                    $newHeight = 960;
                    resize_image($newname,$newWidth,$newHeight);



                    //print "insert işlemi başarılı!";
                }
            }
        }

    }

}




//Remove Product
if (isset($_GET["removeProduct"])) {

    $productID = $_GET["removeProduct"];

    $query = $db->prepare("DELETE FROM products WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $productID
    ));
    $query = $db->prepare("DELETE FROM productimages WHERE productID = :id");
    $delete = $query->execute(array(
        'id' => $productID
    ));
    $query = $db->prepare("DELETE FROM productsize WHERE productID = :id");
    $delete = $query->execute(array(
        'id' => $productID
    ));

    header('Location: productOverview.php');
}


//Add Slider

if (isset($_POST["sliderHeader"]) && isset($_POST["sliderButtonHeader"]) && isset($_POST["sliderUrl"]) && isset($_POST["addSlider"])) {

    //echo "1";
    $directory = 'sliderImages/';
    $fileToUpload = $directory . generateRandomString() . basename($_FILES['sliderPhoto']['name']);

    //echo '<pre>';
    if (move_uploaded_file($_FILES['sliderPhoto']['tmp_name'], $fileToUpload)) {
        //echo "Dosya geçerli ve başarıyla yüklendi.\n";
        $sliderHeader = $_POST["sliderHeader"];
        $sliderButtonHeader = $_POST["sliderButtonHeader"];
        $sliderUrl = $_POST["sliderUrl"];

        $query = $db->prepare("INSERT INTO sliderinformation SET photoUrl = ?, sliderHeader = ?, sliderButtonHeader = ?, sliderButtonUrl = ?");
        $insert = $query->execute(array(
            $fileToUpload,
            $sliderHeader,
            $sliderButtonHeader,
            $sliderUrl
        ));
        if ($insert) {
            $last_id = $db->lastInsertId();
            //print "insert işlemi başarılı!";
            $tempArr = getimagesize($fileToUpload);

            resize_image($newname,$tempArr[0],$tempArr[1]);
        }

    } else {
        //echo "Olası dosya yükleme saldırısı!\n";
    }
    //echo 'Diğer hata ayıklama bilgileri:';
    //print_r($_FILES);
    //print "</pre>";

}

//Update Slider

if (isset($_POST["updateSlider"])) {


    //File Upload Empty
    if ($_FILES["sliderPhoto"]["error"] == 4) {


        $sliderHeader = $_POST["sliderHeader"];
        $sliderButtonHeader = $_POST["sliderButtonHeader"];
        $sliderUrl = $_POST["sliderUrl"];
        $id = $_POST["sliderID"];

        $query = $db->prepare("UPDATE sliderinformation SET sliderHeader = :sliderHeader, sliderButtonHeader = :sliderButtonHeader, sliderButtonUrl = :sliderButtonUrl WHERE id = :id");
        $update = $query->execute(array(
            "sliderHeader" => $sliderHeader,
            "sliderButtonHeader" => $sliderButtonHeader,
            "sliderButtonUrl" => $sliderUrl,
            "id" => $id
        ));
        if ($update) {
            //print "güncelleme başarılı!";
        }


    } else { //File Have Uploaded

        //echo "1";
        $directory = 'sliderImages/';
        $fileToUpload = $directory . generateRandomString() . basename($_FILES['sliderPhoto']['name']);

        //echo '<pre>';
        if (move_uploaded_file($_FILES['sliderPhoto']['tmp_name'], $fileToUpload)) {
            //echo "Dosya geçerli ve başarıyla yüklendi.\n";
            $sliderHeader = $_POST["sliderHeader"];
            $sliderButtonHeader = $_POST["sliderButtonHeader"];
            $sliderUrl = $_POST["sliderUrl"];
            $id = $_POST["sliderID"];
            $query = $db->prepare("UPDATE sliderinformation SET photoUrl = :photoUrl, sliderHeader = :sliderHeader, sliderButtonHeader = :sliderButtonHeader, sliderButtonUrl = :sliderButtonUrl WHERE id = :id");
            $update = $query->execute(array(
                "photoUrl" => $fileToUpload,
                "sliderHeader" => $sliderHeader,
                "sliderButtonHeader" => $sliderButtonHeader,
                "sliderButtonUrl" => $sliderUrl,
                "id" => $id
            ));
            if ($update) {
                //print "güncelleme başarılı!";

                $tempArr = getimagesize($fileToUpload);

                resize_image($newname,$tempArr[0],$tempArr[1]);
            }

        } else {
            //echo "Olası dosya yükleme saldırısı!\n";
        }
        //echo 'Diğer hata ayıklama bilgileri:';
        //print_r($_FILES);
        //print "</pre>";


    }

}

//Remove Slider

if (isset($_GET["removeSlider"])) {

    $productID = $_GET["removeSlider"];

    $query = $db->prepare("DELETE FROM sliderinformation WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $productID
    ));

    //header("location : slider.php");
    header('Location: slider.php');
}


//Add Color

if (isset($_POST["addColor"])) {

    $colorName = $_POST["colorName"];
    $colorValue = "#".$_POST["colorValue"];


    $query = $db->prepare("INSERT INTO productcolors SET colorName = ?, colorValue = ?");
    $insert = $query->execute(array(
        $colorName,
        $colorValue
    ));
    if ($insert) {
        $last_id = $db->lastInsertId();
        //print "insert işlemi başarılı!";
    }
}



//Update Color

if (isset($_POST["updateColor"])) {

    $colorName = $_POST["colorName"];
    $colorID = $_POST["colorID"];
    $colorValue = "#".$_POST["colorValue"];

    $query = $db->prepare("UPDATE productcolors SET colorName = :newColName, colorValue = :newCalVal WHERE id = :colorID");
    $update = $query->execute(array(
        "newColName" => $colorName,
        "newCalVal" => $colorValue,
        "colorID" => $colorID
    ));
    if ($update) {
        //print "güncelleme başarılı!";
    }
}
//Remove Category

if (isset($_POST["removeColor"])) {

    $colorID = $_POST["colorID"];

    $query = $db->prepare("DELETE FROM productColors WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $colorID
    ));
}


//Add Category

if (isset($_POST["catName"]) && isset($_POST["addCategory"])) {

    $categoryName = $_POST["catName"];

    $query = $db->prepare("INSERT INTO categories SET categoryName = ?");
    $insert = $query->execute(array(
        $categoryName
    ));
    if ($insert) {
        $last_id = $db->lastInsertId();
        //print "insert işlemi başarılı!";
    }
}

//Update Category

if (isset($_POST["catName"]) && isset($_POST["catID"]) && isset($_POST["updateCategory"])) {

    $categoryName = $_POST["catName"];
    $productID = $_POST["catID"];

    $query = $db->prepare("UPDATE categories SET categoryName = :newCatName WHERE id = :catID");
    $update = $query->execute(array(
        "newCatName" => $categoryName,
        "catID" => $productID
    ));
    if ($update) {
        //print "güncelleme başarılı!";
    }
}

//Remove Category

if (isset($_POST["catID"]) && isset($_POST["removeCategory"])) {

    $productID = $_POST["catID"];

    $query = $db->prepare("DELETE FROM categories WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $productID
    ));
}

//Admin Login


//Login
//All inputs are defined as required so no need to check if they empty or not
if(isset($_POST["adminLogin"])){

    //TODO: Do the security stuff

    $email = make_safe($_POST["email"]);
    $pass = md5(make_safe($_POST["pass"]));
    $query = $db->query("SELECT * FROM adminUsers WHERE email = '{$email}' AND pass = '{$pass}'")->fetch(PDO::FETCH_ASSOC);
    if ( $query ){

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        unset($query["pass"]);
        $_SESSION["current-user"] = $query;
        header('Location: index.php');

    }else{
        //Incorrect pass
        header("Location: login.php?res=no");
    }

}

//___________________________________________SEC_CHECK____________________________________________________________

function checkUserLoggedIn($db){

    if (session_status() == PHP_SESSION_NONE) {
        session_start();

        if(isset($_SESSION['current-user'])){

            $email = $_SESSION['current-user']['email'];

            $query = $db->query("SELECT * FROM adminUsers WHERE email = '{$email}'")->fetch(PDO::FETCH_ASSOC);
            if ( !$query ){

                header('Location: login.php');
            }

        }else{
            header('Location: login.php');
        }

    }

}


//____________________________________________HELPERS_____________________________________________________________


//Random String Generator
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

//Turn Multiple File Input Into Array

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

//TODO: Enhance security of this function
//Basic XSS and SQLInjection eleminator
function make_safe($variable)
{
    $variable = strip_tags(trim($variable));
    return $variable;
}


//Resize Image Function
function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagejpeg($dst, $file);
    return $dst;
}