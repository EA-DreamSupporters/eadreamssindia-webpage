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
.img{
	
    display: inherit;

}
</style>


<?php
error_reporting(E_ERROR | E_PARSE);
$id=$_GET["id"];
$query = mysqli_query($con, "SELECT * FROM students WHERE sid='$id'"); 
$row=mysqli_fetch_array($query);

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


$name=$_POST["name"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$address=$_POST["address"];

if($file_name=="")
{
	$sql=mysqli_query($con, "UPDATE students SET name='$name',email='$email',phone='$phone',address='$address' WHERE sid='$id'");
          
				echo "<script> alert('Update Successfully')</script>";	
				echo "<script>  window.location.assign('user_view.php')</script>";

}else{
		$sql=mysqli_query($con, "UPDATE students SET name='$name',email='$email',phone='$phone',address='$address',photo='$file_name' WHERE sid='$id'");
	 
				echo "<script> alert('Update Successfully')</script>";	
				echo "<script>  window.location.assign('user_view.php')</script>";
}
   }




?>    


      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit User</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
           


					
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Name</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required />
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					  <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Email</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>" required />
                          </div>
                        </div>
                      </div>
                      
                    </div>

					

					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Phone</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Address</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="80" class="form-control" name="address" ><?php echo $row['address']; ?></textarea>  
                          </div>
                        </div>
                      </div>
	 </div>

				<div class="row">
                     
					  <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Upload Image</label>
                          <div class="col-sm-7 img">
						  <img height="100px" width="95px" src="../images/<?php echo $row['photo']; ?>" />
                            <input type="file" class="form-control" name="image"  />
                          </div>
                        </div>
                      </div>
                      
                    </div>
				
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

<?php
include("footer.php");
?>