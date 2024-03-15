<?php
//  ! 投稿内の繰り返しフィールドとフィールド値が空でないか判定する
function check_fields_not_empty($post_id, $repeater_field_name)
{
  $repeater_data = SCF::get($repeater_field_name, $post_id);
  // リピーターフィールドのデータが取得できない場合は、falseを返す
  if (empty($repeater_data)) {
    return false;
  }
  // 空のフィールドがあれば、falseを返す
  foreach ($repeater_data as $fields) {
    foreach ($fields as $field_name => $value) {
      if (empty($value)) :
        return false;
      endif;
    }
  }
  return true;
}

// ! フィールド値が数値であるか判定する
function check_fields_numeric($post_id, $repeater_field_name, $numeric_name)
{
  $repeater_data = SCF::get($repeater_field_name, $post_id);
  // リピーターフィールドのデータが取得できない場合は、falseを返す
  if (empty($repeater_data)) {
    return false;
  }
  // 数値でないフィールドがあれば、falseを返す
  foreach ($repeater_data as $fields) {
    $field_data = $fields[$numeric_name];
    if (!is_numeric($field_data)) :
      return false;
    endif;
  }
  return true;
}
