<!-- used for Webpage  = weblink is closed. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Weblink Closed</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Base styling */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            overflow: hidden;
        }


        /* Moving background effect */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://mounarchtech.com/vocoxp/upload_link/images/wizard-v4.jpg');
            opacity: 0.2;
            z-index: -1;
            animation: backgroundMove 30s infinite alternate;
        }

        @keyframes backgroundMove {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.2);
            }
        }

        /* Container styling */
        .web-link-closed {
            background-color: rgba(255, 255, 255, 0.5);
            /* Updated to make it transparent */
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }

        .web-link-width {  /* for mobile */
            max-width: 150px;
        }

        @media screen and (min-width: 450px) {
            .web-link-width {
                max-width: 350px;
            }
        }

        @media screen and (min-width: 750px) {
            .web-link-width {
                max-width: 600px;
            }
        }

        /* Floating icons for animation */
        .floating-icon {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }

        /* Top left icons */
        .icon-1 {
            top: -40px;
            left: -50px;
            animation-delay: 0.5s;
        }

        .icon-2 {
            top: 30px;
            /* Adjusted to be slightly lower */
            left: -30px;
            /* Adjusted to be slightly lower */
            animation-delay: 2s;
        }

        /* Bottom right icons */
        .icon-3 {
            bottom: -30px;
            right: -60px;
            animation-delay: 4s;
        }

        .icon-4 {
            bottom: 20px;
            /* Adjusted to position above the bottom */
            right: -70px;
            /* Adjusted to position above the bottom */
            animation-delay: 3s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Text Styling */
        .web-link-closed h1 {
            font-size: 48px;
            color: #834caf;
            margin-bottom: 20px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .web-link-closed p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        /* Success icon */
        .web-link-closed .verification-icon {
            font-size: 60px;
            color: #834caf;
            margin-bottom: 30px;
        }

        /* CTA button */
        .web-link-closed a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .web-link-closed a:hover {
            background-color: #45A049;
        }
    </style>
</head>

<body style="background-image: url('upload_link/images/wizard-v4.jpg');">

    <div class="web-link-closed web-link-width">
        <!-- Floating Icons for Visual Motion -->
        <img src="https://mounarchtech.com/vocoxp/upload_link/images/aadhar_sample.png" alt="floating icon" class="floating-icon icon-1" width="80px" height="50px">
        <img src="https://mounarchtech.com/vocoxp/upload_link/images/pan_sample.jpg" alt="floating icon" class="floating-icon icon-2" width="80px" height="50px">
        <img src="https://mounarchtech.com/vocoxp/upload_link/images/voter_sample_p.jpeg" alt="floating icon" class="floating-icon icon-3" width="80px" height="50px">
        <img src="https://mounarchtech.com/vocoxp/upload_link/images/driving_sample.jpg" alt="floating icon" class="floating-icon icon-4" width="80px" height="50px"> <!-- Add your fourth icon here -->

        <!-- Main Content -->
        <!-- <div class="verification-icon">✔</div>
        <h1>Thank You!!</h1> -->
        <p>Sorry for the inconvenience, this weblink is closed now. Please connect with your agency for further details.</p>

    </div>

</body>

</html>