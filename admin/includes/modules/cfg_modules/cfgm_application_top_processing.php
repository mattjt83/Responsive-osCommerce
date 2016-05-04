<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

  class cfgm_application_top_processing {
    var $code = 'application_top_processing';
    var $directory;
    var $language_directory = DIR_FS_CATALOG_LANGUAGES;
    var $key = 'MODULE_APPLICATION_TOP_PROCESSING_INSTALLED';
    var $title;
    var $template_integration = false;

    function cfgm_application_top_processing() {
        
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_APPLICATION_TOP_PROCESSING_INSTALLED' limit 1");
      if (tep_db_num_rows($check_query) < 1) {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', 'MODULE_APPLICATION_TOP_PROCESSING_INSTALLED', '', 'This is automatically updated. No need to edit.', '6', '0', now())");
        define('MODULE_APPLICATION_TOP_PROCESSING_INSTALLED', '');
      }
      
      $this->directory = DIR_FS_CATALOG_MODULES . 'application_top_processing/';
      $this->title = MODULE_CFG_MODULE_APPLICATION_TOP_PROCESSING_TITLE;
    }
  }
?>