<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cfgm_admin_header_tags {
    var $code = 'admin_header_tags';
    var $directory;
    var $language_directory = DIR_WS_LANGUAGES;
    var $key = 'MODULE_ADMIN_HEADER_TAGS_INSTALLED';
    var $title;
    var $template_integration = true;

    function cfgm_admin_header_tags() {
      $this->directory = DIR_WS_MODULES . 'admin_header_tags/';
      $this->title = MODULE_CFG_MODULE_ADMIN_HEADER_TAGS_TITLE;
        
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADMIN_HEADER_TAGS_INSTALLED' limit 1");
      if (tep_db_num_rows($check_query) < 1) {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_ADMIN_HEADER_TAGS_INSTALLED', '', 'This is automatically updated. No need to edit.', '6', '0', now())");
        define('MODULE_ADMIN_HEADER_TAGS_INSTALLED', '');
      }
        
    }
  }
?>
