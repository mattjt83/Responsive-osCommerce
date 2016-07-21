<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class hook_admin_contentModuleLanguageUpdate_contentmodulelanguageupdate {

    function listen_saveModuleFile() {
      global $_POST;

      $languages = tep_get_languages();
      // foreach language:
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];
        if ( isset( $_POST['file_name_' . $language_id] ) && isset( $_POST['file_contents_' . $language_id] ) ) {
            $file = $_POST['file_name_' . $language_id];
            if (file_exists($file) && tep_is_writable($file)) {
              $new_file = fopen($file, 'w');
              $file_contents = stripslashes($_POST['file_contents_' . $language_id]);
              fwrite($new_file, $file_contents, strlen($file_contents));
              fclose($new_file);
            }
        }
      }
    }

    function listen_outputModuleFileContents() {
      global $_GET, $language, $modules;
      require_once DIR_WS_LANGUAGES . $language . '/define_language.php'; //helper text TEXT_EDIT_NOTE

      $output = '<div id="edit_language">' . "\n";
      $output .= '<h2>' . TEXT_EDIT_LANGUAGE_FILES . '</h2>' . "\n"; 
      $output .=  '<div class="secInfo">' . TEXT_EDIT_NOTE .'</div>' . "\n";

      foreach ( $modules['installed'] as $array_key => $array ) {
        foreach ( $array as $key => $value ) {
          if ( $key == 'code' && $value == $_GET['module'] ){
            $module_group = $modules['installed'][$array_key]['group'];
          }
        }
      }
      
      $languages = tep_get_languages();
      // foreach language installed:
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

        $language_id = $languages[$i]['id'];
        $language_dir = $languages[$i]['directory'];
        $language_name = $languages[$i]['name'];

        $module_file_name = DIR_FS_CATALOG_LANGUAGES . $language_dir . '/modules/content/' . $module_group . '/' . $_GET['module'] . '.php';

        if (!file_exists($module_file_name)) {
          $new_file = fopen($module_file_name, "a");
          fwrite($new_file, "<?php" . PHP_EOL);
          fclose($new_file);
          $output .= '<div class="secError">'. sprintf(TEXT_NO_LANGUAGE_FILE, $language_name)  . '</div>' . "\n";
        }
        
        $file_array = file($module_file_name);
        
        if (tep_is_writable($module_file_name)) {
          $contents   = implode('', $file_array);
          $output .= '<br>' . tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $language_dir . '/images/' . $languages[$i]['image'], '', 'SSL'), $language_name) . '<strong>  ' . sprintf (TEXT_EDIT_LANGUAGE_FILE , $language_name) . '</strong><br>' . "\n";
        
          $output .=  tep_draw_textarea_field('file_contents_' . $language_id, 'soft', '80', '25', $contents, ' style="width: 99%;min-width:600px;"') . "\n";
          $output .= tep_draw_hidden_field('file_name_' . $language_id, $module_file_name) . "\n";
        }
      }
        $output .= '</div>' . "\n";

      return $output;
    }

  }
?>
