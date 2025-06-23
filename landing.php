<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA Dream Supporters</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .notification-text {
            font-size: 14px;
            /* Reduced font size */
            margin: 0;
        }

        .notification-bar {
            width: 100%;
            background-color: #ff6600;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .hero-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 40px;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero {
            position: relative;
            width: 1470px;
            height: 600px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            /* Remove flex */
            /* display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;*/
            text-align: center;
            color: white;
            border-radius: 10px;
            padding: 40px;
            overflow: hidden;
            /* Important: Hide overflow */
        }

        .hero-overlay video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Maintain aspect ratio */
            position: absolute;
            top: 0;
            left: 0;
        }

        .hero-overlay img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Maintain aspect ratio */
            position: absolute;
            top: 0;
            left: 0;
        }

        .hero-timer {
            position: absolute;
            top: 15px;
            left: 0.1px;
            background: rgb(0, 0, 0);
            padding: 8px 15px;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            z-index: 2;
            /* ensure the element be on top of image*/
            cursor: pointer;
            /* Make it look clickable */
            transition: background-color 0.3s ease;
            /* Add a smooth transition for the background-color */
        }

        .hero-timer.clicked {
            background-color: rgba(0, 0, 0, 0.5);
            /*Change background after click */
        }

        .hero-timer.ended {
            background-color: red;
            /* Change background after end */
        }


        .btn-custom {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: #ff6600;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s ease-in-out, transform 0.2s, box-shadow 0.2s;
            display: inline-block;
            overflow: hidden;
            z-index: 2;
            /* ensure the element be on top of image*/
        }

        /* Hover Effect */
        .btn-custom:hover {
            background: #e65c00;
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(255, 102, 0, 0.3);
        }

        /* Button Click Interaction */
        .btn-custom:active {
            transform: scale(0.95);
            transition: transform 0.1s ease-in-out;
        }


        .hero-content {
            position: absolute;
            /* Take it out of flex flow */
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 2;
            /* ensure the element be on top of image*/
            padding: 40px;
            /* Add padding */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            /* Important: Prevent overflow */
        }

        .square-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            width: 100%;
        }

        .square {
            width: 480px;
            height: 480px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 20px;
            font-weight: 500;
            border-radius: 10px;
            /* This remains the same */
            overflow: hidden;
            /* This is crucial */
        }

        .square {
            width: 480px;
            height: 480px;
            /* Adjust height as needed */
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 20px;
            font-weight: 500;
            border-radius: 10px;
            overflow: hidden;
        }

        .square-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Hide the default video controls */
        .square-video::-webkit-media-controls-panel {
            display: none;
        }

        .square-video::-webkit-media-controls-fullscreen-button {
            display: none;
        }

        .square-video::-webkit-media-controls-play-button {
            display: none;
        }

        .square-video::-webkit-media-controls-mute-button {
            display: none;
        }

        .square-video::-webkit-media-controls-volume-slider {
            display: none;
        }

        .square-video::-webkit-media-controls-timeline {
            display: none;
        }


        .square img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            /* Match the container's border-radius */
        }

        .square video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            /* Match the container's border-radius */
        }

        .faq-section {
            background: white;
            padding: 60px 0;
        }

        .registration-wrapper {
            background: #222;
            padding: 60px 0;
        }

        .registration-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            max-width: 900px;
            height: 520px;
            margin: auto;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 1200px) {
            .hero {
                width: 100%;
                height: 500px;
                padding: 30px;
            }

            .square {
                width: 400px;
                height: 400px;
            }
        }

        @media (max-width: 1024px) {
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                /* Prevent horizontal scrolling */
            }

            .hero {
                margin: 0 auto;
                /* Center the hero section */
                width: 95%;
                /* Add some margin on the sides */
                height: 450px;
                padding: 25px;
            }

            .registration-section {
                margin: 0 auto;
                /* Center the registration section */
                width: 90%;
                /* Add some margin on the sides */
                padding: 30px;
                height: auto;
            }

            .faq-section {
                margin: 0 auto;
                /* Center the FAQ section */
                width: 90%;
                /* Add some margin on the sides */
                padding: 50px 20px;
            }

            .notification-bar {
                width: 100%;
                /* Ensure it spans the full width */
                padding: 9px;
                font-size: 13px;
            }

            .square-container {
                margin: 0 auto;
                /* Center the square container */
                width: 95%;
                /* Add some margin on the sides */
                padding: 20px 0 0 0;
            }

            .square {
                width: 350px;
                height: 350px;
            }
        }

        @media (max-width: 992px) {
            .hero {
                height: 400px;
                padding: 20px;
            }

            .btn-custom {
                padding: 8px 15px;
                font-size: 12px;
            }

            .square {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                height: 350px;
                padding: 15px;
            }

            .hero-timer {
                font-size: 12px;
                padding: 6px 10px;
            }

            .btn-custom {
                padding: 6px 10px;
                font-size: 10px;
            }

            .square {
                width: 250px;
                height: 250px;
            }

            .square-container {
                flex-wrap: wrap;
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .hero {
                height: 230px;
                width: 95%;
                padding: 5px;

            }

            .hero-overlay,
            .hero-overlay video,
            .hero-overlay img {
                width: 100%;

            }

            .hero-timer {
                font-size: 10px;
                padding: 5px 8px;
            }

            .btn-custom {
                padding: 5px 8px;
                font-size: 9px;
            }

            .square-container {
                display: flex;
                flex-wrap: nowrap;
                justify-content: center;
                gap: 5px;


            }

            .square {
                flex: 0 0 auto;
                /* Prevent flex-grow and flex-shrink */

                width: 120px;
                height: 120px;
                /* Ensure each square snaps into view */
            }

            .notification-bar {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <?php include('header1.php'); ?>

    <!-- Notification Bar -->
    <div class="notification-bar">
        <!-- Dynamic Notifications from Admin Page -->
        <!-- This section should fade in and show one notification at a time -->
        <p class="notification-text">🔥 Special Offer: Get 50% off on all courses! Limited Time Only.</p>
    </div>

    <div class="hero-container">
        <div class="hero">
            <div class="hero-overlay">
                <?php
                // Fetching the big banner image from the admin panel
                $query = "SELECT * FROM landing WHERE Position='bb'";
                $result = mysqli_query($con, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);

                    if ($row['Type'] == 'image') {
                        echo '<img src="admin/uploads/' . $row['Banner'] . '" class="img-fluid" alt="Hero Image">';
                    } elseif ($row['Type'] == 'video') {
                        echo '<video muted autoplay loop controls class="embed-responsive-item">
                                <source src="admin/uploads/' . $row['Banner'] . '" type="video/mp4">
                                Your browser does not support the video tag.
                              </video>';
                    }
                } else {
                    echo '<div class="placeholder text-white">No hero media uploaded yet.</div>';
                }
                ?>
            </div>
            <div class="hero-content">
                <div class="hero-timer" id="hero-timer-0"></div>
                <!-- Get Started Button (Bottom-Right Corner) -->
                <a href="#" id="get-started-0" class="btn btn-custom">Get Started</a>
            </div>
        </div>
    </div>



    <div class="square-container">
        <?php
        $positions = ['1b', '2b', '3b'];
        foreach ($positions as $position) {
            $query = "SELECT * FROM landing WHERE Position='$position'";
            $result = mysqli_query($con, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                echo '<div class="square">';
                if ($row['Type'] == 'image') {
                    echo '<img src="admin/uploads/' . $row['Banner'] . '" class="img-fluid" alt="' . $position . ' Banner">';
                } elseif ($row['Type'] == 'video') {
                    echo '<video class="square-video" muted>'; // Removed autoplay, loop, and controls
                    echo '<source src="admin/uploads/' . $row['Banner'] . '" type="video/mp4">';
                    echo 'Your browser does not support the video tag.';
                    echo '</video>';
                } else {
                    echo 'No banner uploaded for position ' . $position;
                }
                echo '</div>';
            } else {
                echo '<div class="square">No banner uploaded for position ' . $position . '</div>';
            }
        }
        ?>
    </div>

    <section class="faq-section">
        <div class="container">
            <h2 class="text-center">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What services do we offer?
                        </button>
                    </h3>
                    <div id="faq1" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            We provide structured preparation for competitive exams with expert guidance.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="registration-wrapper">
        <div class="registration-section">
            <div class="container">
                <h2 class="text-center">Register Now</h2>
                <form class="row justify-content-center" id="registrationForm" method="post"
                    action="process_registration.php">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="tel" name="phone" class="form-control mb-3" placeholder="Phone" required>
                        <textarea name="message" class="form-control mb-3" placeholder="Message" rows="4"></textarea>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>

                </form>
                <div id="registrationMessage"></div>
            </div>
        </div>
    </section>


    <?php include('footer1.php'); ?>
    <script>
        const timerElement = document.getElementById('hero-timer-0');
        const button = document.getElementById('get-started-0');
        const slide = <?php echo json_encode($row); ?>;

        if (button && slide.link) {
            button.href = slide.link; // Set the link from database
        }
        // set the date here
        // Countdown Timer Logic
        const startDate = new Date("2025-03-20T10:00:00"); // Set your start date/time here (YYYY-MM-DDTHH:MM:SS)
        const endDate = new Date("2025-03-21T18:00:00"); // Set your end date here (YYYY-MM-DDTHH:MM:SS)
        const targetDate = endDate.getTime();
        const now = new Date().getTime();
        let isTimerStarted = false; // Track if the timer has started

        // Initial message
        let initialMessage = timerElement.textContent;
        // Function to format date and time
        function formatDateTime(date) {
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
        }
        // Check if the event has started or ended
        if (now < startDate.getTime()) {
            // Before start
            timerElement.textContent = `Starts On: ${formatDateTime(startDate)}`;
        } else if (now >= startDate.getTime() && now < endDate.getTime()) {
            // During event
            timerElement.textContent = "Tap here to Reveal!";
            timerElement.addEventListener('click', () => {
                if (!isTimerStarted) {
                    timerElement.classList.add('clicked'); // Add a class to change the background color on click
                    timerElement.textContent = ''; // clear the initial message
                    startCountdown();
                    isTimerStarted = true;
                }
            });
        } else {
            // After end
            timerElement.classList.add('ended');
            timerElement.textContent = `Ended On: ${formatDateTime(endDate)}`;
        }
        function startCountdown() {
            // Update the countdown every second
            setInterval(function () {
                const now = new Date().getTime();
                const timeLeft = targetDate - now;

                // Time calculations for days, hours, minutes, and seconds
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                let hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                // Convert to 12-hour format and determine AM/PM
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'

                if (timeLeft < 0) {
                    timerElement.classList.add('ended'); // Add a class to change the background color when finished
                    timerElement.textContent = `Ended On: ${endDate.toLocaleDateString()} ${endDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })}`; // change the text when ended
                    clearInterval(this); //stop the interval
                } else {
                    // Format the output
                    const formattedTime = `${days}d ${hours}h ${minutes}m ${seconds}s ${ampm}`;
                    timerElement.textContent = `Ends In: ${formattedTime}`;
                }
            }, 1000);
        }


        document.addEventListener('DOMContentLoaded', function () {
            const squareVideos = document.querySelectorAll('.square-video');

            squareVideos.forEach(video => {
                video.play(); // Start playing automatically
                video.addEventListener('ended', () => {
                    video.currentTime = 0; // Loop back to the beginning
                    video.play();
                });
            });
        });

        const form = document.getElementById('registrationForm');
        const messageDiv = document.getElementById('registrationMessage');

        form.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission
            messageDiv.innerHTML = '<p>Submitting...</p>'; // Show loading message

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
                .then(response => response.text())
                .then(data => {
                    messageDiv.innerHTML = data; // Display the response from the PHP script
                })
                .catch(error => {
                    messageDiv.innerHTML = '<p style="color:red;">Error submitting form.</p>';
                });
        });



    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>