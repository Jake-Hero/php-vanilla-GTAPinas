function loginUser()
{
	var email = document.getElementById('emailForm').value;
	var password = document.getElementById('passForm').value;
	var dataString='email='+email + '&password='+password;
	
	$.ajax(
	{
		type:"post",
		url: "ajax.php?function=userlogin",
		data: dataString,
		success: function(html)
		{
			$("#ajax").html(html);
		},
        error: function(jqXHR, textStatus, errorThrown) {
            //console.log(textStatus, errorThrown);
        }
	});
	return false;
}

function settingUser()
{
	var newpass = document.getElementById('passForm').value;
	var curpassword = document.getElementById('curpassForm').value;
	var dataString='password='+ curpassword + '&newpassword='+newpass;
	
	$.ajax(
	{
		type:"post",
		url: "ajax.php?function=settingsave",
		data: dataString,
		success: function(html)
		{
			$("#ajax").html(html);
		},
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
	});
	return false;
}