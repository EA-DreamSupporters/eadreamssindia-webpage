<?php
include("header.php");
?>


<?php
error_reporting(E_ERROR | E_PARSE);
$query = "SELECT * FROM subject";
$result=mysqli_query($con,$query);
if (isset($_POST["submit"]))
		{	
	
$subject=$_POST["subject"];	
$topic=$_POST["topic"];
		
$sql=mysqli_query($con, "INSERT INTO topic(subject_id, topic) VALUES('$subject', '$topic')");

         echo "<script> alert('Added Successfully')</script>";	
		 echo "<script>  window.location.assign('topic_view.php')</script>";
       
 
		
	}
?>   
    
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Topic</h4>
                  <form class="form-sample" method="post">
                    <p class="card-description">
                   
                    </p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Subject</label>
                          <div class="col-sm-9">
						  <select name="subject" class="form-control" >
						  <option value="">Select Subject</option>
							  <?php
							  while($row = mysqli_fetch_array($result))
							  {
							  ?>
							  <option value="<?php echo $row['subjectid']; ?>"><?php echo $row['subject']; ?></option>
							  <?php
							  }
							  ?>
							 
							  </select>
                          </div>
                        </div>
                      </div>
                    </div>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Topic</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="topic"/>
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