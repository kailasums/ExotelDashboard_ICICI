
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<style>
	
	.mail_container{
		font-family: 'Roboto', sans-serif;
	}
	table {
		font-family: 'Roboto', sans-serif;
	    border-collapse: collapse;
	    width: 100%;
	}

	td, th {
	    border: 1px solid #dddddd;
	    text-align: left;
	    padding: 8px;
	}

	tr:nth-child(even) {
	    background-color: #dddddd;
	}
	.mailerfoterlogo{
		float:left;
		width:100%;
		display:100%;
	}
</style>
<div style="width: 600px; margin:0 auto; border:1px solid #d8d8d8;float: left;padding: 25px;" class='mail_container'>
	<!--<div style="float:left;width:600px; padding-top: 25px;">-->
	<div>
	
		<p>Hi <?php echo e($details['userDetails']['name']); ?>,</p>

		You have been registered on Exotel Dashboard for call logs. Please find the below login details.  <br><br>

		<table border="1" style="border-collapse: collapse;">
			<tr>
				<td><b>URL</b></td>
				<td>http://<domainname>.com</td>
			</tr>
			<tr>
				<td><b>LOGIN ID</b></td>
				<td>sachin@test.com</td>
			</tr>
	
			<tr>
				<td><b>PASSWORD</b></td>
				<td>test@1234</td>
			</tr>
			
			<tr>
				<td><b>DESIGNATION</b></td>
				<td>BM</td>
			</tr>
			
			

		</table> <br><br>

        <b>Note*</b>: Please do not share your credentials with anyone <br><br>


		Regards,<br>
		Exotel Dashboard Team
	</div>
	<table style="border: 0px solid #fff;">
			<tbody>
				<br>
			<tr style="background-color: transparent;">
				<td style="border: 0px;">
					<!-- <img src="http://65.0.130.85/images/logo.png" style="width: 300px;margin-left:-10px; text-align: left;"></td> -->
			</tr>
	</tbody>
	</table>
</div><?php /**PATH /var/www/html/ISPCalling/resources/views/emails/registeruser.blade.php ENDPATH**/ ?>