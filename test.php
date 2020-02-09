<?php

include "Dafater.php";

$dafater = new Dafater();
$dafater->authenticate();


$customer_name = rand(1000,9999) .'-'. "Test Account";
$dafater->createDocument("Customer", [
    "name" => $customer_name,
    "customer_name" => $customer_name,
    "customer_type" => "Individual",
    "territory" => "المنطقة الوسطى",
]);

$document = $dafater->getDocument("Customer");

 var_dump($document);


?>