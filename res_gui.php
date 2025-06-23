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
              <h4 class="card-title">Ea Guidance Response</h4>
			  
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
                                    <th>Date</th>   
									<th>Guidance for What ?</th>  	
									<th>Status</th> 
									<th>Guidance</th>				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $sql1 = "SELECT * FROM students WHERE username='$user'";
							$result1=mysqli_query($con,$sql1);
							$row1 = mysqli_fetch_array($result1);
							$sid=$row1['sid'];
						  $x=1;
						  $query ="SELECT a.*,b.* FROM guide AS a,students AS b WHERE a.user=b.sid AND a.user='$sid'";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["gutime"]; ?></td>
									<td><?php echo $row["msg"]; ?></td>
									<td>
									<?php if($row["status"] ==0){ ?>
									<span class="badge badge-warning"><?php echo 'Not Processed Yet'; ?></span>
									<?php }elseif($row["status"] == '1'){ ?>
									<span class="badge badge-success"><?php echo 'Responded'; ?></span>
									<?php } else { ?>
									<span class="badge badge-danger"><?php echo 'Rejected' ; ?></span>
									<?php } ?>
									</td>
									<td>
									<?php if($row["reply"] == ''){ ?>
									<span style="color:red;"><?php echo 'Not Processed Yet'; ?></span>
									<?php } else { ?>
									<span style="color:Blue;"><?php echo $row['reply']; ?></span>
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
