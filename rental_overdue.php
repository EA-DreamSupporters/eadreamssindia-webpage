<?php  
include("header3.php");  
?>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
<script>
function show_confirm() {
    return confirm("Are you sure you want to remove this?");
}
</script> 
<style>
.table td img {
    width: initial;
    height: initial;
} 
.book {
    width: 82px!important;
    height: 100px!important;
    border-radius: 0!important;
}
h4.card-title {
    margin-bottom: 0!important;
}
.table td, .table th {
    vertical-align: middle!important;
	text-align: center!important;
}
table {
  table-layout: fixed;
  width: 100%;
  border-collapse: collapse;
}

/*
 * inline-block elements expand as much as content, even more than 100% of parent
 * relative position makes z-index work
 * explicit width and nowrap makes overflow work
 */
p {
  display: inline-block;
  position: relative;
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: top;
}
/*
 * higher z-index brings element to front
 * auto width cancels the overflow
 */
p:hover {
  z-index: 1;
  width: auto;
  background-color: #FFFFCC;
}
</style>  

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Rental Books Overdue</h4>
			  
			       <div class="row">
                      <div class="col-md-12">
                        <div class="mt-3">
						
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
									<th>Rental Date</th>    
									<th>Rental End Date</th>	
									<th>Total Rental Months</th> 
									<th>Total Amount</th>
									<th>Deposit</th>
									<th>Status</th>								
									

                               </tr>  
                          </thead>  
                          <?php 
						  date_default_timezone_set("Asia/Kolkata");
							$date=date("Y-m-d");
						  $sql1 = "SELECT * FROM students WHERE username='$user'";
							$result1=mysqli_query($con,$sql1);
							$row1 = mysqli_fetch_array($result1);
							$sid=$row1['sid'];
						  $x=1;
						  $query ="SELECT a.*,b.* FROM rentalbooks AS a, rentals AS b WHERE a.rid=b.rendalbook AND b.userid='$sid' AND(b.enddate < '$date' AND b.due != '0') ORDER BY rdate DESC";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["rbook"]; ?></td>
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



        <!-- content-wrapper ends -->
<script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  
 </script> 
<?php
include("footerd.php");
?>
