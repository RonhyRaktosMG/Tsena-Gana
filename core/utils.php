
<?php

require_once  __DIR__ . '/config.php';

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

?>