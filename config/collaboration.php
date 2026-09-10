<?php

return [
    'key' => hash_hmac('sha256', 'project-collaboration', env('APP_KEY', '')),
];
