<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords"
        content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
    <meta name="robots" content="" />
    <meta name="description"
        content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course." />

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />

    <!-- PAGE TITLE HERE -->
    <title>Checkout - EA Dream Supporters</title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/nouislider/nouislider.min.css">


    <!-- GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!--JS CITY SCRIPT-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Include Awesomplete for autocomplete functionality -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>



</head>

<body>
    <div class="page-wraper">
        <div id="loading-area" class="preloader-wrapper-2">
            <div class="preloader-inner">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <?php
        session_start();
        include("header1.php");
        error_reporting(0);
        if (strlen($_SESSION['id']) == 0) {
            header('location:logout.php');
        } else {

            //For Proceeding Payment
            $granttotal = $_GET['total'];
            if (isset($_POST['proceedpayment'])) {
                $address = $_POST['selectedaddress'];
                $gtotal = $_POST['grandtotal'];
                $_SESSION['address'] = $address;
                $_SESSION['gtotal'] = $gtotal;
                // echo "<script type='text/javascript'> document.location ='payment.php'; </script>"; 
        
                $orderno = mt_rand(100000000, 999999999);
                $userid = $_SESSION['id'];
                $address = $_SESSION['address'];
                $totalamount = $_SESSION['gtotal'];
                $txntype = $_POST['paymenttype'];
                $txnno = $_POST['txnnumber'];
                $block = $_POST['block'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $zip = $_POST['zip'];
                $notes = $_POST['notes'];
                $query = mysqli_query($con, "insert into orders(orderNumber,userId,addressId,totalAmount,txnType,txnNumber,block,city,state,zip,notes) values('$orderno','$userid','$address','$granttotal','COD','$txnno','$block','$city','$state','$zip','$notes')");
                if ($query) {
                    $sql = "insert into ordersdetails (userId,productId,quantity) select userID,productId,productQty from cart where userID='$userid';";
                    $sql .= "update ordersdetails set orderNumber='$orderno' where userId='$userid' and orderNumber is null;";
                    $sql .= "delete from  cart where userID='$userid'";
                    $result = mysqli_multi_query($con, $sql);
                    // --- AFFILIATE REFERRAL COMMISSION LOGIC ---
                    if (isset($_SESSION['affiliate_ref']) && isset($_SESSION['affiliate_product'])) {
                        $referral_code = $_SESSION['affiliate_ref'];
                        $buyer_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
                        $product = $_SESSION['affiliate_product'];
                        $referrer_query = mysqli_query($con, "SELECT sid FROM students WHERE referral_code='$referral_code'");
                        if ($referrer_row = mysqli_fetch_assoc($referrer_query)) {
                            $referrer_user_id = $referrer_row['sid'];
                            if ($referrer_user_id != $buyer_user_id) {
                                // Prevent duplicate commission for same buyer/product/order
                                $exists = mysqli_query($con, "SELECT id FROM affiliate_referrals WHERE buyer_user_id='$buyer_user_id' AND product='$product'");
                                if (mysqli_num_rows($exists) == 0) {
                                    // Insert into affiliate_referrals
                                    $now = date('Y-m-d H:i:s');
                                    mysqli_query($con, "INSERT INTO affiliate_referrals (referrer_user_id, buyer_user_id, product, created_at) VALUES ('$referrer_user_id', '$buyer_user_id', '$product', '$now')");
                                    $referral_id = mysqli_insert_id($con);
                                    // Get book price and commission percent
                                    if (preg_match('/book_(\\d+)/', $product, $pmatch)) {
                                        $book_id = $pmatch[1];
                                        $book_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM books WHERE book_id='$book_id'"));
                                        $commission_percent = isset($book_row['affiliate_commission_percent']) ? $book_row['affiliate_commission_percent'] : 10;
                                        $commission_amount = round($book_row['price'] * ($commission_percent / 100), 2);
                                        // Insert into affiliate_commissions
                                        mysqli_query($con, "INSERT INTO affiliate_commissions (referral_id, referrer_user_id, commission_amount, status, created_at) VALUES ('$referral_id', '$referrer_user_id', '$commission_amount', 'pending', '$now')");
                                    }
                                }
                            }
                        }
                        unset($_SESSION['affiliate_ref']);
                        unset($_SESSION['affiliate_product']);
                    }
                    // --- END AFFILIATE REFERRAL COMMISSION LOGIC ---
                    unset($_SESSION['address']);
                    unset($_SESSION['gtotal']);
                    echo "<script type='text/javascript'> document.location ='success.html'; </script>";
                    exit;
                } else {
                    echo "<script>alert('Something went wrong. Please try again');</script>";
                    echo "<script type='text/javascript'> document.location ='checkout.php'; </script>";
                }
            }

            ?>
            <style>
                .notes {
                    margin-top: 58px;
                }

                input#paymenttype {
                    margin: 3px 10px 0px 0px;
                }

                #myDiv {
                    padding: 10px;
                }
            </style>
            <?php

            $userid = $_SESSION['id'];
            $granttotal = $_GET['total'];
            $result = mysqli_query($con, "SELECT * FROM students WHERE sid='$userid'");
            $row = mysqli_fetch_array($result);
            ?>
            <div class="page-content">
                <!-- inner page banner -->
                <div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm"
                    style="background-image:url(images/background/bg3.jpg);">
                    <div class="container">
                        <div class="dz-bnr-inr-entry">
                            <h1>Checkout</h1>
                            <nav aria-label="breadcrumb" class="breadcrumb-row">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php"> Home</a></li>
                                    <li class="breadcrumb-item">Checkout</li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- inner page banner End-->

                <!-- contact area -->
                <section class="content-inner shop-account">
                    <!-- Product -->
                    <div class="container">
                        <form class="shop-form" method="post" id="form">
                            <input type="hidden" name="grandtotal" value="<?php echo $grantotal; ?>">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="widget">
                                        <h4 class="widget-title">Billing & Shipping Address</h4>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" value="<?php echo $row['name']; ?>" class="form-control"
                                                    placeholder="First Name" required name='fname'>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Company Name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="selectedaddress"
                                                value="<?php echo $row['address']; ?>" placeholder="Address" required>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" name="block"
                                                    placeholder="Apartment, suite, unit etc.">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <input id="cityInput" name="city" type="text" placeholder="Town / City"
                                                    class="form-control" required>



                                                <style>
                                                    .awesomplete {
                                                        display: block !important;

                                                    }

                                                    .awesomplete mark {
                                                        background: none !important;
                                                        color: inherit !important;
                                                        font-weight: normal !important;
                                                    }

                                                    .awesomplete li:hover,
                                                    .awesomplete li[aria-selected="true"],
                                                    .awesomplete ul li:hover mark,
                                                    .awesomplete ul li[aria-selected="true"] mark {
                                                        background: none !important;
                                                        color: inherit !important;
                                                    }
                                                </style>


                                                <script>
                                                    // Load list of cities from JSON
                                                    fetch('admin/dataset/cities-india.json') // Replace with actual path
                                                        .then(response => response.json())
                                                        .then(cityList => {
                                                            const input = document.getElementById("cityInput");

                                                            const awesomplete = new Awesomplete(input, {
                                                                list: cityList,
                                                                minChars: 1,
                                                                autoFirst: false,
                                                                item: function (text) {
                                                                    return Awesomplete.ITEM(text, text); // No highlight
                                                                },
                                                                replace: function (text) {
                                                                    this.input.value = text;
                                                                },
                                                                filter: function (text, input) {
                                                                    return text.toLowerCase().startsWith(input.toLowerCase());
                                                                }
                                                            });
                                                        });
                                                </script>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input id="stateInput" name="state" type="text"
                                                    placeholder="State / District" class="form-control" required>



                                                <style>
                                                    /* Completely remove yellow highlight from matched text */
                                                    .awesomplete mark {
                                                        background: none !important;
                                                        color: inherit !important;
                                                        font-weight: normal !important;
                                                    }

                                                    /* Remove background and styles from hovered or selected suggestions */
                                                    .awesomplete li:hover,
                                                    .awesomplete li[aria-selected="true"],
                                                    .awesomplete ul li:hover mark,
                                                    .awesomplete ul li[aria-selected="true"] mark {
                                                        background: none !important;
                                                        color: inherit !important;
                                                    }
                                                </style>





                                                <script>
                                                    // Load list of states from JSON
                                                    fetch('admin/dataset/states-india.json') // Replace with actual path
                                                        .then(response => response.json())
                                                        .then(stateList => {
                                                            const input = document.getElementById("stateInput");

                                                            const awesomplete = new Awesomplete(input, {
                                                                list: stateList,
                                                                minChars: 1,
                                                                autoFirst: false,
                                                                item: function (text) {
                                                                    return Awesomplete.ITEM(text, text); // No highlight
                                                                },
                                                                replace: function (text) {
                                                                    this.input.value = text;
                                                                },
                                                                filter: function (text, input) {
                                                                    return text.toLowerCase().startsWith(input.toLowerCase());
                                                                }
                                                            });
                                                        });
                                                </script>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" name="zip"
                                                    placeholder="Postcode / Zip" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Email input -->
                                            <div class="form-group col-md-6 position-relative">
                                                <input type="email" name="email" class="form-control" id="emailInput"
                                                    value="<?php echo $row['email']; ?>" placeholder="Email" required>

                                                <!-- Icon inside input box -->
                                                <span id="emailIcon"
                                                    style="position: absolute; top: 50%; right: 15px; transform: translate(-10px, -10px); font-size: 1.2rem;"></span>

                                                <script>
                                                    const emailInput = document.querySelector('input[name="email"]');
                                                    const emailHelp = document.getElementById('emailIcon');

                                                    // Simple email regex pattern for live feedback
                                                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                                                    emailInput.addEventListener('input', function () {
                                                        const email = this.value;

                                                        if (email.length === 0) {
                                                            this.style.borderColor = '';
                                                            emailHelp.textContent = 'Enter a valid email address';
                                                            emailHelp.style.color = 'gray';
                                                        } else if (emailPattern.test(email)) {
                                                            this.style.borderColor = 'green';
                                                            emailHelp.textContent = '✔';
                                                            emailHelp.style.color = 'green';
                                                        } else {
                                                            this.style.borderColor = 'red';
                                                            emailHelp.textContent = '✗';
                                                            emailHelp.style.color = 'red';
                                                        }
                                                    });
                                                </script>
                                            </div>


                                            <!-- Phone input (10 digits only) -->
                                            <div class="form-group col-md-6">
                                                <input type="text" name="txnnumber" class="form-control"
                                                    value="<?php echo $row['phone']; ?>"
                                                    placeholder="Phone (10 digits only)" pattern="[0-9]{10}"
                                                    title="Enter a valid 10-digit phone number" inputmode="numeric"
                                                    required>

                                                <!-- Live validation message -->
                                                <small id="phoneHelp" style="color: gray;"></small>

                                            </div>

                                            <!-- JavaScript -->
                                            <script>
                                                const phoneInput = document.querySelector('input[name="txnnumber"]');
                                                const phoneHelp = document.getElementById('phoneHelp');

                                                phoneInput.addEventListener('keypress', function (e) {
                                                    if (!/\d/.test(e.key)) {
                                                        e.preventDefault(); // block non-digits
                                                    }
                                                });

                                                phoneInput.addEventListener('input', function () {
                                                    const phone = this.value;

                                                    if (phone.length === 0) {
                                                        this.style.borderColor = '';
                                                        phoneHelp.textContent = 'Enter 10-digit phone number';
                                                        phoneHelp.style.color = 'gray';
                                                    } else if (/^\d{10}$/.test(phone)) {
                                                        this.style.borderColor = 'green';
                                                        phoneHelp.textContent = 'Right !!';
                                                        phoneHelp.style.color = 'green';
                                                    } else {
                                                        this.style.borderColor = 'red';
                                                        phoneHelp.textContent = 'Wrong ( 10-digit numbers allowed )';
                                                        phoneHelp.style.color = 'red';
                                                    }
                                                });
                                            </script>



                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">


                                    <div class="form-group">
                                        <div class="widget" style="margin:0 !important;">
                                            <h4 class="widget-title">Payment Type</h4>
                                        </div>
                                        <input type="radio" name="payment[]" id="" class="cod" required><label for=""
                                            class="mx-2">Cash On
                                            Delivery (COD)</label>
                                        <br>
                                        <input type="radio" name="payment[]" id="" class="upi" required><label for=""
                                            class="mx-2">Online
                                            Payments (UPI / Net Banking)</label>
                                    </div>
                                    <div class="form-group notes">
                                        <textarea class="form-control"
                                            placeholder="Notes about your order, e.g. special notes for delivery"
                                            name="notes"></textarea>
                                    </div>

                                </div>
                            </div>


                            <div class="dz-divider bg-gray-dark text-gray-dark icon-center  my-5"><i
                                    class="fa fa-circle bg-white text-gray-dark"></i></div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="widget">
                                        <h4 class="widget-title">Your Order</h4>
                                        <table class="table-bordered check-tbl">
                                            <thead class="text-center">

                                                <tr>
                                                    <th>IMAGE</th>
                                                    <th>BOOK NAME</th>
                                                    <th>TOTAL</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <?php
                                                $ret = mysqli_query($con, "SELECT a.*,b.* FROM books AS a, cart AS b WHERE a.book_id=b.productId AND b.userID='$userid'");
                                                while ($row1 = mysqli_fetch_array($ret)) {
                                                    ?>
                                                    <tr>
                                                        <td class="product-item-img"><img
                                                                src="images/books/<?php echo $row1['image']; ?>" alt=""></td>
                                                        <td class="product-item-name"><?php echo $row1['book']; ?></td>
                                                        <td class="product-price">₹
                                                            <?php echo htmlentities($totalamount = $row1['productQty'] * $row1['price']); ?>
                                                        </td>
                                                    </tr>
                                                    <?php $grantotal = $totalamount;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <h4 class="widget-title">Order Total</h4>
                                    <table class="table-bordered check-tbl mb-4">
                                        <tbody>
                                            <tr>
                                                <td>Order Subtotal</td>
                                                <td class="product-price">₹ <?php echo $granttotal; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Shipping</td>
                                                <td>Free Shipping</td>
                                            </tr>
                                            <tr>
                                                <td>Grand Total</td>
                                                <td class="product-price-total">₹ <?php echo $granttotal; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-group">
                                        <input class="btn btn-primary btnhover pay" type="submit" name="proceedpayment"
                                            value="Place Order Now" id="pay">

                                    </div>
                        </form>
                    </div>
            </div>
        </div>
        <!-- Product END -->
        </section>
        <!--contact area End-->
        </div>
    <?php } ?>
    <!--Footer -->
    <?php include 'footer-white.php'; ?>
    <!--Footer End-->

    <button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
    </div>

    < !--JAVASCRIPT FILES=========================================-->
        <script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
        <script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <!-- BOOTSTRAP MIN JS -->
        <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
        <!-- BOOTSTRAP SELECT MIN JS -->
        <script src="vendor/swiper/swiper-bundle.min.js"></script><!-- SWIPER JS -->
        <script src="vendor/countdown/counter.js"></script><!-- COUNTER JS -->
        <script src="vendor/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
        <script src="vendor/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
        <script src="vendor/wnumb/wNumb.js"></script><!-- WNUMB -->
        <script src="vendor/nouislider/nouislider.min.js"></script>
        <!-- NOUSLIDER MIN JS-->
        <script src="js/dz.carousel.js"></script><!-- DZ CAROUSEL JS -->
        <script src="js/dz.ajax.js"></script><!-- AJAX -->
        <script src="js/custom.js"></script><!-- CUSTOM JS -->
        <script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->
        <script>
            function showDiv() {
                var div = document.getElementById('myDiv');
                div.style.display = 'block';
            }

            function hideDiv() {
                var div = document.getElementById('myDiv');
                div.style.display = 'none';
            }
        </script><!-- WOW JS -->
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            $(".cod").on("click", function () {
                var btn = $("#pay");
                btn.attr('name', "proceedpayment");
                btn.removeClass("pay");
                var form = $("#form");
                form.attr('method', "post");
            });
            $(".upi").on("click", function () {
                var btn = $("#pay");
                btn.attr('name', "");
                btn.addClass("pay");
                var form = $("#form");
                form.attr('method', "");

            });




            $('body').on('click', '.pay', function (e) {


                var form = $("#form")[0]; // Get the form element
                var frm = new FormData(form); // Create FormData object

                var requiredFieldsFilled = true; // Assume all required fields are filled initially

                // Iterate through each input/select/textarea element in the form
                $(form).find("input, select, textarea, file, checkbox, radio").each(function () {
                    // Check if the element has the "required" attribute
                    if ($(this).prop("required")) {
                        // If the value of the element is empty, set requiredFieldsFilled to false
                        if ($(this).val().trim() === '') {
                            requiredFieldsFilled = false;
                            return false; // Break out of the loop if any required field is empty
                        }
                    }
                });

                if (requiredFieldsFilled) {



                    var form = $("#form")[0];
                    var frm = new FormData(form);
                    // var total = $(".total").val();
                    var total = <?php echo $_GET['total']; ?>;
                    frm.append('total', total);
                    var options = {
                        "key": "rzp_live_JIQ5usrR7RioCy", //test

                        //  "key": "rzp_live_JIQ5usrR7RioCy", //  live
                        "amount": (total * 100), // 2000 paise = INR 20
                        "name": "EA Dreams",
                        "description": "Payment",
                        "image": "images/logo.png",
                        "handler": function (response) {
                            frm.append('razorpay_payment_id', response
                                .razorpay_payment_id); // Append Razorpay payment ID to the FormData object
                            $.ajax({
                                url: 'payment-proccess.php',
                                type: 'post',
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                data: frm,
                                success: function (response) {
                                    window.location.href = 'success.html';
                                },
                                error: function (xhr, status, error) {
                                    window.location.href = 'failed.php';
                                }
                            });
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                    e.preventDefault();


                } else {

                }


            });
        </script>


</body>

</html>

<?php
// --- AFFILIATE REFERRAL COMMISSION LOGIC (SCHEMA-COMPLIANT) ---
if (isset($_SESSION['affiliate_ref']) && isset($_SESSION['affiliate_product'])) {
    $referral_code = $_SESSION['affiliate_ref'];
    $buyer_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $product = $_SESSION['affiliate_product'];
    $referrer_query = mysqli_query($con, "SELECT sid FROM students WHERE referral_code='$referral_code'");
    if ($referrer_row = mysqli_fetch_assoc($referrer_query)) {
        $referrer_user_id = $referrer_row['sid'];
        if ($referrer_user_id != $buyer_user_id) {
            // Parse product string to get type and id (e.g., book_123)
            if (preg_match('/^(book|course|test)_(\\d+)$/', $product, $pmatch)) {
                $product_type = $pmatch[1];
                $product_id = $pmatch[2];
                // Prevent duplicate commission for same buyer/product/order
                $exists = mysqli_query($con, "SELECT id FROM affiliate_referrals WHERE buyer_user_id='$buyer_user_id' AND product_type='$product_type' AND product_id='$product_id'");
                if (mysqli_num_rows($exists) == 0) {
                    $now = date('Y-m-d H:i:s');
                    // Insert into affiliate_referrals (schema: referrer_user_id, buyer_user_id, product_type, product_id, referral_code, created_at)
                    mysqli_query($con, "INSERT INTO affiliate_referrals (referrer_user_id, buyer_user_id, product_type, product_id, referral_code, created_at) VALUES ('$referrer_user_id', '$buyer_user_id', '$product_type', '$product_id', '$referral_code', '$now')");
                    $referral_id = mysqli_insert_id($con);
                    // Get price and commission percent from correct table
                    $commission_amount = 0;
                    $commission_percent = 10;
                    if ($product_type == 'book') {
                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM books WHERE book_id='$product_id'"));
                        if ($row) {
                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                        }
                    } elseif ($product_type == 'course') {
                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM course WHERE course_id='$product_id'"));
                        if ($row) {
                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                        }
                    } elseif ($product_type == 'test') {
                        $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT price, affiliate_commission_percent FROM test WHERE test_id='$product_id'"));
                        if ($row) {
                            $commission_percent = isset($row['affiliate_commission_percent']) ? $row['affiliate_commission_percent'] : 10;
                            $commission_amount = round($row['price'] * ($commission_percent / 100), 2);
                        }
                    }
                    // Insert into affiliate_commissions (schema: referral_id, referrer_user_id, buyer_user_id, product_type, product_id, order_id, commission_amount, status, created_at)
                    $order_id = isset($orderno) ? $orderno : null;
                    mysqli_query($con, "INSERT INTO affiliate_commissions (referral_id, referrer_user_id, buyer_user_id, product_type, product_id, order_id, commission_amount, status, created_at) VALUES ('$referral_id', '$referrer_user_id', '$buyer_user_id', '$product_type', '$product_id', '$order_id', '$commission_amount', 'pending', '$now')");
                }
            }
        }
    }
    unset($_SESSION['affiliate_ref']);
    unset($_SESSION['affiliate_product']);
}
// --- END AFFILIATE REFERRAL COMMISSION LOGIC (SCHEMA-COMPLIANT) ---
