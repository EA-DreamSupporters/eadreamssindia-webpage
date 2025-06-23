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


<?php
error_reporting(E_ERROR | E_PARSE);

if (isset($_POST["submit"]))
		{							

 $subject=$_POST["subject"];
 $topic=$_POST["topic"];
  $title1=$_POST["title1"];
  $title2=$_POST["title2"];
  $title3=$_POST["title3"];
  $title4=$_POST["title4"];
  $title5=$_POST["title5"];
 $link1=$_POST["link1"];
 $link2=$_POST["link2"];
 $link3=$_POST["link3"];
 $link4=$_POST["link4"];
 $link5=$_POST["link5"];
 $cname1=$_POST["cname1"];
 $cname2=$_POST["cname2"];
 $cname3=$_POST["cname3"];
 $cname4=$_POST["cname4"];
 $cname5=$_POST["cname5"];


	
$sql=mysqli_query($con, "INSERT INTO precontent(subjid, topid,title1,link1,cname1,title2,link2,cname2,title3,link3,cname3,title4,link4,cname4,title5,link5,cname5) VALUES('$subject','$topic','$title1','$link1','$cname1','$title2','$link2','$cname2','$title3','$link3','$cname3','$title4','$link4','$cname4','$title5','$link5','$cname5')");


         echo "<script> alert('Added Successfully')</script>";	
		 echo "<script>  window.location.assign('precontent_view.php')</script>";

		
	}
?>    


      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Precontent</h4>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
           

                    <div class="row">

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Subject</label>
                          <div class="col-sm-9">
						  <select name="subject" class="form-control" id="subject-dropdown">
						  <option value="">Select Subject</option>
							  <?php
							  $result1 = mysqli_query($con,"SELECT * FROM subject");
							  while($row1 = mysqli_fetch_array($result1))
							  {
							  ?>
							  <option value="<?php echo $row1['subjectid']; ?>"><?php echo $row1['subject']; ?></option>
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
						  <select name="topic" class="form-control" id="topic-dropdown">
					
							 
							  </select>
                          </div>
                        </div>
                      </div>
                    </div>
					
<div class="row">
					                        <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title 1</label>
                          <div class="col-sm-9">
						   <input type="text" class="form-control" name="title1"  />
                          </div>
                        </div>
                      </div>
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Channel Name1</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname1"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Video Link 1</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link1"  placeholder="Give Emmbeded link" />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
					<div class="row">
					                        <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title 2</label>
                          <div class="col-sm-9">
						   <input type="text" class="form-control" name="title2"  />
                          </div>
                        </div>
                      </div>
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Channel Name2</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname2"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Video Link 2</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link2" placeholder="Give Emmbeded link"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
					<div class="row">
					                        <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title 3</label>
                          <div class="col-sm-9">
						   <input type="text" class="form-control" name="title3"  />
                          </div>
                        </div>
                      </div>
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Channel Name3</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname3"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Video Link 3</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Give Emmbeded link" name="link3"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
					<div class="row">
					                        <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title 4</label>
                          <div class="col-sm-9">
						   <input type="text" class="form-control" name="title4"  />
                          </div>
                        </div>
                      </div>
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Channel Name4</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname4"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Video Link 4</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Give Emmbeded link" name="link4"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
					<div class="row">
					                        <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title 5</label>
                          <div class="col-sm-9">
						   <input type="text" class="form-control" name="title5"  />
                          </div>
                        </div>
                      </div>
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Channel Name5</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname5"  />
                          </div>
                        </div>
                      </div>
					  
                      
                    </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Video Link 5</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Give Emmbeded link" name="link5"  />
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script>
$(document).ready(function() {
$('#subject-dropdown').on('change', function() {
var subjectid = this.value;
var subjectid = this.value;
$.ajax({
url: "fetch-topic.php",
type: "POST",
data: {
subjectid: subjectid
},
cache: false,
success: function(result){
$("#topic-dropdown").html(result);
}
});
});
});
</script>
<?php
include("footer.php");
?>