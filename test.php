<?php

include "Dafater.php";

$dafater = new Dafater();
$dafater->authenticate();

$company = "شركة الحياة للتجزئة";
//
$customer_name = 1224 .'-'. "Test Account";
$dafater->createDocument("Customer", [
    "name" => $customer_name,
    "customer_name" => $customer_name,
    "customer_type" => "Individual",
    "company" => $company,
    "territory" => "المنطقة الوسطى",
]);

$document = $dafater->getDocument("Customer");

 var_dump($document);


?>