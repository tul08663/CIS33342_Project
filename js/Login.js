$(document).ready(function(){
});

function ShowLogin(){
    $('#LoginDiv').show();
    $('#AcodeDiv').hide();
    $('#RegisterDiv').hide();
}

function ShowRegister(){
    $('#LoginDiv').hide();
    $('#AcodeDiv').hide();
    $('#RegisterDiv').show();
}

function ShowAcode(){
    $('#LoginDiv').hide();
    $('#AcodeDiv').show();
    $('#RegisterDiv').hide();
}
/*
$('.GoToRegister').click(function(){
    ShowRegister();
})

$('.GoToLogin').click(function(){
    ShowLogin();
})
*/