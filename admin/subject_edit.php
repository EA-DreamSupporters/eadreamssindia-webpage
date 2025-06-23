<?php
include("header.php");
?>


<?php
error_reporting(E_ERROR | E_PARSE);
$id=$_GET["id"]; 
$sql = mysqli_query($con, "SELECT * FROM subject WHERE subjectid='$id'"); 
$row=mysqli_fetch_array($sql);
if (isset($_POST["submit"]))
		{							
	
$subject=$_POST["subject"];

$sql1=mysqli_query($con, "UPDATE subject SET subject='$subject' WHERE subjectid='$id'");

         echo "<script> alert('Updated Successfully')</script>";	
		 echo "<script>  window.location.assign('subject_view.php')</script>";
       
 
		
	}
?>   
     
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Subject</h4>
                  <form class="form-sample" method="post">
                    <p class="card-description">
                   
                    </p>
                    
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Subject</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="subject" value="<?php echo $row['subject']; ?>"/>
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