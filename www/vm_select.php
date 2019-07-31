<?php
require_once('ajax/dbconfig.php');

try {
  $stmt = $conn->prepare("SELECT vm_id, template_name FROM student_vms WHERE student = :student;");
  $stmt->bindParam(':student', $_SESSION['user_id']);
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;

chdir('/gocloud/scripts');
$iso_json=shell_exec("sudo ./get_iso.sh");
$iso_files=json_decode($iso_json);
?>
<body class="cards-pf">
  <div class="container-fluid">
    <div class="row row-cards-pf">

      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card-pf">
          <div class="card-pf-heading">
            <h2 class="card-pf-title">啟動/關閉虛擬電腦教室之主機系統功能</h2>
          </div>
          <div class="card-pf-body">
            <form class="form-horizontal" id="startForm">
              <div class="form-group">
                <label class="control-label col-sm-2">課程硬碟名稱:</label>
                <div class="col-sm-10">
                  <select class="form-control" id="vmId">
                    <option value="null">請選擇</option>
                    <?php
                    foreach($rows as $row) {
                      echo '<option value="'.$row["vm_id"].'">'.$row["template_name"].'</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary">開啟機器</button>
                  <div id="loading"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="expand-collapse-pf">
                    <div class="expand-collapse-pf-link-container">
                      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#advanced">
                        <span class="fa fa-angle-right"></span>
                        進階設定
                      </button>
                      <span class="expand-collapse-pf-separator bordered"></span>
                    </div>
                    <div id="advanced" class="expand-collapse-pf-body collapse">
                      <div class="form-group">
                        <label class="control-label col-sm-2">預設開機磁碟:</label>
                        <div class="col-sm-4">
                          <select class="form-control" id="boot">
                            <option value="hd">硬碟 > 光碟</option>
                            <option value="cdrom">光碟 > 硬碟</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="control-label col-sm-2">
                          <div class="checkbox">
                            <label><input type="checkbox" id="attach">放入光碟映像檔</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <select class="form-control" id="cdrom" disabled>
                          <?php
                            foreach ($iso_files->file as $iso) {
                              echo '<option value="'.$iso->name.'">'.$iso->name.'</option>';
                            }
                          ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <?php
    include('misc/modal-alert.html');
  ?>
  <script src="assets/js/gocloud-vm.js"></script>
  <script>
    $(document).ready(function(){
      $('#startForm').submit(function(event) {
        event.preventDefault();
        var vmId = $('#vmId').val();
        var boot = $('#boot').val();
        var attach = +$('#attach').is( ':checked' );
        var cdrom = $('#cdrom').val();
        if(vmId == "null") {
          showAlert('錯誤', '請選擇課程硬碟');
        } else {
          vmStart(vmId, boot, attach, cdrom);
        }
      });
      $('#attach').change(function(){
        if ($('#attach').is(':checked')) {
          $('#cdrom').attr("disabled", false);
        } else {
          $('#cdrom').attr("disabled", true);
        }
      });
    });
  </script>
</body>
