
<?php
define('BASE_URL', 
    (isset($_SERVER['HTTPS']) ? 'https' : 'http') . 
    '://' . $_SERVER['HTTP_HOST']
);
?>