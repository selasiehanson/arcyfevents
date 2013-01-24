<?php 
/*
  Plugin Name: ARCYF EVENTS
  Plugin URI:
  Description: This plugin allows you to creata a events
  Author: Selasie Hanson
  Version: 0.1
  Author URI:
 */

    add_action('init', 'events_register');

    function events_register() {
        $labels = array('name' => _x('Events', 'post type general name'),
            'singular_name' => _x('Event', 'post type singular name'),
            'add_new' => _x('Add New', 'event'),
            'add_new_item' => __('Add New Event'),
            'edit_item' => __('Edit Event'),
            'new_item' => __('New Event'),
            'view_item' => __('View Event'),
            'search_items' => __('Search Events'),
            'not_found' => __('No events found'),
            'not_found_in_trash' => __('No events found in Trash'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'rewrite' => true,
            'query_var' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                //'custom-fields',
                'thumbnail',
                //'author',
                'page-attributes'),
            'taxonomies' => array(
                //'category', 
                'post_tag')
        );

        register_post_type('event', $args);
    }

    add_action("admin_init", "event_init");
    add_action("save_post", "save_event");

    function event_init() {
        add_meta_box('event-details', 'Event Details', 'event_init_meta_options', 'event', 'normal', 'low');
    }

    function save_event() {
        //update events
        global $post;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( !wp_verify_nonce( $_POST["events_nonce"], 'events_nonce' )) {
                return $post_id;
        }
        update_post_meta($post->ID, "startDate", trim($_POST["startDate"]));
        update_post_meta($post->ID, "endDate", trim($_POST["endDate"]));
    }

    function event_init_meta_options() {
        global $post;
        $custom = get_post_custom($post->ID);
        $startDate = $custom["startDate"][0];
        $endDate = $custom["endDate"][0];
        ?>
        <div id="event_parameters" class='my_meta_control'>

            <label>Start date:</label>
            <p><input id="startDate" name='startDate' value='<?php echo $startDate; ?> ' /> </p>
            <label>End Date: </label>
            <p><input id="endDate" name='endDate' value='<?php echo $endDate; ?> ' /> </p>

            <?php $events_nonce = wp_create_nonce('events_nonce' ); ?>
            <input type="hidden" name="events_nonce" id="events_nonce" value="<?php echo $events_nonce; ?>" />
        </div>
        <?php
    }

    add_filter("manage_edit-event_columns", "events_edit_columns");
    add_action("manage_posts_custom_column", "event_custom_columns");

    function events_edit_columns($columns) {
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Name",
            "startDate" => "Start Date",
            "endDate" => "End Date",
            "description" => "Description",
        );

        return $columns;
    }

    function event_custom_columns($columns) {
        global $post;
        $custom = get_post_custom();
        switch ($columns) {
            case "description" :
                the_excerpt();
                break;
            case "startDate":
                echo $custom["startDate"][0];
                break;
            case "endDate":
                echo $custom["endDate"][0];
                break;
        }
    }

    //add scripts to the server side
    //add_action("admin_print_scripts", "add_arcyf_scripts");
    add_action("admin_enqueue_scripts","add_arcyf_scripts");
    add_action('admin_print_styles', 'add_arcyf_styles');

    function add_arcyf_scripts() {
        //wp_register_script("jquery-ui", plugins_url("/js/jquery-ui-1.8.9.custom.min.js", __FILE__));
        wp_enqueue_script("jquery-ui-datepicker");
        wp_register_script("arcyf-events-script", plugins_url( "/js/arcyf_events.js",__FILE__));
        wp_enqueue_script("arcyf-events-script");
    }

    function add_arcyf_styles() {
        wp_register_style('my-events-style', plugins_url('/css/style.css', __FILE__));
        wp_register_style("my-jqueryui-style", plugins_url('/css/smoothness/jquery-ui-1.8.9.custom.css',__FILE__));
        wp_enqueue_style("my-events-style");
        wp_enqueue_style("my-jqueryui-style");
    }

    function show_events(){
        ob_start();
        include_once('client/index.php');
        $list = ob_get_clean();
        return $list;
    }

add_shortcode('show_events',"show_events");
 ?>