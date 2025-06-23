<?php
include("header.php");
?>

<?php
error_reporting(E_ERROR | E_PARSE);


if(isset($_POST['submit'])){



 $name=$_POST["name"];
 $email=$_POST["email"];
 $phone=$_POST["phone"];



$sql=mysqli_query($con, "INSERT INTO books(image, cat_id, subcat_id, book, price) 
VALUES('$file_name','$category','$subcategory','$book','$price')");
      
				echo "<script> alert('Update Successfully')</script>";	
				echo "<script>  window.location.assign('index.php')</script>";


   }




?>    


      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Profile</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
           


					
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">magazine</label>
                          <div class="col-sm-9">
                            <select name="category" class="form-control" id="category-dropdown">
						  <option value="">Select magazine</option>
							  <?php
							  $result1 = mysqli_query($con,"SELECT * FROM magazines");
							  while($row1 = mysqli_fetch_array($result1))
							  {
							  ?>
							  <option value="<?php echo $row1['mid']; ?>"><?php echo $row1['mname']; ?></option>
							  <?php
							  }
							  ?>
							 
							  </select></div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					  <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">plan</label>
                          <div class="col-sm-9">
                            <select name="plan" class="form-control" id="plan">
						  <option value="">Select plan</option>
							  <?php
							  $x = 0;
							  $result11 = mysqli_query($con,"SELECT * FROM magazines");
							  $row11 = mysqli_fetch_array($result11);
							  $mplan=$row11["mplan"];
							  $mprice=$row11["mprice"];
							 $plan = explode(",", $mplan);
							 $price = explode(",", $mprice);
							 $result = count($plan);
							 for ($x = 0; $x < $result; $x++) {
							  ?>
							  <option value="<?php echo $plan[$x]; ?>"><?php echo $plan[$x]; ?> Months</option>
							  <?php
							  }
							  ?>
							 
							  </select></div>
                        </div>
                      </div>
                      
                    </div>

					

					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Price</label>
                          <div class="col-sm-9">
						<?php 
						for ($x = 0; $x < $result; $x++) {
							if($plan[$x]==6){
						echo $price[1]; 
							}
						}
						?>
                  
                          </div>
                        </div>
                      </div>
					  </div>



				
					<div class="row">
					<div class="col-md-6">
                        <div class="mt-3">
						<button type="submit" class="btn btn-primary btn-icon-text" name ="submit">
                          <i class="ti-save btn-icon-prepend"></i>
                          Subscribe
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

<?php
include("footer.php");
?>