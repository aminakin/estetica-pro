<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<title>Document</title>
</head>
<body>
	<div class="res"></div>
	<script>
	$(document).ready(function() {
		var files = [
		'import0_1.xml',
		/*'import0_1.xml',
		'import0_2.xml',
		'import0_3.xml',
		'import0_4.xml',
		'import0_5.xml',
		'import0_6.xml',
		'import0_7.xml',
		'import0_8.xml',
		'import0_9.xml',
		'import0_10.xml',
		'import0_11.xml',
		'import0_12.xml',
		'import0_13.xml',
		'import0_14.xml',
		'import0_15.xml',
		'import0_16.xml',
		'import0_17.xml',
		'import0_18.xml',
		'import0_19.xml',
		'import0_20.xml',*/
		
		'offers0_1.xml',
		/*'offers0_2.xml',
		'offers0_1.xml',
		'offers0_3.xml',
		'offers0_4.xml',
		'offers0_5.xml',
		'offers0_6.xml',
		'offers0_7.xml',
		'offers0_8.xml',
		'offers0_9.xml',
		'offers0_10.xml',
		'offers0_11.xml',
		'offers0_12.xml',
		'offers0_13.xml',
		'offers0_14.xml',
		'offers0_15.xml',
		'offers0_16.xml',
		'offers0_17.xml',
		'offers0_18.xml',
		'offers0_19.xml',
		'offers0_20.xml',*/
		];
		console.log(files);
		vg(files);
		function vg(files) {
			url1="/bitrix/admin/1c_exchange.php?type=catalog&mode=import&filename="+files[0];
			$.ajax({
				url: url1,
				success: function(data){
					$('.res').append(files[0] + '  -  ' + data + '<br/>');
					if (data.substr(0,7)=="success") {
						files.shift();
					}
					if (files.length)
						vg(files);
				}
			});
		}
	});
</script>
</body>
</html>
