<?php

require_once './vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Messages {
    protected $database;
    protected $dbname = 'message';

    public function __construct(){
        $acc = ServiceAccount::fromJsonFile(__DIR__ . '/weddingvibesctis-6c52e34f75a2.json');
        $firebase = (new Factory)->withServiceAccount($acc)->create();

        $this->database = $firebase->getDatabase();
        $this->auth = $firebase->getAuth();
    }

    public function get($array = NULL){
        if (empty($userID) || !isset($userID)) { return FALSE; }

        $receiverID = $array[0];
        $myID = $array[1];
        return $this->database->getReference($this->dbname)->getChildKeys();


        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){
            return $this->database->getReference($this->dbname)->getChild($userID)->getValue();
        } else {
            return FALSE;
        }

    }

    public function getAll(array $data){



        

        try {
            $conversationIDs = $this->database->getReference($this->dbname)->getChildKeys();
             $controller1 = false;
            $controller2 = false;

            for ($i = 0;$i<count($conversationIDs);$i++){

                $messages = $this->database->getReference($this->dbname)->getChild($conversationIDs[$i])->getValue();



                foreach ($messages as $row){

                    if ($row["idSender"] == $data[0]){
                        $controller1 = true;
                    }
                    if ($row["idSender"] == $data[1]){

                        $controller2 = true;
                    }



                }

                if($controller1&&$controller2){
                    return $messages;
                }else{
                    return null;
                }

            }

        }catch(OutOfRangeException $e){
            //hata var ise burada yakalanır
            //echo "mesaj -> ".$e->getMessage(); //hata çıktısı üretilir
        }


       
    }


    public function insert(array $data) {
        if (empty($data) || !isset($data)) { return FALSE; }

        foreach ($data as $key => $value){
            $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
        }

        return TRUE;
    }


    public function delete(int $userID) {
        if (empty($userID) || !isset($userID)) { return FALSE; }

        if ($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){
            $this->database->getReference($this->dbname)->getChild($userID)->remove();
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
