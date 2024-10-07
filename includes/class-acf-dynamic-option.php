<?php
if (!defined('ABSPATH')) {
    exit;
}


class ACF_Dynamic_Data_Processor {
    private $options;

    public function __construct() {
        $this->options = get_option('acf_dynamic_option_plugin_options');
        add_filter('acf/load_field', array($this, 'populate_dynamic_field_choices'));
    }

    public function populate_dynamic_field_choices($field) {
        if ($field['type'] !== 'select') {
            return $field;
        }

        $class = isset($field['wrapper']['class']) ? $field['wrapper']['class'] : '';

        if (!empty($this->options)) {
            foreach ($this->options as $option) {
                if (str_contains($class, $option['class_name'])) {
                    $field['choices'] = $this->get_dynamic_choices($option);
                    break; // Stop after finding the first matching class
                }
            }
        }

        return $field;
    }

    private function get_dynamic_choices($option) {
        $choices = array();

        $repeater_name = $option['repeater_name'];
        $option_page_name = $option['option_page_name'];
        $key_field_name = $option['key_field_name'];
        $name_field_name = $option['name_field_name'];

        $repeater_data = get_field($repeater_name, $option_page_name);

        if (is_array($repeater_data)) {
            foreach ($repeater_data as $row) {
                $key = isset($row[$key_field_name]) ? $row[$key_field_name] : '';
                $value = isset($row[$name_field_name]) ? $row[$name_field_name] : '';
                if ($key && $value) {
                    $choices[$key] = $value;
                }
            }
        }

       

        return $choices;
    }
}

// Initialize the class
new ACF_Dynamic_Data_Processor();