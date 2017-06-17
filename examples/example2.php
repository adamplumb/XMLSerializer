<?php

require_once '../XMLSerializer.php';

$str = '<?xml version="1.0"?>
<root>
    <Person sex="male">
        <Name>Adam</Name>
        <Team>Blue</Team>
    </Person>
    <Person sex="female">
        <Name>Jamie</Name>
        <Team>Red</Team>
    </Person>
</root>';

// Convert the XML string to SimpleXMLElements
$xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

// Convert that to an associative array (there must be a better way)
$arr = json_decode(json_encode($xml), true);

$serializer = new XMLSerializer('root');
echo $serializer->serialize($arr);