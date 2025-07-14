<?php

return [
    'unique'     => 'Такое значение поля :attribute уже используется.',
    'custom'     => [
        'email'     => [
            'unique' => 'Этот E-mail уже зарегистрирован.',
        ],
        'password'  => [
            'min' => 'Пароль должен содержать минимум :min символов.',
            'confirmed' => 'Пароли не совпадают'

        ],
    ],
    'attributes' => [
        'email' => 'E-mail',
    ],
];
