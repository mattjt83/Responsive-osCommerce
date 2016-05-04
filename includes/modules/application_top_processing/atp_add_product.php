<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class atp_add_product {
    var $title;

    function atp_add_product() {
      $this->code = 'atp_add_product';
      $this->title = MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_TITLE;
      $this->description = MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_DESCRIPTION;
      $this->enabled = ((MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_SORT_ORDER;
    }

    function process() {
      global $cart, $messageStack, $_POST, $_GET, $goto, $parameters;
      
      // customer adds a product from the products page
      if ( isset( $_GET['action'] ) && $_GET['action'] == 'add_product' ){
        if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
            $attributes = isset($_POST['id']) ? $_POST['id'] : '';
            $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $attributes))+1, $attributes);
        }
        $messageStack->add_session('product_action', sprintf(PRODUCT_ADDED, tep_get_products_name((int)$_POST['products_id'])), 'success');
        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_STATUS', 'MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable shopping cart to add items', 'MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_STATUS', 'true', 'Do you want to customers to add items to their shopping cart?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_APPLICATION_TOP_PROCESSING_ADD_PRODUCT_SORT_ORDER', '1', 'Sort order to process.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>