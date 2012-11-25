<?php

require_once 'Generator.php';

use Ejsmont\Artur\Cacti\TemplateGenerator\Generator;

$def = array(
    "dsName" => 'System Data',
    "inputUri" => '/stats/stats_vmstat.php',
    "fields" => array(
        "load_avg_1g" => array("input" => "load_avg_1", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "load_avg_5g" => array("input" => "load_avg_5", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "load_avg_15g" => array("input" => "load_avg_15", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_user" => array("input" => "percent_user", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_system" => array("input" => "percent_system", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_idle" => array("input" => "percent_idle", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
        "percent_waiting" => array("input" => "percent_waiting", "type" => Generator::DATA_SOURCE_TYPE_GAUGE),
    ),
    'graphs' => array(
        // =================================== SINGLE LINES DIFFERENT COLORS =========================================
        // 
        // Simple Single Line as Area
        array(
            "unitLabel" => "requests",
            "name" => "Internal Web Service 1 - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_AREA, Generator::C_BLUE),
                "edge" => array(Generator::C_BLUE_E),
            ),
        ),
        // Simple Single Line as Area
        array(
            "unitLabel" => "requests",
            "name" => "Internal Web Service 2 - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_AREA, Generator::C_GREEN),
                "edge" => array(Generator::C_GREEN_E),
            ),
        ),
        // Simple Single Line as Area
        array(
            "unitLabel" => "requests",
            "name" => "Internal Web Service 3 - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_AREA, Generator::C_YELLOW),
                "edge" => array(Generator::C_YELLOW_E),
            ),
        ),
        // Simple Single Line as Area
        array(
            "unitLabel" => "requests",
            "name" => "Internal Web Service 4 - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_AREA, Generator::C_PURPLE),
                "edge" => array(Generator::C_PURPLE_E),
            ),
        ),
        // Simple Single Line as Area
        array(
            "unitLabel" => "requests",
            "name" => "Internal Web Service 5 - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_AREA, Generator::C_RED),
                "edge" => array(Generator::C_RED_E),
            ),
        ),
        // ==================================== STACKS DIFFERENT COLOR SETS ===========================================

        array(
            "unitLabel" => "processes in queue",
            "name" => "System - Load",
            "lines" => array(
                "load_avg_15g" => array("15 min average", Generator::ITEM_TYPE_AREA, Generator::C_YELLOW),
                "load_avg_5g" => array("5 min average", Generator::ITEM_TYPE_STACK, Generator::C_ORANGE),
                "load_avg_1g" => array("1 min average", Generator::ITEM_TYPE_STACK, Generator::C_RED),
                "total" => array(),
            ),
        ),
        // stacks with total
        array(
            "unitLabel" => "percent",
            "name" => "System - CPU",
            "lines" => array(
                "percent_idle" => array("Idle", Generator::ITEM_TYPE_AREA, Generator::C_GREEN_E),
                "percent_system" => array("System", Generator::ITEM_TYPE_STACK, Generator::C_YELLOW),
                "percent_user" => array("User", Generator::ITEM_TYPE_STACK, Generator::C_PURPLE),
                "percent_waiting" => array("Waiting", Generator::ITEM_TYPE_STACK, Generator::C_RED),
                "total" => array(),
            ),
        ),
        // ========================================== MULTI LINES =====================================================
        
        // Multiple lines to compare without total
        array(
            "unitLabel" => "requests",
            "name" => "Web Services - Requests",
            "lines" => array(
                "percent_system" => array("User Service", Generator::ITEM_TYPE_LINE2, Generator::C_PURPLE),
                "percent_idle" => array("Product Service", Generator::ITEM_TYPE_LINE2, Generator::C_SEE),
                "percent_user" => array("Checkout Service", Generator::ITEM_TYPE_LINE2, Generator::C_ORANGE),
            ),
        ),
        // Simple Single Thick Line
        array(
            "unitLabel" => "requests",
            "name" => "Admin Web Service - Requests",
            "lines" => array("percent_user" => array("Internal Service", Generator::ITEM_TYPE_LINE3, Generator::C_BLUE),),
        ),
    ),
);

// ===================================================================================================================

$generator = new Generator();
$xml = $generator->generateXml($def);
echo $xml;
