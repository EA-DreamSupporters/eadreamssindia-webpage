<?php
session_start();
include('config.php');

// error_reporting(0);


 


if(isset($_POST['send'])){

    $pay_id = $_SESSION['payment_id'];
    $yoe = $_POST['yoe'];
    $wt = $_POST['wt'];
    $st = $_POST['st'];
    $pt = $_POST['pt'];
    $nt = $_POST['nt'];
    $father = $_POST['father'];
    $mother = $_POST['mother'];
    $yourself = $_POST['yourself'];
    $pr = $_POST['pr'];
    $fs = $_POST['fs'];
    $ds = $_POST['ds'];

    $cs = $_POST['cs'];
    if($cs == 'Yes'){
        $inst = $_POST['inst'];
    }else{
        $inst = 'No';
    }
    $problems = implode(",",$_POST['problems']);


     $update  = mysqli_query($con,"UPDATE guide SET yoe='$yoe',wakeup_time='$wt',sleep_time='$st',pre_time='$pt',walk_time='$nt',father='$father',mother='$mother',about='$yourself',problem_faced='$pr',favorite='$fs',difficult='$ds',institute='$inst',problems='$problems' WHERE payment_id='$pay_id'");

     if($update){

         echo "<script>window.alert('Success')</script>";
         echo "<script>window.location.assign('dashboard.php')</script>";

     }else{

        echo "<script>window.alert('Try Again')</script>";


     }
    

   
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords"
        content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="description"
        content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course." />

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />

    <!-- PAGE TITLE HERE -->
    <title>EA Guidance - EA Dream Supporters</title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">

    <!-- GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
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

    .show {
        display: block;
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

        <?php include'header1.php'; ?>
        <!-- Header End -->



        <div class="page-content bg-white">
            <!--banner-->
            <div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm mb-5"
                style="background-image:url(images/background/bg3.jpg);">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>EA Guidance Form</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"> Home </a></li>
                                <li class="breadcrumb-item">EA GUIDANCE FORM</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>



            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary msgBtn d-none" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Launch demo modal
            </button>

            <!-- Modal -->
            <div class="modal fade msgModal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Message!</h1>
                            <button type="button" class="btn-close d-none" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Please Fill Below Form to Complete The Process
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary cbtn" data-bs-dismiss="modal">Continue</button>
                            <button type="button" class="btn btn-primary d-none">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Box -->
            <section class="contact-wraper1 mt-5"
                style=" background-image: url(images/background/bg3.jpg); background-repeat:no-repeat!important;">
                <div class="container my-5">
                    <div class="row">


                        <div class="col-lg-5">
                            <div class="contact-info">


                            </div>
                        </div>



                        <div class=" col-lg-7">

                            <div class="p-5 mt-5 m-r20 m-md-r0"
                                style="  background-color: #eae7e4;margin-bottom: -40px;">
                                <div class="style-1">
                                    <h6 class="sub-title text-primary">Fill Now</h6>
                                    <h3 class="title ">To Kick Start Your Dream!
                                    </h3>
                                </div>


                                <form id="myform" class="row" method="post">



                                    <div class="col-md-6 form-group mb-2">

                                        <label for="yoe">Years Of Experience</label>
                                        <input required type="text" class="form-control" name="yoe"
                                            placeholder="Years Of Experience">

                                    </div>
                                    <div class="col-md-6 form-group mb-2">

                                        <label for="wt">WakeUp Time</label>
                                        <input required type="time" class="form-control" name="wt">

                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label for="st">Sleeping Time</label>
                                        <input required type="time" class="form-control" name="st">
                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label for="pt">Preparation Time</label>
                                        <input required type="time" class="form-control" name="pt">
                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label for="nt">Walking Time</label>
                                        <input required type="time" class="form-control" name="nt">
                                    </div>

                                    <div class="col-12 form-group ">

                                        <label for="parents">Parents Profession</label>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <input required type="text" class="form-control mb-2" name="father"
                                                    placeholder="Father">


                                            </div>
                                            <div class="col-md-6">
                                                <input required type="text" class="form-control mb-2" name="mother"
                                                    placeholder="Mother">


                                            </div>


                                        </div>

                                    </div>


                                    <div class="col-12 form-group mb-2">
                                        <label for="pr">Tell Us About Yourself ?</label>
                                        <textarea required name="yourself" rows="5" class="form-control"
                                            placeholder="About Yourself"></textarea>
                                    </div>

                                    <div class="col-12 form-group mb-2">
                                        <label for="pr">Problem You Face in Previous Experience ?</label>
                                        <textarea required name="pr" rows="5" class="form-control"
                                            placeholder="Problems"></textarea>
                                    </div>

                                    <div class="col-md-6 form-group mb-2">
                                        <label for="fs">Favorite Subjects</label>
                                        <input class="form-control" type="text" name="fs" required
                                            placeholder="Enter Your Favorite Subjects">

                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label for="ds">Difficult Subjects</label>
                                        <input class="form-control" type="text" name="ds" required
                                            placeholder="Enter Your Difficult Subjects">

                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label for="cs">Are You Coaching Institute Student?</label>

                                        <select name="cs" class="form-select cs">
                                            <option value="">Select</option>

                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group mb-2 csis">

                                    </div>
                                    <div class="col-12 form-group mb-2">
                                        <label for="sp">Select Your Problems</label>


                                        <!-- <select name="problems" id="" class="form-select" multiple>

                                        <option value="Inconsistent">Inconsistent</option>
                                        <option value="Indicipline">Indicipline</option>
                                        <option value="Irregular Preparation">Irregular Preparation</option>
                                        <option value="No Revision">No Revision</option>
                                        <option value="No Proper Test">No Proper Test</option>
                                        <option value="Sleep During Preparation">Sleep During Preparation</option>
                                        <option value="Depression or Stress">Depression or Stress</option>
                                        

                                        </select> -->

                                        <div class="row">

                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="Inconsistent" name="problems[]"
                                                    class="form-check me-2"> <label for="">Inconsistent</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="Indicipline" name="problems[]"
                                                    class="form-check me-2"> <label for="">Indicipline</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="Irregular Priparation" name="problems[]"
                                                    class="form-check me-2"> <label for="">Irregular
                                                    Preparation</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="No Revision" name="problems[]"
                                                    class="form-check me-2"> <label for="">No
                                                    Revision</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="No Proper Test" name="problems[]"
                                                    class="form-check me-2"> <label for="">No Proper
                                                    Test</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="Sleep During Preparation"
                                                    name="problems[]" class="form-check me-2"> <label for="">Sleep
                                                    During
                                                    Preparation</label>
                                            </div>
                                            <div class="d-flex col-md-6">
                                                <input type="checkbox" value="Depression or Stress" name="problems[]"
                                                    class="form-check me-2"> <label for="">Depression
                                                    or
                                                    Stress</label>
                                            </div>

                                        </div>

                                    </div>






                                    <?php
									 if($_SESSION["id"])
                                              {
									 ?>
                                    <div class="col-12 form-group   mt-4">
                                        <button name="send" type="submit" value="submit"
                                            class="btn w-100  btn-primary btnhover" id="buy">SUBMIT</button>
                                    </div>
                                    <?php
                                         } else{
									?>
                                    <div class="col-12 form-group  mt-4">
                                        <a href="" class="btn w-100  btn-primary btnhover" data-bs-toggle="modal"
                                            data-bs-target="#myModal">Login to get guidance</a>
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
            <!-- FORM -->




            <!-- Newsletter -->

            <!-- Newsletter End -->

        </div>

        <!-- Footer -->
        <?php include'footer-white.php'; ?>
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


    <script>
    $(document).ready(function() {

        $(".cs").on("change", function() {

            var csVal = $(".cs").val();
            if (csVal == 'Yes') {

                $(".csis").html(
                    "<label >Institute Name</label><input required name='inst' type='text' placeholder='Enter Institute Name' class='form-control'>"
                );

            } else {
                $(".csis").html("");
            }


        });



        $(".msgBtn").click();




        $(".cbtn").on("click", function() {

            $(window).scrollTop("400");


        });



    });
    </script>



</body>

</html>