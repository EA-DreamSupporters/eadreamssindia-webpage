<?php
include('config.php');
?>

<!DOCTYPE html>

<html
  lang="en"
  class="light-style"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Rejected Rentals | Admin</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include('sidebar.php'); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php include('navbar.php'); ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4">Recieved Payments</h4>

              <!-- Basic Layout & Basic with Icons -->
              <div class="card">
                <h5 class="card-header">Recieved Payments</h5>
				<ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="false">New Payments</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="true">Security Deposit Recieved</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-2" role="tab" aria-controls="profile-1" aria-selected="true">Rental Payment Pending</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-3" role="tab" aria-controls="profile-1" aria-selected="true">Rental Payment Paid</a>
                    </li>
					<li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile-4" role="tab" aria-controls="profile-1" aria-selected="true">Total Payments</a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade active show" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                      <div class="media">
					  <div class="row">
                      <div class="col-md-12">
                        <div class="mt-3 adddealer">
						
						</div>
					</div>
				</div>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                     <table id="employee_data" class="table table-hover table-striped">  
                          <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Security Deposit</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
					<?php
					$cnt=1;
					$ret1=mysqli_query($con,"SELECT a.*,b.*,c.*,d.* FROM user AS a, product As b, orders AS c, payments AS d WHERE a.UserID=c.UserID AND b.productID=c.ProductID AND c.OrderID=d.OrderID");
while($row1=mysqli_fetch_array($ret1))
{
					?>
					
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $cnt; ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['O_date'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['OrderID'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['Username'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['Productname'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['plan'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['price'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row1['sdeposit'] ?></strong></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="rental_status.php?id=<?php echo $row1['OID'] ?>"
                                ><i class='bx bxs-message-edit'></i> Action</a>
                            </div>
                          </div>
                        </td>
                      </tr>
<?php $cnt=$cnt+1; }  ?>
                    </tbody>
                     </table>  
                </div>
                </div>
              </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data1" class="table table-hover table-striped">  
                          <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Security Deposit</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
					<?php
					$cnt=1;
					$ret=mysqli_query($con,"SELECT a.*,b.*,c.*,d.* FROM user AS a, product As b, orders AS c, payments AS d WHERE a.UserID=c.UserID AND b.productID=c.ProductID AND c.OrderID=d.OrderID AND c.order_sts=1");
while($row=mysqli_fetch_array($ret))
{
					?>
					
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $cnt; ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['O_date'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['OrderID'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Username'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Productname'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['plan'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['price'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['sdeposit'] ?></strong></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="rental_status.php?id=<?php echo $row['OID'] ?>"
                                ><i class='bx bxs-message-edit'></i> Action</a>
                            </div>
                          </div>
                        </td>
                      </tr>
<?php $cnt=$cnt+1; }  ?>
                    </tbody>
                     </table>
                </div>
                </div>
              </div>
                      </div>
                    </div>
<div class="tab-pane fade" id="profile-2" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data2" class="table table-hover table-striped">  
                          <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Security Deposit</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
					<?php
					$cnt=1;
					$ret=mysqli_query($con,"SELECT a.*,b.*,c.*,d.* FROM user AS a, product As b, orders AS c, payments AS d WHERE a.UserID=c.UserID AND b.productID=c.ProductID AND c.OrderID=d.OrderID AND c.order_sts=1");
while($row=mysqli_fetch_array($ret))
{
					?>
					
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $cnt; ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['O_date'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['OrderID'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Username'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Productname'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['plan'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['price'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['sdeposit'] ?></strong></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="rental_status.php?id=<?php echo $row['OID'] ?>"
                                ><i class='bx bxs-message-edit'></i> Action</a>
                            </div>
                          </div>
                        </td>
                      </tr>
<?php $cnt=$cnt+1; }  ?>
                    </tbody>
                     </table>
                </div>
                </div>
              </div>
                      </div>
                    </div>

<div class="tab-pane fade" id="profile-3" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data3" class="table table-hover table-striped">  
                          <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Security Deposit</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
					<?php
					$cnt=1;
					$ret=mysqli_query($con,"SELECT a.*,b.*,c.*,d.* FROM user AS a, product As b, orders AS c, payments AS d WHERE a.UserID=c.UserID AND b.productID=c.ProductID AND c.OrderID=d.OrderID AND c.order_sts=1");
while($row=mysqli_fetch_array($ret))
{
					?>
					
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $cnt; ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['O_date'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['OrderID'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Username'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Productname'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['plan'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['price'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['sdeposit'] ?></strong></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="rental_status.php?id=<?php echo $row['OID'] ?>"
                                ><i class='bx bxs-message-edit'></i> Action</a>
                            </div>
                          </div>
                        </td>
                      </tr>
<?php $cnt=$cnt+1; }  ?>
                    </tbody>
                     </table>
                </div>
                </div>
              </div>
                      </div>
                    </div>

<div class="tab-pane fade" id="profile-4" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">  
                                          <table id="employee_data4" class="table table-hover table-striped">  
                         <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>User Name</th>
                        <th>Product Name</th>
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Security Deposit</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
					<?php
					$cnt=1;
					$ret=mysqli_query($con,"SELECT a.*,b.*,c.*,d.* FROM user AS a, product As b, orders AS c, payments AS d WHERE a.UserID=c.UserID AND b.productID=c.ProductID AND c.OrderID=d.OrderID AND c.order_sts=1");
while($row=mysqli_fetch_array($ret))
{
					?>
					
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $cnt; ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['O_date'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['OrderID'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Username'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['Productname'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['plan'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['price'] ?></strong></td>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i><strong><?php echo $row['sdeposit'] ?></strong></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="rental_status.php?id=<?php echo $row['OID'] ?>"
                                ><i class='bx bxs-message-edit'></i> Action</a>
                            </div>
                          </div>
                        </td>
                      </tr>
<?php $cnt=$cnt+1; }  ?>
                    </tbody>
						 
                     </table>
                </div>
                </div>
              </div>
                      </div>
                    </div>

                  </div>
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include('footer.php') ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- / Content -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
