<?php
include 'dbconnect.php'; // Include database connection file

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['attendance']) && !empty($_POST['session']) && !empty($_POST['class_code']) && !empty($_POST['date'])) {
        $session = $_POST['session'];
        $class_code = $_POST['class_code'];
        $date = $_POST['date'];
        $subject = $_POST['subject'];
        
        // Insert attendance records
        $stmt = $conn->prepare("INSERT INTO attendance (session, class_code, student_id, status, date, subject) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($_POST['attendance'] as $student_id => $attendance_status) {
            $stmt->bind_param("ssisss", $session, $class_code, $student_id, $attendance_status, $date, $subject);

            if (!$stmt->execute()) {
                echo "Error recording attendance for student ID $student_id: " . $stmt->error;
            }
        }

        $stmt->close();
        echo "Attendance successfully recorded!";
    } else {
        echo "All fields are required. Please fill in the form correctly.";
    }
}

// Fetch classes
$class_query = "SELECT * FROM classes";
$classes = $conn->query($class_query);

// Fetch subjects
$subject_query = "SELECT DISTINCT subject FROM subject";
$subject_result = $conn->query($subject_query);

$subjects = [];
while ($row = $subject_result->fetch_assoc()) {
    $subjects[] = $row['subject'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/attendance.css">
    <title>Attendance Page</title>
    <style>
        .studentlist-area {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .studentlist {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .studentdetails {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .studentdetails div {
            margin-right: 15px;
            font-size: 1rem;
        }

        .checkbox-area input[type="checkbox"]:checked {
            background-color: green;
            border-color: green;
        }

        .checkbox-area input[type="checkbox"]:checked::after {
            content: '✔';
            color: white;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header-area">
            <div class="logo-area">
                <h1 class="logo">Attendance App</h1>
            </div>
            <div class="logout-area">
                <form action="logout.php" method="POST">
                    <button class="btnlogout" type="submit">LOGOUT</button>
                </form>
            </div>
        </div>

        <form method="POST" action="">
            <div class="session-area">
                <div class="label-area"><label>SESSION</label></div>
                <div class="dropdown-area">
                    <select name="session" class="ddlclass" required>
                        <option value="">SELECT ONE</option>
                        <option value="2023 AUTUMN">2023 AUTUMN</option>
                        <option value="2023 SPRING">2023 SPRING</option>
                    </select>
                </div>
            </div>

            <div class="subject-area">
                <div class="label-area"><label>SUBJECT</label></div>
                <div class="dropdown-area">
                    <select name="subject" class="ddlclass" id="subjectDropdown" required>
                        <option value="">SELECT ONE</option>
                        <?php foreach ($subjects as $subject) { ?>
                            <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="classlist-area">
                <?php while ($class = $classes->fetch_assoc()) { ?>
                    <div class="classcard">
                        <input type="radio" name="class_code" value="<?php echo $class['class_code']; ?>" required>
                        <?php echo $class['class_name']; ?>
                    </div>
                <?php } ?>
            </div>

            <div class="classdetails-area">
                <div class="classdetails">
                    <div class="code-area">Class Code: <span id="class_code"><?php echo $_POST['class_code'] ?? ''; ?></span></div>
                    <div class="ondate-area">
                        <input type="date" name="date" required>
                    </div>
                </div>
            </div>

            <div class="studentlist-area" id="studentList">
                <div class="studentlist"><label>STUDENT LIST</label></div>
                <!-- Student list will be loaded here based on selected subject -->
            </div>

            <button type="submit">Submit Attendance</button>
        </form>
    </div>

    <script src="js/jquery.js"></script>
    <script>
        document.getElementById('subjectDropdown').addEventListener('change', function() {
            var subject = this.value;
            var studentListDiv = document.getElementById('studentList');
            
            if (subject) {
                // AJAX request to fetch student list based on selected subject
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_students.php?subject=' + subject, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        studentListDiv.innerHTML = this.responseText;
                    }
                };
                xhr.send();
            } else {
                studentListDiv.innerHTML = '<div class="studentlist"><label>STUDENT LIST</label></div>';
            }
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Close connection to database
?>
