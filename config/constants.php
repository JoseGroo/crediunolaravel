<?php
/**
 * Created by PhpStorm.
 * User: josemanuelguerrerosanchez
 * Date: 2020-04-30
 * Time: 17:48
 */

return [
    'regexs' => [
        'email' => "^[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$",
        'email2' => "^[a-zA-Z0-9.!#$%&’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$",
        'phone' => "^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$",
    ],
    'colors' => [
        'indigo-bg',
        'bg-secondary',
        'bg-info',
        'purple-bg',
        'bg-teal'

    ]
];