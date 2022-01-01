<?php

/**
 * Check if the value has only numbers.
 *
 * @param  string $value The value to
 * @return int|false Returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.
 */
function checkNumbersOnly($value)
{
    return preg_match('/^([0-9]*)$/', $value);
}

/**
 * Get the error message from PDOException depending of error code.
 *
 * @param  PDOException $e The object PDOException.
 * @return string Return the message error from PDOException.
 */
function getErrorMessage($e)
{
    $message = "";

    /*
        0	SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
        1	Driver-specific error code.
        2	Driver-specific error message.
    */
    if (!empty($e->errorInfo[1])) {
        switch($e->errorInfo[1]) {
            case 1062: $message = "This registry is already exists."; break;
            case 1451: $message = "Registry with related data."; break;
            default: $message = $e->errorInfo[1] . ": " . $e->errorInfo[2];

        }
    } else {
        switch($e->getCode()) {
            case 1044: $message = "Database error: User or password incorrect."; break;
            case 1049: $message = "Database error: Unknown database."; break;
            case 2002: $message = "Database error: Server not found."; break;
            default: $message = $e->getCode() . ": " . $e->getMessage();
        }
    }

    return $message;
}
