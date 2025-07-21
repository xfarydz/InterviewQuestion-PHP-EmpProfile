<?php
$file = 'employees.csv';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - View All Employees</title>
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
            max-width: 1200px;
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

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .employee-count {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
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

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .employees-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .employees-table th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9em;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .employees-table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
            font-weight: 500;
            vertical-align: middle;
        }

        .employees-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .employees-table tr:hover {
            background-color: #e3f2fd;
            transform: scale(1.001);
            transition: all 0.3s ease;
        }

        .no-employees {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(243, 156, 18, 0.3);
        }

        .no-employees h3 {
            font-size: 2em;
            margin-bottom: 15px;
        }

        .no-employees p {
            font-size: 1.1em;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .employee-id {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            display: inline-block;
        }

        .department-badge {
            background: linear-gradient(135deg, #16a085, #1abc9c);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            display: inline-block;
        }

        .email-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .email-link:hover {
            text-decoration: underline;
        }

        .phone-number {
            font-family: 'Courier New', monospace;
            background: #ecf0f1;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.9em;
        }

        .delete-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }

        .edit-btn {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }

        .success-notification {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
            animation: slideInDown 0.5s ease;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            
            .top-section {
                flex-direction: column;
                text-align: center;
            }
            
            .employees-table {
                font-size: 0.8em;
            }
            
            .employees-table th,
            .employees-table td {
                padding: 8px;
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
            <p>View All Registered Employees</p>
        </div>
        <div class="content">
    <?php
    // Show success message if employee was deleted
    if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
        echo '<div class="success-notification">';
        echo '✅ Employee deleted successfully! Employee numbers have been automatically reset.';
        echo '</div>';
    }
    
    // Show success message if employee was updated
    if (isset($_GET['updated']) && $_GET['updated'] == '1') {
        echo '<div class="success-notification">';
        echo '✅ Employee updated successfully! All changes have been saved.';
        echo '</div>';
    }
    
    if (!file_exists($file)) {
        echo '<div class="no-employees">';
        echo '<h3>📝 No Employees Found</h3>';
        echo '<p>The employee database is empty. Start by adding your first employee!</p>';
        echo '<a href="index.html" class="btn btn-primary">➕ Add First Employee</a>';
        echo '</div>';
        echo '</div></div></body></html>';
        exit;
    }

    // Count total employees
    $total_employees = 0;
    $fp_count = fopen($file, 'r');
    while (fgetcsv($fp_count) !== false) {
        $total_employees++;
    }
    fclose($fp_count);
    $total_employees--; // Subtract header row

    echo '<div class="top-section">';
    echo '<div class="employee-count">👥 Total Employees: ' . $total_employees . '</div>';
    echo '<a href="index.html" class="btn btn-primary">➕ Add New Employee</a>';
    echo '</div>';

    echo '<div class="table-container">';
    echo '<table class="employees-table">';
    
    $fp = fopen($file, 'r');
    $first = true;
    $row_number = 1;
    
    while (($row = fgetcsv($fp)) !== false) {
        if ($first) {
            echo "<tr>";
            echo "<th>#</th>"; // Add row number column
            foreach ($row as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "<th>Actions</th>"; // Add actions column
            echo "</tr>";
            $first = false;
        } else {
            echo "<tr>";
            echo '<td><span class="employee-id">' . $row_number . '</span></td>';
            
            foreach ($row as $index => $data) {
                echo "<td>";
                
                // Special formatting for specific columns
                if ($index == 4 && filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    // Email column - make it clickable
                    echo '<a href="mailto:' . htmlspecialchars($data) . '" class="email-link">' . htmlspecialchars($data) . '</a>';
                } elseif ($index == 3) {
                    // Phone column - special formatting
                    echo '<span class="phone-number">' . htmlspecialchars($data) . '</span>';
                } elseif ($index == 9) {
                    // Department column - badge style
                    echo '<span class="department-badge">' . htmlspecialchars($data) . '</span>';
                } else {
                    echo htmlspecialchars($data);
                }
                
                echo "</td>";
            }
            
            // Add action buttons
            echo '<td>';
            echo '<a href="edit_employee.php?row=' . $row_number . '" class="edit-btn">✏️ Edit</a>';
            echo '<a href="javascript:void(0)" class="delete-btn" onclick="confirmDelete(' . $row_number . ', \'' . htmlspecialchars($row[0], ENT_QUOTES) . '\')">🗑️ Delete</a>';
            echo '</td>';
            
            echo "</tr>";
            $row_number++;
        }
    }
    
    fclose($fp);
    echo "</table>";
    echo '</div>';
    ?>

        </div>
    </div>

    <script>
        function confirmDelete(rowNumber, employeeName) {
            if (confirm('Are you sure you want to delete employee "' + employeeName + '"?\n\nThis action cannot be undone and will reset all employee numbers.')) {
                window.location.href = 'delete_employee.php?row=' + rowNumber;
            }
        }

        // Auto-hide success notification after 5 seconds
        setTimeout(function() {
            const notification = document.querySelector('.success-notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-30px)';
                setTimeout(function() {
                    notification.remove();
                }, 500);
            }
        }, 5000);
    </script>
</body>
</html>