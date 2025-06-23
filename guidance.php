<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['selected_plan'])) {
        $_SESSION['selected_plan'] = $_POST['selected_plan'];
    }
    if (isset($_POST['selected_exam'])) {
        $_SESSION['selected_exam'] = $_POST['selected_exam'];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedExam = $_POST['selectedExam'] ?? '';
    $selectedPlan = $_POST['selectedPlan'] ?? '';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="description" content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course." />

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <!-- PAGE TITLE HERE -->
    <title>GovPrep - EA Dream Supporters</title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/dist/css/bootstrap-select.min.css">

    <!-- GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">


    <style>
        .badge-container {
            display: flex;
            align-items: center;
        }

        .badge-2 {
            font-family: Poppins;
            font-weight: 800;
            font-size: 1.5rem;
            /* Adjust font size as needed */
            color: #fff;
            background: linear-gradient(45deg, #B70C02, #CC0B00);
            padding: 10px 15px;
            /* Adjust padding for better text fitting */
            border-radius: 10px;
            text-align: center;
            line-height: 1;
            /* Adjust line-height to match font size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            /* Prevent text wrapping */

        }

        .badge-2:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4), transparent);
            animation: shine 2s infinite;
            transform: rotate(-30deg);
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .error {
            color: #FF0001;
        }

        .section-head {
            margin-bottom: 219px;
        }

        iframe {
            width: 100%;
            height: 500px;
        }

        .rwd-media {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* 16:9 */
        }

        .rwd-media iframe,
        .rwd-media video {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .pricing-card {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1064px;
            overflow: hidden;
            position: relative;
            right: -57px;
        }

        .card-logo {
            padding: 20px;
            background-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-logo i {
            font-size: 48px;
            color: #fff;
        }

        .card-content {
            padding: 20px;
            flex: 1;
        }

        .plan-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 32px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .plan-description p {
            font-size: 18px;
            margin: 5px 0;
        }

        .card-select {
            margin-top: 20px;
            text-align: center;
        }

        .card-select select {
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ddd;
            width: 16%;
        }

        @media (max-width: 768px) {
            .pricing-card {
                flex-direction: column;
            }

            .card-logo {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }

            .card-select select {
                width: 100%;
            }
            .border-gradient-rounded
			{
				display:none;
			}
        }

        .svg-logo {
            margin-right: 20px;
            /* Space between SVG and text */
        }

        .text-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title {
            font-family: poppins;
            font-weight: bolder;
            margin: 0;
        }

        .highlight-text {
            font-family: poppins;
            font-weight: bolder;
            color: white;
            margin-top: 10px;
            /* Space below the ESSENTIAL Plan heading */
        }

        .fs {
            width: 250px;
            text-align: center;
            border-radius: 80px !important;
            border: 1px solid black;
            padding: 10px 0;
            appearance: none !important;
        }

        .rounded-container {
            background-image: url('images/background/bg23-man.jpg');
            background-size: cover;
            border-radius: 20px;
            /* Rounded corners */
            max-width: 1280px;
            /* Max width for the container */
            padding: 20px;
            /* Padding inside the container */
            margin-bottom: 192px;
            position: relative;
            /* To position the logo absolutely */
            color: white;
            text-align: center;
            z-index: 1;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 100px;
        }

        .form-control-1 {
            background-color: transparent;
            /* Ensure no fill */
            border: 2px solid white;
            margin-left: 0px !important;
            border-radius: 5px;
            padding: 10px;
            width: 200px;
            /* Adjust width as needed */
            color: white;
            /* Adjust text color as needed */
        }

        .form-control-1:focus,
        .form-control-1:active,
        .form-control-1 option:checked {
            background-color: #1a1a1a;
            /* Light transparent background on focus/active */
        }

        select.form-control-1 option {
            background-color: #333;
            /* Background for options */
            color: white;
            /* Text color for options */
        }

        select.form-control-1 option:hover,
        select.form-control-1 option:focus {
            background-color: #1a1a1a;
            /* Light transparent background on hover/focus */
        }
        .video-container {
            position: relative;
            width: 100%; /* Match the width of the iframe */
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            border-radius: 30px; /* Adjust the radius as needed */
            overflow: hidden; /* Ensures the iframe content does not overflow */
}

        .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        }
                
    </style>

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
        <!-- Header -->

        <?php include('header1.php') ?>
        <!-- Header End -->

        <div class="page-content bg-white">
            <!--banner-->
            <div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>GovPrep</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item">GovPrep</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!--Our Mission Section-->
            <section class="content-inner-1 bg-light" style="padding-bottom: 1px;">
                <div class="container">
                    <div class="section-head text-center " style="margin-bottom: 100px;">
                        <h2 class="title">What is GovPrep?</h2><br><br>
                        <div class="video-container">
                            <iframe src="https://www.youtube.com/embed/QCv6oALIpow?si=obkGx1t31fEyOHfh" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </section>
            <!--icon-box3 section-->
            <?php include('subscriptionplan.php'); ?>

            <!-- Testimonial -->

            <!-- Testimonial End -->


            <!-- Feature Box -->
            <section class="py-5 rounded-container " style=" background-size: cover; margin-bottom: 220px; margin-top: 100px;" title="Illustration by Olha Khomich" ;>
                <div class="container">
                    <div class="row pt-3 subscride-inner">
                        <div class="col-12 d-flex flex-column align-items-start" style="margin-top: -30px">
                            <!-- Flex container for SVG and ESSENTIAL Plan -->
                            <div class="d-flex align-items-center mb-2">
                                <!-- SVG Logo -->
                                <div class="svg-logo me-3">
                                    <img src="images/background/Gov.svg" alt="" height="100px" width="160px">
                                </div>

                                <!-- ESSENTIAL Plan -->
                                <div class="badge-container">
                                    <div class="badge-2 animate__animated animate__pulse">ESSENTIAL Plan</div>
                                </div>
                            </div>

                            <!-- Description Text -->
                            <p class="highlight-text" style="font-family: Outfit; font-size:20px; font-weight: 500">Looking For Only Personalised Preparation Management?</p>
                        </div>
                    </div>

                    <!-- Main Content and Buy Now Button -->
                    <div class="row pt-3">
                        <!-- Center content in a flex container -->
                        <div class="col-12 d-flex justify-content-start align-items-center">
                            <!-- Select Exam Dropdown -->
                            <select id="examChoose" class="fs form-control-1 me-3" style="color: #fff; border-radius:3px;">
                                <option value="" disabled selected>Select Exam</option>
                                <option value="UPSC">UPSC</option>
                                <option value="TNPSC - Group 1">TNPSC Group 1</option>
                                <option value="TNPSC - Group 2">TNPSC Group 2</option>
                                <option value="TNPSC - Group 4">TNPSC Group 4</option>
                                <option value="CGL">SSC CGL</option>
                                <option value="CHSL">SSC CHSL</option>
                                <option value="MTS">SSC MTS</option>
                                <option value="Bank">Banking</option>
                            </select>

                            <!-- Pricing Display -->
                            <div class="d-flex" style="margin-right:20px;">
                                <div id="pricingDisplay" class="me-3 align-content-center" style="font-weight:bolder; font-size:25px; margin-right:10px;"></div>
                            </div>

                            <!-- Buy Now Button -->
                            <div class="buy-now">
                                <?php
                                if (strlen($_SESSION['id']) == 0) {
                                ?>
                                    <a class="btn btn-primary" href="login.php">
                                        <i aria-hidden="true" style="padding-right: 5px;"></i>
                                        Buy Now
                                    </a>
                                <?php
                                } else {
                                ?>
                                    <button class="btn btn-primary btnhover" id="buyNowButton">
                                        <i class="bi-cart-fill me-1"></i> Buy Now
                                    </button>
                                <?php
                                }
                                ?>
                            </div>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const examSelect = document.getElementById("examChoose");
                                    const planSelect = document.getElementById("plan");
                                    const pricingDisplay = document.getElementById("pricingDisplay");

                                    const plan = {
                                        "UPSC": "₹ 45,999",
                                        "TNPSC - Group 1": "₹ 34,999",
                                        "TNPSC - Group 2": "₹ 25,999",
                                        "TNPSC - Group 4": "₹ 20,999",
                                        "CGL": "₹ 25,999",
                                        "CHSL": "₹ 20,999",
                                        "MTS": "₹ 14,999",
                                        "Bank": "₹ 29,999",
                                    };

                                    const plans = {
                                        "UPSC": "GovPrep ( Essential Plan ) | UPSC ",
                                        "TNPSC - Group 1": "GovPrep ( Essential Plan ) | TNPSC Group 1 ",
                                        "TNPSC - Group 2": "GovPrep ( Essential Plan ) | TNPSC Group 2 ",
                                        "TNPSC - Group 4": "GovPrep ( Essential Plan ) | TNPSC Group 4 ",
                                        "CGL": "GovPrep ( Essential Plan ) | SSC CGL ",
                                        "CHSL": "GovPrep ( Essential Plan ) | SSC CHSL ",
                                        "MTS": "GovPrep ( Essential Plan ) | SSC MTS ",
                                        "Bank": "GovPrep ( Essential Plan ) | Banking",
                                    };
                                    

                                    examSelect.addEventListener("change", function() {
                                        const selectedExam = examSelect.value;
                                        if (plans[selectedExam]) {
                                            planSelect.innerHTML = `<option value="${plans[selectedExam]}">${plans[selectedExam]} - ${plan[selectedExam]}</option>`;
                                            pricingDisplay.innerHTML = `<p style="margin-bottom:5px;">${plan[selectedExam]}</p>`;
                                        } else {
                                            planSelect.innerHTML = '<option value="" disabled selected>Select Plan above to auto select</option>';
                                            pricingDisplay.innerHTML = '';
                                        }
                                    });
                                    // Initialize to hide pricing display
                                    pricingDisplay.innerHTML = '';
                                });
                            </script>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const buyNowButton = document.getElementById("buyNowButton");
                                    const loginGuidanceButton = document.getElementById("govprep");

                                    if (buyNowButton && loginGuidanceButton) {
                                        buyNowButton.addEventListener("click", function(event) {
                                            // Prevent default behavior if necessary
                                            event.preventDefault();

                                            // Scroll to the login guidance button smoothly
                                            loginGuidanceButton.scrollIntoView({
                                                behavior: 'smooth'
                                            });
                                        });
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>

        </div>
    </div>
    </div>
    </div>
    </section>

    <!-- FORM -->
    <div class="page-content">
        <section class="contact-wraper1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center align-items-center" style="margin-top: -50px; ">
                        <style>
                            .contact-info {
                                height: 100%;
                                width: 100%;
                                background-size: contain;
                                background-repeat: no-repeat;
                                background-position: center;
                                position: relative;
                            }

                            .contact-info:after {
                                bottom: 0px;
                                right: -7px;
                                position: absolute;
                                content: "";
                                width: 90%;
                                z-index: 1;
                                height: 70%;

                                border-top: 52px solid transparent;
                                border-left: 52px solid transparent;
                                border-bottom: 52px solid #eaff00;
                                border-right: 52px solid #eaff00;
                            }

                            @media(max-width:768px) {
                                .contact-info {
                                    height: 100vh;
                                }

                                .contact-info:after {

                                    bottom: 0px;
                                    right: 0px;
                                    position: absolute;
                                    content: "";
                                    /* width: 254px;
                                        height: 254px; */
                                    z-index: 1;


                                    border-top: 22px solid transparent;
                                    border-left: 22px solid transparent;
                                    border-bottom: 22px solid #eaff00;
                                    border-right: 22px solid #eaff00;

                                }

                            }
                        </style>
                        <div class="contact-info" style="background-image: url(images/background/avatar.png); background-repeat:no-repeat!important;">


                        </div>
                    </div>
                    <div class="col-md-6 m-b40">
                        <div class="contact-area1 m-r20 m-md-r0 " style="  background-color: #eae7e4;" id="govprep">
                            <div class="section-head style-1">
                                <h6 class="sub-title text-primary">Register Now</h6>
                                <h3 class="title m-b20" style="margin-bottom: -193px;">To Kick Start Your Dream!
                                </h3>
                            </div>
                            <form id="myform">
                                <p>Name<span class="error">* <?php echo $nameErr; ?> </span></p>
                                <div class="input-group">
                                    <input required type="text" class="form-control" name="name" placeholder="Full Name">
                                </div>
                                <p>Date Of Birth</p>
                                <div class="input-group">
                                    <input required type="date" class="form-control" name="dob" value="Full Name">
                                </div>
                                <p>Email<span class="error">* <?php echo $emailErr; ?></p>
                                <div class="input-group">
                                    <input required type="email" class="form-control" name="email" placeholder="Email Adress">
                                </div>
                                <p>Contact<span class="error">* <?php echo $mobilenoErr; ?> </span> </p>
                                <div class="input-group">
                                    <input required type="tel" class="form-control" name="phone" pattern="[0-9]{10}" placeholder="Phone No.">
                                </div>
                                <div class="form-group">


                                    <label for="plan">Choose Plan</label>
                                    </br>
                                    <select required class="form-control" id="plan" name="plan" required>
                                        <option value="" disabled selected>Select Plan above to auto select</option>
                                    </select>

                                </div></br>
                                <div class="form-group">

                                    <label for="sel1">Profession</label>
                                    </br>
                                    <select required name="passed" class="form-control" id="sel1">
                                        <option value="">Select Profession</option>
                                        <option value="Student">Student</option>
                                        <option value="Working Proffessional">Working Proffessional</option>
                                        <option value="Full Time Aspirants">Full time Aspirants</option>
                                        <option value="Enterprise Owner/CEO/Senior Level Officer">Enterprise Owner/CEO/Senior Level Officer</option>
                                        <option value="Enterprise Managers/Officers">Enterprise Manager/Officers</option>

                                    </select>
                                </div></br>

                                <p>Address</p>
                                <div class="input-group">
                                    <input required type="text" class="form-control" name="address" placeholder="Address">
                                </div>
                                <div class="input-group">
                                    <textarea required name="subject" rows="5" class="form-control" placeholder="Message"></textarea>
                                </div>
                                <div class="mb-3">
                                    <div required class="g-recaptcha" data-sitekey="6LfZlSETAAAAAC5VW4R4tQP8Am_to4bM3dddxkEt"></div>
                                </div>
                                <?php
                                if ($_SESSION["id"]) {
                                ?>
                                    <div>
                                        <button name="send" type="submit" value="submit" class="btn w-100 btn-primary btnhover" id="buy">SUBMIT</button>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div>
                                        <a href="" class="btn w-100 btn-primary btnhover" data-bs-toggle="modal" data-bs-target="#myModal">Login to get guidance</a>
                                    </div>
                                <?php
                                }
                                ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>



    <!-- Newsletter -->

    <!-- Newsletter End -->

    </div>

    <!-- Footer -->
    <?php include 'footer1.php'; ?>
    <!-- Footer End -->

    <button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
    </div>

    <!-- JAVASCRIPT FILES ========================================= -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
    <script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
    <script src="vendor/swiper/swiper-bundle.min.js"></script><!-- SWIPER JS -->
    <script src="vendor/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
    <script src="vendor/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
    <script src="js/dz.carousel.js"></script><!-- DZ CAROUSEL JS -->
    <script src="js/dz.ajax.js"></script><!-- AJAX -->
    <script src="js/custom.js"></script><!-- CUSTOM JS -->
    <script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->




    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        $('body').on('click', '#buy', function(e) {


            var form = $("#myform")[0]; // Get the form element
            var frm = new FormData(form); // Create FormData object

            var requiredFieldsFilled = true; // Assume all required fields are filled initially

            // Iterate through each input/select/textarea element in the form
            $(form).find("input, select, textarea, file, checkbox, radio").each(function() {
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



                var form = $("#myform")[0];
                var frm = new FormData(form);
                // var total = $(".total").val();
                var total = $("#plan option:selected").data("plan");
                frm.append('total', total);
                var options = {






                    // "key": "rzp_test_RJn81fRP4u1OSA", //test  

                    "key": "rzp_live_JIQ5usrR7RioCy", //  live
                    "amount": (total * 100), // 2000 paise = INR 20
                    "name": "EA Dreams",
                    "description": "Payment",
                    "image": "images/logo.png",
                    "handler": function(response) {
                        frm.append('razorpay_payment_id', response
                            .razorpay_payment_id); // Append Razorpay payment ID to the FormData object
                        $.ajax({
                            url: 'stepform1.php',
                            type: 'post',
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            data: frm,
                            success: function(response) {
                                window.location.href = 'stepform2.php';
                            },
                            error: function(xhr, status, error) {

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