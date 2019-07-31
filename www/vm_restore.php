<?php
$title="Restore";
require_once('misc/header.php');
?>
<?php
require_once('ajax/dbconfig.php');

try {
  $stmt = $conn->prepare("SELECT vm_id FROM student_vms WHERE student = :student_id;");
  $stmt->bindParam(':student_id', $_SESSION['user_id']);
  $stmt->execute();
  $user_vm = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
chdir('/gocloud/scripts');
$output=shell_exec("sudo ./vms.sh");
$vms=json_decode($output);
?>
<div class="container-fluid">
  <div class="toolbar-pf">
    <div class="toolbar-pf-actions">
      <div class="toolbar-pf-action-right">
        <div class="form-group toolbar-pf-view-selector">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div id="pf-list-standard" class="list-group list-view-pf list-view-pf-view">
    <?php 
      foreach ($vms->vm as $vm) {
        if (in_array($vm->id, $user_vm)) {
    ?>
    <div class="list-group-item">
      <div class="list-view-pf-actions">
      <?php if ($vm->status == "down") { ?>
        <button class="btn btn-default" onclick="restore('<?php echo $vm->id; ?>')">還原</button>
      <?php } else { ?>
        <button class="btn btn-default" disabled>還原</button>
      <?php } ?>
      </div>
      <div class="list-view-pf-main-info">
        <div class="list-view-pf-left">
          <span class="fa fa-linux list-view-pf-icon-sm"></span>
        </div>
        <div class="list-view-pf-body">
          <div class="list-view-pf-description">
            <div class="list-group-item-heading">
              <?php echo $vm->name; ?>
            </div>
            <div class="list-group-item-text">
              <?php echo $vm->id; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
        }
      }
    ?>
  </div>
</div>





</div>
</div>
<script>
  function restore(id) {
    var check=confirm("確定要還原此虛擬機嗎?");
    if (check == true) {
      $.ajax({
        url: "ajax/vm.php?f=restore",
        method: "POST",
        data: { vmId: id },
        success: function(result) {
          if (result == 'ok') {
            alert('還原成功');
            location.reload();
          } else {
            alert('還原失敗');
            location.reload();
          }
        }
      });
    }
  }
</script>
<?php
require_once('misc/footer.php');
?>
