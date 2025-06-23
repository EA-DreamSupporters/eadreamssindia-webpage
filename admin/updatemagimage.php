<?php
include("header.php");

$id=$_GET['mid'];
$ret=mysqli_query($con,"select*from magazines where mid='$id'");
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

     move_uploaded_file($_FILES["uimg"]["tmp_name"],"../images/".$propic);
	 
	$vel=mysqli_query($con,"UPDATE magazines SET image='$propic' WHERE mid='$id'");
	
	if($vel){
    echo "<script>alert('Updated Successfully');</script>"; 
	
	header("Location: magazine_view.php");
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
                  <h4 class="card-title">Change Magazine Image</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Featured Image</label>
                          <div class="col-sm-9">
                           <img src="../images/<?php echo $row['image'];?>" width="400" height="200">
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
							Note:* Image Size Must be 511 x 307 Pixels *
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