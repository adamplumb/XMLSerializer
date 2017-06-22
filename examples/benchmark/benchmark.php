<?php

require 'vendor/autoload.php';

$url = "http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/uwm.xml";
$filename = "test.xml";
if (is_file($filename)) {
    $contents = file_get_contents($url);
} else {
    $contents = file_get_contents($url);
    file_put_contents($filename, $contents);
}


// Removes single-line comments which seem to cause issues with the simplexml loader
$contents = preg_replace("/\<\!\-\-(.*?)\-\-\>/", "", $contents);


// Convert the XML string to SimpleXMLElements
$xml = simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);

// Convert that to an associative array (there must be a better way)
$arr = json_decode(json_encode($xml), true);

// My Serializer
require_once '../../XMLSerializer.php';
$serializer = new XMLSerializer('root');
$start = microtime(true);
$output = $serializer->serialize($arr);
file_put_contents("XMLSerializer-output.xml", $output);
unset($serializer);
$end = microtime(true);
$xmlserializer_time = round($end - $start, 4);

// JMS Serializer
$serializer = JMS\Serializer\SerializerBuilder::create()->build();
$start = microtime(true);
$output = $serializer->serialize($arr, 'xml');
file_put_contents("JMSSerializer-output.xml", $output);
unset($serializer);
$end = microtime(true);
$jms_time = round($end - $start, 4);



// PEAR XML Serializer
$serializer = new XML_Serializer(array(
    'indent'    => '    ',
    'linebreak' => "\n",
    'typeHints' => false,
    'addDecl'   => true,
    'rootName'  => 'Response',
    'attributesArray' => '@attributes',
    'mode'      => 'simplexml'
));
$start = microtime(true);
$serializer->serialize($arr);
$output = $serializer->getSerializedData();
file_put_contents("PEAR_XML_Serializer-output.xml", $output);
unset($serializer);
$end = microtime(true);
$pear_time = round($end - $start, 4);



// Symfony Serializer
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
$encoders = array(new XmlEncoder());
$normalizers = array(new ObjectNormalizer());
$serializer = new Serializer($normalizers, $encoders);
$start = microtime(true);
$output = $serializer->serialize($arr, 'xml');
file_put_contents("SymfonyXMLSerializer-output.xml", $output);
unset($serializer);
$end = microtime(true);
$symfony_time = round($end - $start, 4);

print "XMLSerializer:          {$xmlserializer_time}s\n";
print "JMSSerializer:          {$jms_time}s\n";
print "PEAR XML_Serializer:    {$pear_time}s\n";
print "Symfony Serializer:     {$symfony_time}s\n";