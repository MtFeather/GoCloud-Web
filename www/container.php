<?php
$title="Container";
require_once('misc/header.php');
require_once('ajax/dbconfig.php');
try {
  $stmt = $conn->prepare("SELECT name, uid, namespace FROM student_containers WHERE student = :student;");
  $stmt->bindParam(':student', $_SESSION['user_id']);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
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
    <?php foreach($rows as $row) { ?>
    <div class="list-group-item">
      <div class="list-view-pf-actions">
        <a href="shell.php?name=<?php echo $row['name']; ?>&namespace=<?php echo $row['namespace']; ?>" target="_blank">
          <button class="btn btn-default">Console</button>
        </a>
      </div>
      <div class="list-view-pf-main-info">
        <div class="list-view-pf-left">
          <span class="fa fa-linux list-view-pf-icon-sm"></span>
        </div>
        <div class="list-view-pf-body">
          <div class="list-view-pf-description">
            <div class="list-group-item-heading">
              <?php echo $row['namespace']; ?>
            </div>
            <div class="list-group-item-text">
              <?php echo $row['uid']; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
  
  
  
  
  
</div>
</div>
<script src="assets/js/gocloud-container.js"></script>
<script>
  $(document).ready(function(){
  });
</script>
<?php
require_once('misc/footer.php');
?>
