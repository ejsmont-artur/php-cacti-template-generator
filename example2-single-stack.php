<?php
require_once 'Generator.php';
use Ejsmont\Artur\Cacti\TemplateGenerator\Generator;

$def = array(
    // define Data Template and Data Input Method
    "dsName" => 'Example 2 Data', // used for Data Template and Data Input Method
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
        // stacks with total
        array(
            "unitLabel" => "percent",
            "name" => "Example 2 CPU",
            "lines" => array(
                // rrdtool requires AREA as first item before you can add STACK items, first fields shows at the bottom
                "percent_idle_g" => array("Idle", Generator::ITEM_TYPE_AREA, Generator::C_GREEN_E),
                "percent_system_g" => array("System", Generator::ITEM_TYPE_STACK, Generator::C_YELLOW),
                "percent_user_g" => array("User", Generator::ITEM_TYPE_STACK, Generator::C_PURPLE),
                // last stack field shows at the top
                "percent_waiting_g" => array("Waiting", Generator::ITEM_TYPE_STACK, Generator::C_RED),
                // Total added by adding just one line as below
                "total" => array(),
            ),
        ),
    ),
);

// execute the generator
$generator = new Generator();
$xml = $generator->generateXml($def);
echo $xml;
