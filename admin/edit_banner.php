<?php
include("header.php");

$id=$_GET['id'];
$ret=mysqli_query($con,"select*from tblbanner where bannerID='$id'");
$row=mysqli_fetch_array($ret);

if(isset($_POST['submit']))
{
	$position=$_POST['position'];
	$vel=mysqli_fetch_array($con,"UPDATE tblbanner SET position='$position' WHERE bannerID='$id'");
	
	if($vel){
    echo "<script>alert('Updated Successfully');</script>"; 
	
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
                  <h4 class="card-title">Edit Banner</h4>
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
							  <option value="mag" <?php if($row['position']=='mag'){ echo 'selected';} ?>>Magazine Page</option>
							  <option value="book" <?php if($row['position']=='book'){ echo 'selected';} ?>>Books Page</option>
							  <option value="course" <?php if($row['position']=='course'){ echo 'selected';} ?>>Course Page</option>
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
                           <img src="image/<?php echo $row['banner'];?>" width="400" height="200" value="<?php  echo $row['banner'];?>"><br><a href="changebanner.php?id=<?php echo $row['bannerID'];?>"> &nbsp; Edit Image</a>
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