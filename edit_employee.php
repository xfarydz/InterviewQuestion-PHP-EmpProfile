<?php
// Enable error reporting for troubleshooting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$file = 'employees.csv';
$employee_data = null;
$row_number = 0;
$errors = [];

// Handle POST request to update employee data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $required_fields = ["name", "gender", "marital_status", "phone", "email", "address", "dob", "nationality", "hire_date", "department"];
    $row_to_update = (int)$_POST['row_number'];

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

    if (count($errors) == 0) {
        // Update employee data
        if (!file_exists($file)) {
            $errors[] = "Employee database not found.";
        } else {
            // Read all data
            $employees = [];
            $fp = fopen($file, 'r');
            while (($row = fgetcsv($fp)) !== false) {
                $employees[] = $row;
            }
            fclose($fp);

            // Update the specific row
            if ($row_to_update >= 1 && $row_to_update < count($employees)) {
                $employees[$row_to_update] = [
                    $_POST["name"], $_POST["gender"], $_POST["marital_status"],
                    $_POST["phone"], $_POST["email"], $_POST["address"],
                    $_POST["dob"], $_POST["nationality"], $_POST["hire_date"], $_POST["department"]
                ];

                // Rewrite the file
                $fp = fopen($file, 'w');
                foreach ($employees as $employee) {
                    fputcsv($fp, $employee);
                }
                fclose($fp);

                // Redirect to save_employee.php to show updated details
                header('Location: save_employee.php?updated=1&employee_data=' . urlencode(json_encode([
                    'name' => $_POST["name"],
                    'gender' => $_POST["gender"], 
                    'marital_status' => $_POST["marital_status"],
                    'phone' => $_POST["phone"],
                    'email' => $_POST["email"],
                    'address' => $_POST["address"],
                    'dob' => $_POST["dob"],
                    'nationality' => $_POST["nationality"],
                    'hire_date' => $_POST["hire_date"],
                    'department' => $_POST["department"]
                ])));
                exit;
            } else {
                $errors[] = "Employee not found for update.";
            }
        }
    }
    
    // If we reach here, there were errors, so keep form data for correction
    $employee_data = [
        $_POST["name"], $_POST["gender"], $_POST["marital_status"], 
        $_POST["phone"], $_POST["email"], $_POST["address"], 
        $_POST["dob"], $_POST["nationality"], $_POST["hire_date"], $_POST["department"]
    ];
    $row_number = $row_to_update;
}

