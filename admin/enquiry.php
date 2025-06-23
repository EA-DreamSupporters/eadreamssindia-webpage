<?php  
include("header.php");  
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
.check{
	border-radius: 0!important;
}
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
              <h4 class="card-title">Received Enquiry</h4>
			  
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
									<th>User</th> 
                                    <th>Subject</th> 
									<th>Message</th>
									<th>Status</th>
									<th>Action</th>				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $x=1;
						  $query ="SELECT a.*,b.* FROM enquiry AS a, students AS b WHERE a.uid=b.sid ORDER BY date";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["date"]; ?></td>
									<td><?php echo $row["name"]; ?></td>
									<td><?php echo $row["subject"]; ?></td>
									<td><?php echo $row["message"]; ?></td>
									<td><?php if($row["status"] == 1){ echo "<img src='images/1.jpg' class='check'>"; }else{ echo "<img src='images/2.jpg' class='check'>"; } ?></td>
									<td><a href="respond_enquiry.php?id=<?php echo $row['eid']; ?>" class=""><img src='images/edit.png' class='edit'></a> </td>
									
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
include("footer.php");
?>
