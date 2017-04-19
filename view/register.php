<?php 
  $pageTitle="Register form";
  include "inc/header.php"; 
?>

<body>
   <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
         <div class="navbar-header">
            <a class="navbar-brand" href="#"><img src="/FinalProject/view/images/aui-header-logo-jira.png" alt=""></a>
         </div>
      </div>
      <!--  class="container" -->
   </nav>
   <section id="content" role="main" class="container">
     <div id="register-panel">
        <div class="register-header">
           <h2>Administrator account setup</h2>
        </div>
        <hr>
        <div class="register-body">

        	<p>You'll need administrator credentials to log in to JIRA. Please provide an first name. last name, email address, username and password for your first administrator account. Make sure the password is secure. If you have account <a href="/FinalProject/view/index.php">Log in</a></p>

			<form id="jira-setup-account" action="../controller/RegisterController.php" method="post">
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-username">First Name</label>
					</div>
					<div class="col-xs-12 col-md-10">
			    		<input class="form-control" type="text" name="firstname" id="jira-setup-account-field-firstname" placeholder="Type in a first name" required="required">
			    	</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-username">Last Name</label>
					</div>
					<div class="col-xs-12 col-md-10">
						<input class="form-control" type="text" name="lastname" id="jira-setup-account-field-lastname" placeholder="Type in a last name" required="required">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-email">Email</label>
					</div>
					<div class="col-xs-12 col-md-10">
						<input class="form-control" type="email" name="email" id="jira-setup-account-field-email" placeholder="Type your email address" required="required">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-username">Username</label>
					</div>
					<div class="col-xs-12 col-md-10">
						<input class="form-control" type="text" name="username" id="jira-setup-account-field-username" placeholder="Type in a username" required="required">
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-password">Password</label>
					</div>
					<div class="col-xs-12 col-md-10">
			    		<input class="form-control" type="password" name="password" id="jira-setup-account-field-password" placeholder="Enter a password" required="required">
			    	</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-md-2">
						<label for="jira-setup-account-field-retype-password">Re-type password</label>
					</div>
					<div class="col-xs-12 col-md-10">
				    	<input class="form-control" type="password" name="repassword" id="jira-setup-account-field-retype-password" placeholder="Re-type password" required="required">
				    </div>
				</div>
				<div class="buttons-container text-right">
					<div class="buttons">	
						<input type="submit" class="btn btn-primary" name="submit" value="Register">
					</div>
				</div> <!-- class="buttons-container" -->
			</form>

        </div> <!-- class="register-body" -->
     </div> <!-- id="register-panel" -->
   </section><!-- /.container -->
<?php include "inc/footer.php"; ?>

   