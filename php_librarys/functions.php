<?php 
//Check if value has only numbers.
function numbers_only($value)
{
    return preg_match('/^([0-9]*)$/', $value);
}
