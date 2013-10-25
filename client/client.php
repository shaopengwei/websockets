

<!DOCTYPE html>
<html>
<head>
<meta charset="utf8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
window.onload=function() {

	if(!("WebSocket" in window)){
		$('#chatLog, input, button, #examples').fadeOut("fast");	
		//$('<p>Oh no, you need a browser that supports WebSockets. How about <a href="http://www.google.com/chrome">Google Chrome</a>?</p>').appendTo('#container');		
		window.location.href = 'FlashSockectClient.html';
		}
	else{
		//The user has WebSockets
		connect();
	}
};

function connect(){
		var socket;
		var host = "ws://localhost:8787/socket/server/startDaemon.php";
		
		try{
			var socket = new WebSocket(host);
			message('<p class="event">Socket Status: '+socket.readyState);
			message('<p class="event">name: (自己的用户名)');
			socket.onopen = function(){
				message('<p class="event">Socket Status: '+socket.readyState+' (open)');	
			};

			socket.onmessage = function(msg){
					message('<p class="message">Sent from (自己的用户名): '+msg.data);
			};

			socket.onclose = function(){
				message('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
			};

		} catch(exception){
			message('<p>Error'+exception);
		}

		function send(){
			var text = $('#text').val();

			if(text==""){
				message('<p class="warning">内容不能为空！');
				return ;	
			}

			try{
				socket.send(text);
				message('<p class="event">Sent from (自己的用户名): '+text);
				//将发送的内容写入数据库
				storemessage($('#text').val());
			} catch(exception){
				message('<p class="warning">');
			}
			$('#text').val("");
		};

		function message(msg){
			$('#chatLog').append(msg+'</p>');
		};//End message()

		$('#text').keypress(function(event) {
		  if (event.keyCode == '13') {
			 send();
		   }
		});

		$('#disconnect').click(function(){
			socket.close();
		});
};

var xmlHttp;

function storemessage(str)
{ 
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	{
		alert ("浏览器不支持HTTP请求！");
		return;
	}
	var url="store.php";
	url=url+"?q="+str;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged ;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
};

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText;
	} 
};

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
};

		
</script>

<style type="text/css">
body{font-family:Arial, Helvetica, sans-serif;}
#container{
	border:5px solid grey;
	width:800px;
	margin:0 auto;
	padding:10px;
}
#chatLog{
	padding:5px;
	border:1px solid black;
}
#chatLog p{margin:0;}
.event{color:#999;}
.warning{
	font-weight:bold;
	color:#CCC;
}
</style>

<title>聊天窗口</title>

</head>
<body>
  <div id="wrapper">
  
  	<div id="container">
    
    	<h1>聊天窗口</h1>
        
        <div id="chatLog">
        </div>
        
        <p></p>
        
    	<input id="text" type="text" />
        <button id="disconnect">断开连接</button>

	</div>
  
  </div>
</body>
</html>
