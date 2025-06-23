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
$query = mysqli_query($con, "SELECT * FROM precontent WHERE p_id='$id'"); 
$row=mysqli_fetch_array($query);

if(isset($_POST['submit'])){
	
 $subject=$_POST["subject"];
 $topic=$_POST["topic"];
 $title1=$_POST["title1"];
 $title2=$_POST["title2"];
 $title3=$_POST["title3"];
 $title4=$_POST["title4"];
 $title5=$_POST["title5"];
 $cname1=$_POST["cname1"];
 $cname2=$_POST["cname2"];
 $cname3=$_POST["cname3"];
 $cname4=$_POST["cname4"];
 $cname5=$_POST["cname5"];
 $link1=$_POST["link1"];
 $link2=$_POST["link2"];
 $link3=$_POST["link3"];
 $link4=$_POST["link4"];
 $link5=$_POST["link5"];


	$sql=mysqli_query($con, "UPDATE precontent SET subjid='$subject',topid='$topic',title1='$title1',cname1='$cname1',link1='$link1',title2='$title2',cname2='$cname2',link2='$link2',title3='$title3',cname3='$cname3',link3='$link3',title4='$title4',cname4='$cname4',link4='$link4',title5='$title5',cname5='$cname5',link5='$link5' WHERE p_id='$id'");
          
				echo "<script> alert('Update Successfully')</script>";	
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
                          <label class="col-sm-3 col-form-label">Category</label>
                          <div class="col-sm-9">
						  <select name="subject" class="form-control" id="subject-dropdown">
						  <option value="">Select Subject</option>
							  <?php
							  $result1 = mysqli_query($con,"SELECT * FROM subject");
							  while($row1 = mysqli_fetch_array($result1))
							  {
							  ?>
							  <option value="<?php echo $row1['subjectid']; ?>"<?php if($row1['subjectid']==$row['subjid']){ echo 'selected';}?>><?php echo $row1["subject"];?></option>
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
								<?php
								$result2 = mysqli_query($con,"SELECT * FROM topic");
								while($row2 = mysqli_fetch_array($result2)) {
								?>
								<option value="<?php echo $row2["topicid"];?>" <?php if($row2['topicid']==$row['topid']){ echo 'selected';} ?>><?php echo $row2["topic"];?></option>
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
                          <label class="col-sm-3 col-form-label">Title1</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="title1" value="<?php echo $row['title1']; ?>"  />
                          </div>
                        </div>
                      </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Link1</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link1" value="<?php echo $row['link1']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">channel1</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname1" value="<?php echo $row['cname1']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title2</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="title2" value="<?php echo $row['title2']; ?>"  />
                          </div>
                        </div>
                      </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Link2</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link2" value="<?php echo $row['link2']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">channel2</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname2" value="<?php echo $row['cname2']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title3</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="title3" value="<?php echo $row['title3']; ?>"  />
                          </div>
                        </div>
                      </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Link3</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link3" value="<?php echo $row['link3']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">channel3</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname3" value="<?php echo $row['cname3']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title4</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="title4" value="<?php echo $row['title4']; ?>"  />
                          </div>
                        </div>
                      </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Link4</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link4" value="<?php echo $row['link4']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">channel4</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname4" value="<?php echo $row['cname4']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Title5</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="title5" value="<?php echo $row['title5']; ?>"  />
                          </div>
                        </div>
                      </div>
				<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Link5</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="link5" value="<?php echo $row['link5']; ?>"  />
                          </div>
                        </div>
                      </div>
				
                      
                    </div>
					<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">channel5</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="cname5" value="<?php echo $row['cname5']; ?>"  />
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