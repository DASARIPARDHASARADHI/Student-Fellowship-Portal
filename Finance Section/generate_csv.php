<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "pardhu14225";
$dbname = "fellowship_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available columns from both tables
$table1 = 'students';
$table2 = 'students_claims';
$columns = ['students' => [], 'students_claims' => []];
$column_names = [];

// Get columns from 'students' table
$result = $conn->query("SHOW COLUMNS FROM $table1");
while ($row = $result->fetch_assoc()) {
    if (!in_array($row['Field'], ['password', 'id', 'claimed_month', 'claimed_time', 'claimed_amount', 'claimed'])) { // Exclude sensitive fields
        $columns['students'][] = "$table1." . $row['Field'];
        $column_names["$table1." . $row['Field']] = $row['Field']; // Store column names for headers
    }
}

// Get columns from 'students_claims' table
$result = $conn->query("SHOW COLUMNS FROM $table2");
while ($row = $result->fetch_assoc()) {
    if (!in_array($row['Field'], ['fname', 'lname', 'id', 'rollno'])) { // Exclude redundant fields
        $columns['students_claims'][] = "$table2." . $row['Field'];
        $column_names["$table2." . $row['Field']] = $row['Field'];
    }
}

if (isset($_POST['generate_csv'])) {
    ob_clean(); // Clear output buffer to avoid unwanted spaces
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=student_fellowship_data.csv');

    $output = fopen('php://output', 'w');

    // Get selected columns or default to all
    $selected_columns = isset($_POST['columns']) ? $_POST['columns'] : array_merge($columns['students'], $columns['students_claims']);

    // Write CSV header
    fputcsv($output, array_map(fn($col) => $column_names[$col], $selected_columns));

    // Create SQL query with formatted date fields
    $query = "SELECT " . implode(", ", $selected_columns) . " 
              FROM $table1 
              INNER JOIN $table2 ON $table1.rollno = $table2.rollno";

    $result = $conn->query($query);
    if (!$result) {
        die("Query Error: " . $conn->error);
    }


    while ($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            // Format date fields properly
            if (preg_match('/date|time/i', $key) && strtotime($value)) {
                $row[$key] = date('Y-m-d H:i:s', strtotime($value));
            }
            // Preserve leading zeros in numeric fields
            elseif (is_numeric($value) && strlen($value) > 10) {
                $row[$key] = "'" . $value;
            }
        }
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Generator | Student Fellowship Portal</title>
    <link rel="icon" href="/Student/iitp_symbol.png" type="image/png">
    <style>
        body {
            background-color: #1f94ca;
            margin: 20px;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        #container {
            width: 500px;
            border: solid #0d86be 3px;
            border-radius: 5px;
            background-color: #a0cee4;
            padding: 20px;
            margin: auto;
            box-shadow: 2px 2px 9px 8px #045b83;
            text-align: left;
        }

        .column-selection {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            background: white;
        }

        .bulk-select {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        input[type="submit"],
        button {
            padding: 10px 20px;
            border: none;
            background-color: rgb(7, 160, 65);
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: rgb(3, 90, 36);
        }
    </style>
</head>

<body>
    <div class="heading-container">
        <!-- <img src="iitp_symbol.png" alt="IITP Symbol" width="100" height="100"> -->
        <h1 style="color:white">Student Fellowship Portal</h1>
    </div>

    <h2>Generate CSV</h2>

    <div id="container">
        <form method="post">
            <label><strong>Select Columns:</strong></label>
            <div class="bulk-select">
                <button type="button" onclick="selectAll(true)">Select All</button>
                <button type="button" onclick="selectAll(false)">Deselect All</button>
            </div>
            <div class="column-selection">
                <?php foreach ($columns as $table => $cols) { ?>
                    <strong><?php echo ucfirst(str_replace('_', ' ', $table)); ?>:</strong><br>
                    <?php foreach ($cols as $col) {
                        $colName = $column_names[$col];
                        echo "<input type='checkbox' name='columns[]' value='$col'> $colName<br>";
                    } ?>
                    <br>
                <?php } ?>
            </div>
            <br>
            <input type="submit" name="generate_csv" value="Download CSV">
        </form>
    </div>

    <script>
        function selectAll(select) {
            document.querySelectorAll("input[type='checkbox']").forEach(cb => cb.checked = select);
        }
    </script>
</body>

</html>