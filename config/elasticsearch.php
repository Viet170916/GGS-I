<?php
return [
    'hosts' => [ env( 'ELASTIC_HOST', 'https://localhost:9200' ), ],
    'username' => env( 'ELASTIC_USER_NAME', 'elastic' ),
    'password' => env( 'ELASTIC_PASSWORD' ),
    'caBundle' => env( 'ELASTIC_CA_BUNDLE' ),
    'cloudId' => env( 'ELASTIC_CLOUD_ID' ),
    'apiKey' => env( 'ELASTIC_API_KEY' ),
];

