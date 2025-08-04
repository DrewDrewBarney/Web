<?php



// returns the index value or false if the index does not exist
function safelyIndex($key, $array){
    if (array_key_exists($key, $array)){
        return $array[$key];
    } else {
        return false;
    }
}

