<?php
/*
Plugin Name: TrustCloud - TrustCard Widget
Plugin URI: http://trustcloud.com/trustcard-widget-for-wordpress
Description: Adds a TrustCard to your Wordpress Widgets for easy addition to your site.
Version: 0.53
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
    echo '<script data-cfasync="false" type="text/javascript" src="https://api.trustcloud.com/display/showidcard?userid=' . $options['type'] . '-' . ($options['type'] == 'twitter' ? preg_replace('/^@/', '', $options['input']) : $options['input']) . '" ></script>';
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
        $options['input'] = htmlspecialchars($_POST['trustcard-widget-input']);
        $options['type'] = 'email';
        if (preg_match('/^[A-Za-z0-9_]+$/', $options['input'])) {
            $options['type'] = 'membername';
        } else if (preg_match('/^@/', $options['input'])) {
            $options['type'] = 'twitter';
        }
        update_option('widget_trustcard', $options);
    }
?>
    <label for="trustcard-widget-title">Widget Title:</label>
    <input type="text" id="trustcard-widget-title" name="trustcard-widget-title" value="<?php echo $options['title'] ?>" style="width:225px" />
    <br><br>
    <label for="trustcard-widget-input">Member name, @Twitter, or Email:</label>
    <input type="text" id="trustcard-widget-input" name="trustcard-widget-input" value="<?php echo $options['input'] ?>" style="width:225px" />
    <br><br>
    <p>Member Name - "TomCumulus<br>Twitter handle - "@TomCumulus"<br>Email - "tomc@trustcloud.com"</p>

    <input type="hidden" id="trustcard-submit" name="trustcard-submit" value="1" />
<?php
}

function init_trustcard() {
    register_sidebar_widget('TrustCard', 'widget_trustcard');
    register_widget_control('TrustCard', 'control_trustcard');
}
add_action("plugins_loaded", "init_trustcard");

?>
