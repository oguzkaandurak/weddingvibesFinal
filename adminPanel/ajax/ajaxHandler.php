<?php
include_once "../include/dbconnection.php";
if (isset($_POST["imageID"])){

    $imageID = $_POST["imageID"];

    $query = $db->prepare("DELETE FROM productimages WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $imageID
    ));
    if ($delete){
        echo $delete;
    }

}