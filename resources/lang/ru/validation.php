<?php

return [
    'unique'     => 'Такое значение поля :attribute уже используется',
    'custom'     => [
        'email'     => [
            'unique' => 'Этот E-mail уже зарегистрирован',
        ],
        'password'  => [
            'min' => 'Пароль должен иметь длину не менее :min символов',
            'confirmed' => 'Пароль и подтверждение не совпадают'

        ],
    ],
    'attributes' => [
        'email' => 'E-mail',
    ],
];
