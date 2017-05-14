<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function readMoreHelper($string) {
        $new_short_string = substr($string, 0, strpos($string, '<div class="hidden">read more</div>'));

        if(!empty($new_short_string)){
            $string = $new_short_string;
        }

        return $string;
    }
}