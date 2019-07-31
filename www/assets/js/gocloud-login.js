$(document).ready(function(){
  $('#loginForm').submit(function(event) {
    event.preventDefault();
    if (validateLoginForm() != 'false') {
      checkLogin();
    }
  });
});

function validateLoginForm() {
  var e = '';
  var c = [$('#account'), $('#password')];
  for (var i = 0; i < c.length; i++) {
    c[i].closest('.form-group').removeClass('has-error');
    c[i].closest('.form-group').find('.help-block').hide();
  }
  for (var i = 0; i < c.length; i++) {
    if(c[i].val() == '') {
      e = 'false';
      c[i].closest('.form-group').addClass('has-error');
      c[i].closest('.form-group').find('.help-block').show();
    }
  }
  return e;
}

function checkLogin() {
  $.ajax({
    url: "ajax/checkLogin.php",
    method: "POST",
    data: { account: $('#account').val(), password: $('#password').val() },
    success: function(result) {
      if (result == 'success') {
        window.location.href = "vm.php";
      } else {
        $('.login-pf-header span').html('The account or password you entered is incorrect.');
      }
    }
  });
}
