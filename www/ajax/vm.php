<?php
function vmStart() {
  $vmId = $_POST['vmId'];
  $boot = $_POST['boot'];
  $attach = $_POST['attach'];
  $cdrom = $_POST['cdrom'];
  if (!empty($vmId) && !empty($boot)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_start.sh $vmId $boot $attach $cdrom");
    echo trim($result);
  }
}

function vmStop() {
  $vmId = $_POST['vmId'];
  if (!empty($vmId)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_stop.sh $vmId");
    echo trim($result);
  }
}

function vmReboot() {
  $vmId = $_POST['vmId'];
  if (!empty($vmId)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_reboot.sh $vmId");
    echo trim($result);
  }
}

function vmConsole() {
  $vmId = $_POST['vmId'];
  if (!empty($vmId)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_console.sh $vmId");
    echo trim($result);
  }
}

function vmCdrom() {
  $vmId = $_POST['vmId'];
  $cdrom = $_POST['cdrom'];
  if (!empty($vmId)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_cdrom.sh $vmId $cdrom");
    echo trim($result);
  }
}


function restore() {
  $vmId = $_POST['vmId'];
  if (!empty($vmId)) {
    chdir('/gocloud/scripts');
    $result = shell_exec("sudo ./vm_restore.sh $vmId");
    echo trim($result);
  }
}

if (function_exists($_GET['f'])) {
  $_GET['f']();
}
?>
