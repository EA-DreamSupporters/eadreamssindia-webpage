<?php
include("header.php");

$id=$_GET['id'];
$ret=mysqli_query($con,"select*from tblbanner where bannerID='$id'");
$row=mysqli_fetch_array($ret);

if(isset($_POST['submit']))
{
	$pic=$_FILES["uimg"]["name"];
$extension = substr($pic,strlen($pic)-4,strlen($pic));

// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Featured image has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}

else
{
//rename property images
$propic=md5($pic).time().$extension;

     move_uploaded_file($_FILES["uimg"]["tmp_name"],"image/".$propic);
	 
	$vel=mysqli_query($con,"UPDATE tblbanner SET banner='$propic' WHERE bannerID='$id'");
	
	if($vel){
    echo "<script>alert('Updated Successfully');</script>"; 
	
	header("Location: manage_banner.php");
  }
  else
    {
    echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
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
                  <h4 class="card-title">Change Banner</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Featured Image</label>
                          <div class="col-sm-9">
                           <img src="image/<?php echo $row['banner'];?>" width="400" height="200">
                          </div>
                        </div>
                      </div>
                    </div>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">New Image</label>
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
                          Update
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