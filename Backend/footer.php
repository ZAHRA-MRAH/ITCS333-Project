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

       /* body {
            font-family: 'Montserrat', sans-serif;
        }*/

        .main-footer {
            padding: 70px 0;
            display: flex;
            justify-content: space-evenly;
            background-color: 	whitesmoke;
            border-bottom-right-radius:5px;
            border-bottom-left-radius: 5px;
        }

        .main-footer ul {
            list-style: none;
        }

        .main-footer h1 {
            font-size: 22px;
            line-height: 117%;
            color: #b393d3;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .main-footer h2 {
            color: #b393d3;
            font-weight: 500;
        }

        .main-footer ul li a {
            color: #b393d3;
            text-decoration: none;
        }

        footer {
            background-color: #f1f0fc;
            font-size: 17px;
            padding: 15px 5px;
            color: #b393d3;
            text-align: center;
        }

        footer a {
            text-decoration: none;
            color: #b393d3;
        }



        .contact-details {
            margin-top: 20px;
        }

        .contact-details li {
            list-style: none;
            margin: 10px 0;
        }

        .contact-details li a {
            text-decoration: none;
            color: #b393d3;
        }

        .contact-details .fa {
            color: #b393d3;
            margin-right: 10px;
        }

        .sociallogos {
            padding: 20px 0;
        }

        .sociallogos .logobox a {
            padding: 0 10px;
            text-decoration: none;
            color: #b393d3;
            font-size: 22px;
        }

        .com ul li {
            padding: 5px 0;
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

            .com {
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
                    <li>
                        <div class="fa fa-phone"></div><a href="tel:+91">+973 17536478</a>
                    </li>
                    <li>
                        <div class="fa fa-envelope"></div><a href="mailto:mail@gmail.com">mail@gmail.com</a>
                    </li>
                    </li>
                </div>
            </div>
            <div class="info" data-aos="fade-up">
                <h1>Social Media</h1>
                <div class="sociallogos">
                    <div class="logobox">
                        <i></i>
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