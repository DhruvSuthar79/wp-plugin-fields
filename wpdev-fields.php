<?php 

/**
 * PLugin Name: WPdev Fields
 * Version: 1.0.0
 * Author: WPDEV
 * Description: Development purpose only
 */

/**
 * Prevent direct access
 */

if( ! defined( 'ABSPATH' ) ) exit;

class WpdevFields
{

    /**
     * Actions and filters
     */

    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'registerPage' ) );
        add_action( 'admin_init', array( $this, 'settings' ) );
    }

    /**
     * Register setting section
     * Add setting fields
     * Register setting fields
     */

    function settings() {
        add_settings_section( 'wpdev_first_section', null, null, 'wpdev-fields' );

        // Text field 

        add_settings_field( 'simple_text', 'Text Field', array( $this, 'textFieldCallback' ), 'wpdev-fields', 'wpdev_first_section' );
        register_setting( 'field_group', 'simple_text', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Test') );

        // Select field

        add_settings_field( 'simple_select', 'Select Field', array( $this, 'selectFieldCallback' ), 'wpdev-fields', 'wpdev_first_section' );
        register_setting( 'field_group', 'simple_select', array('sanitize_callback' => 'sanitize_text_field', 'default' => '2') );

        // Textarea field

        add_settings_field( 'simple_textarea', 'Textarea Field', array( $this, 'textareaFieldCallback' ), 'wpdev-fields', 'wpdev_first_section' );
        register_setting( 'field_group', 'simple_textarea', array( 'type' => 'string' , 'sanitize_callback' => 'sanitize_textarea_field', 'default' => '') );

        // Checkbox field

        add_settings_field( 'simple_checkbox', 'Checkbox Field', array( $this, 'checkboxFieldCallback' ), 'wpdev-fields', 'wpdev_first_section' );
        register_setting( 'field_group', 'simple_checkbox', array( 'type' => 'string' , 'sanitize_callback' => 'sanitize_textarea_field', 'default' => '') );

        // File upload field

        add_settings_field( 'simple_file_upload', 'Upload Field', array( $this, 'uploadFieldCallback' ), 'wpdev-fields', 'wpdev_first_section' );
        register_setting( 'field_group', 'simple_file_upload', 'handle_file_upload' );
    }

    /**
     * Handle file upload
     */

    function handle_file_upload( $option ) {
        if(!empty($_FILES["simple_file_upload"]["tmp_name"]))
        {
            $urls = wp_handle_upload($_FILES["simple_file_upload"], array('test_form' => FALSE));
            $temp = $urls["url"];
            return $temp;   
        }
        return $option;
}

    /**
     * Upload field callback function
     */

    function uploadFieldCallback( $option ) { 
        
        ?>
        <input type="file" name="simple_file_upload" id="">
        <?php 
            echo get_option('simple_file_upload');
            ?>
            <img class="header_logo" src="<?php echo get_option( 'simple_file_upload' ); ?>" height="50" width="50"/>
    <?php }

    /**
     * Checkbox field callback function
     */

    function checkboxFieldCallback() {
        $options = get_option( 'simple_checkbox' ); ?>
        <input type="checkbox" name="simple_checkbox" value="1" id="simple_checkbox" <?php checked( get_option( 'simple_checkbox', '1' ), '1' ) ?>>
    <?php }

    /**
     * Textarea field callback function
     */

    function textareaFieldCallback() { 
        $options = get_option( 'simple_textarea' );?>
        <textarea name="simple_textarea" id="" cols="100" rows="5"><?php echo isset( $options ) ? esc_textarea( $options ) : '' ?></textarea>
    <?php }

    /**
     * Select field callback function
     */

    function selectFieldCallback() { 
        $options = get_option( 'simple_select' );
        ?>
        <select name="simple_select">
            <option value="one" <?php selected( 'one', $options ) ?>>One</option>
            <option value="two" <?php selected( 'two', $options ) ?>>Two</option>
            <option value="three" <?php selected( 'three', $options ) ?>>Three</option>
            <option value="four" <?php selected( 'four', $options ) ?>>Four</option>
            <option value="five" <?php selected( 'five', $options ) ?>>Five</option>
            <option value="six" <?php selected( 'six', $options ) ?>>Six</option>
            <option value="seven" <?php selected( 'seven', $options ) ?>>Seven</option>
        </select>
    <?php }

    /**
     * Text field callback function
     */

    function textFieldCallback() { ?>
        <input type="text" name="simple_text" value="<?php echo esc_attr( get_option( 'simple_text' ) ) ?>">
    <?php }

    /**
     * Register menu page
     */

    function registerPage() {
        add_menu_page( 
            __( 'WPdev Fields', 'textdomain' ),
            'WPdev Fields',
            'manage_options',
            'wpdev-fields',
            array( $this, 'menuPage' ),
            'dashicons-smiley',
            6
        );
    }

    /**
     * Menu page callback function
     */

    function menuPage() { ?>
        <div class="wrap">
            <h1>WPDEV Settings</h1>
            <form action="options.php" method="POST">
                <?php
                    settings_fields( 'field_group' );
                    do_settings_sections( 'wpdev-fields' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php }
}

$wpdevFields = new WpdevFields();