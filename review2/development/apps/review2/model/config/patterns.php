<?php
$patterns = [
    'name_pattern' => [
        'regex' => '/([A-Z]{1}[a-z]{1,20})$/'
    ],
    'phone' => [
        'regex' => '/^[0-9]{9,14}$/',
        'callback' => function ($matches)  {
            printme($matches);
            $phone_res = str_pad($matches[0], 13, '+380', STR_PAD_LEFT); //добавление дополнительных символов слева, при условии недостаточного количества цифр
            if((strlen($matches[0])>=9)and(strlen($matches[0])<=14))
                return $phone_res;
        }
    ],
    'email' => [
        'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
    ]
];