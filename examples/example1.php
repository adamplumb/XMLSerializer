<?php

require_once '../XMLSerializer.php';

$arr = array(
    'Person' => array(
        array('Name' => 'Adam', 'Team' => 'Blue', '@attributes' => array('sex' => 'male')), 
        array('Name' => 'Jamie', 'Team' => 'Red', '@attributes' => array('sex' => 'female'))
    )
);

$serializer = new XMLSerializer('root');
echo $serializer->serialize($arr);