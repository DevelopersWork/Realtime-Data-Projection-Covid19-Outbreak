<?php 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link id="favicon" rel="shortcut icon" type="image/png" href="./images/logo.png" src="./images/logo.png">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORONAVIRUS LIVE DATA - Developers@Work</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style>
    #developerswork{
        max-width: 100%;
        border-radius: 5%;
        padding : 2%;
        position: relative;
        text-align: center;
    }

    #developerswork>a>img {
        max-width: 100%;
        /* height: 10vh; */
    }

    .brandLogo {
        animation: Brand-logo-spin infinite 15s linear;
        pointer-events: none;
        /* max-width: 25%; */
        max-height: 15vh;
    }

    @keyframes Brand-logo-spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    tbody{
        height:50vh;
        overflow:auto;
    }

    body {
        background: #74ebd5;
        background: -webkit-linear-gradient(to right, #74ebd5, #ACB6E5);
        background: linear-gradient(to right, #61ffe1, #6661ff);
        max-height: 100vh;
    }
    
    </style>
</head>
<body>
    <script src="./script.js?v=2.3.1"></script>
    
    <div class="container py-5">
        <div class="spinner-border spinner-border-md" id="loading"></div>
        <div class="row">
            <div class="col-12 bg-white rounded shadow">

                <!-- Fixed header table-->
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>PLACE</th>
                                <th>TOTAL CASES</th>
                                <th>TOTAL RECOVERED</th>
                                <th>TOTAL DEATHS</th>
                                <th>TODAY CASES</th>
                                <th>TODAY DEATHS</th>
                            </tr>
                        </thead>
                        <tbody id="dataholder">
                            <script>fetchData()</script>
                        </tbody>
                    </table>
                </div><!-- End -->
            </div>
            <div class="col">
            <div class="copyright text-center text-xl-left text-muted">
            <h1 class="footer-copyright container text-center text-white py-3" id="developerswork">
            <img src="./images/brandLogo.png" class="brandLogo" alt="Developers@Work" />
            <a href="https://developerswork.online" target="_blank" rel="noopener noreferrer">
                <img src="./images/brandingDesign.png" alt="DevelopersWork" />
            </a>
            </h1>
        </div>
        </div>
        </div>
    </div>
    
    
</body>
</html>

