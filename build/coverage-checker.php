<?php
/**
 * Geocoder (http://geocoder-php.org)
 *
 * @see       https://github.com/geocoder-php/GeocoderModule for the canonical source repository
 * @copyright Copyright (c) 2016, Julien Guittard <julien.guittard@me.com>
 * @license   https://github.com/geocoder-php/GeocoderModule/blob/master/LICENSE.md New BSD License
 */

$inputFile = $argv[1];
$percentage = min(100, max(0, (int) $argv[2]));
if (!file_exists($inputFile)) {
    throw new InvalidArgumentException('Invalid input file provided');
}
if (!$percentage) {
    throw new InvalidArgumentException('An integer checked percentage must be given as second parameter');
}
$xml = new SimpleXMLElement(file_get_contents($inputFile));
/* @var $metrics SimpleXMLElement[] */
$metrics = $xml->xpath('//metrics');
$totalElements = 0;
$checkedElements = 0;
foreach ($metrics as $metric) {
    $totalElements   += (int) $metric['elements'];
    $checkedElements += (int) $metric['coveredelements'];
}
$coverage = round(($checkedElements / $totalElements) * 100);
if ($coverage < $percentage) {
    echo 'Code coverage is ' . $coverage . '%, which is below the accepted ' . $percentage . '%' . PHP_EOL;
    exit(1);
}
echo 'Code coverage is ' . $coverage . '% - OK!' . PHP_EOL;