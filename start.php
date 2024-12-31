<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start</title>
    <link rel="icon" href="images/iitp_symbol.png" type="image/png">

    <style>
        body {
            background-color: rgb(8, 73, 105);

            background-repeat: no-repeat;
            margin: 5px;
            padding: 5px;
        }

        #flex_container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: space-between;
            width: 100%;
        }


        /*For displaying image in center*/
        #img1 {
            display: block;

            margin: 60px auto;
            border: rgb(190, 202, 207) solid 5px;

        }

        #div1 {
            margin: auto;
            width: 600px;
            height: 50px;
            border: solid;
        }



        /* #student:link,
        #student:visited {
            border: solid rgb(126, 7, 126) 2px;
            background-color: rgb(126, 7, 126);
            color: white;

        }

        #student:hover,
        #student:active {
            border: solid rgb(68, 2, 68) 2px;
            background-color: rgb(68, 2, 68);
            color: white;

        }

        #faculty {
            border: solid rgb(4, 116, 32) 2px;
            background-color: rgb(4, 116, 32);
            color: white;
        } */

        ul {

            list-style-type: none;
            width: 600px;
            margin: auto;
        }

        li {
            display: inline-block;
            margin: 15px 15px 0 15px;
            font-weight: bold;

        }

        li a {

            display: block;
            margin: auto;
            padding: 10px 18px;

            text-decoration: none;


            background-color: rgb(38, 107, 5);
            color: white;

            border: rgb(211, 222, 228) solid 2px;


        }

        li a:hover,
        li a:active {

            padding: 11px 19px;

            border: rgb(211, 222, 228) solid 2px;

            background-color: rgb(13, 121, 148);



        }
    </style>
</head>

<body>

    <div id="flex_container">
        <div>
            <h1
                style="text-align: center; color: white; font-size: 35px; text-decoration: underline rgb(225, 226, 221);">
                Indian Institute of Technology Patna</h1>
        </div>
        <div>
            <h2 style="text-align: center; color: white; font-size: 29px;">
                Student
                Fellowship Portal</h2>
        </div>
        <div>
            <img style="display: block; box-shadow: 3px 3px 8px 5px rgb(3, 72, 94);" id="img1"
                src="images\iitpatna_admin.jpg" width="700px" height="220px">
        </div>

        <!-- <div id="div1">

            <a id="student" href="register.html" target="_blank">Student</a>
            <a id="faculty" href="tables.html" target="_blank">Faculty</a>
            <a id="hod" href="tables.html" target="_blank">HOD</a>
            <a id="administration" href="tables.html" target="_blank">Administration</a>


        </div> -->

        <div id="main">
            <div>
                <ul>
                    <li><a href="\Student\stud_register.php" target="_blank"> Student</a></li>
                    <!-- <li><a href="\Supervisor\supervisor_login.php" target="_blank"> Supervisor</a></li> -->
                    <li><a href="\Faculty_Advisor\fac_adv_login.php" target="_blank"> Faculty</a></li>
                    <li><a href="\HOD\hod_login.php" target="_blank"> HOD</a></li>
                    <li><a href="\Department_Office\dept_offc_login.php" target="_blank"> Department Office</a></li>

                </ul>
            </div>

        </div>

</body>

</html>
