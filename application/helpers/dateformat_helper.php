<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('dateformat')) {

    function dateFormat($format = 'M d Y', $givenDate = null) {
        return date($format, strtotime($givenDate));
    }

}

if (!function_exists('isDate')) {

    function isDate($value) {
        if (!$value) {
            return false;
        }

        try {
            new DateTime($value);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
if (!function_exists('toYmd')) {

    function toYmd($value) {
        if (!$value) {
            return false;
        }
        try {
            $value = str_replace("/","-",$value);            
            $format = 'Y-m-d';
            $d = new DateTime($value);
            return $d->format($format);
        } catch (Exception $e) {
            return false;
        }
    }

}

if (!function_exists('todmY')) {

    function todmY($value) {
        if (!$value) {
            return '';
        }
        try {          
            $format = 'd-m-Y';
            $d = new DateTime($value);
            return $d->format($format);
        } catch (Exception $e) {
            return false;
        }
    }

}

if (!function_exists('todmYSlash')) {

    function todmYSlash($value) {
        if (!$value) {
            return '';
        }
        try {          
            $format = 'd/m/Y';
            $d = new DateTime($value);
            return $d->format($format);
        } catch (Exception $e) {
            return false;
        }
    }

}

