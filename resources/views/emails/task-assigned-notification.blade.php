<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body
    style="font-family:Montserrat;background-color: #FED158; border: 1px solid green; border-radius: 20px;max-width: 600px;margin: 0 auto;">
<div>
    <!--Header Section Started-->
    <header style="width: 98%;">
        <div style="  max-width: 40%;
      margin: 0 auto;
      text-align: center;
      opacity: 1;">
            <p>
                <a href="#">
                    LOGO
                </a>
            </p>

        </div>

    </header>
    <!--Header Section End-->

    <!--Main Section Start-->
    <main>
        <div style="margin: 5px;">
            <h1 style=" text-align: center;
        color: blue;">Task Assigned Notification</h1>
            <h4 style="text-indent: 16px;">Hello Dear {{$user->name}},</h4>

            <p>
                Greetings from Us.You Assigned Task {{$task->title}}
                <br><br>
                Thanks <br>
                It Team
            </p>


            <hr style="height: 1px;
        background-color: #303840;
        clear: both;
        width: 96%;
        margin: auto;"/>
    </main>
    <!--Main Section End-->

    <!--Footer Section Start-->

    <footer>
        <div
            style="text-align: center; padding-bottom: 3%;  line-height: 16px; font-size: 15px; font-weight: bold; word-spacing: 2px; color: #303840;">
            Dhaka, Bangladesh. <br/>
        </div>

    </footer>
    <!--Footer Section End-->
</div>

</body>

</html>
