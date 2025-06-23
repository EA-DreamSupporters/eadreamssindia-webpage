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
              <h4 class="card-title">Books</h4>
			  
			       <div class="row">
          <div class="col-md-12">
            <div class="mt-3" style="text-align: right; margin-bottom: 10px;">

              <a href="book_add.php" class="d-inline-block">
                <button type="button" class="btn btn-success btn-icon-text">
                  <i class="ti-plus btn-icon-prepend"></i>
                  Add New Book
                </button>
              </a>

              <!-- Button to trigger modal -->
              <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal"
                data-bs-target="#importModal">
                <i class="ti-download btn-icon-prepend"></i>
                Import as CSV
              </button>

              <!-- Modal -->
              <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                  <form action="import_books.php" method="post" enctype="multipart/form-data" id="csvForm"
                    class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title" id="importModalLabel">Import Your File</h3>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <label for="csv_file" class="form-label">Choose CSV File:</label>
                      <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv" required>
                      <div id="fileName" class="form-text mt-2"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="import" class="btn btn-success">Import</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>

              <script>
                // Show selected file name below input
                document.getElementById('csv_file').addEventListener('change', function () {
                  const name = this.files.length > 0 ? this.files[0].name : '';
                  document.getElementById('fileName').textContent = name;
                });

                // Optional: Ask confirm on submit
                document.getElementById('csvForm').addEventListener('submit', function (e) {
                  if (!confirm("Are you sure you want to import this CSV?")) {
                    e.preventDefault();
                  }
                });
              </script>



              <form action="export_books.php" method="post" class="d-inline-block" style="display: inline;">
                <button type="submit" class="btn btn-success btn-icon-text">
                  <i class="ti-download btn-icon-prepend"></i>
                  Export as CSV
                </button>
              </form>




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
									<th>Image</th> 
                                    <th>Book</th> 
									<th>Category</th> 
                                    <th>SubCategory</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Author</th> 
                                    <th>Description</th>
									<th>Edit</th>					
									<th>Delete</th> 				
									

                               </tr>  
                          </thead>  
                          <?php 
						  $x=1;
						  $query ="SELECT a.*,b.*,c.* FROM books AS a, subcategory AS b, category AS c WHERE a.cat_id=c.category_id AND a.subcat_id=b.subcategory_id";  
						  $result = mysqli_query($con, $query); 						  
                          while($row = mysqli_fetch_array($result))  
                          {  
						  ?>							   
                               <tr>  
									
									<td><?php echo $x; ?></td>
									<td><img src="../images/books/<?php echo $row["image"]; ?>" class="book"></td>
									<td><?php echo $row["book"]; ?></td>
									<td><?php echo $row["category"]; ?></td>
									<td><?php echo $row["subcategory"]; ?></td>
									<td>₹<?php echo $row["price"]; ?></td>
									<td><?php echo $row["totquantity"]; ?></td>
									<td><?php echo $row["author"]; ?></td>
									<td><p class="mb-0"><?php echo $row["description"]; ?></p></td>
									<td><a href="book_edit.php?id=<?php echo $row['book_id']; ?>" class=""><img src='images/edit.png' class='edit'></a></td>
									<td><a href="book_delete.php?id=<?php echo $row['book_id']; ?>" class="" onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
									
								
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
