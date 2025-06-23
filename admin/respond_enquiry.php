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
.main-panel input.form-control {
       background-color: #e9ecef!important;
}
</style>


<?php
error_reporting(E_ERROR | E_PARSE);
$id=$_GET["id"];
$query = mysqli_query($con, "SELECT * FROM guide WHERE guid='$id'"); 
$row=mysqli_fetch_array($query);
$sts=$row['status'];

if(isset($_POST['submit'])){
      
$reply=$_POST["reply"];
$sts=$_POST["sts"];



	$sql=mysqli_query($con, "UPDATE guide SET reply='$reply',status='$sts' WHERE guid='$id'");
          
				echo "<script> alert('Update Successfully')</script>";	
				echo "<script>  window.location.assign('guide.php')</script>";


   }




?>    


      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
				<?php
				if($sts!=0)
				{
				?>
                  <h4 class="card-title">Ea-Guidance View</h4>
				  <?php
				}
				else
				{
				  ?>
				   <h4 class="card-title">Ea-Guidance Update</h4>
				   <?php
				}
				   ?>
				   
				   <h5 class="card-title">Basic Details</h5>
                  <form class="form-sample" method="post" enctype="multipart/form-data">
                    <p class="card-description">
                   
                    </p>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Name</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['name']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Email</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['email']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Date of Birth</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['dob']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Phone No</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['phone']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Passed Out ?</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['pout']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Passed out year</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['poutyear']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Self Paced Person ?</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['pace']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
	

					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Address</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="80" class="form-control" disabled><?php echo $row['address']; ?></textarea>  
                          </div>
                        </div>
                      </div>
					</div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Message</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="80" class="form-control" disabled><?php echo $row['msg']; ?></textarea>  
                          </div>
                        </div>
                      </div>
					</div>
					<h5 class="card-title">Timing Details</h5>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Years of Experience?</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['yoe']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Wake up Time</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['wakeup_time']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Sleeping Time</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['sleep_time']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Preparation Time</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['pre_time']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Walking Time</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['walk_time']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <h5 class="card-title">Parent's Proffession</h5>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Father's Proffession</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['father']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Mother's Proffession</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['mother']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <h5 class="card-title">Additional Details about Candidate</h5>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">About themselves</label>
                          <div class="col-sm-9">
						  <textarea class="form-control" disabled><?php echo $row['about']; ?></textarea>
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Problems Faced in Previous Experience</label>
                          <div class="col-sm-9">
						  <textarea class="form-control" disabled><?php echo $row['problem_faced']; ?></textarea>
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Favourite Subject</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['favorite']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Difficult Subject</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['difficult']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Coaching Institute Student?</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['institute']; ?>" disabled />
                  
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Problems Facing?</label>
                          <div class="col-sm-9">
						  <textarea class="form-control" disabled><?php echo $row['problems']; ?></textarea>
                  
                          </div>
                        </div>
                      </div>
					  </div>
					<h5 class="card-title">Payment Details</h5>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Purchased Plan</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['plan']; ?>" disabled />
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Plan Price</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['amount']; ?>" disabled />
                          </div>
                        </div>
                      </div>
					  </div>
					  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Payment ID</label>
                          <div class="col-sm-9">
						  <input type="text" class="form-control" value="<?php echo $row['payment_id']; ?>" disabled />
                          </div>
                        </div>
                      </div>
					  </div>
					  <h5 class="card-title">Approval Section</h5>
					<?php
					$rev=mysqli_query($con,"select*from guide where status='0' and guid='$id'");
					$num=mysqli_num_rows($rev);
					if($num>0)
					{
					?>
				<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Reply</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="80" class="form-control" name="reply" ></textarea>  
                          </div>
                        </div>
                      </div>
					</div>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                           <select  class="form-control" name="sts">
						   <option value="" selected>Select Status</option>
						   <option value="1">Respond</option>
						   <option value="-1">Rejected</option>
						   </select>
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
					  <?php
					}
					$rv=mysqli_query($con,"select*from guide where status!='0' and guid='$id'");
					$nm=mysqli_num_rows($rv);
					if($nm)
					{
					?>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Reply</label>
                          <div class="col-sm-9">
                           <textarea rows="10" cols="80" class="form-control" name="reply" readonly><?php echo $row['reply']; ?></textarea>  
                          </div>
                        </div>
                      </div>
					</div>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                           <p><?php if($row["status"] == 1){ echo "<img src='images/1.jpg' class='check'>- Responded"; } elseif($row["status"] == 0){ echo"<b style='color:#1f3bb3;font-size:16px;'>Pending</b>";} else{ echo "<img src='images/2.jpg' class='check'> - Rejected"; }?></p>
                          </div>
                        </div>
                      </div>
					</div>
					 <?php
					}
					 ?>
					
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