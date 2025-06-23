
<?php
include("header3.php");
?>
<?php
$userid=$_SESSION['id']; 
$sql = "SELECT * from orders WHERE userId='$userid'";

if ($result = mysqli_query($con, $sql)) {

    // Return the number of rows in result set
    $rowcount = mysqli_num_rows( $result );

 }
 
$sql1 = "SELECT * from subscriptions WHERE user_id='$userid'";

if ($result1 = mysqli_query($con, $sql1)) {

    // Return the number of rows in result set
    $rowcount1 = mysqli_num_rows( $result1 );

 } 
$sql2 = "SELECT * from rentals WHERE userid='$userid'";

if ($result2 = mysqli_query($con, $sql2)) {

    // Return the number of rows in result set
    $rowcount2 = mysqli_num_rows( $result2 );

 } 
 
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");
$sql3 = "SELECT * from orders WHERE userId='$userid' AND orderDate='$date'";

if ($result3 = mysqli_query($con, $sql3)) {

    // Return the number of rows in result set
    $rowcount3 = mysqli_num_rows( $result3 );

 }
?>

<style>
.row.dashboard-header1 {
    margin-top: 20px;
}
.zoom {

  transition: transform .2s;

  margin: 0 auto;
}

.zoom:hover {
  -ms-transform: scale(1.1); /* IE 9 */
  -webkit-transform: scale(1.1); /* Safari 3-8 */
  transform: scale(1.1); 
}
.statistics-title {
    font-size: 18px;
    line-height: 18px;
    text-align: center;
}
.rate-percentage {
    font-size: 26px;
    text-align: center;
}
.card{
    padding: 61px 61px;
}

@media only screen and (max-width: 544px)
{
.card {
    margin-bottom: 20px;
}
.row.dashboard-header1 {
    margin-top: 0px;
}
}
</style>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                    <div class="row dashboard-header">
					
						<div class="col-md-3 zoom">
                          <div class="card dashboard-product" id="card1">
                            <p class="statistics-title">My Orders</p>
                            <h3 class="rate-percentage"><?php echo $rowcount; ?></h3>
                          </div>
						  </div>
						  	<div class="col-md-3 zoom">
                          <div class="card dashboard-product" id="card4">
                            <p class="statistics-title">New Orders</p>
                            <h3 class="rate-percentage"><?php echo $rowcount3; ?></h3>
                          </div>
						  </div>
						<div class="col-md-3 zoom">
                          <div class="card dashboard-product" id="card2">
                            <p class="statistics-title">My Subscriptions</p>
                            <h3 class="rate-percentage"><?php echo $rowcount1; ?></h3>
                          </div>
						  </div>
					<div class="col-md-3 zoom">
                          <div class="card dashboard-product" id="card3">
                            <p class="statistics-title">My Rental Books</p>
                            <h3 class="rate-percentage"><?php echo $rowcount2; ?></h3>
                          </div >
						  </div>
					
                  
                    </div> 
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		
        <!-- content-wrapper ends -->
<?php
include("footerd.php");
?>
