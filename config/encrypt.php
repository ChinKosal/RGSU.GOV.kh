<?php

return [
    'secret_key' => file_get_contents(base_path('public_key.pem')),
    'secret_iv' => file_get_contents(base_path('private_key.pem')),
];