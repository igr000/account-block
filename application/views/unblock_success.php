<!DOCTYPE html>
<!--------------------------------------------------------------------------------------
-- Unblock_success page will show if user unblock account request has been successful. -
----------------------------------------------------------------------------------------
-- Author: Irene Gayle Roque -----------------------------------------------------------
--------------------------------------------------------------------------------------->
<head>
	<title>Unblock Account</title>
	<!-- Style -->
	<style type="text/css">
		html{
			background: #FDF2E9;
			background: -webkit-linear-gradient(left, #F5CBA7, #FDF2E9);
            background: -o-linear-gradient(left, #F5CBA7, #FDF2E9);
            background: -moz-linear-gradient(left, #F5CBA7, #FDF2E9);
            background: linear-gradient(left, #F5CBA7, #FDF2E9);
            padding: 15px;
		}
	</style>
</head>
<body>
	<h1>Congratulations! Your account is now unblocked!</h1>
	<!-- Goes back to account_login page if 'here' is clicked -->
	<em>You may be able to login again to our website by clicking <a href="<?php echo base_url('Account_login'); ?>">here</a></em>
</body>
</html>