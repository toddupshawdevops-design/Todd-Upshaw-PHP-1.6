<?php
/**
 * Main Web Application Interface
 * 
 * Renders the input validation form, consumes validation functions from validator.php,
 * and displays field errors and overall validation summary.
 */

require_once('validator.php');

// Initialize form input variables
$name = '';
$dob = '';
$email = '';
$fav_int = '';
$nickname = '';

// Initialize error message variables
$name_error = '';
$dob_error = '';
$email_error = '';
$fav_int_error = '';
$nickname_error = '';

$form_submitted = false;
$has_errors = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_submitted = true;

    // Retrieve posted values
    $name     = $_POST['name'] ?? '';
    $dob      = $_POST['dob'] ?? '';
    $email    = $_POST['email'] ?? '';
    $fav_int  = $_POST['fav_int'] ?? '';
    $nickname = $_POST['nickname'] ?? '';

    // 1. Validate Name (Returns string, parameter by value, REGEX)
    $name_error = \lastname_validator\validateName($name);

    // 2. Validate DOB (Pass by reference, control logic)
    \lastname_validator\validateDOB($dob, $dob_error);

    // 3. Validate Email (PHP built-in filter_var)
    $email_error = \lastname_validator\validateEmail($email);

    // 4. Validate Integer (Exception handling)
    try {
        \lastname_validator\validateInteger($fav_int);
    } catch (\Exception $e) {
        $fav_int_error = $e->getMessage();
    }

    // 5. Validate Nickname (Optional, control logic)
    $nickname_error = \lastname_validator\validateNickname($nickname);

    // Determine overall validation status
    if (
        !empty($name_error) || 
        !empty($dob_error) || 
        !empty($email_error) || 
        !empty($fav_int_error) || 
        !empty($nickname_error)
    ) {
        $has_errors = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Name Wk 1 Performance Assessment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 250px;
            padding: 5px;
        }
        .error {
            color: red;
            margin-left: 10px;
            font-weight: bold;
        }
        .submit-btn {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
        }
        .summary {
            margin-top: 20px;
            font-size: 1.1em;
            font-weight: bold;
        }
        .summary.success {
            color: green;
        }
        .summary.failure {
            color: red;
        }
    </style>
</head>
<body>

    <h2>User Input Validation Application</h2>

    <form method="POST" action="">
        
        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (!empty($name_error)): ?>
                <span class="error"><?php echo htmlspecialchars($name_error); ?></span>
            <?php endif; ?>
        </div>

        <!-- Date of Birth Field -->
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="text" id="dob" name="dob" placeholder="MM/DD/YYYY" value="<?php echo htmlspecialchars($dob); ?>">
            <?php if (!empty($dob_error)): ?>
                <span class="error"><?php echo htmlspecialchars($dob_error); ?></span>
            <?php endif; ?>
        </div>

        <!-- Email Address Field -->
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (!empty($email_error)): ?>
                <span class="error"><?php echo htmlspecialchars($email_error); ?></span>
            <?php endif; ?>
        </div>

        <!-- Favorite Integer Field -->
        <div class="form-group">
            <label for="fav_int">Favorite Integer:</label>
            <input type="text" id="fav_int" name="fav_int" value="<?php echo htmlspecialchars($fav_int); ?>">
            <?php if (!empty($fav_int_error)): ?>
                <span class="error"><?php echo htmlspecialchars($fav_int_error); ?></span>
            <?php endif; ?>
        </div>

        <!-- Nickname Field -->
        <div class="form-group">
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?php echo htmlspecialchars($nickname); ?>">
            <?php if (!empty($nickname_error)): ?>
                <span class="error"><?php echo htmlspecialchars($nickname_error); ?></span>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <input type="submit" value="Validate" class="submit-btn">

    </form>

    <!-- Overall Validation Results Message -->
    <?php if ($form_submitted): ?>
        <div class="summary <?php echo $has_errors ? 'failure' : 'success'; ?>">
            <?php 
            if ($has_errors) {
                echo "Errors found, please check your entries";
            } else {
                echo "All fields valid";
            }
            ?>
        </div>
    <?php endif; ?>

</body>
</html>
