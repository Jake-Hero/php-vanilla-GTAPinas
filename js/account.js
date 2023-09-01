function loginUser()
{
	var email = document.getElementById('emailForm').value;
	var password = document.getElementById('passForm').value;
	var dataString='email='+email + '&password='+password;
	
	$.ajax(
	{
		type:"post",
		url: "index.php?page=ajax&function=userlogin",
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