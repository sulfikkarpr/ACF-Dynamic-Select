<?php
/*
Plugin Name: ACF Dynamic Option Plugin
Description: A WordPress plugin that creates a dynamic select box populated by ACF repeater field.
Version: 1.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ACF_Dynamic_Option_Plugin {

    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
    }

    public function add_plugin_page() {
        add_options_page(
            'Dynamic ACF Options',
            'Dynamic ACF Options', 
            'manage_options', 
            'dynamic-acf-options', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page() {
        $this->options = get_option( 'acf_dynamic_option_plugin_options' ); 
        ?>
        <div class="wrap">
            <h1>Dynamic ACF Options</h1>
            <form method="post" action="options.php" id="dynamic-acf-options-form">
                <?php
                settings_fields( 'acf_dynamic_option_plugin_group' ); // Still needed for saving
                ?>

                <h2>Dynamic Select Settings</h2>
                <p>Configure the dynamic select box options:</p>
                <button type="button" id="add-row" class="button">Add Row</button>

                <table class="form-table" id="dynamic-rows-table">
                    <thead>
                        <tr>
                            <th>ACF Repeater Field Name</th>
                            <th>Target Option Page Name / ID</th>
                            <th>Key field</th>
                            <th>Name Field</th>
                            <th>Class Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ( !empty($this->options) ) { 
                            $i = 0;
                            foreach ( $this->options as $option ) {
                                echo '<tr valign="top">';
                                    echo '<td><input type="text" id="repeater_name_' . $i . '" name="acf_dynamic_option_plugin_options[' . $i . '][repeater_name]" value="' . esc_attr( $option['repeater_name'] ) . '" /></td>';
                                    echo '<td><input type="text" id="option_page_name_' . $i . '" name="acf_dynamic_option_plugin_options[' . $i . '][option_page_name]" value="' . esc_attr( $option['option_page_name'] ) . '" /></td>';
                                    echo '<td><input type="text" id="key_field_name_' . $i . '" name="acf_dynamic_option_plugin_options[' . $i . '][key_field_name]" value="' . esc_attr( $option['key_field_name'] ) . '" /></td>';
                                    echo '<td><input type="text" id="name_field_name_' . $i . '" name="acf_dynamic_option_plugin_options[' . $i . '][name_field_name]" value="' . esc_attr( $option['name_field_name'] ) . '" /></td>';
                                    echo '<td><input type="text" id="class_name_' . $i . '" name="acf_dynamic_option_plugin_options[' . $i . '][class_name]" value="' . esc_attr( $option['class_name'] ) . '" /></td>';
                                    echo '<td><button type="button" class="delete-row button">Delete Row</button></td>';
                                echo '</tr>';
                                $i++;
                            } 
                        }
                        ?>
                    </tbody>
                </table>

                <?php submit_button(); ?> 
            </form>
        </div>
        <?php
    }


    public function page_init() {
        register_setting(
            'acf_dynamic_option_plugin_group',
            'acf_dynamic_option_plugin_options', 
            array( $this, 'sanitize_options' ) 
        ); 
    }

    public function output_save_button() {
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'dynamic-acf-options' ) {
            echo '<div style="text-align: right; margin-bottom: 15px;">'; 
            submit_button();
            echo '</div>';
        }
    }

    public function sanitize_options( $input ) {
        $new_input = array();
        if ( is_array( $input ) ) {
            foreach ( $input as $key => $value ) {
                if ( is_array( $value ) ) {
                    foreach ( $value as $subKey => $subValue ) {
                        $new_input[$key][$subKey] = sanitize_text_field( $subValue );
                    }
                } else {
                    $new_input[$key] = sanitize_text_field( $value );
                }
            }
        }
        return $new_input;
    }

    public function enqueue_scripts( $hook ) {
        if ( 'settings_page_dynamic-acf-options' !== $hook ) {
            return; 
        }
        wp_enqueue_script( 'dynamic-acf-options-script', plugin_dir_url( __FILE__ ) . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_style( 'dynamic-acf-options-style', plugin_dir_url( __FILE__ ) . '/assets/css/style.css' ); 
    }
}

if ( is_admin() ) {
    $acf_dynamic_option_plugin = new ACF_Dynamic_Option_Plugin();
}

require_once plugin_dir_path(__FILE__) . '/includes/class-acf-dynamic-option.php';
