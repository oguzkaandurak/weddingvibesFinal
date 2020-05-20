<?php

include "master/dbMaster.php";
$resultText = "";
if (isset($_GET["message"]) && isset($_GET["spID"]) && isset($_GET["uID"])) {

    $message = $_GET["message"];
    $spID = $_GET["spID"];
    $uID = $_GET["uID"];



    $queryInsert = $db->prepare("INSERT INTO comments SET serviceProviderId = ?, userId = ?, comment = ?");
    $insert = $queryInsert->execute(array($spID, $uID, $message));
    if ( $insert ){

        $query = $db->query("SELECT * FROM comments WHERE serviceProviderId = ".$spID, PDO::FETCH_ASSOC);


        foreach ($query as $row) {

            $q2 = $db->prepare('SELECT * FROM users WHERE id = ?');
            $q2->execute(array($row["userId"]));
            $results = $q2->fetch();


            $resultText .= "<div class=\"col-md-12\" >
                                        <hr>
                                        <h1 style=\"font-size: 15px; padding-bottom: 15px;\">".$results["name_surname"]."</h1>
                                        <span style=\"margin: 10px; color: #888888;\">".$row["comment"]."</span>

                                    </div>";
        }

    }

    echo $resultText;



}