<cacti>
<?php foreach ($graphs as $graph): ?>
<?php echo $graph['xml'] ?>
<?php endforeach; ?>
    
    <<?php echo $dsHash ?>>
    <name><?php echo $dsName ?></name>
    <ds>
        <t_name></t_name>
        <name>|host_description| - <?php echo $dsName ?></name>
        <data_input_id><?php echo $inputHash ?></data_input_id>
        <t_rra_id></t_rra_id>
        <t_rrd_step></t_rrd_step>
        <rrd_step>300</rrd_step>
        <t_active></t_active>
        <active>on</active>
        <rra_items><?php echo $timeHash1 ?>|<?php echo $timeHash2 ?>|<?php echo $timeHash3 ?>|<?php echo $timeHash4 ?>|<?php echo $timeHash5 ?></rra_items>
    </ds>
    <items>
        <?php foreach ($fields as $name => $field): ?>
            <<?php echo $field['itemHash'] ?>>
            <t_data_source_name></t_data_source_name>
            <data_source_name><?php echo $name ?></data_source_name>
            <t_rrd_minimum></t_rrd_minimum>
            <rrd_minimum>0</rrd_minimum>
            <t_rrd_maximum></t_rrd_maximum>
            <rrd_maximum>1000000000</rrd_maximum>
            <t_data_source_type_id></t_data_source_type_id>
            <data_source_type_id><?php echo $field['type'] ?></data_source_type_id>
            <t_rrd_heartbeat></t_rrd_heartbeat>
            <rrd_heartbeat>600</rrd_heartbeat>
            <t_data_input_field_id></t_data_input_field_id>
            <data_input_field_id><?php echo $field['fieldHash'] ?></data_input_field_id>
            </<?php echo $field['itemHash'] ?>>
        <?php endforeach; ?>
    </items>
    <data>
        <item_000>
            <data_input_field_id><?php echo $inputHostHash ?></data_input_field_id>
            <t_value></t_value>
            <value></value>
        </item_000>
    </data>
    </<?php echo $dsHash ?>>
    <<?php echo $inputHash ?>>
    <name><?php echo $dsName ?></name>
    <type_id>1</type_id>
    <input_string>curl http://&lt;hostname&gt;<?php echo $inputUri ?></input_string>
    <fields>
        <<?php echo $inputHostHash ?>>
        <name>hostname</name>
        <update_rra></update_rra>
        <regexp_match></regexp_match>
        <allow_nulls></allow_nulls>
        <type_code>hostname</type_code>
        <input_output>in</input_output>
        <data_name>hostname</data_name>
        </<?php echo $inputHostHash ?>>
        <?php foreach ($fields as $name => $field): ?>
            <<?php echo $field['fieldHash'] ?>>
            <name><?php echo $field['inputDesc'] ?></name>
            <update_rra>on</update_rra>
            <regexp_match></regexp_match>
            <allow_nulls></allow_nulls>
            <type_code></type_code>
            <input_output>out</input_output>
            <data_name><?php echo $field['input'] ?></data_name>
            </<?php echo $field['fieldHash'] ?>>
        <?php endforeach; ?>
    </fields>
    </<?php echo $inputHash ?>>
    
    <<?php echo $timeHash1 ?>>
        <name>Daily (5 Minute Average)</name>
        <x_files_factor>0.5</x_files_factor>
        <steps>1</steps>
        <rows>600</rows>
        <timespan>86400</timespan>
        <cf_items>1|3</cf_items>
    </<?php echo $timeHash1 ?>>
    <<?php echo $timeHash2 ?>>
        <name>Weekly (30 Minute Average)</name>
        <x_files_factor>0.5</x_files_factor>
        <steps>6</steps>
        <rows>700</rows>
        <timespan>604800</timespan>
        <cf_items>1|3</cf_items>
    </<?php echo $timeHash2 ?>>
    <<?php echo $timeHash3 ?>>
        <name>Monthly (2 Hour Average)</name>
        <x_files_factor>0.5</x_files_factor>
        <steps>24</steps>
        <rows>775</rows>
        <timespan>2678400</timespan>
        <cf_items>1|3</cf_items>
    </<?php echo $timeHash3 ?>>
    <<?php echo $timeHash4 ?>>
        <name>Yearly (1 Day Average)</name>
        <x_files_factor>0.5</x_files_factor>
        <steps>288</steps>
        <rows>797</rows>
        <timespan>33053184</timespan>
        <cf_items>1|3</cf_items>
    </<?php echo $timeHash4 ?>>
    <<?php echo $timeHash5 ?>>
        <name>Hourly (1 Minute Average)</name>
        <x_files_factor>0.5</x_files_factor>
        <steps>1</steps>
        <rows>500</rows>
        <timespan>14400</timespan>
        <cf_items>1|3</cf_items>
    </<?php echo $timeHash5 ?>>

    <?php if ($totalLineHash1): ?>
        <<?php echo $totalLineHash1 ?>>
            <name>Total All Data Sources</name>
            <items>
                <<?php echo $totalLineHash2 ?>>
                    <sequence>1</sequence>
                    <type>4</type>
                    <value>ALL_DATA_SOURCES_NODUPS</value>
                </<?php echo $totalLineHash2 ?>>
            </items>
        </<?php echo $totalLineHash1 ?>>
    <?php endif; ?>
    
	<<?php echo $gPrintLabelHash ?>>
		<name>Normal</name>
		<gprint_text>%8.2lf %s</gprint_text>
	</<?php echo $gPrintLabelHash ?>>
    
</cacti>