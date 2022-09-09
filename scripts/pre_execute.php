<?php

/*
 * This script execute in `ModuleInstaller` class
 */

if (!function_exists('dieWithMessage')) {

    function dieWithMessage($message)
    {
        echo '<p class="error">' . $message . '</p>';
        die('<script>document.getElementById("displayLog").style.display=""</script>');
    }
}

/** @var ModuleInstaller $this */
$minVersions = [
    '/(7\.10\.[2-9])/', // for >=7.10.2
    '/(7\.10\.\d{2})/', // for >=7.10.2
    '/(7\.1[12]\.\d+)/', // for >=7.11.0 and // for >= 7.12.0
];

global $suitecrm_version;

if (empty($suitecrm_version)) {
    global $sugar_config;
    $suitecrm_version = $sugar_config['suitecrm_version'];
}

$avaibleSugarVersion = false;
foreach ($minVersions as $minVersion) {
    if (preg_match($minVersion, $suitecrm_version)) {
        $avaibleSugarVersion = true;
        break;
    }
}

if (!$avaibleSugarVersion) {
    dieWithMessage('Not supported SuiteCRM version [' . $suitecrm_version . ']. Support above 7.10.2');
}

foreach (['Color', 'TableCssGenerator', 'Format'] as $class) {
    if (!class_exists('SuiteCRM\\Custom\\Utility\\' . $class)) {
        dieWithMessage('You mast install Utility package (http://...)');
    }
}
