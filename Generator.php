<?php

namespace Ejsmont\Artur\Cacti\TemplateGenerator;

/**
 * Simple class that allows you to generate cacti input methods, data templates and graph templates.
 * All you have to do is create a assoc array with declaration of inputs/graphs and pass it into generateXml.
 * 
 * XML create is ready for import, you may need cacti 0.8.8 but if you changed version constant it should 
 * let you generate older templates as i dont think they change that much (did not test though).
 * 
 * License: You are free to use this code for any purpose, but if you redistribute please include original link and author.
 * @link http://artur.ejsmont.org/blog/content/cacti-graphs-generator-class-for-custom-metrics-import
 * @author Artur Ejsmont
 * @since 2012-11-25 
 */
class Generator {
    // data types

    const DATA_SOURCE_TYPE_GAUGE = 1; // gauge is a value like percent, temperature etc, you measure current value
    const DATA_SOURCE_TYPE_COUNTER = 2; // for always increasing values
    const DATA_SOURCE_TYPE_DERIVE = 3; //  for always increasing values but handles decrease like value reset or overflow
    const DATA_SOURCE_TYPE_ABSOLUTE = 4; // ?
    // graph item types
    const ITEM_TYPE_LINE1 = 4;
    const ITEM_TYPE_LINE2 = 5;
    const ITEM_TYPE_LINE3 = 6;
    const ITEM_TYPE_AREA = 7;
    const ITEM_TYPE_STACK = 8; // before stack you have to have a single area item
    // colors
    const C_ORANGE = "FF9900";
    const C_YELLOW = "FFFF00";
    const C_SEE = "01C5BB";
    const C_PURPLE = "9FA4EE";
    const C_MAGENTA = "8B008B";
    const C_ROSE = "FF00CC";
    const C_PINK = "FF1CAE";
    const C_GREEN = "00FF00";
    const C_RED = "FF0000";
    const C_BLUE = "3399FF";
    const C_BOOL_ORANGE = "CC3300";
    const C_GREEN_D1 = "00B200";
    const C_GREEN_D2 = "007D00";
    const C_GREEN_D3 = "005800";
    const C_RED_D1 = "B20000";
    const C_RED_D2 = "7D0000";
    const C_RED_D3 = "580000";
    const C_BLUE_D1 = "0000FF";
    const C_BLUE_D2 = "0000CC";
    const C_BLUE_D3 = "000058";
    const CACTI_HASH_PREFIX = '0023';
    // colors for edges (darker versions)
    const C_YELLOW_E = "FF5700";
    const C_BLUE_E = "0000FF";
    const C_RED_E = "942D0C";
    const C_GREEN_E = "00694A";
    const C_PURPLE_E = "784890";

    // ===============================================================================================================
    // each xml node type has a different prefix then CACTI_HASH_PREFIX then unique md5 - you should not have to edit it
    // these constants are copied from cacti global arrays file to be able to generate hashes
    private $hash_type_codes = array(
        "round_robin_archive" => "15",
        "cdef" => "05",
        "cdef_item" => "14",
        "gprint_preset" => "06",
        "data_input_method" => "03",
        "data_input_field" => "07",
        "data_template" => "01",
        "data_template_item" => "08",
        "graph_template" => "00",
        "graph_template_item" => "10",
        "graph_template_input" => "09",
        "data_query" => "04",
        "data_query_graph" => "11",
        "data_query_sv_graph" => "12",
        "data_query_sv_data_source" => "13",
        "host_template" => "02"
    );

    // ================================================================================================================

