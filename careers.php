<?php
session_start();
include('config.php');

$hide_form = false;
$thank_you_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $check_stmt = $conn->prepare("SELECT id FROM job_applications WHERE email = ? OR phone = ?");
    $check_stmt->bind_param("ss", $email, $phone);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $thank_you_message = "You have already submitted an application. We will contact you soon.";
        $hide_form = true; // hide the form
    } else {
        // Handle file upload
$resume_path = '';
if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $target_dir = "admin/cv/"; // path relative to this PHP file
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // create folder if missing
    }

    $filename = basename($_FILES['resume']['name']);
    $target_file = $target_dir . time() . "_" . $filename;

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
        $resume_path = $target_file;
    } else {
        echo "Failed to move uploaded file.";
    }
}

        $num_children = isset($_POST['num_children']) ? $_POST['num_children'] : NULL;

        $stmt = $conn->prepare("INSERT INTO job_applications 
            (first_name, last_name, email, phone, position, start_date, employment, social_status, num_children, resume) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssssss", 
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['position'],
            $_POST['start_date'],
            $_POST['employment'],
            $_POST['social'],
            $num_children,
            $resume_path
        );

        if ($stmt->execute()) {
            $thank_you_message = "Thank you for your application! We will contact you soon.";
            $hide_form = true;
        } else {
            $thank_you_message = "There was an error submitting your application.";
        }

        $stmt->close();
    }

    $check_stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link href="style.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Job Application Form</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;

}
form {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom:50px;
}
form img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 20px;
}
form h2 {
    margin-bottom: 15px;
    text-align: center;
}
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
input[type="text"], input[type="email"], input[type="date"], input[type="number"], select {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    margin-bottom: 10px;
}
input[type="radio"], input[type="checkbox"] {
    margin-right: 10px;
}
.children-info {
    display: none;
    margin-left: 20px;
    padding: 10px 0;

}
button {
    padding: 12px 20px;
    background-color: #E43636;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
button:hover {
    background-color: #b32b2b;
}
.job-application{
    padding-top:150px;
}


.modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5); /* Black with opacity */
}

.modal-content {
  background-color: #dad7d7ff;
  margin: 15% auto; /* 15% from top */
  padding: 20px;
  border-radius: 10px;
  width: 90%;
  max-width: 400px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  cursor: pointer;
}

.close:hover {
  color: #000;
}

#modalCloseBtn {
  margin-top: 15px;
  padding: 10px 20px;
  background-color: #E43636;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

#modalCloseBtn:hover {
  background-color: #b32b2b;
}
.thank-you-box {
    max-width: 800px;
    margin: 50px auto;
    background: #e8f5e9;
    border: 2px solid #4caf50;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    font-family: 'Poppins', sans-serif;
}
.thank-you-box h2 {
    color: #2e7d32;
}

</style>
</head>
<body>
    <?php include('header.php');?>

<div class="job-application">
    <?php if (!$hide_form): ?>
        <!-- Show Form -->
        <form action="" method="POST" enctype="multipart/form-data">

            <h2>Job Application Form</h2>

            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="first_name" placeholder="First Name">
                <input type="text" name="last_name" placeholder="Last Name">
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="ex: myname@example.com">
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" placeholder="(000) 000-0000">
            </div>

            <div class="form-group">
                <label>Position Applying For</label>
                <select name="position" required>
                    <option value="">-- Select a Position --</option>
                    <?php
                    include('./config.php'); // DB connection

                    // Fetch positions
                    $sql = "SELECT id, position_name FROM job_positions ORDER BY position_name ASC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="'. htmlspecialchars($row['position_name']) .'">'. htmlspecialchars($row['position_name']) .'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Available Start Date *</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>

            <div class="form-group">
                <label>Current Employment Status *</label>
                <input type="radio" name="employment" value="employed"> Employed
                <input type="radio" name="employment" value="unemployed"> Unemployed
                <input type="radio" name="employment" value="self"> Self-Employed
                <input type="radio" name="employment" value="student"> Student
            </div>

            <div class="form-group">
                <label>Upload Your Resume *</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx,.jpg,.png">
                <small>Accepted file formats: PDF, DOC, DOCX, JPG, PNG</small>
            </div>

            <div class="form-group">
                <label>Social Status *</label>
                <input type="radio" name="social" value="single"> Single
                <input type="radio" name="social" value="relation"> In Relation
                <input type="radio" name="social" value="married"> Married
            </div>

            <div class="children-info">
                <div class="form-group">
                    <label>Number of Children</label>
                    <input type="number" name="num_children" min="1">
                </div>
            </div>

            <button type="submit">Submit Application</button>
        </form>

    <?php else: ?>
        <!-- Show Thank You Message -->
        <div class="thank-you-box">
            <h2><?php echo $thank_you_message; ?></h2>
            <p>If you need to update your details, please contact HR.</p>
        </div>
    <?php endif; ?>
</div>

  <?php include('footer.php');?>
<script>
const socialRadios = document.querySelectorAll('input[name="social"]');
const childrenInfo = document.querySelector('.children-info');

socialRadios.forEach(radio => {
    radio.addEventListener('change', () => {
        if(radio.value === 'married'){
            childrenInfo.style.display = 'block';
        } else {
            childrenInfo.style.display = 'none';

            // Clear children fields when hidden
            const numChildren = childrenInfo.querySelector('input[name="num_children"]');
            const genderChildren = childrenInfo.querySelector('select[name="children_gender"]');
            numChildren.value = '';
            genderChildren.value = '';
        }
    });
});

</script>

  <script
      src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/gsap.min.js"
      integrity="sha512-qF6akR/fsZAB4Co1QDDnUXWnaQseLGXoniuSuSlPQK6+aWhlMZcHzkasCSlnWoe+TJuudlka1/IQ01Dnhgq95g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>

    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.1/ScrollTrigger.min.js"
      integrity="sha512-IHDCHrefnBT3vOCsvdkMvJF/MCPz/nBauQLzJkupa4Gn4tYg5a6VGyzIrjo6QAUy3We5HFOZUlkUpP0dkgE60A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if(isset($show_modal) && $show_modal): ?>
        var modal = document.getElementById("successModal");
        var closeBtn = document.getElementsByClassName("close")[0];
        var modalCloseBtn = document.getElementById("modalCloseBtn");

        modal.style.display = "block";

        closeBtn.onclick = function() { modal.style.display = "none"; }
        modalCloseBtn.onclick = function() { modal.style.display = "none"; }

        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
    <?php endif; ?>
});
</script>

    
<script src="header.js"></script>
<script src="footer.js"></script>


</body>
</html>
