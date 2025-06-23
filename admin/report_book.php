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

      <div class="main-panel">
        <div class="content-wrapper">
		<div class="col-md-12 col-xl-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Book Order</h4>
                 
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">New Orders</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-1" aria-selected="true">Accepted</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-3" role="tab" aria-controls="profile-1" aria-selected="true">Rejected</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-4" role="tab" aria-controls="profile-1" aria-selected="true">Intransist</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-5" role="tab" aria-controls="profile-1" aria-selected="true">Delivered</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">Total Orders</a>
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
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
 date_default_timezone_set("Asia/Kolkata");
 $date=date("Y-m-d");
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.orderStatus='' ORDER BY a.orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td style="color:red;"><?php if($row["orderStatus"]==NULL){ echo 'Not Processed Yet'; } ?></td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                        <table id="employee_data1" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									<th>S.No</th>
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.orderStatus='Packed' ORDER BY orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td><span class="badge badge-info"><?php echo $row["orderStatus"]; ?></span></td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                        <table id="employee_data2" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									<th>S.No</th>
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.orderStatus='Cancelled' ORDER BY orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td><span class="badge badge-danger"><?php echo $row["orderStatus"]; ?></span></td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                        <table id="employee_data3" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									<th>S.No</th>
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.orderStatus='In Transit' ORDER BY orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td><span class="badge badge-warning"><?php echo $row["orderStatus"]; ?></span></td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
					<div class="tab-pane fade" id="profile-5" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                        <table id="employee_data4" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									<th>S.No</th>
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid AND a.orderStatus='Delivered' ORDER BY orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td><span class="badge badge-success"><?php echo $row["orderStatus"]; ?></span></td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
                        <table id="employee_data5" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									<th>S.No</th>
                                    <th>Order No</th> 								
                                    <th>Order By</th>  
									<th>Order Amount (₹)</th>  	
									<th>Order Date</th> 
									<th>Order Status</th> 
									<th>Action</th> 								
                               </tr>  
                          </thead>  
 <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM orders AS a, students AS b WHERE a.userId=b.sid ORDER BY orderDate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["orderNumber"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["totalAmount"]; ?></td>
									<td><?php echo $row["orderDate"]; ?></td>
									<td>
									<?php if($row["orderStatus"] == ''){ ?>
									<span style="color:red;">Not Processed Yet	</span>
									<?php }elseif($row["orderStatus"] == 'Packed'){ ?>
									<span class="badge badge-info"><?php echo $row["orderStatus"]; ?></span>
									<?php }elseif($row["orderStatus"] == 'Cancelled'){ ?>
									<span class="badge badge-danger"><?php echo $row["orderStatus"]; ?></span>
									<?php }elseif($row["orderStatus"] == 'In Transit'){ ?>
									<span class="badge badge-warning"><?php echo $row["orderStatus"]; ?></span>
									<?php }elseif($row["orderStatus"] == 'Delivered'){ ?>
									<span class="badge badge-success"><?php echo $row["orderStatus"]; ?></span>
									<?php } ?>
									</td>
									<td><a href="order_details.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
   $(document).ready(function(){  
      $('#employee_data5').DataTable();  
 });
 </script> 
<?php
include("footer.php");
?>
