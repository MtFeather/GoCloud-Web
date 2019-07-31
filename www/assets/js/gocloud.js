$(document).ready(function(){
  $('.expand-collapse-pf button').click(function() {
    $(this).find('span').toggleClass("fa-angle-down fa-angle-right");
  }); 
});

function showAlert(t, m) {
  $('#alertModal .modal-header h4').text(t);
  $('#alertModal .modal-body').html(m);
  $('#alertModal').modal('show');
}

function showComfirm(t, m) {
  $('#comfirmModal .modal-header h4').text(t);
  $('#comfirmModal .modal-body').html(m);
  $('#comfirmModal').modal('show');
}
