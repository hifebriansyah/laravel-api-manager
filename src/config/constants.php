<?php

//response
define('BLANK', 0);
define('SUCCESS', 1);
define('INVALID_CREDENTIAL', 2);
define('VALIDATION_ERRORS', 3);
define('API_ERRORS', 4);
define('UNCONFIRMED_EMAIL', 5);
define('INVALID_ACCESS', 6);
define('UNKNOWN_ERRORS', 10);

//setting for token
define('TOKEN_TIME', 60);
define('TOKEN_KEY', '1234567812345678');
define('DEBUG_TOKEN_KEY', 'debug');

//setting
define('PER_PAGE', 30);
define('INDEX', 1);

//level
define('ADMIN_LEVEL_ID', 1);
define('MEMBER_LEVEL_ID', 2);

//date
date_default_timezone_set('GMT');
define('NOW', time());
define('TODAY', gmdate('Y-m-d H:i:s'));