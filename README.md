# XMLSerializer
A simple fast xml serializer for PHP.  Modify the class itself to change behavior.

The standard [PEAR XML_Serializer](https://pear.php.net/package/XML_Serializer) works great but on largish XML files (>100KB) it tends to run slowly.  On an example 300KB file I had, it took 20s to serialize the structure back to an XML string, whereas with this class it takes a tiny fraction of a second (~0.04s).

# Usage

### Example 1

*Code*

```
require_once 'XMLSerializer.php';

$arr = array(
    'Person' => array(
        array('Name' => 'Adam', 'Team' => 'Blue', '@attributes' => array('sex' => 'male')), 
        array('Name' => 'Jamie', 'Team' => 'Red', '@attributes' => array('sex' => 'female'))
    )
);

$serializer = new XMLSerializer('root');
echo $serializer->serialize($arr);
```

*Output*

```
<?xml version="1.0"?>
<root>
    <Person sex="male">
        <Name>Adam</Name>
        <Team>Blue</Team>
    </Person>
    <Person sex="female">
        <Name>Jamie</Name>
        <Team>Red</Team>
    </Person>
</root>
```

### Example 2

*Code*

```
require_once 'XMLSerializer.php';

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

$serializer = new XMLSerializer('Response');
echo $serializer->serialize($arr);
```