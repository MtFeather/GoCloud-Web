<?php
$title="Virtual Machine";
require_once('misc/header.php');
?>
<?php
chdir('/gocloud/scripts');
$output=shell_exec("sudo ./check_up_vm.sh ".$_SESSION['user_id']);
$vm_up_id=trim("$output");
if ($vm_up_id == "") {
  require_once('vm_select.php');
} else {
  require_once('vm_status.php');
}
?>
<?php
require_once('misc/footer.php');
?>
