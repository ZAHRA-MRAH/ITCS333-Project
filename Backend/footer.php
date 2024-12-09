<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .main-footer {
            padding: 50px 0;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            flex-grow: 1;
            background-color: #F2F1EC;
            border-bottom-right-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .main-footer ul {
            list-style: none;
            padding: 0;
        }

        .main-footer h1 {
            font-size: 18px;
            line-height: 1.5;
            color: #2F5175;
            margin-bottom:5px;
            font-weight: 500;
            text-align: center;
        }

        footer {
            background-color: #E5E3DA;
            font-size: 17px;
            padding: 15px 5px;
            color: #2F5175;
            text-align: center;
        }

        footer a {
            text-decoration: none;
            color: #2F5175;
        }

        .contact-details {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .contact-details div {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .contact-details a {
            font-size: 16px;
            text-decoration: none;
            color: #2F5175;
        }

        .contact-details .fa {
            color: #2F5175;
            margin-right: 10px;
        }

        .sociallogos {
            padding: 20px 0;
            text-align: center;
        }

        .logobox a {
            margin: 0 10px;
            text-decoration: none;
            color: #2F5175;
            font-size: 22px;
        }

        #footer {
            height: 150px;
        }

        @media only screen and (max-width: 749px) {
            .main-footer {
                padding: 20px;
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .info {
                padding: 20px 0;
            }
        }

        @media (max-width: 480px) {
            .main-footer {
                grid-template-columns: 1fr;
            }

            .sociallogos {
                padding: 20px 0;
            }
        }
    </style>

</head>

<body>
    <section id="footer">
        <div class="main-footer">
            <div class="logoinfo" data-aos="fade-up">
                <div class="contact-details">
                    <h1>Contact Us</h1>
                    <div>
                        <i class="fa fa-phone"></i>
                        <a href="tel:+91">+973 17536478</a>
                    </div>
                    <div>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:mail@gmail.com">mail@gmail.com</a>
                    </div>
                </div>
            </div>
            <div class="info" data-aos="fade-up">
                <h1>Social Media</h1>
                <div class="sociallogos">
                    <div class="logobox">
                        <a href="#" class="fa-brands fa-facebook"></a>
                        <a href="#" class="fa-brands fa-instagram"></a>
                        <a href="#" class="fa-brands fa-whatsapp"></a>
                    </div>
                </div>
            </div>
        </div>
        <footer>© ITCS333 Project Copyright 2024 All Rights Reserved</footer>
    </section>
</body>

</html>
