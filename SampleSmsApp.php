<?php
/**
 *   (C) Copyright 1997-2013 hSenid International (pvt) Limited.
 *   All Rights Reserved.
 *
 *   These materials are unpublished, proprietary, confidential source code of
 *   hSenid International (pvt) Limited and constitute a TRADE SECRET of hSenid
 *   International (pvt) Limited.
 *
 *   hSenid International (pvt) Limited retains all title to and intellectual
 *   property rights in these materials.
 */

include_once 'libs/SmsReceiver.php';
include_once 'libs/SmsSender.php';
include_once 'log.php';
ini_set('error_log', 'sms-app-error.log');

try {
    $receiver = new SmsReceiver(); // Create the Receiver object

    $content = $receiver->getMessage(); // get the message content
    $address = $receiver->getAddress(); // get the sender's address
    $requestId = $receiver->getRequestID(); // get the request ID
    $applicationId = $receiver->getApplicationId(); // get application ID
    $encoding = $receiver->getEncoding(); // get the encoding value
    $version = $receiver->getVersion(); // get the version

    logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version ]");

    $responseMsg;

    //your logic goes here......
   
    $responseMsg = "You are not in the disaster area.You are in uva wellassa";

    // Create the sender object server url
    $sender = new SmsSender("https://api.dialog.lk/sms/send");

    //sending a one message

 	$applicationId = "APP_018053";
 	$encoding = "0";
 	$version =  "1.0";
    $password = "e96a27d7703506637e21004eac747789";
    $destinationAddresses = array($address);
    
    $res = $sender->sms($responseMsg, $destinationAddresses, $password, $applicationId, $encoding, $version);

} catch (SmsException $ex) {
    //throws when failed sending or receiving the sms
    error_log("ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
}

/*
    BMI logic function
**/
function bmiLogicHere($split)
{
    if (sizeof($split) < 2) {
        $responseMsg = "Invalid message content";
    } else {
        $weight = (float)$split[0];
        $height = (float)$split[1];

        $bmi = getBMIValue($weight, ($height / 100));
        $category = getCategory($bmi);

        $responseMsg = "Your BMI :" . round($bmi, 2) . ", Category :" . $category;
    }
    return $responseMsg;
}

/*
    Get BMI value
**/

function getBMIValue($weight, $height)
{
    return ($weight / pow($height, 2));
}

/*
    Get category according to BMI value
**/

function getCategory($bmiValue)
{
    if ($bmiValue < 18.5) {
        return "Underweight";
    } else if ($bmiValue >= 18.5 && $bmiValue < 24.9) {
        return "Normal Weight";
    } else if ($bmiValue >= 25 && $bmiValue < 29.9) {
        return "Overweight";
    } else {
        return "Obesity";
    }
}

?>