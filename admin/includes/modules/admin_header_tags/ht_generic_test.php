<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class ht_generic_test {
    var $code = 'ht_generic_test';
    var $group = 'footer_scripts';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_generic_test() {
      $this->title = MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_TITLE;
      $this->description = MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_DESCRIPTION;

      if ( defined('MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_STATUS == 'True');
      }
    }

    function execute() {
      global $PHP_SELF, $oscTemplate, $request_type;     

$output = <<<EOD
<script>
console.log("TEST OF ADMIN HEADER_TAGS");
</script>
EOD;

      $oscTemplate->addBlock($output, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Create Account State Update', 'MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_STATUS', 'True', 'Do you want to enable the Generic Test module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_STATUS', 'MODULE_ADMIN_HEADER_TAGS_GENERIC_TEST_SORT_ORDER');
    }

  }
?>