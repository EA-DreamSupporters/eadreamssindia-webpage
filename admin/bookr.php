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
width: 100px !important;
height: 100px !important;
border-radius: 0 !important;

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
                  <h4 class="card-title">Course View</h4>
                 
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">Institute</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-1" aria-selected="true">Educators</a>
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
									
									<tr>  
									
									
									<th>S.No</th>
									<th>Course Title</th> 
									<th>Institute</th> 
									<th>Duration</th> 
                                    <th>Sell Price</th> 
									<th>MRP</th> 
                                    <th>Description</th>
									<th>Features</th>
									<th>image</th>
									<th>Edit</th>					
									<th>Delete</th> 				
									

                               </tr> 								
                               </tr>  
                          </thead>  
  <?php 
						  $x=1;
						  $query ="SELECT*from course where ccate='institute'";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["ctitle"]; ?></td>
									<td><?php echo $row["ins"]; ?></td>
									<td><?php echo $row["cdura"]; ?></td>
									<td>₹<?php echo $row["csprice"]; ?></td>
									<td>₹<?php echo $row["mrp"]; ?></td>
									<td><p class="mb-0"><?php echo $row["cdesc"]; ?></p></td>
									<td><p class="mb-0"><?php echo $row["cfea"]; ?></p></td>
									<td><img src="../image/<?php echo $row["image"]; ?>" class="book"></td>
									<td><a href="course_edit.php?id=<?php echo $row['id']; ?>" class="" ><img src='images/edit.png' class='edit' height="120px" width="100px"></a></td>
									<td><a href="course_delete.php?id=<?php echo $row['id']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
									
									<tr>  
									
									
									<th>S.No</th>
									<th>Course Title</th> 
									<th>Institute</th> 
									<th>Duration</th> 
                                    <th>Sell Price</th> 
									<th>MRP</th> 
                                    <th>Description</th>
									<th>Features</th>
									<th>image</th>
									<th>Edit</th>					
									<th>Delete</th> 				
									

                               </tr> 								
                               </tr>  
                          </thead>  
  <?php 
						  $x=1;
						  $query ="SELECT*from course where ccate='educators'"; 
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["ctitle"]; ?></td>
									<td><?php echo $row["ins"]; ?></td>
									<td><?php echo $row["cdura"]; ?></td>
									<td>₹<?php echo $row["csprice"]; ?></td>
									<td>₹<?php echo $row["mrp"]; ?></td>
									<td><p class="mb-0"><?php echo $row["cdesc"]; ?></p></td>
									<td><p class="mb-0"><?php echo $row["cfea"]; ?></p></td>
									<td><img src="../image/<?php echo $row["image"]; ?>" class="book" ></td>
									<td><a href="course_edit.php?id=<?php echo $row['id']; ?>" class=""><img src='images/edit.png' class='edit'></a></td>
									<td><a href="course_delete.php?id=<?php echo $row['id']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
