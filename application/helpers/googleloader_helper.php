<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('GoogleClientLoader'))
{
    function GoogleClientLoader()
    {
      require_once APPPATH . "libraries/google-api-php-client/src/Google/autoload.php";
      include_once APPPATH . "libraries/google-api-php-client/src/Google/Client.php";
      include_once APPPATH . "libraries/google-api-php-client/src/Google/Service/Oauth2.php";
    }

}

