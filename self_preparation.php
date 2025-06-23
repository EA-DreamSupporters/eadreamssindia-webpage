<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Online book center, Government exam materials rental, book rental, TNPSC, SSC, UPSC, BANKING, career guidance, Youtube videos, magazine subscription, institute courses, test packs for government exam preparations" />
    <meta name="author" content="DexignZone" />
    <meta name="robots" content="" />
    <meta name="description" content="EA Dream Supporters is an ED tech business that began to care about the career aspirations of Indian youngsters. Numerous youths struggled to locate the appropriate resources and opportunities for their dream. So EA creates Dream Supporting Services, a unique one-stop portal for competitive test preparation. We support youth by acting as a prodding force for their desired course." />

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />

    <!-- PAGE TITLE HERE -->
    <title>Self Preparation Zone - EA Dream Supporters</title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">


    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">



    <!-- GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .dz-blog.blog-half.style-1 .dz-media {
            width: 300px;
        }

        button {
            background: none;
            color: inherit;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
        }

        .mfp-container {
            z-index: 200000 !important;
        }
    </style>

    <style>
        .widget-posts {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 0.375rem;
            margin-bottom: 10px;
            transition: all 0.5s;
            gap: 0 10px;



        }

        .widget-posts:hover {
            box-shadow: 0px 0px 60px rgba(0, 0, 0, 0.1);
        }



        .cut-text {
            text-overflow: ellipsis;
            overflow: hidden;
            display: block;
            width: 160px;
            height: 1.2em;
            white-space: nowrap;
        }

        .w-img {
            width: 100%;
        }

        .sidber-video-btn a {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            margin: auto;
            content: "";
            bottom: 0;
            height: 50px;
            width: 50px;
            background: #ffffff;
            line-height: 50px;
            text-align: center;
            border-radius: 50%;
            font-size: 16px;
            color: #eaa451;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {

                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {

                box-shadow: 0 0 0 45px rgba(255, 255, 255, 0);
            }

            100% {

                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .dz-blog.blog-half.style-1 .dz-media a {
            height: fit-content !important;
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
        <?php include 'header1.php'; ?>
        <!-- Header End -->
        <?php
        if (isset($_POST['video'])) {
            $pid = $_POST['pid'];
            $result2 = mysqli_query($con, "SELECT * FROM precontent WHERE p_id='$pid'");
            $row2 = mysqli_fetch_array($result2);
            $count = $row2['count'];
            $plus = $count + 1;

            $sql = mysqli_query($con, "UPDATE precontent SET count='$plus' WHERE p_id='$pid'");

            echo "<script>  window.location.assign('" . $_POST['plink'] . "')</script>";
        }
        ?>
        <div class="page-content">
            <!-- inner page banner -->
            <div class="dz-bnr-inr overlay-secondary-dark dz-bnr-inr-sm" style="background-image:url(images/background/bg3.jpg);">
                <div class="container">
                    <div class="dz-bnr-inr-entry">
                        <h1>Self Study India</h1>
                        <nav aria-label="breadcrumb" class="breadcrumb-row">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"> Home</a></li>
                                <li class="breadcrumb-item">Self Study India</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- inner page banner End-->

            <!-- Blog Large -->
            <section class="content-inner-1 bg-img-fix">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8">
                            <h3 class="title">SELF STUDY INDIA: Prep Smarter, Achieve Faster.</h3>
                            <p class="m-b30">Self Study India provides a streamlined platform for efficient learning. Access meticulously curated YouTube video content, categorized by subject and topic. Optimize your study time with our focused approach.</p>
                            <form method="POST">
                                <div class="form-group">

                                    <select name="subject" class="form-control" id="subject-dropdown" style="margin-top: 14px;" required>
                                        <option selected>Choose the Subject</option>
                                        <?php
                                        $result1 = mysqli_query($con, "SELECT * FROM subject");
                                        while ($row1 = mysqli_fetch_array($result1)) {
                                        ?>
                                            <option value="<?php echo $row1['subjectid']; ?>">
                                                <?php echo $row1['subject']; ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>

                                </div></br>
                                <div class="form-group">

                                    <select name="topic" class="form-control" id="topic-dropdown" required>
                                        <option selected>Choose the Topic</option>
                                    </select>

                                </div></br>
                                <label><b>Choose Language</b></label>
                                <div class="form-group" style="margin-top:10px;">
                                    <input type="radio" id="tamil" name="language" value="Tamil" required>
                                    <label for="tamil">Tamil</label>
                                    <input type="radio" id="english" name="language" value="English" style="margin-left:10px" required>
                                    <label for="english">English</label>
                                    <input type="radio" id="hindi" name="language" value="Hindi" style="margin-left:10px" required>
                                    <label for="hindi">Hindi</label>
                                </div></br>
                                <div class="extra-nav">
                                    <div class="extra-cell">
                                        <button class="btn btn-primary btnhover" type="submit" name="search">Submit</button>
                                    </div>
                                </div>
                            </form>

                            <br>
                            <br>
                            <form method="post" action="<?php echo ($_SERVER["PHP_SELF"]); ?>">
                                <br>
                                <br>
                                <style>
                                    .dz-blog .dz-media,
                                    .dz-blog .dz-info {
                                        flex: 0;
                                    }
                                </style>
                                <?php
                                if (isset($_POST['search'])) {
                                    $sub = $_POST['subject'];
                                    $topi = $_POST['topic'];
                                    $result3 = mysqli_query($con, "SELECT * FROM precontent WHERE subjid='$sub' AND topid='$topi'AND language='" . $_POST['language'] . "' ORDER BY count DESC");
                                    $row3 = mysqli_fetch_array($result3);
                                    $cnt = mysqli_num_rows($result3);
                                    if ($cnt > 0) {
                                ?>
                                        <p class="dz-title">Showing Search results</p>

                                        <?php
                                        if ($row3['link1'] != '') {
                                        ?>
                                            <div class="dz-blog style-1 bg-white m-b30 blog-half " style="border: 1px solid rgba(185, 95, 95, 0.13);">
                                                <div class="dz-media dz-img-effect -video" style="padding: 10px;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row3['link1']; ?>"><i class="fas fa-play"></i></a>

                                                    </div>



                                                </div>

                                                <div class="dz-info" style="border: none;">
                                                    <a href="<?php echo $row3['link1']; ?>">
                                                        <h5 class="dz-title"><?php echo $row3['title1']; ?></h5>
                                                    </a>
                                                    <p class="dz-title"><?php echo $row3['cname1']; ?></p>

                                                    <input class="form-control" name="pid" type="hidden" value="<?php echo $row3['p_id']; ?>" />
                                                    <input class="form-control" name="plink" type="hidden" value="<?php echo $row3['link1']; ?>" />
                                                    <p class="dz-title"><a href="<?php echo $row3['link1']; ?>" target="_blank">
                                                            <button name="video" type="submit">Watch This Video</button>
                                                        </a></p>



                                                </div>

                                            </div>

                                        <?php } ?>
                                        <?php
                                        if ($row3['link2'] != '') {
                                        ?>
                                            <div class="dz-blog style-1 bg-white m-b30 blog-half " style="border: 1px solid rgba(185, 95, 95, 0.13);">
                                                <div class="dz-media dz-img-effect -video" style="padding: 10px;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row3['link2']; ?>"><i class="fas fa-play"></i></a>
                                                    </div>

                                                </div>

                                                <div class="dz-info" style="border: none;">
                                                    <a href="<?php echo $row3['link2']; ?>">
                                                        <h5 class="dz-title"><?php echo $row3['title2']; ?></h5>
                                                    </a>
                                                    <p class="dz-title"><?php echo $row3['cname2']; ?></p>

                                                    <input class="form-control" name="pid" type="hidden" value="<?php echo $row3['p_id']; ?>" />
                                                    <input class="form-control" name="plink" type="hidden" value="<?php echo $row3['link2']; ?>" />
                                                    <p class="dz-title"><a href="<?php echo $row3['link2']; ?>" target="_blank">
                                                            <button name="video" type="submit">Watch This Video</button>
                                                        </a></p>


                                                </div>

                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($row3['link3'] != '') {
                                        ?>
                                            <div class="dz-blog style-1 bg-white m-b30 blog-half " style="border: 1px solid rgba(185, 95, 95, 0.13);">
                                                <div class="dz-media dz-img-effect -video" style="padding: 10px;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row3['link3']; ?>"><i class="fas fa-play"></i></a>
                                                    </div>

                                                </div>


                                                <div class="dz-info" style="border: none;">
                                                    <a href="<?php echo $row3['link3']; ?>">
                                                        <h5 class="dz-title"><?php echo $row3['title3']; ?></h5>
                                                    </a>
                                                    <p class="dz-title"><?php echo $row3['cname3']; ?></p>

                                                    <input class="form-control" name="pid" type="hidden" value="<?php echo $row3['p_id']; ?>" />
                                                    <input class="form-control" name="plink" type="hidden" value="<?php echo $row3['link3']; ?>" />
                                                    <p class="dz-title"><a href="<?php echo $row3['link3']; ?>" target="_blank">
                                                            <button name="video" type="submit">Watch This Video</button>
                                                        </a></p>


                                                </div>

                                            </div>
                                        <?php } ?>

                                        <?php
                                        if ($row3['link4'] != '') {
                                        ?>
                                            <div class="dz-blog style-1 bg-white m-b30 blog-half " style="border: 1px solid rgba(185, 95, 95, 0.13);">
                                                <div class="dz-media dz-img-effect -video" style="padding: 10px;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row3['link4']; ?>"><i class="fas fa-play"></i></a>
                                                    </div>

                                                </div>


                                                <div class="dz-info" style="border: none;">
                                                    <a href="<?php echo $row3['link4']; ?>">
                                                        <h5 class="dz-title"><?php echo $row3['title4']; ?></h5>
                                                    </a>
                                                    <p class="dz-title"><?php echo $row3['cname4']; ?></p>

                                                    <input class="form-control" name="pid" type="hidden" value="<?php echo $row3['p_id']; ?>" />
                                                    <input class="form-control" name="plink" type="hidden" value="<?php echo $row3['link4']; ?>" />
                                                    <p class="dz-title"><a href="<?php echo $row3['link4']; ?>" target="_blank">
                                                            <button name="video" type="submit">Watch This Video</button>
                                                        </a></p>


                                                </div>

                                            </div>
                                        <?php } ?>
                                        <?php
                                        if ($row3['link5'] != '') {
                                        ?>
                                            <div class="dz-blog style-1 bg-white m-b30 blog-half " style="border: 1px solid rgba(185, 95, 95, 0.13);">
                                                <div class="dz-media dz-img-effect -video" style="padding: 10px;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row3['link5']; ?>"><i class="fas fa-play"></i></a>
                                                    </div>

                                                </div>


                                                <div class="dz-info" style="border: none;">
                                                    <a href="<?php echo $row3['link5']; ?>">
                                                        <h5 class="dz-title"><?php echo $row3['title5']; ?></h5>
                                                    </a>
                                                    <p class="dz-title"><?php echo $row3['cname5']; ?></p>

                                                    <input class="form-control" name="pid" type="hidden" value="<?php echo $row3['p_id']; ?>" />
                                                    <input class="form-control" name="plink" type="hidden" value="<?php echo $row3['link5']; ?>" />
                                                    <p class="dz-title"><a href="<?php echo $row3['link5']; ?>" target="_blank">
                                                            <button name="video" type="submit">Watch This Video</button>
                                                        </a></p>


                                                </div>

                                            </div>
                                        <?php } ?>
                            </form>
                    <?php

                                    } else {
                                        echo "<p class='dz-title' style='color:red;'>No Records Found</p>";
                                    }
                                } ?>


                    <div class="dz-blog style-1 post-quote bg-white m-b30 blog-half">
                        <div class="dz-info">
                            <div class="post-quote-icon">
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 298.667 298.667" style="enable-background:new 0 0 298.667 298.667;" xml:space="preserve">
                                    <polygon points="0,170.667 64,170.667 21.333,256 85.333,256 128,170.667 128,42.667 0,42.667" />
                                    <polygon points="170.667,42.667 170.667,170.667 234.667,170.667 192,256 256,256 298.667,170.667 298.667,42.667" />
                                </svg>
                            </div>

                            <h4 class="dz-title">
                                <a href="blog-details-2.html">Hard Work is Surely Key to Success. <br> But, Smart-work is key to be Successful.<br></a>
                                <a href="blog-details-2.html" style="font-weight:normal; font-size:20px;">- Masood Ahmad</a>
                            </h4>
                        </div>
                    </div>

                        </div>
                        <div class="col-xl-4 col-lg-4">
                            <aside class="side-bar sticky-top mt-lg-0 mt-md-5">





                                <div class="widget recent-posts-entry">
                                    <h4 class="widget-title">Recent Videos</h4>
                                    <div class="widget-post-bx">
                                        <style>
                                            .dz-media,
                                            .dz-info {
                                                flex: 50%;
                                            }
                                        </style>

                                        <?php


                                        $result4 = mysqli_query($con, "SELECT * FROM precontent ORDER BY p_id DESC LIMIT 3");
                                        while ($row4 = mysqli_fetch_array($result4)) {
                                        ?>
                                            <div class="widget-posts clearfix">

                                                <div class="dz-media" style="width: 100%;">

                                                    <img class="w-img" src="images/thum.jpeg" alt="">
                                                    <div class="sidber-video-btn">
                                                        <a class="popup-video" href="<?php echo $row4['link1']; ?>"><i class="fas fa-play"></i></a>
                                                    </div>

                                                    <!-- <iframe src="" title="YouTube video player"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen style="width: 160px; height:100px;">

                                                  

                                                </iframe>  -->
                                                </div>
                                                <div class="dz-info">
                                                    <h6 class="title"><a class="cut-text" target="_blank" href="<?php echo $row4['link1']; ?>"><?php echo $row4['title1']; ?></a>
                                                    </h6>
                                                    <div class="dz-meta">
                                                        <ul>
                                                            <li class="post-date"> Learn more</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>

                                    </div>
                                </div>

                                <div class="widget">
                                    <h4 class="widget-title">Advertisement</h4>
                                    <div class="add-box">
                                        <?php
                                        $ret = mysqli_query($con, "SELECT * FROM tblbanner WHERE position='side1' order by bannertime DESC LIMIT 1");
                                        $rem = mysqli_fetch_array($ret);

                                        ?>
                                        <img class="img-fluid" src="admin/image/<?php echo $rem['banner']; ?>" alt="">
                                    </div>

                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </section>
        </div>
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
                        success: function(result) {
                            $("#topic-dropdown").html(result);
                        }
                    });
                });
            });
        </script>
        <!-- Footer -->
        <?php include 'footer-white.php'; ?>
        <!-- Footer End -->

        <button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
    </div>

    <!-- JAVASCRIPT FILES ========================================= -->
    <script src="js/jquery.min.js"></script><!-- JQUERY MIN JS -->
    <script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
    <script src="js/dz.ajax.js"></script><!-- AJAX -->
    <script src="js/custom.js"></script><!-- CUSTOM JS -->
    <script src="vendor/wow/wow.min.js"></script><!-- WOW JS -->



    <!-- jQuery 1.7.2+ or Zepto.js 1.0+ -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- Magnific Popup core JS file -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Magnific Popup JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>


    <script>
        $(document).ready(function() {
            function getYoutubeVideoID(url) {
                var regex =
                    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
                var matches = url.match(regex);
                return matches ? matches[1] : null;
            }

            $('.popup-video').magnificPopup({
                type: 'iframe',
                iframe: {
                    patterns: {
                        youtube: {
                            index: 'youtube.com/',
                            id: function(url) {
                                return getYoutubeVideoID(url);
                            },
                            src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                        },
                        youtu_be: {
                            index: 'youtu.be/',
                            id: function(url) {
                                return getYoutubeVideoID(url);
                            },
                            src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                        }
                    }
                }
            });
        });
    </script>

</body>


</html>