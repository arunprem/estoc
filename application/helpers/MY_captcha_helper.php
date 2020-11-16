<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  Document   : login
  Created on : Nov 6, 2014, 11:26:05 PM
  Author     : Mukesh MR
  Description: Extending CI captcha
 */

function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '') {
    
    $defaults = array('word' => '', 'img_path' => '', 'img_url' => '', 'img_width' => '200', 'img_height' => '50', 'font_path' => '', 'expiration' => 7200, 'scale' => 2,'line_width' => 0, 'word_length'=>5,
                        'max_rotation'=>8);

    //default values//
    
   
    /** Dictionary word file (empty for random text) */
    $wordsFile = APPPATH.'third_party/captcha_resources/words/en.php';

    /**
     * Path for resource files (fonts, words, etc.)
     *
     * "resources" by default. For security reasons, is better move this
     * directory to another location outise the web server
     *
     */
    $resourcesPath = APPPATH.'third_paryt/captcha_resources';
    

    /** Min word length (for non-dictionary random text generation) */
    $minWordLength = 5;

    /**
     * Max word length (for non-dictionary random text generation)
     * 
     * Used for dictionary words indicating the word-length
     * for font-size modification purposes
     */
    $maxWordLength = 5;
    
    $word_length = 5;
    /** Sessionname to store the original text */
    $session_var = 'captcha';

    /** Background color in RGB-array */
    $backgroundColor = array(255, 255, 255);

    /** Foreground colors in RGB-array */
    $colors = array(
        array(27,78,181), // blue
        array(22,163,35), // green
        array(214,36,7),  // red
    );

    /** Shadow color in RGB-array or null */
    $shadowColor = null; //array(0, 0, 0);

    /** Horizontal line through the text */
    $lineWidth = 0;

    /**
     * Font configuration
     *
     * - font: TTF file
     * - spacing: relative pixel space between character
     * - minSize: min font size
     * - maxSize: max font size
     */
    $fonts = array(
        'Antykwa'  => array('spacing' => -3, 'minSize' => 27, 'maxSize' => 30, 'font' => 'AntykwaBold.ttf'),
        'DingDong' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 30, 'font' => 'Ding-DongDaddyO.ttf'),
        'Duality'  => array('spacing' => -2, 'minSize' => 30, 'maxSize' => 38, 'font' => 'Duality.ttf'),
        'Heineken' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 34, 'font' => 'Heineken.ttf'),
        'Jura'     => array('spacing' => -2, 'minSize' => 28, 'maxSize' => 32, 'font' => 'Jura.ttf'),
        'StayPuft' => array('spacing' =>-1.5,'minSize' => 28, 'maxSize' => 32, 'font' => 'StayPuft.ttf'),
        'Times'    => array('spacing' => -2, 'minSize' => 28, 'maxSize' => 34, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => -1, 'minSize' => 20, 'maxSize' => 28, 'font' => 'VeraSansBold.ttf'),
    );

    /** Wave configuracion in X and Y axes */
    $Yperiod    = 12;
    $Yamplitude = 14;
    $Xperiod    = 11;
    $Xamplitude = 5;

    /** letter rotation clockwise */


    /**
     * Internal image size factor (for better image quality)
     * 1: low, 2: medium, 3: high
     */
    $scale = 3;

    /** 
     * Blur effect for better image quality (but slower image processing).
     * Better image results with scale=3
     */
    $blur = false;

    /** Debug? */
    $debug = false;
    
    /** Image format: jpeg or png */
    $imageFormat = 'jpeg';


    /** GD image */
    $im;

    
    
    
    
    //////////////////
    
    
    
    
    
    
    foreach ($defaults as $key => $val) {
        if (!is_array($data)) {
            if (!isset($$key) OR $ $key == '') {
                $$key = $val;
            }
        } else {
            $$key = (!isset($data[$key])) ? $val : $data[$key];
        }
    }
    
    
    
    if ($img_path == '' OR $img_url == '') {
        return FALSE;
    }

    if (!@is_dir($img_path)) {
        return FALSE;
    }

    if (!is_writable($img_path)) {
        return FALSE;
    }

    if (!extension_loaded('gd')) {
        return FALSE;
    }

    // -----------------------------------
    // Remove old images
    // -----------------------------------

    list($usec, $sec) = explode(" ", microtime());
    $now = ((float) $usec + (float) $sec);

    $current_dir = @opendir($img_path);

    while ($filename = @readdir($current_dir)) {
        if ($filename != "." and $filename != ".." and $filename != "index.html") {
            $name = str_replace(".jpg", "", $filename);

            if (($name + $expiration) < $now) {
                @unlink($img_path . $filename);
            }
        }
    }

    @closedir($current_dir);

    // -----------------------------------
    // Do we have a "word" yet?
    // -----------------------------------

    

    //////////custom code
    $ini = microtime(true);
    
    
    
    if (!empty($im)) {
            imagedestroy($im);
        }

        $im = imagecreatetruecolor($img_width*$scale, $img_height*$scale);

        // Background color
        $GdBgColor = imagecolorallocate($im,
            $backgroundColor[0],
            $backgroundColor[1],
            $backgroundColor[2]
        );
        imagefilledrectangle($im, 0, 0, $img_width*$scale, $img_height*$scale, $GdBgColor);

        // Foreground color
        $color           = $colors[mt_rand(0, sizeof($colors)-1)];
        $GdFgColor = imagecolorallocate($im, $color[0], $color[1], $color[2]);

        // Shadow color
        if (!empty($shadowColor) && is_array($shadowColor) && sizeof($shadowColor) >= 3) {
            $GdShadowColor = imagecolorallocate($im,
                $shadowColor[0],
                $shadowColor[1],
                $shadowColor[2]
            );
        }
        $text = GetDictionaryCaptchaText($wordsFile);
        
        if (!$text) {
            $text = GetRandomCaptchaText($word_length);
        }
        
        
        $fontcfg  = $fonts[array_rand($fonts)];
        
        
        ///////////////////////////////////////
        $fontfile = APPPATH.'third_party/captcha_resources/fonts/'.$fontcfg['font'];


        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);

        // Text generation (char by char)
        $x      = 20*$scale;
        $y      = round(($img_height*27/40)*$scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++) {
            $degree   = rand($max_rotation*-1, $max_rotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize'])*$scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);

            if ($shadowColor) {
                $coords = imagettftext($im, $fontsize, $degree,
                    $x+$scale, $y+$scale,
                    $GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($im, $fontsize, $degree,
                $x, $y,
                $GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$scale);
        }

        $textFinalX = $x;
        
    if (!empty($lineWidth)) {    
        $x1 = $img_width*$scale*.15;
        $x2 = $textFinalX;
        $y1 = rand($img_height*$scale*.40, $img_height*$scale*.65);
        $y2 = rand($img_height*$scale*.40, $img_height*$scale*.65);
        $img_width = $lineWidth/2*$scale;

        for ($i = $img_width*-1; $i <= $img_width; $i++) {
            imageline($im, $x1, $y1+$i, $x2, $y2+$i, $GdFgColor);
        }
    }
        
        ////wave///
    
        $xp = $scale*$Xperiod*rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($img_width*$scale); $i++) {
            imagecopy($im, $im,
                $i-1, sin($k+$i/$xp) * ($scale*$Xamplitude),
                $i, 0, 1, $img_height*$scale);
        }

        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $scale*$Yperiod*rand(1,2);
        for ($i = 0; $i < ($img_height*$scale); $i++) {
            imagecopy($im, $im,
                sin($k+$i/$yp) * ($scale*$Yamplitude), $i-1,
                0, $i, $img_width*$scale, 1);
        }
        ////////////
        
        //blur//
        if ($blur && function_exists('imagefilter')) {
            imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        //////
        ///Reduce image////
        
        $imResampled = imagecreatetruecolor($img_width, $img_height);
        imagecopyresampled($imResampled, $im,
            0, 0, 0, 0,
            $img_width, $img_height,
            $img_width*$scale, $img_height*$scale
        );
        imagedestroy($im);
        $im = $imResampled;
        
        ///////////////////////////
        
        
        
    ////////////////////////custom code ends
    
    // -----------------------------------
    //  Generate the image
    // -----------------------------------

    $img_name = $now . '.jpg';

    ImageJPEG($im, $img_path . $img_name);

    $img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \"  />";

    ImageDestroy($im);

    return array('word' => $text, 'time' => $now, 'image' => $img);
}


// ------------------------------------------------------------------------


function GetDictionaryCaptchaText($wordsFile,$extended = false) {
        if (empty($wordsFile)) {
            return false;
        }

        // Full path of words file
        
        if (!file_exists($wordsFile)) {
            return false;
        }

        $fp     = fopen($wordsFile, "r");
        $length = strlen(fgets($fp));
        if (!$length) {
            return false;
        }
        $line   = rand(1, (filesize($wordsFile)/$length)-2);
        if (fseek($fp, $length*$line) == -1) {
            return false;
        }
        $text = trim(fgets($fp));
        fclose($fp);


        /** Change ramdom volcals */
        if ($extended) {
            $text   = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);
            $vocals = array('a', 'e', 'i', 'o', 'u');
            foreach ($text as $i => $char) {
                if (mt_rand(0, 1) && in_array($char, $vocals)) {
                    $text[$i] = $vocals[mt_rand(0, 4)];
                }
            }
            $text = implode('', $text);
        }

        return $text;
    }
    
    
    function GetRandomCaptchaText($length = 5) {
        

        $words  = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";

        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, 4), 1);
            } else {
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }