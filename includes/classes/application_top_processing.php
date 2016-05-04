<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

    class applicationTopProcessing {
        var $modules;
        
// class constructor
        function applicationTopProcessing() {
            global $language;

            if (defined('MODULE_APPLICATION_TOP_PROCESSING_INSTALLED') && tep_not_null(MODULE_APPLICATION_TOP_PROCESSING_INSTALLED)) {
                $this->modules = explode(';', MODULE_APPLICATION_TOP_PROCESSING_INSTALLED);

                reset($this->modules);
                foreach ( $this->modules as $value ) {
                    include(DIR_WS_LANGUAGES . $language . '/modules/application_top_processing/' . $value);
                    include(DIR_WS_MODULES . 'application_top_processing/' . $value);

                    $class = substr($value, 0, strrpos($value, '.'));
                    $GLOBALS[$class] = new $class;
                }
            }
        }
        
        function process() {
            $sort_order_array = array();
            
            if (is_array($this->modules)) {
                reset($this->modules);
                foreach ( $this->modules as $value ) {
                    $class = substr($value, 0, strrpos($value, '.'));
                    if ($GLOBALS[$class]->enabled) {
                        //build sort order array
                        $sort_order_array[][$class] = $GLOBALS[$class]->sort_order;
                    }
                }
                
                sort( $sort_order_array );
                
                foreach ( $sort_order_array as $k => $v ) {
                    foreach ( $v as $k_class => $v_sort_order ) {
                        $GLOBALS[$k_class]->process();
                    }
                }
            }
        }
        
    }
?>