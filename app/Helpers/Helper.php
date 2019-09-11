<?php

if (!function_exists('pr')) {
    function pr($str)
    {
        echo "<pre>".print_r($str, true)."</pre>";
    }
}