// Handle GET request to load employee data
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['row'])) {
    $row_to_edit = (int)$_GET['row'];
    
    if (!file_exists($file)) {
        $errors[] = "Employee database not found.";
    } else {
        // Read all data
        $employees = [];
        $fp = fopen($file, 'r');
        while (($row = fgetcsv($fp)) !== false) {
            $employees[] = $row;
        }
        fclose($fp);

        // Check if row exists (accounting for header)
        if ($row_to_edit < 1 || $row_to_edit >= count($employees)) {
            $errors[] = "Employee not found.";
        } else {
            $employee_data = $employees[$row_to_edit];
            $row_number = $row_to_edit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - Edit Employee</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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

        .form-container {
            padding: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
            background: white;
            transform: translateY(-2px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-section {
            text-align: center;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            padding: 18px 40px;
            margin: 0 10px;
            text-decoration: none;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .required-indicator {
            color: #e74c3c;
            font-weight: bold;
        }

        .form-note {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 500;
        }

        .field-description {
            font-size: 0.85em;
            color: #7f8c8d;
            margin-top: 5px;
            font-style: italic;
        }

        .error-message {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
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
            
            .form-container {
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                padding: 15px 30px;
            }
        }

        /* Custom select styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 20px;
            padding-right: 45px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee Management System</h1>
            <p>Edit Employee Information</p>
        </div>
        
        <div class="form-container">
            <?php
            // Show errors if any
            if (count($errors) > 0) {
                echo '<div class="error-message">';
                echo '<h3>❌ Validation Errors</h3>';
                echo '<ul style="list-style: none; padding: 0; margin-top: 15px;">';
                foreach ($errors as $error) {
                    echo '<li style="margin: 8px 0; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.2);">• ' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>';
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<a href="view_employees.php" class="btn btn-secondary">🔙 Back to List</a>';
                echo '</div>';
                echo '</div>';
                echo '</div></div></body></html>';
                exit;
            }

            // Show form if we have employee data
            if ($employee_data) {
            ?>
            <div class="form-note">
                ✏️ Editing employee: <strong><?php echo htmlspecialchars($employee_data[0]); ?></strong>
            </div>

            <form id="employeeForm" action="edit_employee.php" method="POST">
                <input type="hidden" name="row_number" value="<?php echo $row_number; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Employee Name <span class="required-indicator">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Enter full name" value="<?php echo htmlspecialchars($employee_data[0]); ?>" />
                        <div class="field-description">Enter the employee's full name as it appears on official documents</div>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender <span class="required-indicator">*</span></label>
                        <select name="gender" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($employee_data[1] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($employee_data[1] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="marital_status">Marital Status <span class="required-indicator">*</span></label>
                        <select name="marital_status" id="marital_status" required>
                            <option value="">Select Status</option>
                            <option value="Single" <?php echo ($employee_data[2] == 'Single') ? 'selected' : ''; ?>>Single</option>
                            <option value="Married" <?php echo ($employee_data[2] == 'Married') ? 'selected' : ''; ?>>Married</option>
                            <option value="Divorced" <?php echo ($employee_data[2] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                            <option value="Widowed" <?php echo ($employee_data[2] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required-indicator">*</span></label>
                        <input type="tel" name="phone" id="phone" pattern="[0-9]{10,15}" required placeholder="0123456789" value="<?php echo htmlspecialchars($employee_data[3]); ?>" />
                        <div class="field-description">Enter 10-15 digits without spaces or dashes</div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required-indicator">*</span></label>
                        <input type="email" name="email" id="email" required placeholder="employee@company.com" value="<?php echo htmlspecialchars($employee_data[4]); ?>" />
                        <div class="field-description">This will be used for official communications</div>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality <span class="required-indicator">*</span></label>
                        <input type="text" name="nationality" id="nationality" required placeholder="Malaysian" value="<?php echo htmlspecialchars($employee_data[7]); ?>" />
                        <div class="field-description">Employee's citizenship/nationality</div>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth <span class="required-indicator">*</span></label>
                        <input type="date" name="dob" id="dob" required value="<?php echo htmlspecialchars($employee_data[6]); ?>" />
                        <div class="field-description">Used for age verification and benefits calculation</div>
                    </div>

                    <div class="form-group">
                        <label for="hire_date">Hire Date <span class="required-indicator">*</span></label>
                        <input type="date" name="hire_date" id="hire_date" required value="<?php echo htmlspecialchars($employee_data[8]); ?>" />
                        <div class="field-description">Employee's first day of work</div>
                    </div>

                    <div class="form-group">
                        <label for="department">Department <span class="required-indicator">*</span></label>
                        <input type="text" name="department" id="department" required placeholder="Human Resources" value="<?php echo htmlspecialchars($employee_data[9]); ?>" />
                        <div class="field-description">Employee's assigned department or division</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address <span class="required-indicator">*</span></label>
                        <textarea name="address" id="address" rows="4" required placeholder="Enter complete address including street, city, state, and postal code"><?php echo htmlspecialchars($employee_data[5]); ?></textarea>
                        <div class="field-description">Complete residential address for official records</div>
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-success">💾 Update Employee</button>
                    <a href="view_employees.php" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
            <?php
            } else {
                echo '<div class="error-message">';
                echo '<h3>❌ Invalid Request</h3>';
                echo '<p>No employee data provided for editing.</p>';
                echo '<a href="view_employees.php" class="btn btn-secondary">🔙 Back to List</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
        // Form validation and enhancement
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
            const requiredFields = ['name', 'gender', 'marital_status', 'phone', 'email', 'address', 'dob', 'nationality', 'hire_date', 'department'];
            let isValid = true;
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.style.borderColor = '#e74c3c';
                    input.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.1)';
                    isValid = false;
                } else {
                    input.style.borderColor = '#27ae60';
                    input.style.boxShadow = '0 0 0 3px rgba(39, 174, 96, 0.1)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
        
        // Real-time validation
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#27ae60';
                }
            });
            
            input.addEventListener('focus', function() {
                this.style.borderColor = '#3498db';
            });
        });
    </script>
</body>
</html>
