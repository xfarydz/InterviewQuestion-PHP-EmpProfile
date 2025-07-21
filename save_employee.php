<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - Save Result</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .success-message {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.2em;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
        }

        .error-message {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .employee-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9em;
        }

        .employee-table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
            font-weight: 500;
        }

        .employee-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .employee-table tr:hover {
            background-color: #e8f4f8;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .field-label {
            font-weight: bold;
            color: #2c3e50;
            text-transform: capitalize;
        }

        .field-value {
            color: #34495e;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .content {
                padding: 20px;
            }
            
            .employee-table {
                font-size: 0.9em;
            }
            
            .employee-table th,
            .employee-table td {
                padding: 10px 8px;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Management System</h1>
            <p>Professional Employee Data Management</p>
        </div>
        <div class="content">

<?php
// Enable error reporting for troubleshooting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if this is an update from edit page
if (isset($_GET['updated']) && $_GET['updated'] == '1' && isset($_GET['employee_data'])) {
    $employee_data = json_decode(urldecode($_GET['employee_data']), true);
    
    if ($employee_data) {
        // Display success message with updated employee details
        echo '<div class="success-message">';
        echo '<h3>✅ Employee Updated Successfully!</h3>';
        echo '<p>The following employee information has been updated in the system:</p>';
        echo '</div>';

        // Display employee details in a nice table
        echo '<table class="employee-table">';
        echo '<tr><th colspan="2" style="text-align: center; font-size: 1.2em;">Updated Employee Details</th></tr>';
        
        $field_labels = [
            "name" => "Full Name",
            "gender" => "Gender", 
            "marital_status" => "Marital Status",
            "phone" => "Phone Number",
            "email" => "Email Address",
            "address" => "Address",
            "dob" => "Date of Birth",
            "nationality" => "Nationality",
            "hire_date" => "Hire Date",
            "department" => "Department"
        ];

        foreach ($field_labels as $field => $label) {
            echo '<tr>';
            echo '<td class="field-label">' . $label . '</td>';
            echo '<td class="field-value">' . htmlspecialchars($employee_data[$field]) . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';

        echo '<div class="action-buttons">';
        echo '<a href="view_employees.php" class="btn btn-primary">👥 View All Employees</a>';
        echo '<a href="index.html" class="btn btn-secondary">➕ Add New Employee</a>';
        echo '</div>';
        
        echo '</div></div></body></html>';
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $required_fields = ["name", "gender", "marital_status", "phone", "email", "address", "dob", "nationality", "hire_date", "department"];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "$field is required.";
        }
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match('/^[0-9]{10,15}$/', $_POST["phone"])) {
        $errors[] = "Invalid phone number format.";
    }

    if (count($errors) > 0) {
        echo '<div class="error-message">';
        echo '<h3>❌ Validation Errors</h3>';
        echo '<ul style="list-style: none; padding: 0; margin-top: 15px;">';
        foreach ($errors as $error) {
            echo '<li style="margin: 8px 0; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.2);">• ' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '<div class="action-buttons">';
        echo '<a href="index.html" class="btn btn-primary">🔙 Go Back</a>';
        echo '</div>';
        echo '</div></div></body></html>';
        exit;
    }

    // Prepare data
    $employee = [
        $_POST["name"],
        $_POST["gender"],
        $_POST["marital_status"],
        $_POST["phone"],
        $_POST["email"],
        $_POST["address"],
        $_POST["dob"],
        $_POST["nationality"],
        $_POST["hire_date"],
        $_POST["department"]
    ];

    // Save to CSV
    $file = 'employees.csv';
    $is_new_file = !file_exists($file);

    // Attempt to open file for appending
    $fp = fopen($file, 'a');

    if ($fp === false) {
        echo "Error: Unable to open or create the file. Please check folder permissions.";
        exit;
    }

    if ($is_new_file) {
        // Write header if file is new
        $header = ["Name","Gender","Marital Status","Phone","Email","Address","Date of Birth","Nationality","Hire Date","Department"];
        fputcsv($fp, $header);
    }

    // Write employee data
    if (fputcsv($fp, $employee) === false) {
        echo '<div class="error-message">';
        echo '<h3>❌ Save Error</h3>';
        echo '<p>Unable to save data. Please check file permissions.</p>';
        echo '</div>';
        echo '<div class="action-buttons">';
        echo '<a href="index.html" class="btn btn-primary">🔙 Go Back</a>';
        echo '</div>';
        echo '</div></div></body></html>';
        fclose($fp);
        exit;
    }

    fclose($fp);

    // Display success message with employee details
    echo '<div class="success-message">';
    echo '<h3>✅ Employee Saved Successfully!</h3>';
    echo '<p>The following employee has been added to the system:</p>';
    echo '</div>';

    // Display employee details in a nice table
    echo '<table class="employee-table">';
    echo '<tr><th colspan="2" style="text-align: center; font-size: 1.2em;">Employee Details</th></tr>';
    
    $field_labels = [
        "name" => "Full Name",
        "gender" => "Gender", 
        "marital_status" => "Marital Status",
        "phone" => "Phone Number",
        "email" => "Email Address",
        "address" => "Address",
        "dob" => "Date of Birth",
        "nationality" => "Nationality",
        "hire_date" => "Hire Date",
        "department" => "Department"
    ];

    foreach ($required_fields as $field) {
        echo '<tr>';
        echo '<td class="field-label">' . $field_labels[$field] . '</td>';
        echo '<td class="field-value">' . htmlspecialchars($_POST[$field]) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';

    echo '<div class="action-buttons">';
    echo '<a href="index.html" class="btn btn-primary">➕ Add Another Employee</a>';
    echo '<a href="view_employees.php" class="btn btn-secondary">👥 View All Employees</a>';
    echo '</div>';

} else {
    echo '<div class="error-message">';
    echo '<h3>❌ Invalid Request</h3>';
    echo '<p>This page can only be accessed via form submission.</p>';
    echo '</div>';
    echo '<div class="action-buttons">';
    echo '<a href="index.html" class="btn btn-primary">🔙 Go to Form</a>';
    echo '</div>';
}
?>

        </div>
    </div>
</body>
</html>