<?php
include("header.php");
?>


<?php
if(isset($_POST['submit']))
{
$_SESSION["admin"] = $admin;
$cpassword=md5($_POST['currentpassword']);
$newpassword=md5($_POST['newpassword']);
$query=mysqli_query($con,"select username from user where username='$admin' and   password='$cpassword'");
$row=mysqli_fetch_array($query);
if($row>0){
$ret=mysqli_query($con,"update user set password='$newpassword' where username='$admin'");
 echo '<script>alert("Your password has been updated.")</script>';
} else {
 echo '<script>alert("Your current password is wrong!")</script>';


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
                  <h4 class="card-title">Change Password</h4>
                  <form class="form-sample" method="post">
                    <p class="card-description">
                   
                    </p>
                  <div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Current Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control"  name="currentpassword"/>
                          </div>
                        </div>
                      </div>
                  
                    </div>
					<div class="row">
					<div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">New Password</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control"  value=""  name="newpassword"/>
                          </div>
                        </div>
                      </div>
                  
                    </div>
					<div class="row">
					<div class="col-md-6">
                        <div class="mt-3">
						<button type="submit" class="btn btn-primary btn-icon-text"  value="" name ="submit">
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