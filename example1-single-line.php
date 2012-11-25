<?php
require_once 'Generator.php';
use Ejsmont\Artur\Cacti\TemplateGenerator\Generator;

$def = array(
    // define Data Template and Data Input Method
    "dsName" => 'Example System Data 1', // used for Data Template and Data Input Method
    "inputUri" => '/stats/stats_vmstat.php', // used for Data Input Method
    "fields" => array(
        // Internal Data Source Name => ( filed name in the input script, Data Source Type)
        // Internal name has to be no more than 19 chars, i usually append _g, _c or _d to indicate type
        "percent_user_g" => array("input" => "percent_user", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_system_g" => array("input" => "percent_system", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_idle_g" => array("input" => "percent_idle", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_waiting_g" => array("input" => "percent_waiting", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
    ),
    'graphs' => array(
        // Simple Single Line as Area
        array(
            // Vertical Label on the graph
            "unitLabel" => "requests",
            // used for graph name and title
            "name" => "Example 1 Web Service Metric",
            "lines" => array(
                // Internal Data Source Name => Label, Item type (area/line1-3/stack), Color)
                "percent_system_g" => array("Example Metric", Generator::ITEM_TYPE_AREA, Generator::C_BLUE),
                // Special type of item that draws extra line without label - just to increase contrast on the graph
                "edge" => array(Generator::C_BLUE_E),
            ),
        ),
    ),
);

// execute the generator
$generator = new Generator();
$xml = $generator->generateXml($def);
echo $xml;
