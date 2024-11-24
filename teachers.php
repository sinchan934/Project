<?php
session_start();

// Ensure the user is logged in as a teacher
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Handle attendance submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mark_attendance'])) {
    $attendance_date = $conn->real_escape_string($_POST['attendance_date']);
    $attendance = $_POST['attendance']; // Array of student_id => status

    foreach ($attendance as $student_id => $status) {
        $student_id = $conn->real_escape_string($student_id);
        $status = $conn->real_escape_string($status);

        $sql = "INSERT INTO attendance (student_id, attendance_date, status)
                VALUES ('$student_id', '$attendance_date', '$status')
                ON DUPLICATE KEY UPDATE status='$status'";
        if (!$conn->query($sql)) {
            $message = "Error saving attendance: " . $conn->error;
        }
    }

    if (empty($message)) {
        $message = "Attendance saved successfully for $attendance_date.";
    }
}

// Fetch all students
$students = $conn->query("SELECT id, name FROM students ORDER BY name ASC");

$conn->close();
?>


<div class="page">
    <!-- Header Area -->
    <div class="header-area">
        <div class="logo">Attendance App</div>
        <button class="btnlogout" onclick="logout()">LOGOUT</button>
    </div>

    <!-- Session Selection -->
    <div class="session-area">
        <label for="session">SESSION</label>
        <select class="ddlclass" id="session" onchange="loadClasses()">
            <option value="">SELECT ONE</option>
            <option value="2023_AUTUMN">2023 AUTUMN</option>
            <option value="2023_SPRING">2023 SPRING</option>
        </select>
    </div>

    <!-- Class Details -->
    <div class="classdetails-area">
        <h2 id="class-code">CSA101</h2>
        <h3 id="class-title">Introduction to Scientific Computing</h3>
        <input type="date" id="attendance-date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>">
    </div>

    <!-- Attendance Form -->
    <form id="attendance-form" method="post">
        <div class="studentlist-area">
            <label>STUDENT LIST</label>
            <div id="student-list">
                <?php
                if ($students->num_rows > 0) {
                    while ($row = $students->fetch_assoc()) {
                        echo '<div class="student-detail">';
                        echo '<label>' . $row['name'] . '</label>';
                        echo '<select name="attendance[' . $row['id'] . ']" required>';
                        echo '<option value="present">Present</option>';
                        echo '<option value="absent">Absent</option>';
                        echo '</select>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No students found.</p>';
                }
                ?>
            </div>
        </div>
        <button type="submit" name="mark_attendance" class="btn-submit">Save Attendance</button>
    </form>

    <!-- Alert Message -->
    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>
</div>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
}

.page {
    margin: 0 auto;
    max-width: 1200px;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Header Area */
.header-area {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border-radius: 10px 10px 0 0;
}

.logo {
    font-size: 24px;
    font-weight: bold;
}

.btnlogout {
    background-color: #dc3545;
    border: none;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
}

.btnlogout:hover {
    background-color: #c82333;
}

/* Form Styling */
#attendance-form {
    margin: 20px auto;
    padding: 20px;
    max-width: 600px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
}

.studentlist label {
    font-size: 20px;
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
}

.student-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.student-detail label {
    font-size: 16px;
}

.student-detail select {
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 5px;
}

.btn-submit {
    background-color: #28a745;
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #218838;
}

/* Alerts */
.alert {
    padding: 10px;
    margin-top: 20px;
    border-radius: 5px;
    background-color: #e7f3fe;
    border: 1px solid #b3d8ff;
    color: #31708f;
}
</style>