function vmStart(id, boot, attach, cdrom) {
  $('#loading').addClass('spinner spinner-xs spinner-inline');
  $("#startForm button[type=submit]").attr("disabled", true);
  $.ajax({
    url: "ajax/vm.php?f=vmStart",
    method: "POST",
    data: { vmId: id, boot: boot, attach: attach, cdrom: cdrom },
    success: function(result) {
      if (result == 'ok') {
        window.location.href = "vm.php";
      } else {
        showAlert('錯誤', '開啟課程硬碟發生錯誤!');
        $('#loading').removeClass('spinner spinner-xs spinner-inline');
        $("#startForm button[type=submit]").attr("disabled", false);
      }
    }
  });
}

function vmStop(id) {
  $.ajax({
    url: "ajax/vm.php?f=vmStop",
    method: "POST",
    data: { vmId: id },
    success: function(result) {
      if (result == 'ok') {
        window.location.href = "vm.php";
      } else {
        showAlert('錯誤', '關閉課程硬碟發生錯誤!');
      }
    }
  });
}

function vmReboot(id) {
  $.ajax({
    url: "ajax/vm.php?f=vmReboot",
    method: "POST",
    data: { vmId: id },
    success: function(result) {
      if (result == 'ok') {
        window.location.href = "vm.php";
      } else {
        showAlert('錯誤', '重新啟動課程硬碟發生錯誤!');
      }
    }
  });
}

function vmCdrom(id, cdrom) {
  $.ajax({
    url: "ajax/vm.php?f=vmCdrom",
    method: "POST",
    data: { vmId: id, cdrom: cdrom },
    success: function(result) {
      window.location.href = "vm.php";
    }
  });
}


function vmConsole(id) {
  $.ajax({
    url: "ajax/vm.php?f=vmConsole",
    method: "POST",
    data: { vmId: id },
    success: function(result) {
      var blob = new Blob([result], { type: 'application/x-virt-viewer' });
      var a = document.createElement('a');
      a.download = 'console.vv';
      a.href = URL.createObjectURL(blob);
      a.click();
    }
  });
}
