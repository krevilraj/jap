<?php


function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;

}

function sanitizing_meta_info($meta_info)
{

  $final_filter_arr = [];
  if (is_array($meta_info) && !empty($meta_info)) {
    foreach ($meta_info as $each_info){
      $meta_info_arr = explode("|", $each_info);
      $filter_arr = [];
      if (isset($meta_info_arr) && !empty($meta_info_arr)) {
        foreach ($meta_info_arr as $data) {
          $filter_arr[] = sanitize_text_field($data);
        }
      }
      if (isset($filter_arr) && !empty($filter_arr)) {
        $final_filter_arr[] = implode('|',$filter_arr);
      }
    }

  }
  return $final_filter_arr;
}

function validate_telfone($telefone)
{
  return preg_match('/^[0-9]+$/', $telefone);
}

function error_check($error, $key)
{
  if (isset($error) && isset($error[$key])) {
    echo '<p class="kia__error"><i class="fa-solid fa-xmark"></i> ' . $error[$key] . '</p>';
  }
}

function error_label($error, $key)
{
  if (isset($error) && isset($error[$key])) {
    echo 'error_label';
  }

}



//function view($path, $data)
//{
//
//  if (!empty($data)){
//      $var = array_keys($data);
//      ${$var[0]} = $data[$var[0]];
//  }
//
//  $path = str_replace(".", "/", $path);
//  $path = dirname(__FILE__) . '/views/' . $path . '.php';
//  require_once $path;
//}

function view($path, $data = [])
{
    if (!empty($data)) {
        if (is_array($data)) {
            $var = array_keys($data);
            ${$var[0]} = $data[$var[0]];
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
        }
    }

    $path = str_replace(".", "/", $path);
    $path = dirname(__FILE__) . '/views/' . $path . '.php';
    require_once $path;
}


function get_no_result()
{
  view('template.404');
}

function jap_redirect($path)
{
  ?>
  <script>
    location.href = "<?php echo site_url(); ?>/wp-admin/admin.php?page=<?php echo $path?>";
  </script>
  <?php
}

/* Store form values in session var */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  foreach ($_POST as $field => $value) $_SESSION['formfields'][$field] = $value;
}

/* Function used in html - provides previous value or empty string */
function fieldvalue($field = false)
{
  return ($field && !empty($field) && isset($_SESSION['formfields']) && array_key_exists($field, $_SESSION['formfields'])) ? esc_html($_SESSION['formfields'][$field]) : '';
}

function editfieldvalue($field = false, $db_value)
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    return ($field && !empty($field) && isset($_SESSION['formfields']) && array_key_exists($field, $_SESSION['formfields'])) ? $_SESSION['formfields'][$field] : '';
  } else {
    if (isset($db_value->$field)) {
      return $db_value->$field;
    }
  }

}



function reset_field()
{

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $field => $value) unset($_SESSION['formfields'][$field]);
  }
}

function check_select($index, $val)
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_REQUEST[$index]) && $_REQUEST[$index] == $val)
      echo "selected=selected";
  }
}

function check_edit_select($index, $val, $proposta)
{
  $result = "";
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_REQUEST[$index]) && $_REQUEST[$index] == $val)
      $result = "selected=selected";
  } else {
    if (isset($proposta->$index) && $proposta->$index == $val) {
      $result = "selected=selected";
    }
  }

  echo $result;
}



function pt_num($number){
  return number_format($number, 2, ',', '.');
}

function twodecimal($value)
{
  return number_format((float)$value, 2, '.', '');
}

function the_assets($path)
{
  $path = JAP_URL . '/' . $path;
  echo esc_url($path);
}
function get_the_assets($path)
{
  $path = JAP_URL . '/' . $path;
  return esc_url($path);
}

function pre($data){
  echo '<pre>';
  print_r($data);
  echo '</pre>';
//  exit();
}
function my_get_users_name($user_id = null)
{

    $user_info = $user_id ? new WP_User($user_id) : wp_get_current_user();
 return $user_info->user_login;
    if ($user_info->first_name) {

        if ($user_info->last_name) {
            return $user_info->first_name . ' ' . $user_info->last_name;
        }

        return $user_info->first_name;
    }

    return $user_info->display_name;
}