<?php
/**
 * Validator Module
 * 
 * Provides validation functions for user inputs using namespaces, REGEX, 
 * built-in PHP filter functions, and exception handling.
 */

namespace lastname_validator;

/**
 * Validates the Name field using REGEX.
 * Expects format "Lastname, Firstname" (Lastname >= 2 chars, Firstname >= 1 char).
 *
 * @param string $val The input string passed by value.
 * @return string Error message if invalid, or empty string if valid.
 */
function validateName($val) {
    if (empty(trim($val))) {
        return 'Required Entry';
    }
    
    // REGEX: At least 2 word characters, a comma, optional spaces, and at least 1 word character
    $pattern = '/^[A-Za-z]{2,},\s*[A-Za-z]{1,}$/';
    if (!preg_match($pattern, trim($val))) {
        return 'Must be formatted as "Lastname, Firstname" (Lastname >= 2 chars, Firstname >= 1 char)';
    }
    
    return '';
}

/**
 * Validates Date of Birth using control logic and passes error message back BY REFERENCE.
 * Expects format MM/DD/YYYY and valid calendar date.
 *
 * @param string $val The input date string passed by value.
 * @param string &$error_msg Variable passed by reference to store error message.
 * @return void
 */
function validateDOB($val, &$error_msg) {
    if (empty(trim($val))) {
        $error_msg = 'Required Entry';
        return;
    }
    
    // Split date into parts
    $parts = explode('/', trim($val));
    if (count($parts) === 3) {
        $month = (int)$parts[0];
        $day   = (int)$parts[1];
        $year  = (int)$parts[2];
        
        // Control logic to check if valid date
        if (checkdate($month, $day, $year) && strlen($parts[2]) === 4) {
            $error_msg = '';
            return;
        }
    }
    
    $error_msg = 'Must be a valid date formatted as MM/DD/YYYY';
}

/**
 * Validates Email Address using PHP built-in filter format validation.
 *
 * @param string $val Email input value.
 * @return string Error message if invalid, or empty string if valid.
 */
function validateEmail($val) {
    if (empty(trim($val))) {
        return 'Required Entry';
    }
    
    if (!filter_var(trim($val), FILTER_VALIDATE_EMAIL)) {
        return 'Must be a valid email address format (e.g. name@domain.com)';
    }
    
    return '';
}

/**
 * Validates Favorite Integer using Exception handling (try-catch).
 *
 * @param string $val Input value to check for integer.
 * @throws \Exception If value is empty or not an integer.
 * @return string Returns empty string if valid.
 */
function validateInteger($val) {
    if (empty(trim($val))) {
        throw new \Exception('Required Entry');
    }
    
    if (!filter_var(trim($val), FILTER_VALIDATE_INT) && trim($val) !== '0') {
        throw new \Exception('Must be a valid integer value');
    }
    
    return '';
}

/**
 * Validates optional Nickname using simple control logic.
 *
 * @param string $val Optional nickname input string.
 * @return string Error message if invalid, or empty string if valid.
 */
function validateNickname($val) {
    $trimmed = trim($val);
    
    // Nickname is optional, but if entered must be at least 2 characters
    if (strlen($trimmed) > 0 && strlen($trimmed) < 2) {
        return 'If entered, must contain at least 2 characters';
    }
    
    return '';
}
