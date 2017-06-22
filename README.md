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

$serializer = new XMLSerializer('root');
echo $serializer->serialize($arr);
```

### Benchmarks

I've updated this README with some benchmarks comparing this tool to several others I've since found.  You can see the script I used in examples/benchmark.  For an example XML file I'm using some real files from [here](http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/www/repository.html).  The xml file in question is about 2MB with 66K nodes.

| Library | Time |
| --- | --- |
| XMLSerializer | 0.077s |
| JMSSerializer | 0.1549s |
| PEAR XML_Serializer | 13.4742s |
| Symfony Serializer | 0.1537s |


Some thoughts:
 * There are some pretty good options for serializers afterall!
 * The JMSSerializer output converted all node names to 'entry' and added CDATA to everything.  I couldn't find anything obvious in the documention to help with this but I might have missed it.  
 * The Symfony serializer is pretty fast too, but I couldn't get it to do indenting or whitespacing.
 * The PEAR XML_Serializer class should probably be taken out back and put out of its misery.

Overall, I'm pretty happy with the performance of this serializer class so far.  I'm sure there is room for improvement too.\