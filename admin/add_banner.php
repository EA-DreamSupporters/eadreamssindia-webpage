<?php
include("header.php");
?>


<?php
error_reporting(E_ERROR | E_PARSE);
if(isset($_POST['submit']))
  {
$pos=$_POST['position'];
 $pic=$_FILES["uimg"]["name"];
$extension = substr($pic,strlen($pic)-4,strlen($pic));
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
$propic=md5($pic).time().$extension;
move_uploaded_file($_FILES["uimg"]["tmp_name"],"image/".$propic);
  $query=mysqli_query($con, "insert into tblbanner (banner,position) values('$propic','$pos')");
	 if($query){
    echo "<script>alert('Banner Image uploaded Successfully');</script>"; 
	
	header("Location: manage_banner.php");
  }
  else
    {
    echo "<script>alert('Something Went Wrong. Please try again');</script>";
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
                  <h4 class="card-title">Add Banner</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Banner Position</label>
                          <div class="col-sm-9">
						  <select name="position" class="form-control">
						  <option value="">Select Position</option>
							  <option value="mag">Magazine Page</option>
							  <option value="book">Buy-Books Page</option>
							  <option value="rbook">Rent-Books Page</option>
							  <option value="course">Course Page</option>
							  <option value="side1">BuyBook Page side</option>
							  </select>
                          </div>
                        </div>
                      </div>
                    </div>
					

					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Banner Image</label>
                          <div class="col-sm-9">
                            <input type="file" class="form-control" name="uimg"/>
								<br>
							Note:* Image Size Must be 1920 x 366 Pixels *
                          </div>
                        </div>
                      </div>
                  
                    </div>
					<div class="row">
					<div class="col-md-6">
                        <div class="mt-3">
						<button type="submit" class="btn btn-primary btn-icon-text" name ="submit">
                          <i class="ti-save btn-icon-prepend"></i>
                          Save
                        </button>
						<button type="reset" class="btn btn-warning btn-icon-text">
                          <i class="ti-reload btn-icon-prepend"></i>                                                    
                          Reset
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