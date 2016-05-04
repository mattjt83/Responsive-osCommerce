<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class atp_update_product {
    var $title;

    function atp_update_product() {
      $this->code = 'atp_update_product';
      $this->title = MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_TITLE;
      $this->description = MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_DESCRIPTION;
      $this->enabled = ((MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_SORT_ORDER;
    }

    function process() {
      global $cart, $messageStack, $_POST, $_GET, $goto, $parameters;
      
      // customer wants to update the product quantity in their shopping cart
      if ( isset( $_GET['action'] ) && $_GET['action'] == 'update_product' ){
        $n=sizeof($_POST['products_id']);
        for ($i=0; $i<$n; $i++) {
            if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
                $cart->remove($_POST['products_id'][$i]);
                $messageStack->add_session('product_action', sprintf(PRODUCT_REMOVED, tep_get_products_name($_POST['products_id'][$i])), 'warning');
            } else {
                $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
                $cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
            }
        }
        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_STATUS', 'MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable shopping cart to update quantity of items', 'MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_STATUS', 'true', 'Do you want to customers to update their cart quantity on the shopping cart page?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_APPLICATION_TOP_PROCESSING_UPDATE_PRODUCT_SORT_ORDER', '1', 'Sort order to process.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>