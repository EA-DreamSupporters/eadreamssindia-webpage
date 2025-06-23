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
              <h4 class="card-title">Precontent</h4>
			  
			       <div class="row">
                      <div class="col-md-12">
                        <div class="mt-3">
						<a href="precontent_add.php"><button type="button" class="btn btn-success btn-icon-text" style="float: right; margin-bottom: 10px;">
                          <i class="ti-plus btn-icon-prepend"></i>                                                    
                          Add New
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
                                    <th>Topic</th> 
									<th>Title1</th>
									<th>Title2</th>
									<th>Title3</th>
									<th>Title4</th>
									<th>Title5</th>
									<th>Edit</th>					
									<th>Delete</th> 				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM precontent AS a, topic AS b, subject AS c WHERE a.subjid=c.subjectid AND a.topid=b.topicid";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><?php echo $row["subject"]; ?></td>
									<td><?php echo $row["topic"]; ?></td>
									<td><?php echo $row["title1"]; ?></td>
									<td><?php echo $row["title2"]; ?></td>
									<td><?php echo $row["title3"]; ?></td>
									<td><?php echo $row["title4"]; ?></td>
									<td><?php echo $row["title5"]; ?></td>
									<td><a href="precontent_edit.php?id=<?php echo $row['p_id']; ?>" class=""><img src='images/edit.png' class='edit'></a></td>
									<td><a href="precontent_delete.php?id=<?php echo $row['p_id']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
