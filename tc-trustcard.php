<?php
/*
Plugin Name: TrustCloud - TrustCard Widget
Plugin URI: trustcloud.com/trustcard-widget-for-wordpress 
Description: Adds a TrustCard to your Wordpress Widgets for easy addition to your site.
Version: 0.5
Author: TrustCloud
Author URI: http://trustcloud.com
License: GPL2
*/

function widget_trustcard($args) {
    extract($args);
    $options = get_option("widget_trustcard");
    echo $before_widget;
    echo $before_title . $options['title'] . $after_title;
    echo '<style> .tc-trustcard { margin: 0 auto; } </style>';
    echo '<script type="text/javascript" src="https://api.trustcloud.com/display/showidcard?userid=' . $options['type'] . '-' . $options['input'] . '" ></script>';
    echo $after_widget;
}

function control_trustcard() {
    $options = get_option("widget_trustcard");
    if (!is_array($options)) {
        $options = array(
            'title' => 'TrustCard'
        );
    }
        if ($_POST['trustcard-submit']) {
        $options['title'] = htmlspecialchars($_POST['trustcard-widget-title']);
        $options['input'] = preg_replace('/^@/', '', htmlspecialchars($_POST['trustcard-widget-input']));
        $options['type'] = preg_match('/^[A-Za-z0-9_]+$/', $options['input']) ? 'twitter' : 'email';
        update_option('widget_trustcard', $options);
    }
?>
    <label for="trustcard-widget-title">Widget Title:</label>
    <input type="text" id="trustcard-widget-title" name="trustcard-widget-title" value="<?php echo $options['title'] ?>" style="width:225px" />
    <label for="trustcard-widget-input">Twitter handle or email:</label>
    <input type="text" id="trustcard-widget-input" name="trustcard-widget-input" value="<?php echo $options['input'] ?>" style="width:225px" />
    <input type="hidden" id="trustcard-submit" name="trustcard-submit" value="1" />
<?php
}

function init_trustcard() {
    register_sidebar_widget('TrustCard', 'widget_trustcard');
    register_widget_control('TrustCard', 'control_trustcard');
}
add_action("plugins_loaded", "init_trustcard");

?>
