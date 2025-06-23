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
                  <h4 class="card-title">Rental Book</h4>
                 
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">New Request</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">Accepted</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-1" aria-selected="true">Rejected</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-3" role="tab" aria-controls="profile-1" aria-selected="true">Payment Paid</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-4" role="tab" aria-controls="profile-1" aria-selected="true">Overdue</a>
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
                                    <th>Book Name</th> 
									<th>Rental By</th>									
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>		
									<th>Action</th>									
                               </tr>  
                          </thead>  
   <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM rentalbooks AS a, students AS b, rentals AS c WHERE a.rid=c.rendalbook AND b.sid=c.userid AND c.rdate='$date' AND c.status='' ORDER BY c.rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["rdate"]; ?></td>
									<td><?php echo $row["enddate"]; ?></td>
									<td><?php echo $row["tdays"]; ?> Months</td>
									<td><?php echo $row["tamount"]; ?></td>
									<td><?php echo $row["paid"]; ?></td>
									<td style="color:red;"><?php if($row["substatus"]==NULL){ echo 'Not Processed Yet'; } ?></td>
									<td><a href="rental_details.php?id=<?php echo $row['rentalid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                                    <th>Book Name</th> 
									<th>Rental By</th>									
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>		
									<th>Action</th>							
                               </tr>  
                          </thead>  
                     			<?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM rentalbooks AS a, students AS b, rentals AS c WHERE a.rid=c.rendalbook AND b.sid=c.userid AND c.status='Accepted' ORDER BY c.rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["rdate"]; ?></td>
									<td><?php echo $row["enddate"]; ?></td>
									<td><?php echo $row["tdays"]; ?> Months</td>
									<td><?php echo $row["tamount"]; ?></td>
									<td><?php echo $row["paid"]; ?></td>
									<td>
									<?php if($row["status"] == ''){ ?>
									<span style="color:red;"><?php echo 'Not Processed Yet'; ?></span>
									<?php }elseif($row["status"] == 'Accepted'){ ?>
									<span class="badge badge-success"><?php echo $row["status"]; ?></span>
									<?php }elseif($row["status"] == 'Rejected'){ ?>
									<span class="badge badge-danger"><?php echo $row["status"]; ?></span>
									<?php } ?>
									</td>
									<td><a href="rental_details.php?id=<?php echo $row['rentalid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                                    <th>Book Name</th> 
									<th>Rental By</th>									
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>		
									<th>Action</th>								
                               </tr>  
                          </thead>  
                     			<?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM rentalbooks AS a, students AS b, rentals AS c WHERE a.rid=c.rendalbook AND b.sid=c.userid AND c.status='Rejected' ORDER BY c.rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["rdate"]; ?></td>
									<td><?php echo $row["enddate"]; ?></td>
									<td><?php echo $row["tdays"]; ?> Months</td>
									<td><?php echo $row["tamount"]; ?></td>
									<td><?php echo $row["paid"]; ?></td>
									<td>
									<?php if($row["status"] == ''){ ?>
									<span style="color:red;"><?php echo 'Not Processed Yet'; ?></span>
									<?php }elseif($row["status"] == 'Accepted'){ ?>
									<span class="badge badge-success"><?php echo $row["status"]; ?></span>
									<?php }elseif($row["status"] == 'Rejected'){ ?>
									<span class="badge badge-danger"><?php echo $row["status"]; ?></span>
									<?php } ?>
									</td>
									<td><a href="rental_details.php?id=<?php echo $row['rentalid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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

<div class="tab-pane fade" id="profile-3" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data3" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									<th>S.No</th>
                                    <th>Book Name</th> 
									<th>Rental By</th>									
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>		
									<th>Action</th>									
                               </tr>  
                          </thead>  
                     			<?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM rentalbooks AS a, students AS b, rentals AS c WHERE a.rid=c.rendalbook AND b.sid=c.userid AND c.status='Paid' ORDER BY c.rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["rdate"]; ?></td>
									<td><?php echo $row["enddate"]; ?></td>
									<td><?php echo $row["tdays"]; ?> Months</td>
									<td><?php echo $row["tamount"]; ?></td>
									<td><?php echo $row["paid"]; ?></td>
									<td><span class="badge badge-info"><?php if($row['status']=='Paid'){ echo 'Paid';} ?></span></td>
									<td><a href="rental_details.php?id=<?php echo $row['rentalid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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

<div class="tab-pane fade" id="profile-4" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data4" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									<th>S.No</th>
                                    <th>Book Name</th> 
									<th>Rental By</th>									
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>		
									<th>Action</th>									
                               </tr>  
                          </thead>  
                     			<?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM rentalbooks AS a, students AS b, rentals AS c WHERE a.rid=c.rendalbook AND b.sid=c.userid AND c.status='Accepted' AND c.enddate < '$date' ORDER BY c.rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["rdate"]; ?></td>
									<td><?php echo $row["enddate"]; ?></td>
									<td><?php echo $row["tdays"]; ?> Months</td>
									<td><?php echo $row["tamount"]; ?></td>
									<td><?php echo $row["paid"]; ?></td>
						  <td><span class="badge badge-warning"><?php if($row['status']=='Overdue'){ echo 'Overdue';}else{ echo "Not Exceeded";} ?></span></td>
									<td><a href="rental_details.php?id=<?php echo $row['rentalid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
