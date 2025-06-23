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
.table td img {
    width: initial;
    height: initial;
} 
h4.card-title {
    margin-bottom: 0!important;
}
</style>  

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Subject</h4>
			  
			       <div class="row">
                      <div class="col-md-12">
                        <div class="mt-3">
						<a href="subject_add.php"><button type="button" class="btn btn-success btn-icon-text" style="float: right; margin-bottom: 10px;">
                          <i class="ti-plus btn-icon-prepend"></i>                                                    
                          Add New subject
                        </button>
						</a>
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
                                    <th>Subject</th> 
									<th>Edit</th>					
									<th>Delete</th> 				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $x=1;
						  $query ="SELECT * FROM subject";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["subject"]; ?></td>
									<td><a href="subject_edit.php?id=<?php echo $row['subjectid']; ?>" class=""><img src='images/edit.png' class='edit'></a></td>
									<td><a href="subject_delete.php?id=<?php echo $row['subjectid']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
