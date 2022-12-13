var regState;
var email;
var firstName;
var lastName;
var rememberMe;

$(document).ready(function() {
  $.ajax(
    {
      type: 'GET',
      url: 'php/readRegState.php',
      async: false,
      dataType: "json",
      encode: false
    }
  ).always(function(data) {
       // log data to the console so we can see
       console.log(data);
      // Report RegState
      regState = parseInt(data);
    }
  );

  switch(regState) {
    case 6:
      $("#welcomeMessage").text("Welcome, " + window.localStorage.getItem("firstName"));
      $("#logoutMessage").text(window.localStorage.getItem("message"));
      break;
    case 3:
      $('#LoginDiv').hide();
      $('#RegisterDiv').hide();
      $('#SetPasswordDiv').hide();
      $('#ForgotPasswordDiv').show();
      break;
    case 2:
      $('#LoginDiv').hide();
      $('#RegisterDiv').hide();
      $('#SetPasswordDiv').show();
      $('#ForgotPasswordDiv').hide();
      break;
    case 1:
      $('#LoginDiv').hide();
      $('#RegisterDiv').show();
      $('#SetPasswordDiv').hide();
      $('#ForgotPasswordDiv').hide();
      break;
    default:
      $('#LoginDiv').show();
      $('#RegisterDiv').hide();
      $('#SetPasswordDiv').hide();
      $('#ForgotPasswordDiv').hide();
  }

  $("#register-btn").click(function() {
    $.ajax(
      {
        type: 'GET',
        url: 'php/register0.php',
        async: false,
        dataType: "json",
        encode: false
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data);
        location.reload();
      }
    );
  });

  $("#reset-password-btn").click(function() {
    $.ajax(
      {
        type: 'GET',
        url: 'php/forget0.php',
        async: false,
        dataType: "json",
        encode: false
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data);
        location.reload();
      }
    );
  });

  $(".back-btn").click(function() {
    $.ajax(
      {
        type: 'GET',
        url: 'php/backButton.php',
        async: false,
        dataType: "json",
        encode: false
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data);
        location.reload();
      }
    );
  });

  $("#register").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "first-name" : $('input[name=first-name]').val(),
      "last-name" : $("input[name=last-name]").val(),
      "email": $("input[name=email]").val()
    };
  //make ajax call
    $.ajax(
      {
        type: 'GET',
        url: 'php/register.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
         //console.log(parseInt(data.RegState));
        // Report RegState
        regState = parseInt(data.RegState);
        email = data.Email;
        //define view according to regState
        if (regState == 5) {
          window.location.href="/~tuj93221/3342/final_project/MFA/confirm_google_auth.php";
        } else {
          $('#LoginDiv').hide();
          $('#RegisterDiv').show();
          $('#SetPasswordDiv').hide();
          $('#ForgotPasswordDiv').hide();
          $('#registerMessage').html(data.Message);
        }
      }
    )
  });

  $("#auth").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "acode" : $('input[name=acode]').val(),
      "email" : email
    };
  //make ajax call
    $.ajax(
      {
        type: 'POST',
        url: 'php/authenticate.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data.RegState);
        //define view according to regState
        if (regState == 2) {
          $('#LoginDiv').hide();
          $('#RegisterDiv').hide();
          $('#SetPasswordDiv').show();
          $('#ForgotPasswordDiv').hide();
          $('#setPasswordMessage').html(data.Message);
        } else {
          $('#LoginDiv').hide();
          $('#RegisterDiv').show();
          $('#SetPasswordDiv').hide();
          $('#ForgotPasswordDiv').hide();
          $('#registerMessage').html(data.Message);
        }
      }
    );
  });

  $("#reset-password").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "reset-password-email": $("input[name=reset-password-email]").val()
    };
  //make ajax call
    $.ajax(
      {
        type: 'POST',
        url: 'php/resetPassword.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data.HELP);
        // Report RegState
        regState = parseInt(data.RegState);
        email = data.Email;
        //define view according to regState
        $('#LoginDiv').hide();
        $('#RegisterDiv').hide();
        $('#SetPasswordDiv').hide();
        $('#ForgotPasswordDiv').show();
        $('#resetPasswordMessage').html(data.Message);
      }
    )
  });

  $("#auth2").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "acode2" : $('input[name=acode2]').val(),
      "email" : email
    };
  //make ajax call
    $.ajax(
      {
        type: 'POST',
        url: 'php/authenticate2.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
        console.log(data);
        // Report RegState
        regState = parseInt(data.RegState);
        //define view according to regState
        if (regState == 2) {
          $('#LoginDiv').hide();
          $('#RegisterDiv').hide();
          $('#SetPasswordDiv').show();
          $('#ForgotPasswordDiv').hide();
          $('#setPasswordMessage').html(data.Message);
        } else {
          $('#LoginDiv').hide();
          $('#RegisterDiv').hide();
          $('#SetPasswordDiv').hide();
          $('#ForgotPasswordDiv').show();
          $('#resetPasswordMessage').html(data.Message);
        }
      }
    );
  });

  $("#set-password").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "new-password" : $('input[name=new-password]').val(),
      "reenter-password" : $('input[name=reenter-password]').val()
    };
  //make ajax call
    $.ajax(
      {
        type: 'POST',
        url: 'php/setPassword.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data.RegState);
        //define view according to regState
        if (regState == 0) {
          $('#LoginDiv').show();
          $('#RegisterDiv').hide();
          $('#SetPasswordDiv').hide();
          $('#ForgotPasswordDiv').hide();
          $('#loginMessage').html(data.Message);
        } else {
          $('#LoginDiv').hide();
          $('#RegisterDiv').hide();
          $('#SetPasswordDiv').show();
          $('#ForgotPasswordDiv').hide();
          $('#setPasswordMessage').html(data.Message);
        }
      }
    );
  });

  $("#login").click(function(e) {
    event.preventDefault(e);
    // Get form data items in xml form
    var formData = {
      "login-email" : $('input[name=login-email]').val(),
      "login-password" : $('input[name=login-password]').val()
    };
  //make ajax call
    $.ajax(
      {
        type: 'POST',
        url: 'php/login.php',
        async: true,
        data: formData,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data.RegState);
        //define view according to regState
        if (regState == 4) {
          window.localStorage.setItem("message", data.Message);
          window.localStorage.setItem("firstName", data.FirstName);
          window.location.href="/~tuj93221/3342/final_project/MFA/validate_login.php";
        } else {
          $('#loginMessage').html(data.Message);
        }
      }
    );
  });

  $("#logout").click(function(e) {
    event.preventDefault(e);
    $.ajax(
      {
        type: 'GET',
        url: 'php/logout.php',
        async: true,
        dataType: "json",
        encode: true
      }
    ).always(function(data) {
         // log data to the console so we can see
         console.log(data);
        // Report RegState
        regState = parseInt(data.RegState);
        //define view according to regState
        if (regState == 0) {
          window.location.href="/~tuj93221/3342/final_project/index.html";
        } else {
          $('#logoutMessage').html(data.Message);
        }
      }
    );
  });
});
