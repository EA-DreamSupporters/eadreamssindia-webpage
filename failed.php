<?php
error_reporting(0);
                            if(isset($_GET['error'])){

                                $err = urldecode($_GET['error']);
                                echo "<script>window.alert('$err')</script>";
                            }else{
                                echo "<script>window.alert('Unknown Error')</script>";
                            }
                          
?>
<!DOCTYPE html>
<html> 

<head>
    <title>Failed! - Tenant | Laptop & PC Rental Service.</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
* {
    font-family: poppins;
}

body {
    height: 100vh;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.check {
    text-shadow: 0px 0px 10px grey;
    position: relative;
    font-size: 100px;
    padding: 20px 32px;
    border: 2px solid red;
    color: red;
    border-radius: 5px;

}

.check::after {

    content: "";
    position: absolute;
    height: 20px;
    width: 20px;

    border-radius: 50%;

    top: 0;
    left: 0;
    animation: circle 10s infinite ease-in-out;
}

@keyframes circle {
    0% {

        background-color: rgb(255, 0, 101);
        transform: translateX(-10px) translateY(-10px);
    }

    25% {

        transform: translateX(130px) translateY(-10px);
    }

    50% {

        transform: translateY(130px) translateX(130px);
    }

    75% {

        transform: translateY(130px) translateX(-10px);
    }

    100% {

        background-color: #127f99;
        transform: translateY(-10px) translateX(-10px);
    }

}

.thank {
    font-family: poppins;
}

.img {
    filter: drop-shadow(0px 0px 10px grey);
}

.btnsuccess {
    padding: 10px 20px;
    background-color: red;
    color: white;
    border-radius: 30px;
    transition: .5s;
    border: 2px solid transparent;

}

a:hover {
    box-shadow: 0px 0px 10px orangered;
    text-decoration: none;
    color: black;
    background-color: transparent;
    transition: .5s;
    border: 2px solid red;
}
</style>

<body class="">





    <div class=" mb-3">
        <div class="card-body text-center ">


            <h2 class="mb-5 text-dark">Failed!</h2>
            <div>
                <i class="fa-solid fa-close check"></i>
            </div>


            <div class="d-flex flex-row justify-content-center align-items-center mt-5 mb-5">

                <img src="images/logo.png" alt="" width="200px" class="img">
                <span class="text-dark thank text-capitalize h3 ml-3">Payment Failed / Please Try Again...</span>
                


            </div>


            <a href="index.php" class="btnsuccess mt-5">Back</a>

        </div>

    </div>




</body>

</html>