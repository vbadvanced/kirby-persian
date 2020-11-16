<?php
require_once __DIR__ . '/helpers.php';

if (function_exists('jdate') === false) {
    // define global jdate function
    function jdate(string $format, int $timestamp = null, $numbers = 'fa') {
        return '';
        //return Helpers::dateFormat($timestamp ?? time(), $format, true, $numbers);
    }
}

if (function_exists('tn') === false) {
    // auto convert number shapes to farsi if needed
    function tn($num) {
        return Helpers::numberFormat($num, 0, null, null);
    }
}

Kirby::plugin('vbadvanced/kirby-persian', [

    'fields' => [

        'date' => [
            'extends' => 'date',
            'props' => [

                'jalali' => function (string $jalali = null) {
                    return $jalali ?? Helpers::option('jalaliCalendar', 'auto');
                },
            ]
        ]
    ],

    'fieldMethods' => [
        'toDate' => function($field, string $format = null, string $fallback = null, bool $jalali = null, bool $persianNumbers = null) {
            if (empty($field->value) === true && $fallback === null) {
                return null;
            }
            
            $time = empty($field->value) === true ? strtotime($fallback) : $field->toTimestamp();
            
            // respect field's jalali prop in panel
            if (Helpers::isPanel() === true) {
                $blueprint = $field->parent()->blueprint()->field($field->key());
                if (isset($blueprint['jalali'])) {
                    $jalali = $jalali ?? $blueprint['jalali'];
                }
            }
            
            return Helpers::dateFormat($time, $format, $jalali, $persianNumbers);
        },
        'toNumber' => function($field, int $decimals = 0, string $dec_point = null, string $thousands_sep = null) {
            if (empty($field->value) === true) {
                return null;
            }

            return Helpers::numberFormat($field->value, $decimals, $dec_point, $thousands_sep);
        }
    ],

    'tags' => [
        'date' => [
            'attr' => [],
            'html' => function ($tag) {
                return Helpers::dateFormat(
                    strtotime('now'), 
                    strtolower($tag->date) === 'year' ? 'Y' : $tag->date
                );
            }
        ],
        'jdate' => [
            'attr' => [],
            'html' => function ($tag) {
                return Helpers::dateFormat(
                    strtotime('now'), 
                    strtolower($tag->jdate) === 'year' ? 'Y' : $tag->jdate,
                    true
                );
            }
        ]
    ],

    'hooks' => [
        'kirbytext:after' => function ($text) {
            // return 'ts';
            return Helpers::textFormat($text);
        }
    ],

    'translations' => [
        'en'    => @include_once __DIR__ . '/i18n/en.php',
        'fa'    => @include_once __DIR__ . '/i18n/fa.php',
    ],

    'options' => [

        // panel specific options
        'panel' => [
            // change panel fonts for persian. set to false for no change
            // options: 'Vazir'
            // this doesn't make any change until I find a way to use config
            // options in panel!
            'font' => 'Vazir',

            // replace date fields, datepickers and displaying dates 
            // with a jalali enabled one in the panel
            // options: 'auto', true, false
            // when set to 'auto' the dates will switch between 
            // jalali and default based on selected translation
            'jalaliCalendar' => 'auto',

            // default date format
            'dateFormat' => [
                'default' => 'Y-m-d',
                'jalali' => 'Y/m/d',
            ],

            // convert english numbers to persian automatically
            'numbers' => [
                'dates' => true
            ],
        ],

        'site' => [

            // jalali dates for $field->toDate() and (date) kirbytags
            'jalaliCalendar' => 'auto',

            // default date format
            'dateFormat' => [
                'default' => 'Y-m-d',
                'jalali' => 'Y/m/d',
            ],
            
            'numbers' => [
                'dates' => true,
                'kirbytext' => true,
                'fields' => true,
                
                'decimals' => '.',
                'thousands' => ',',
            ],
        ]
    ]
]);
