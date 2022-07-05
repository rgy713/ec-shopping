<?php

/**
 * @param $str
 * @param int $l
 * @return array|array[]|false|string[]
 */
function str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

/**
 * @param $args
 * @param $length
 * @return string
 */
function textWrapping($args, $length)
{
    if(isset($length))
        return implode(" ", str_split_unicode($args, $length));
    else
        return $args;
}

/**
 * @param $path
 * @return false|string
 */
function get_contents($path)
{
    return file_get_contents(storage_path('app/views/'.str_replace('.', '/', $path)).'.blade.php');
}

function get_contents_html($path)
{
    $contents = get_contents($path);
    return nl2br($contents);
}

/**
 * @param $email
 * @param $index
 * @return mixed
 */
function explode_email($email, $index)
{
    if(isset($email) && ($index==0 || $index==1)){
        return explode("@", $email)[$index];
    }
    else{
        return $email;
    }
}