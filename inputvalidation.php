<?php

function validateInput($name, $matricno, $email, $curraddress, $homeaddress, $mobilephone, $homephone) {
    $errors = [];

    // Validation using regular expressions
    $namePattern = '/^[A-Za-z\s]+$/';
    $emailPattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';
    $phonePattern = '/^01\d-\d{7}$/';
    $matricnoPattern = '/^\d{7}$/';
    $addressPattern = '/^[A-Za-z0-9/\s,\-.]+$/';


    // Validate name
    if (!preg_match($namePattern, $name)) { 
        // Store error message  
        $errors['firstname'] = "Please enter a valid first name.";
    }

    if (!preg_match($emailPattern, $email)) {
        $errors['email'] = "Please follow this format: example@example.com";
    } 

    if(!preg_match($phonePattern, $mobilephone)) {
        $errors['mobilephone'] = "Please enter a valid phone number.";
    }

    if(!preg_match($phonePattern, $homephone)) {
        $errors['homephone'] = "Please enter a valid phone number.";
    }   

    if(!preg_match($matricnoPattern, $matricno)) {
        $errors['matricno'] = "Please enter a valid matric number.";
    }

    if(!preg_match($addressPattern , $curraddress)) {
        $errors['curraddress'] = "Please enter a valid current address.";
    }   

    if(!preg_match($addressPattern , $homeaddress)) {
        $errors['homeaddress'] = "Please enter a valid home address.";
    }



    return $errors;
}

?>