    /**
     * The only method you need to call.
     *
     * @param array $def assoc array with declaration of inputs and graphs you want
     * @throws Exception on errors i thorw exceptions when possible
     * 
     * @return string Generated XML
     */
    public function generateXml($def) {
        $seed = microtime(true) . ' ' . mt_rand(1, 10000000);

        // data input fields
        foreach ($def['fields'] as $name => $data) {
            if (strlen($name) > 19) {
                throw new Exception("Field '" . $name . "' has too long name, reduce it by " . (strlen($name) - 19) . " characters.");
            }
            if (!isset($def['fields'][$name]["input"])) {
                throw new Exception("Field '" . $name . "' needs an 'input' attribute.");
            }
            if (!isset($def['fields'][$name]["type"])) {
                throw new Exception("Field '" . $name . "' needs a 'type' attribute.");
            }

            $def['fields'][$name]["fieldHash"] = $this->getHash('data_input_field', $name . ":f:" . $seed);
            $def['fields'][$name]["itemHash"] = $this->getHash('data_template_item', $name . ":i:" . $seed);
            if (!isset($def['fields'][$name]["inputDesc"])) {
                $def['fields'][$name]["inputDesc"] = $name;
            }
        }

        // globals
        $def['dsHash'] = $this->getHash('data_template', 'ds:' . $seed);
        $def['inputHash'] = $this->getHash('data_input_method', 'input:' . $seed);
        $def['inputHostHash'] = $this->getHash('data_input_field', 'host:' . $seed);
        $def['timeHash1'] = $this->getHash('round_robin_archive', 't1:' . $seed);
        $def['timeHash2'] = $this->getHash('round_robin_archive', 't2:' . $seed);
        $def['timeHash3'] = $this->getHash('round_robin_archive', 't3:' . $seed);
        $def['timeHash4'] = $this->getHash('round_robin_archive', 't4:' . $seed);
        $def['timeHash5'] = $this->getHash('round_robin_archive', 't5:' . $seed);
        $def['totalLineHash1'] = $this->getHash('cdef', 'cdef:' . $seed);
        $def['totalLineHash2'] = $this->getHash('cdef_item', 'cdef1:' . $seed);
        $def['gPrintLabelHash'] = $this->getHash('gprint_preset', 'gprint:' . $seed);

        $templateItemSeq = 1;
        // graphs
        foreach ($def['graphs'] as $index => $data) {
            $graphSeed = microtime(true) . ' ' . mt_rand(1, 10000000);
            $def['graphs'][$index]['hash'] = $this->getHash('graph_template', $index . ":g:" . $graphSeed);
            $def['graphs'][$index]['items'] = array();
            $def['graphs'][$index]['inputs'] = array();

            // build display definitions
            foreach ($def['graphs'][$index]['lines'] as $dsFieldName => $lineDefinition) {
                $templateFile = "template-graph-line.php";
                $item = array();
                $item['itemHash1'] = $this->getHash('graph_template_item', $dsFieldName . 'gti1:' . $graphSeed);
                $item['itemHash2'] = $this->getHash('graph_template_item', $dsFieldName . 'gti2:' . $graphSeed);
                $item['itemHash3'] = $this->getHash('graph_template_item', $dsFieldName . 'gti3:' . $graphSeed);
                $item['itemHash4'] = $this->getHash('graph_template_item', $dsFieldName . 'gti4:' . $graphSeed);
                $item['itemHash5'] = $this->getHash('graph_template_item', $dsFieldName . 'gti5:' . $graphSeed);
                $item['gprintHash'] = $def['gPrintLabelHash'];
                $item['itemSeq1'] = $templateItemSeq++;
                $item['itemSeq2'] = $templateItemSeq++;
                $item['itemSeq3'] = $templateItemSeq++;
                $item['itemSeq4'] = $templateItemSeq++;

                if (count($lineDefinition) < 3 && $dsFieldName == 'total') {
                    $item['name'] = 'Total';
                    $item['itemType'] = self::ITEM_TYPE_LINE1;
                    $item['color'] = isset($lineDefinition[0]) ? $lineDefinition[0] : "000000";
                    $item['cdef'] = $def['totalLineHash1'];
                    $item['dsItemHash'] = 0;
                } elseif (count($lineDefinition) < 3 && $dsFieldName == 'edge') {
                    $item['name'] = '';
                    $item['itemType'] = self::ITEM_TYPE_LINE1;
                    $item['color'] = isset($lineDefinition[0]) ? $lineDefinition[0] : "000000";
                    $item['cdef'] = $def['totalLineHash1'];
                    $item['dsItemHash'] = 0;
                    $templateFile = "template-graph-single-line.php";
                } else {
                    $item['name'] = $lineDefinition[0];
                    $item['itemType'] = $lineDefinition[1];
                    $item['color'] = $lineDefinition[2];
                    $item['cdef'] = 0;
                    $item['dsItemHash'] = $def['fields'][$dsFieldName]["itemHash"];
                }
                $item['xml'] = $this->renderFile($templateFile, array("item" => $item));
                $def['graphs'][$index]['items'][] = $item;
            }

            // build graph input vars
            foreach ($def['graphs'][$index]['lines'] as $dsFieldName => $lineDefinition) {
                $input = array();
                $input['hash'] = $this->getHash('graph_template_input', $dsFieldName . 'gtinput2:' . $graphSeed);
                $input['dsFieldName'] = $dsFieldName;
                $input['lineHash1'] = $this->getHash('graph_template', $dsFieldName . 'gti1:' . $graphSeed); // same hash as itemHash1 but starts with 00
                $input['lineHash2'] = $this->getHash('graph_template', $dsFieldName . 'gti2:' . $graphSeed);
                $input['lineHash3'] = $this->getHash('graph_template', $dsFieldName . 'gti3:' . $graphSeed);
                $input['lineHash4'] = $this->getHash('graph_template', $dsFieldName . 'gti4:' . $graphSeed);
                $input['xml'] = $this->renderFile("template-graph-input.php", array("input" => $input));
                $def['graphs'][$index]['inputs'][] = $input;
            }

            // render the entire graph
            $def['graphs'][$index]['xml'] = $this->renderFile("template-graph.php", array("graph" => $def['graphs'][$index]));
        }

        return $this->renderFile("template-ds.php", $def);
    }

    /**
     * Renders partial xml fragment from template file by populating variables.
     * 
     * @param string $templatePath template name
     * @param array $vars assoc array of variables pushed into the template's scope
     * @return string rendered fragment
     */
    private function renderFile($templatePath, $vars) {
        // define all vars in local scope
        foreach ($vars as $name => $value) {
            $$name = $value;
        }

        // process template and return it
        ob_start();
        include dirname(__FILE__) . "/templates/" . $templatePath;
        return ob_get_clean();
    }

    /**
     * Generates hash for xml node by type. Cacti XML needs particular hashes that include node type and version.
     * 
     * @param string $type type of the node from the $hash_type_codes array
     * @param string $string any data used to create the hash
     * @return string hash
     */
    private function getHash($type, $string) {
        return 'hash_' . $this->hash_type_codes[$type] . self::CACTI_HASH_PREFIX . md5($string);
    }

}
