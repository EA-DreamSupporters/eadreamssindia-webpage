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
	border-radius:5px;
} 
h4.card-title {
    margin-bottom: 0!important;
}
#banner{
	width:400px;
	height:200px;
}
</style>  

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Banner View</h4>
			  
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                     <table id="employee_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
									
									
									<th>S.No</th>
                                    <th>Position</th> 
									<th>Banner Image</th>									
									<th>Edit</th>									
									<th>Delete</th> 				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $x=1;
						  $query ="SELECT * FROM tblbanner";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php if($row["position"]=='mag'){ echo "Magazine Page";}elseif($row['position']=='book'){ echo"Book Page";} elseif($row['position']=='side1'){ echo "Book Page";} else{ echo "Course Page";}?></td>
									<td><img src="image/<?php echo $row['banner'];?>" id="banner"></td>
									<td><a href="edit_banner.php?id=<?php echo $row['bannerID']; ?>" class="" ><img src='images/edit.png' id='edit'></td>
									
									<td><a href="delete_banner.php?id=<?php echo $row['bannerID']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
