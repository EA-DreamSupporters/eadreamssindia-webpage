			
			<?php
error_reporting(0);
include('config.php');
if(isset($_POST["update"]))
{
$gg=$_POST["sub_email"];
$sql=mysqli_query($con,"insert into subscription (email) values('$gg')");
 if ($sql) {
    echo '<script>alert("Thanks For Your Subscripton.")</script>';
  }                                                                                            
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
}
?>
			<section class="py-5 newsletter-wrapper" style="background-image: url('images/background/bg1.jpg'); background-size: cover;">
			<div class="container">
				<div class="subscride-inner">
					<div class="row style-1 justify-content-xl-between justify-content-lg-center align-items-center text-xl-start text-center">
						<div class="col-xl-7 col-lg-12">
							<div class="section-head mb-0">
								<h2 class="title text-white my-lg-3 mt-0">Subscribe our newsletter for latest updates</h2>
							</div>
						</div>
						<div class="col-xl-5 col-lg-6">
							<form class="style-1"  method="post">
								
								<div class="form-group">
									<div class="input-group mb-0">
										<input name="sub_email" required="required" type="email" class="form-control bg-transparent text-white" placeholder="Your Email Address">
										<div class="input-group-addon">
											<button name="update" value="Submit" type="submit" class="btn btn-primary btnhover">
												<span>SUBSCRIBE</span>
												<i class="fa-solid fa-paper-plane"></i>
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<script>
								const fbBtn = document.querySelector(".fb-btn");
const waBtn = document.querySelector(".wa-btn");
const twBtn = document.querySelector(".tw-btn");
const liBtn = document.querySelector(".li-btn");

const pageUrl = location.href
const message = "hi every one ,please check  out"


function init(){
	
	let postUrl=encodeURI(document.location.href);
	let postTitle=encodeURI("hi every one ,please check this out: ");

	fbBtn.setAttribute("href",`https://www.facebook.com/sharer.php?u=${postUrl}`);
	waBtn.setAttribute("href",`https://api.whatsapp.com/send?text=${message} , ${pageUrl}`);
	twBtn.setAttribute("href",`https://twitter.com/share?url=${postUrl}&text=${postTitle}&via=[via]&hashtags=[hashtags]`);
	liBtn.setAttribute("href",`https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`);
}
init();
								</script>
	<footer class="site-footer style-1">
		<!-- Footer Category -->
		
		<!-- Footer Category End -->
		
		<!-- Footer Top -->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col-xl-3 col-lg-12">
						<div class="widget widget_about">
							<div class="footer-logo logo-white">
								<a href="index.php"><img src="images/logo.png" alt="" style="max-width: 234px;"></a> 
							</div>
							<p class="text">Ezhuga Aramey Dream Supporters LLP is an ED-Tech that begins to care about the career aspirations of Indian youngsters.</p>
							<div class="dz-social-icon style-1">
								<ul>
									<li><a href="https://www.facebook.com/profile.php?id=100084243005569" target="_blank" ><i class="fa-brands fa-facebook-f"></i></a></li>
									<li><a href="https://twitter.com/eadreamssindia?t=g7l770eakCwtbzWWAG6FCg&s=09" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
									<li><a href="https://www.linkedin.com/in/eadreamssindia" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
									<li><a href="https://www.instagram.com/eadreamssindia/" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-4">
					<div class="widget widget_services">
							<h5 class="footer-title">Our Links</h5>
							<ul>
								<li><a href="about-us">About us</a></li>
								<li><a href="contact-us">Contact us</a></li>
								<li><a href="privacy">Privacy Policy</a></li>
								<li><a href="terms">Terms & Conditions</a></li>
								<li><a href="refund">Return & Refund Policy </a></li>
								<li><a href="rental_t&c">Rental Policies & Terms </a></li>
								
							</ul>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-sm-4 col-4">
						<div class="widget widget_services">
							<h5 class="footer-title">Our Services</h5>
							<ul>
								<li><a href="buy-book">Books & Exam Kits</a></li>
								<li><a href="subscribe">Subscription</a></li>
								<li><a href="self-preparation">Self Study India</a></li>
								<li><a href="guidance">GovPrep</a></li>
								<li><a href="rent-book">Rent A Book</a></li>
							</ul>     
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-4">
					<div class="widget widget_services">
							<h5 class="footer-title">Resources</h5>
								<ul>
							<li><a href="faq">FAQ</a></li>
								<li><a href="#">Blogs</a></li>
								<li><a href="#">Help Center</a></li>
								<li><a href="register">Register</a></li>
								<li><a href="#" data-bs-toggle="modal" data-bs-target="#myModal" >Login</a></li>
								
							</ul>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 wow fadeInUp" data-wow-delay="0.5s">
						<div class="widget widget_getintuch">
							<h5 class="footer-title">Get in Touch With Us</h5>
							<ul>
								<li>
									<i class="flaticon-placeholder"></i>
									<span>No.2 ,CMC Road, Keelaoorani South, Karaikudi, Sivaganga District, TamilNadu - 630001</span>
								</li>
								<li>
									<i class="flaticon-phone"></i>
									<span><a href="tel:+91 9487277924" >+91 9487277924</a></span>
								</li>
								<li>
									<i class="flaticon-email"></i> 
									<span><a href="mailto:eadreamssindia@gmail.com">eadreamssindia@gmail.com</a></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer Top End -->
		
		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="container">
			<div class="row fb-inner">
					<div class="col-lg-6 col-md-12 text-start"> 
						<p class="copyright-text">Ezhuga Aramey Dream Supporters LLP -  © 2022 All Rights Reserved</p>
					</div>
					<div class="col-lg-6 col-md-12 text-end"> 
						<p>Made with <span class="heart"></span> by <a href="https://brandbeetech.com/">BrandBee Technologies</a></p>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer Bottom End -->
		
		<script>
//Pagination


	pageSize = 6;

	var pageCount =  $(".line-content").length / pageSize;
    
     for(var i = 0 ; i<pageCount;i++){
        
       $("#pagin").append('<li class="page-item"><a class = "page-link active"  href="#">'+(i+1)+'</a></li> ');
     }
        $("#pagin li").first().find("a").addClass("current")
    showPage = function(page) {
	    $(".line-content").hide();
	    $(".line-content").each(function(n) {
	        if (n >= pageSize * (page - 1) && n < pageSize * page)
	            $(this).show();
	    });        
	}
    
	showPage(1);

	$("#pagin li a").click(function() {
	    $("#pagin li a").removeClass("current");
	    $(this).addClass("current");
	    showPage(parseInt($(this).text())) 
	});
	</script>