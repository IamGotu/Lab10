<?php

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cookie Notice</title>
        <!-- Add your CSS links here -->
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .container {
                max-width: 800px;
                background-color: #fff;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.3s ease;
            }

            .container:hover {
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            }

            h1 {
                color: #007bff;
                text-align: center;
                margin-bottom: 20px;
                text-transform: uppercase;
            }

            p {
                color: #6c757d;
                line-height: 1.6;
                margin-bottom: 15px;
            }

            .btn {
                display: inline-block;
                padding: 12px 24px;
                background: linear-gradient(45deg, #007bff, #00bfff);
                color: #fff;
                text-decoration: none;
                border: none;
                border-radius: 25px;
                transition: background 0.3s ease;
            }

            .btn:hover {
                background: linear-gradient(45deg, #00bfff, #007bff);
            }
        </style>
    </head>
    <body>

    <div class="container">
        <h1>Information About Our Use of Cookies</h1>
        <p>This website uses cookies to ensure you get the best experience on our website. By continuing to browse the site, you are agreeing to our use of cookies as described in this notice.</p>
        
        <h2>Types of Cookies that We Use</h2>
        <p>We use both session and persistent cookies on this website. Session cookies are temporary cookies that are erased when you close your browser, while persistent cookies remain on your device for a set period of time or until you delete them manually.</p>
        
        <h2>How to Control and Manage Cookies</h2>
        <p>You can control and/or delete cookies as you wish. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. If you do this, however, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.</p>

        <div style="text-align: center;">
        <a href="index.php" class="btn">Accept</a>
        <a href="index.php" class="btn">Decline (Back to home)</a>
        </div>
    </div>

    </body>
    </html>

    <?php

?>
