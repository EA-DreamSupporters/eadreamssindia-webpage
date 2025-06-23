<?php  
include("header.php");  
?>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
<script>
function show_confirm() {
    return confirm("Are You Sure");
}
</script> 
<style>
.table td img {
    width: initial;
    height: initial;
} 
h4.card-title {
    margin-bottom: 0!important;
}
ul.nav.nav-tabs {
    margin-top: 30px;
}
.media {
    display: initial;
}
.media .adddealer {
    margin-top: 0!important;
}
</style>  
<?php 

?>
      <div class="main-panel">
        <div class="content-wrapper">
		<div class="col-md-12 col-xl-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Test Subscription</h4>
                 
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">New Subscription</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">Accepted Subscription</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-1" aria-selected="true">Rejected Subscription</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-3" role="tab" aria-controls="profile-1" aria-selected="true">Total Subscription</a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active show" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                      <div class="media">
					  <div class="row">
                      <div class="col-md-12">
                        <div class="mt-3 adddealer">
						
						</div>
					</div>
				</div>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                     <table id="employee_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									<th>S.No</th>
                                    <th>Magazine</th>
									<th>Subscribed By</th> 									
									<th>End Date</th>
									<th>Subscribtion Status</th>
									<th>Action</th> 									
                               </tr>  
                          </thead>  
  <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM test AS a, students AS b, test_subscription AS c WHERE a.id=c.mid AND b.sid=c.uid AND c.mid=a.id AND c.status=0"; 
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["title"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["intime"]; ?></td>
									<td style="color:red;"><?php if($row["status"]==0){ echo 'Not Processed Yet'; } ?></td>
									<td><a href="test_substatus.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
                               </tr> 
						  <?php							        
                          $x++;
                          }   
                          ?> 
                     </table>   
                </div>
                </div>
              </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                         <table id="employee_data1" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									<th>S.No</th>
                                    <th>Magazine</th>
									<th>Subscribed By</th> 									
									<th>End Date</th>
									<th>Subscribtion Status</th>
									<th>Action</th> 								
                               </tr>  
                          </thead>  
  <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						$query ="SELECT a.*,b.*,c.* FROM test AS a, students AS b, test_subscription AS c WHERE a.id=c.mid AND b.sid=c.uid AND c.mid=a.id AND c.status=1";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["title"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["intime"]; ?></td>
									<td><span class="badge badge-success"><?php if($row["status"]==1){ echo 'Accepted'; } ?></span></td>
									<td><a href="test_substatus.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
                               </tr> 
						  <?php							        
                          $x++;
                          }   
                          ?> 
                     </table>   
                </div>
                </div>
              </div>
                      </div>
                    </div>
<div class="tab-pane fade" id="profile-2" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                         <table id="employee_data2" class="table table-striped table-bordered">  
                          <thead>  
                                               <tr>  
								<th>S.No</th>
                                    <th>Magazine</th>
									<th>Subscribed By</th> 									
									<th>End Date</th>
									<th>Subscribtion Status</th>
									<th>Action</th> 								
                               </tr>  
                          </thead>  
  <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						$query ="SELECT a.*,b.*,c.* FROM test AS a, students AS b, test_subscription AS c WHERE a.id=c.mid AND b.sid=c.uid AND c.mid=a.id AND c.status=-1";
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["title"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["intime"]; ?></td>
									<td><span class="badge badge-danger"><?php if($row["status"]==-1){ echo 'Rejected'; } ?></span></td>
									<td><a href="test_substatus.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
                               </tr> 
						  <?php							        
                          $x++;
                          }   
                          ?> 
                     </table>   
                </div>
                </div>
              </div>
                      </div>
                    </div><div class="tab-pane fade" id="profile-3" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                         <table id="employee_data3" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									<th>S.No</th>
                                    <th>Magazine</th>
									<th>Subscribed By</th> 									
									<th>Subscribtion Date</th> 
									<th>Subscribtion Status</th>
									<th>Action</th> 									
                               </tr>  
                          </thead>  
  <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
				$query ="SELECT a.*,b.*,c.* FROM test AS a, students AS b, test_subscription AS c WHERE a.id=c.mid AND b.sid=c.uid AND c.mid=a.id";
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["title"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["intime"]; ?></td>
									<td>
									<?php if($row["status"] == -1){ ?>
									<span class="badge badge-danger">Rejected</span>
									<?php }elseif($row["status"] == 1){ ?>
									<span class="badge badge-success">Accepted</span>
									<?php }else{ ?>
									<span style="color:red;">Not Processed Yet</span>
									<?php } ?></td>
									<td><a href="test_substatus.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
                               </tr> 
						  <?php							        
                          $x++;
                          }   
                          ?> 
                     </table>   
                </div>
                </div>
              </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>
       
        <!-- content-wrapper ends -->
<script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  
  $(document).ready(function(){  
      $('#employee_data1').DataTable();  
 });
   $(document).ready(function(){  
      $('#employee_data2').DataTable();  
 });
   $(document).ready(function(){  
      $('#employee_data3').DataTable();  
 });
   $(document).ready(function(){  
      $('#employee_data4').DataTable();  
 });
 </script> 
<?php
include("footer.php");
?>
