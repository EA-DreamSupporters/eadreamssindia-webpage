<?php
include("header.php");
?>
<style>
select.form-control {
    color: black;
}
textarea.form-control {
    height: 4rem;
}

</style>
<style type="text/css">
	table tr td,
	table tr th {
		border: 1px solid #dee2e6;
		padding: 10px;
		    text-align: center;
	}
	.plan {
    width: 100%;
	border-style: none;
}
.col-sm-4.form-group {
    margin-bottom: 40px;
}
button{
	border-style: none;
}
#myTable{
    width: 100%;
}
</style>

<script>
	var items = 1;
	function addItem() {
		items++;

		var html = "<tr>";
			//html += "<td>" + items + "</td>";
			html += "<td><input type='text' class='plan' name='plan[]' required></td>";
			html += "<td><input type='number' class='plan' name='price[]' required></td>";
			html += "<td><button class='btnDelete' name='btnDelete[]' onclick='deleteRow(this)'><img src='images/delete.png' id='delete'></button></td>";
		html += "</tr>";

		var row = document.getElementById("tbody").insertRow();
		row.innerHTML = html;
	}
</script>
<script>
function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}
  
  $(document).ready(function() { 
    $('#my_select').on('change', do_something); 
}); 
 

</script>


<?php
error_reporting(E_ERROR | E_PARSE);
$id=$_GET["id"];
$sql = mysqli_query($con, "SELECT * FROM magazines WHERE mid='$id'"); 
$row=mysqli_fetch_array($sql);
	


$mplan=$row["mplan"];
$mprice=$row["mprice"];

    $plan = explode(",", $mplan);
	$price = explode(",", $mprice);
	$result = count($plan);
	
if(isset($_POST['submit'])){
		  $errors= array();
	 $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"../images/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
	
	
	$mname = $_POST['mname'];  
	$desc = $_POST['desc'];
	
	$plan=implode(',',$_POST["plan"]); 
	$price=implode(',',$_POST["price"]); 
	
	$sql1=mysqli_query($con, "UPDATE magazines SET mname='$mname',mdescription='$desc',mplan='$plan',mprice='$price' WHERE mid='$id'");
                 
				echo "<script> alert('Update Successfully')</script>";	
				echo "<script>  window.location.assign('magazine_view.php')</script>";
			

   }

?>    


      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Magazine</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
           

					
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Magazine Name</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>" required />
                          </div>
                        </div>
                      </div>

                      </div>
             
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Description</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="50" class="form-control" name="desc" ><?php echo $row['mdescription']; ?></textarea>  
                          </div>
                        </div>
                      </div>
						</div>
						<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Upload Image</label>
                          <div class="col-sm-9">
                          <img height="100px" width="95px" src="../images/<?php echo $row['image']; ?>" />
							<a href="updatemagimage.php?mid=<?php echo $row['mid']?>"> Edit Image</a>
                          </div>
                        </div>
                      </div>
						</div>
						
						<div class="row">
						<div class="col-md-6">
						<div class="form-group row">
                          <label class="col-sm-3 col-form-label">Subscription Plan</label>
						   <div class="col-sm-9">
						<table id="myTable">
							<tr>
								<!--<th>S.No</th>-->
								<th>Plan</th>
								<th>Price</th>
							</tr>

							<tbody id="tbody">
							<?php 
							for ($x = 0; $x < $result; $x++) { 
							?>
							 <tr>
								<!--<td>1</td>-->
								<td><input type='text' class='plan' value="<?php echo $plan[$x]; ?>" name='plan[]' required></td>
								<td><input type='number' class='plan' value="<?php echo $price[$x]; ?>" name='price[]' required></td>
								<td><button class='btnDelete' onclick='deleteRow(this)' ><img src='images/delete.png'></button></td>
							
							</tr>
							<?php 
							}
							?>
							</tbody>
						</table>
			
						<p align="right" class="additem">
						<button type="button" class="btn btn-dark btn-icon-text" onclick="addItem();">Add Plan 
                        <i class="ti-menu float-right"></i>
                        </button>
						</p>
						</div>
                    </div>
  </div>  </div>



				
					<div class="row">
					<div class="col-md-6">
                        <div class="mt-3">
						<button type="submit" class="btn btn-primary btn-icon-text" name ="submit">
                          <i class="ti-save btn-icon-prepend"></i>
                          Update
                        </button>
							</div>
                        </div>
                      </div>
					 
					
                  </form>
				  					
					
					

                </div>
              </div>
            </div>
         
          </div>
        </div>
        <!-- content-wrapper ends -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>
$(document).ready(function() {
$('#category-dropdown').on('change', function() {
var category_id = this.value;
var category_id = this.value;
$.ajax({
url: "fetch-subcategory.php",
type: "POST",
data: {
category_id: category_id
},
cache: false,
success: function(result){
$("#sub-category-dropdown").html(result);
}
});
});
});
</script>
<?php
include("footer.php");
?>