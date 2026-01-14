let inputs = {};

$(document).on('click', 'button[type=submit]', function() {
  let page;
  if ($(this).text() === "Log In") {
    page = "login.php";
  } else {
    page = "register.php";
  }

  jQuery.ajax({
    url: "ajax/login/"+page,
    data:'username='+inputs.username
          +"&password="+inputs.password,
    type: "POST",
  success:function(res){
    console.log(res);
    let status = JSON.parse(res).status;

    if (status === "success") {
      location.reload();
    } else {
      let msg = JSON.parse(res).msg;
      $("#errormessage").text(msg); //error message
    }
  },
  error:function(err){
    let res = JSON.parse(err.responseText);

    $("#errormessage").text(res.msg); //error message
  }
  });
});

$(document).on('input', 'input', function() {
  let key = $(this).attr('name');
  let value = $(this).val();

  inputs[key] = value;
});

$(document).on('click', '#toggleState', function() {
  if ($(this).text() === "Register") {
    $(this).text("Log In");
    $("button[type=submit]").text("Register");
    $("#login-title").text("Register");
  } else {
    $(this).text("Register");
    $("button[type=submit]").text("Log In");
    $("#login-title").text("Log In");
  }
})