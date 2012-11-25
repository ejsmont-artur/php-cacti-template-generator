<<?php echo $graph['hash'] ?>>
    <name><?php echo $graph['name'] ?></name>
    <graph>
        <t_title></t_title>
        <title>|host_description| - <?php echo $graph['name'] ?></title>
        <t_image_format_id></t_image_format_id>
        <image_format_id>1</image_format_id>
        <t_height></t_height>
        <height>120</height>
        <t_width></t_width>
        <width>500</width>
        <t_slope_mode></t_slope_mode>
        <slope_mode>on</slope_mode>
        <t_auto_scale></t_auto_scale>
        <auto_scale>on</auto_scale>
        <t_auto_scale_opts></t_auto_scale_opts>
        <auto_scale_opts>2</auto_scale_opts>
        <t_auto_scale_log></t_auto_scale_log>
        <auto_scale_log></auto_scale_log>
        <t_scale_log_units></t_scale_log_units>
        <scale_log_units></scale_log_units>
        <t_auto_scale_rigid></t_auto_scale_rigid>
        <auto_scale_rigid>on</auto_scale_rigid>
        <t_auto_padding></t_auto_padding>
        <auto_padding>on</auto_padding>
        <t_export></t_export>
        <export>on</export>
        <t_upper_limit></t_upper_limit>
        <upper_limit>100</upper_limit>
        <t_lower_limit></t_lower_limit>
        <lower_limit>0</lower_limit>
        <t_base_value></t_base_value>
        <base_value>1000</base_value>
        <t_unit_value></t_unit_value>
        <unit_value></unit_value>
        <t_unit_exponent_value></t_unit_exponent_value>
        <unit_exponent_value></unit_exponent_value>
        <t_vertical_label></t_vertical_label>
        <vertical_label><?php echo $graph['unitLabel'] ?></vertical_label>
    </graph>
    <items>
<?php foreach ($graph['items'] as $seq => $row): ?>
<?php echo $row['xml'] ?>
<?php endforeach; ?>
    </items>
    <inputs>
<?php foreach ($graph['inputs'] as $seq => $row): ?>
<?php echo $row['xml'] ?>
<?php endforeach; ?>
    </inputs>
</<?php echo $graph['hash'] ?>>

