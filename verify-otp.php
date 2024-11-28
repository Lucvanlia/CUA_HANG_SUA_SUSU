<?php


// verify-otp.php
session_start();

$enteredOtp = $_POST['otp'];
if ($enteredOtp == $_SESSION['otp']) {
    echo 'OTP verified';
} else {
    echo 'Invalid OTP';
}
