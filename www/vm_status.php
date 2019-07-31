<?php
require_once('ajax/dbconfig.php');

try {
  $stmt = $conn->prepare("SELECT template_name FROM student_vms WHERE vm_id = :vm_id;");
  $stmt->bindParam(':vm_id', $vm_up_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;

chdir('/gocloud/scripts');
$output=shell_exec("sudo ./vm_xml.sh $vm_up_id");
$vm=json_decode($output, true);

$iso=shell_exec("sudo ./vm_current_cdrom.sh $vm_up_id");

$iso_json=shell_exec("sudo ./get_iso.sh");
$iso_files=json_decode($iso_json);
//echo "<script>console.log($output);</script>";
?>
<body class="cards-pf">
  <div class="container-fluid">
    <div class="row toolbar-pf">
      <div class="col-sm-12">
        <div class="toolbar-pf-actions">
          <div class="toolbar-pf-action-right toolbar-pf-height-shim">
            <div class="form-group toolbar-pf-view-selector">
              <div class="actions-line">
                <button class="btn btn-danger" id="shutdown">關閉機器</button>
                <button class="btn btn-default" id="reboot">重新開機</button>
                <button class="btn btn-default" id="console">Spice</button>
                <button class="btn btn-default" id="cdrom" data-toggle="modal" data-target="#cdromModal">更換光碟</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row row-cards-pf">

      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="card-pf">
          <div class="card-pf-heading">
            <h2 class="card-pf-title"><?php echo $result['template_name']; ?></h2>
          </div>
          <div class="card-pf-body">
            <div class="form-horizontal">
              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">Host:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['host']['name']; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">光碟:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo ($iso == '') ? '無光碟' : $iso; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">CPU 位元:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['cpu']['architecture']; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">vCPU:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['cpu']['topology']['cores']*$vm['cpu']['topology']['sockets']*$vm['cpu']['topology']['threads']; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">BIOS:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['bios']['type']; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">記憶體:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['memory']/1024/1024; ?> MB</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">硬碟容量:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['disk_attachments']['disk_attachment'][0]['disk']['provisioned_size']/1024/1024/1024; ?> GB</p>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="control-label col-sm-2">MAC:</label>
                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $vm['nics']['nic'][0]['mac']['address']; ?></p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="modal fade" id="cdromModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
            <span class="pficon pficon-close"></span>
          </button>
          <h4 class="modal-title">更換光碟</h4>
        </div>
        <div class="modal-body">
          <div class="form-horizontal">
            <div class="form-group">
              <label class="control-label col-sm-3">請選擇光碟:</label>
              <div class="col-sm-8">
                <select class="form-control" id="iso">
                  <option value="">退出光碟</option>
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
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <?php
    require_once('misc/modal-alert.html');
    require_once('misc/modal-comfirm.html');
  ?>
  <script src="assets/js/gocloud-vm.js"></script>
  <script>
    $(document).ready(function(){
      var vm_id="<?php echo $vm_up_id; ?>";
      $('#shutdown').click(function(){
        showComfirm('關閉機器', '確定要關閉此機器嗎?');
        $('#comfirmModal button[type=submit]').click(function(){
          $('#comfirmModal .modal-body').html('<div class="spinner spinner-lg blank-slate-pf-icon"></div>');
          vmStop(vm_id);
        });
      });

      $('#reboot').click(function(){
        showComfirm('重新開機', '確定要重新啟動機器嗎?');
        $('#comfirmModal button[type=submit]').click(function(){
          $('#comfirmModal .modal-body').html('<div class="spinner spinner-lg blank-slate-pf-icon"></div>');
          vmReboot(vm_id);
        });
      });

      $('#console').click(function(){
        vmConsole(vm_id);
      });

      $('#cdromModal button[type=submit]').click(function(){
        var iso = $('#iso').val();
        $('#cdromModal .modal-body').html('<div class="spinner spinner-lg blank-slate-pf-icon"></div>');
        vmCdrom(vm_id, iso);
      });
    });
  </script>
</body>
