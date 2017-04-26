<?php 
  $pageTitle="Project: " . $infoProject['name'];
  include "inc/header.php"; 
  
  if(!isset($_SESSION['user'])){
  	header('Location:../view/index.php');
  }
?>

<body>

     <?php include "inc/nav.php";  ?>

   <section id="content" role="main" class="container">
    <div id="homepage-panel">
      <div class="row">          
        <div class="myproject-header">
          <div class="myproject-title col-xs-12 col-md-10">
            <h2><img style="width: 20px;" src="../view/images/bug.png" alt="<?php echo $infoProject['name']; ?>"> Project: <?php echo $infoProject['name']; ?></h2>
          </div>
          <div class="myproject-button col-xs-12 col-md-2">
             <button onclick="location.href = '../controller/MyProjectsController.php';" class="btn btn-primary">Back</button>
          </div>
        </div>
      </div>
       <div class="row">  
        <table id="viewproject" style="width: 80%;" class="myproject-table table table-responsive table-bordered">
            <tr>
              <th>Name</th>
              <td><?php echo $infoProject['name']; ?></td>
            </tr>
            <tr>
              <th >Prefix</th>
              <td><?php echo $infoProject['prefix']; ?></td>
            </tr>
            <tr>
              <th>Admin</th>
              <td><?php echo $infoProject['username']; ?></td>
            </tr>
             <tr>
              <th>Client</th>
              <td><?php echo $infoProject['client']; ?></td>
            </tr>
             <tr>
              <th>Status</th>
              <td><?php echo $infoProject['status']; ?></td>
            </tr>
             <tr>
              <th>All tasks</th>
              <td><?php echo $infoProject['all_tasks']; ?></td>
            </tr>
            <tr>
              <th>Progress</th>
              <td><?php if($infoProject['avg_tasks_progress'] == null){
              				echo "<em>No tasks found.</em>";
              			}else{?>
              			
              	  <div class="progress-wrap progress" data-progress-percent="<?= $infoProject['avg_tasks_progress']?>">
				  <div class="progress-bar progress"></div>
				  
				</div>
				<p class="progress_perc" ><?=$infoProject['avg_tasks_progress']?>%</p>
             			<?php }?>
              </td>
            </tr>
             <tr>
              <th>Start Date</th>
              <td><?=($infoProject['start_date'] == '0000-00-00' ? "<em style='color:red;'>Not set</em>" : $infoProject['start_date'])?></td>
            </tr>
             <tr>
              <th>End Date</th>
			  <td><?=($infoProject['end_date'] == '0000-00-00'? "<em style='color:red;'>Not set</em>" : $infoProject['end_date'])?></td>
            </tr>
            <tr>
              <th>Email</th>
             <td>
                 <a href="mailto:<?php if(isset($infoProject['user_email'])){$infoProject['user_email'];}?>"><span class="glyphicon glyphicon-envelope"></span></a>
              </td>
            </tr>
        </table>
        <div class="bg-success">
          <p>Export as word, exel, pdf</p>
        </div>

       

      </div> <!-- class="row" -->
    </div>
   </section><!-- /.container -->
<?php include "inc/footer.php"; ?>

   