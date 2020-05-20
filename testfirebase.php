<?php

include "secret/User.php";


$users = new Users();

$currentUser = $users->createUser("okd9@okd9.com","123456");
print "<pre>";
print_r($currentUser);
print "</pre>";