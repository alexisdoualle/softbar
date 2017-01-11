<?php
include('session.php');

function isValidSessionId($session_id)
{
  return !empty($session_id) && preg_match('/^[a-zA-Z0-9]{26}$/', $session_id);
}
print_r(preg_match('/^[a-zA-Z0-9]{0,40}$/', $util));

?>
