<?php

/**
 * include the composer libraries
 */
include_once "vendor/autoload.php";

/**
 * Establish the connection information.
 */
$VirtualMin = new VirtualMin("https://localhost:1000", "root", "somepassword");

// for a simple domain list
$domains = $VirtualMin->action("list-domains", array());

// or to show domain list with details
$domains = $VirtualMin->action("list-domains", array("multiline"=>""));

/**
 * Trap any issues....
 */
if(!$domains) {
    die("We have an error: " . $VirtualMin->lastError . ", Code: " . $VirtualMin->lastErrorCode);
}

print_r($domains);