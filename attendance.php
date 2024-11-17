<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/attendance.css">
    <title>Document</title>
</head>
<body>
    <div class="page">
        <div class="header-area">
            <div class="logo-area"><h1 class="logo">Attendance App</h1></div>
            <div class="logout-area"><button class="btnlogout">LOGOUT</button></div>
        </div>

        <div class="session-area">
          <div class="label-area"><label>SESSON</label></div>
          <div class="dropdown-area">
            <select class="ddlclass">
                <option>SELECT ONE</option>
                <option>2023 AUT0UM</option>
                <option>2023 SPRING</option>
            </select>

          </div>
        </div>

        <div class="classlist-area">
            <div class="classcard">CSA105</div>
            <div class="classcard">CSA105</div>
            <div class="classcard">CSA105</div>
            <div class="classcard">CSA105</div>
            <div class="classcard">CSA105</div>
        </div>

        <div class="classdetails-area">
            <div class="classdetails">
                <div class="code-area">CSA101</div>
                <div class="title-area">Introduction to scientific computing</div>
                <div class="ondate-area">
                    <input type="date">
                </div>
            </div>
        </div>

        <div class="studentlist-area">
            <div class="studentlist"><label>STUDENT LIST</label></div>
            <div class="studentdetails">
                <div class="slno-area">001</div>
                <div class="rollno-area">CSB2022</div>
                <div class="name-area">Samrin Adhikari</div>
                <div class="checkbox-area">
                    <input type="checkbox">
                </div>
            </div>
            <div class="studentdetails">
                <div class="slno-area">002</div>
                <div class="rollno-area">CSB2022</div>
                <div class="name-area">Kailash Thapa</div>
                <div class="checkbox-area">
                    <input type="checkbox">
                </div>
            </div>
            <div class="studentdetails">
                <div class="slno-area">003</div>
                <div class="rollno-area">CSB2022</div>
                <div class="name-area">Sajan Shrestha</div>
                <div class="checkbox-area">
                    <input type="checkbox">
                </div>
            </div>
            <div class="studentdetails">
                <div class="slno-area">004</div>
                <div class="rollno-area">CSB2022</div>
                <div class="name-area">Sajjan Jung KUnwar</div>
                <div class="checkbox-area">
                    <input type="checkbox">
                </div>
            </div>
        </div>
       

    </div>
    <script src="js/jquery.js"></script>
    <script src="js/logout.js"></script>
</body>
</html>