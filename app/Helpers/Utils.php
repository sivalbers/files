<?php

if (!function_exists('verketten')) {
    function verketten($m1, $m2, $m3) {
        $string = "";

        if (!empty($m1)) $string .= $m1;
        if (!empty($m2)) $string .= "-" . $m2;
        if (!empty($m3)) $string .= "-" . $m3;

        return $string;
    }
}
