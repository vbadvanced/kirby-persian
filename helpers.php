<?php
require_once __DIR__ . '/vendor/jdf/jdf.php';

class Helpers {

    public static function dateFormat($value = null, string $format = null, bool $forceJalali = null, bool $convertNumbers = null) {
        if ($value === null) {
            return null;
        }

        // if ($format === null) {
        //     return $value;
        // }
        
        $translation = static::isPanel() ? page()->translation()->code() : kirby()->language()->code();
        $convertNumbers = $convertNumbers ?? (static::option('numbers.dates') && $translation === 'fa');
        $jalali = $forceJalali ?? static::option('jalaliCalendar');
        $jalali = $jalali === 'auto' ? $translation === 'fa' : $jalali;
        $format = $format ?? ($jalali ? static::option('dateformat.jalali') : static::option('dateformat.default')); 

        if ($jalali) {
            $timezone = option('date.timezone', date_default_timezone_get());
            return JDF::jdate($format, $value, '', $timezone, $convertNumbers ? 'fa' : 'en');
        }

        $date = kirby()->option('date.handler', 'date')($format, $value);
        return $convertNumbers ? static::numbers_en2fa($date) : $date;
    }

    public static function numberFormat($num, $decimals = 0, $dec_point, $thousands_sep) {

        $translation = kirby()->language()->code();
        $dec_point = $dec_point ?? ($translation === 'fa' ? static::option('numbers.decimals') : '.');
        $thousands_sep = $thousands_sep ?? ($translation === 'fa' ? static::option('numbers.thousands') : ',');
        $convertNumbers = static::option('numbers.fields') && $translation === 'fa';

        
        if (is_numeric($num) == true) {
            $num = number_format($num, $decimals, $dec_point, $thousands_sep);
        }
        return $convertNumbers ? static::numbers_en2fa($num) : $num;
    }

    public static function textFormat( $content ) {
        if ($content === null) {
            return null;
        }

        $numbers = static::Option('numbers.kirbytext');
        $letters = static::option('letters', true);
        
        if ($numbers) {
            $content = static::numbers_en2fa( $content );
        }

        if ($letters) {
            $content = static::letters_ar2fa( $content );
        }

        return $content;
    }

    public static function isPanel() {
        return strpos(
            kirby()->request()->url()->toString(),
            kirby()->urls()->api
        ) !== false;
    }
    
    public static function language() {
        return static::isPanel() 
            ? page()->translation()->code()
            : kirby()->language()->code();
    }

    public static function option(string $key = null, $default = null) {
        $key = 'vbadvanced.kirby-persian.' . (static::isPanel() ? 'panel' : 'site') . ($key ? '.' . $key : '');
        return kirby()->option($key, $default);
    }

    public static function letters_ar2fa( $content ) {
        return str_replace( array( 'ي', 'ك', 'ة', '٤', '٥', '٦' ), array( 'ی', 'ک', 'ه', '۴', '۵', '۶' ), $content );
    }

    public static function numbers_en2fa( $content ) {
        return preg_replace_callback('/(?:&#\d{2,4};)|(\d+[\.\d]*)|(?:[a-z](?:[\x00-\x3B\x3D-\x7F]|<\s*[^>]+>)*)|<\s*[^>]+>/i',
        ['Helpers', 'numbers_to_farsi'], $content );
    }

    public static function numbers_to_farsi( $matches ) {
        $farsi_array   = array( "۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "." );
        $english_array = array( "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "." );
        if ( isset( $matches[1] ) ) {
            return str_replace( $english_array, $farsi_array, $matches[1] );
        }
        return $matches[0];
    }
    
    public static function numbers_to_english( $num ) {
        $farsi_array   = array( "۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "." );
        $english_array = array( "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "." );
    
        return str_replace( $farsi_array, $english_array, $num );
    } 
}