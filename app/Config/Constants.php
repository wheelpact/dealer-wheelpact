<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);


/* wheelpact main site path */

define('MAIN_SITE_PATH', 'https://www.wheelpact.com/');

define("WHEELPACT_VEHICLE_UPLOAD_IMG_PATH", "https://www.wheelpact.com/public/uploads/");

define("NO_IMAGE_AVAILABLE", "https://www.wheelpact.com/public/uploads/image_not_available.jpg");

define('VEHICLE_TYPE', [
    1 => 'Car',
    2 => 'Bike',
    3 => 'Car & Bike Both'
]);

define('BRANCH_TYPE', [
    0 => 'All',
    1 => 'Main Branch',
    2 => 'Sub Branch'
]);

define('INSURANCE_TYPE', [
    1 => 'Third Party',
    2 => 'Comprehensive / Zero Debt'
]);

define('OWNER_TYPE', [
    1 => '1st',
    2 => '2nd',
    3 => '3rd',
    4 => '3+'
]);

define('CAR_AIRBAGS', [
    1 => 'None',
    2 => '1',
    3 => '2',
    4 => '3',
    5 => '4',
    6 => '5',
    7 => '6',
    8 => '7',
    9 => '7+'
]);

define('CENTERLOCK_TYPE', [
    1 => 'None',
    2 => 'Key',
    3 => 'Keyless'
]);

define('HEADLAMPS_TYPE', [
    1 => 'LED',
    2 => 'Halogen'
]);

define('BIKE_START_TYPE', [
    1 => 'Electric Start',
    2 => 'Kick Start',
    3 => 'Electric + KickStart'
]);

define('BIKE_ODOMETER', [
    1 => 'Digital',
    2 => 'Analog'
]);


define('YES_NO_OPTIONS', [
    1 => 'Yes',
    2 => 'No'
]);

define('GENDER', [
    1 => 'Male',
    2 => 'Female',
    3 => 'Transgender',
    4 => 'Choose Not To Tell',
]);


define('RAZP_TOKEN', [
    'key_id' => 'rzp_test_KH6djyMTqNWc5b',
    'key_secret' => '7LYkGl4uz16cUmQQG8qpAFix'
]);

define('UPLOAD_IMG_WIDTH', '1024');
define('UPLOAD_IMG_HEIGHT', '800');

define('DEFAULT_IMG', '../default-img.png');

define('FROM_EMAIL', 'no-reply@wheelpact.com');
define('FROME_NAME', 'wheelpact.com');

/* test mode details start */
define('RZP_KEY', 'rzp_test_KH6djyMTqNWc5b');
define('RZP_SECRET', '7LYkGl4uz16cUmQQG8qpAFix');
/* test mode details ednd */


$httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

define("SERVER_SITE_PATH", "http://" . $httpHost . '/');
define("SERVER_ROOT_PATH_UPLOADS", SERVER_SITE_PATH . 'uploads/');
define("SERVER_ROOT_PATH_ASSETS", SERVER_SITE_PATH . 'assets/');

define('SERVER_ROOT_PATH_DEFAULTIMAGE', SERVER_SITE_PATH . 'assets/admin/src/images/default-img.png');


define('ORDER_PREFIX', 'WP-ORD-');
define('FREE_TRIAL_DAYS', '30');

/* default values*/
define('RESERVATION_PERCENT', 0.10);
define('INTEREST_RATE', 12);
define('EMI_TENURE', 15);


/* drop dpwn value for PromotionPage */
define('PROMTION_TYPE', [
    1 => 'Featured',
    2 => 'On-Sale'
]);

define('TEST_DRIVE_SLOTS', [
    1 => 'Morning (11 am - 1 pm)',
    2 => 'Afternoon (1 pm - 4 pm)',
    3 => 'Evening (4 pm - 8 pm)'
]);

define('WP_DEALER_JWT_TOKEN', 'K2ARaauESDawjYHp98dN5h6mrnpUWkP2KDCj');
