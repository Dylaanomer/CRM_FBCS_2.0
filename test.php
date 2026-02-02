<?php
// Enable error display for testing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize test results
$tests = array();

// Test 1: Database Connection (ajax/dbh.php)
$test_name = "Database Connection (ajax/dbh.php)";
try {
    include 'ajax/dbh.php';
    if ($conn && $conn->connect_error === null) {
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'Connected to: ' . $mysqldb . ' on ' . $mysqlserver,
            'details' => 'User: ' . $mysqluser
        );
    } else {
        $tests[$test_name] = array(
            'status' => 'FAIL',
            'message' => $conn->connect_error,
            'details' => 'Connection failed'
        );
    }
} catch (Exception $e) {
    $tests[$test_name] = array(
        'status' => 'ERROR',
        'message' => $e->getMessage(),
        'details' => 'Exception thrown'
    );
}

// Test 2: Database Login Connection (ajax/login/dbh.php)
$test_name = "Database Connection (ajax/login/dbh.php)";
try {
    include 'ajax/login/dbh.php';
    if ($conn && $conn->connect_error === null) {
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'Connected to: ' . $mysqldb . ' on ' . $mysqlserver,
            'details' => 'User: ' . $mysqluser
        );
    } else {
        $tests[$test_name] = array(
            'status' => 'FAIL',
            'message' => $conn->connect_error,
            'details' => 'Connection failed'
        );
    }
} catch (Exception $e) {
    $tests[$test_name] = array(
        'status' => 'ERROR',
        'message' => $e->getMessage(),
        'details' => 'Exception thrown'
    );
}

// Test 3: Check Users Table
$test_name = "Users Table Check";
$users_data = array();
try {
    $user_count_sql = "SELECT * FROM users;";
    $user_result = $conn->query($user_count_sql);
    if ($user_result) {
        $user_count = $user_result->num_rows;
        $users_data = array();
        while ($user_row = $user_result->fetch_assoc()) {
            $users_data[] = $user_row;
        }
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'Users table accessed successfully',
            'details' => 'Total users: ' . $user_count,
            'data' => $users_data
        );
    } else {
        $tests[$test_name] = array(
            'status' => 'FAIL',
            'message' => 'Could not access users table',
            'details' => $conn->error
        );
    }
} catch (Exception $e) {
    $tests[$test_name] = array(
        'status' => 'ERROR',
        'message' => $e->getMessage(),
        'details' => ''
    );
}

// Test 4: Check Cookie Functionality
$test_name = "Cookie Module (ajax/login/cookie.php)";
try {
    include 'ajax/login/cookie.php';
    if (function_exists('checkLoginCookie')) {
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'Cookie module loaded successfully',
            'details' => 'checkLoginCookie() function available'
        );
    } else {
        $tests[$test_name] = array(
            'status' => 'FAIL',
            'message' => 'Cookie module did not load required functions',
            'details' => 'checkLoginCookie() function not found'
        );
    }
} catch (Exception $e) {
    $tests[$test_name] = array(
        'status' => 'ERROR',
        'message' => $e->getMessage(),
        'details' => ''
    );
}

// Test 5: Check PHPMailer and Email Functionality
$test_name = "PHPMailer and Email Configuration";
$mail_info = array();
try {
    // Check if PHPMailer is installed via Composer
    $composer_autoload = 'vendor/autoload.php';
    $phpmailer_installed = false;
    $mail_config_found = false;
    $mail_usage_found = false;

    if (file_exists($composer_autoload)) {
        require_once $composer_autoload;
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $phpmailer_installed = true;
            $mail_info[] = 'PHPMailer installed via Composer';
        }
    }

    // Check for manual PHPMailer installation
    $phpmailer_manual_paths = array(
        'PHPMailer/PHPMailer.php',
        'phpmailer/PHPMailer.php',
        'includes/PHPMailer.php',
        'lib/PHPMailer.php'
    );

    foreach ($phpmailer_manual_paths as $path) {
        if (file_exists($path)) {
            $phpmailer_installed = true;
            $mail_info[] = 'PHPMailer found at: ' . $path;
            break;
        }
    }

    // Check for email configuration files
    $config_files = array(
        'config/mail.php',
        'includes/mail-config.php',
        'ajax/mail-config.php',
        'mail-config.php'
    );

    foreach ($config_files as $config_file) {
        if (file_exists($config_file)) {
            $mail_config_found = true;
            $mail_info[] = 'Mail config found: ' . $config_file;
        }
    }

    // Search for mail usage in PHP files
    $search_directories = array('ajax', 'includes', '.');
    $mail_patterns = array(
        '/new PHPMailer/',
        '/mail\(/',
        '/->send\(/',
        '/->addAddress\(/',
        '/->setFrom\(/'
    );

    foreach ($search_directories as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*.php');
            foreach ($files as $file) {
                $content = file_get_contents($file);
                foreach ($mail_patterns as $pattern) {
                    if (preg_match($pattern, $content)) {
                        $mail_usage_found = true;
                        $mail_info[] = 'Mail usage found in: ' . $file;
                        break 2;
                    }
                }
            }
        }
    }

    // Check if PHP mail function is enabled
    $mail_function_available = function_exists('mail');
    if ($mail_function_available) {
        $mail_info[] = 'PHP mail() function is available';
    }

    // Determine test result
    if ($phpmailer_installed || $mail_usage_found) {
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'Email functionality detected',
            'details' => implode(' | ', $mail_info),
            'data' => array(
                array(
                    'Component' => 'PHPMailer Installed',
                    'Status' => $phpmailer_installed ? 'Yes' : 'No'
                ),
                array(
                    'Component' => 'Mail Config Found',
                    'Status' => $mail_config_found ? 'Yes' : 'No'
                ),
                array(
                    'Component' => 'Mail Usage in Code',
                    'Status' => $mail_usage_found ? 'Yes' : 'No'
                ),
                array(
                    'Component' => 'PHP mail() Available',
                    'Status' => $mail_function_available ? 'Yes' : 'No'
                )
            )
        );
    } else if ($mail_function_available) {
        $tests[$test_name] = array(
            'status' => 'PASS',
            'message' => 'PHP mail() function available (no PHPMailer detected)',
            'details' => 'Native PHP mail can be used',
            'data' => array(
                array(
                    'Component' => 'PHPMailer Installed',
                    'Status' => 'No'
                ),
                array(
                    'Component' => 'PHP mail() Available',
                    'Status' => 'Yes'
                )
            )
        );
    } else {
        $tests[$test_name] = array(
            'status' => 'FAIL',
            'message' => 'No email functionality detected',
            'details' => 'Neither PHPMailer nor PHP mail() function found',
            'data' => array(
                array(
                    'Component' => 'PHPMailer Installed',
                    'Status' => 'No'
                ),
                array(
                    'Component' => 'PHP mail() Available',
                    'Status' => 'No'
                )
            )
        );
    }
} catch (Exception $e) {
    $tests[$test_name] = array(
        'status' => 'ERROR',
        'message' => $e->getMessage(),
        'details' => 'Exception during email check'
    );
}

// Test 6: Check Required Backend Files
$backend_files = array(
    'ajax/login/login.php' => 'Login Handler',
    'ajax/login/logout.php' => 'Logout Handler',
    'ajax/login/register.php' => 'Register Handler',
    'ajax/save-reminder.php' => 'Save Reminder',
    'ajax/delete-reminder.php' => 'Delete Reminder',
    'ajax/render-dashboard.php' => 'Render Dashboard'
);

foreach ($backend_files as $file => $description) {
    if (file_exists($file)) {
        $tests["File: $description"] = array(
            'status' => 'PASS',
            'message' => 'File exists and is readable',
            'details' => 'Path: ' . $file
        );
    } else {
        $tests["File: $description"] = array(
            'status' => 'FAIL',
            'message' => 'File not found or not readable',
            'details' => 'Path: ' . $file
        );
    }
}

?>

<html>
  <head>
    <title>FBCS System Test</title>
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

        .test-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .test-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .test-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .test-header p {
            opacity: 0.9;
            font-size: 1.1em;
        }

        .test-summary {
            display: flex;
            justify-space-around;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        .summary-item {
            text-align: center;
        }

        .summary-count {
            font-size: 2em;
            font-weight: bold;
            margin: 5px 0;
        }

        .summary-pass { color: #4caf50; }
        .summary-fail { color: #f44336; }
        .summary-error { color: #ff9800; }

        .tests-content {
            padding: 30px;
        }

        .test-item {
            margin-bottom: 20px;
            border-left: 5px solid #ddd;
            padding: 15px;
            border-radius: 3px;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }

        .test-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .test-item.pass {
            border-left-color: #4caf50;
            background: #f1f8f6;
        }

        .test-item.fail {
            border-left-color: #f44336;
            background: #fef5f5;
        }

        .test-item.error {
            border-left-color: #ff9800;
            background: #fff8f3;
        }

        .test-name {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .test-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            margin-left: 10px;
        }

        .status-pass {
            background: #4caf50;
            color: white;
        }

        .status-fail {
            background: #f44336;
            color: white;
        }

        .status-error {
            background: #ff9800;
            color: white;
        }

        .test-message {
            color: #333;
            margin: 5px 0;
            font-size: 0.95em;
        }

        .test-details {
            color: #666;
            font-size: 0.85em;
            margin-top: 5px;
            font-style: italic;
        }

        .test-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .data-table thead {
            background: #667eea;
            color: white;
        }

        .data-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border: none;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            word-break: break-word;
        }

        .data-table tbody tr:hover {
            background: #f5f5f5;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
    </style>
  </head>
  <body>
    <div class="test-container">
        <div class="test-header">
            <h1>🔍 System Test Report</h1>
            <p>FBCS Reminder PHP Application</p>
        </div>

        <div class="test-summary">
            <div class="summary-item">
                <div>Total Tests</div>
                <div class="summary-count"><?php echo count($tests); ?></div>
            </div>
            <div class="summary-item">
                <div>Passed</div>
                <div class="summary-count summary-pass">
                    <?php echo count(array_filter($tests, fn($t) => $t['status'] === 'PASS')); ?>
                </div>
            </div>
            <div class="summary-item">
                <div>Failed</div>
                <div class="summary-count summary-fail">
                    <?php echo count(array_filter($tests, fn($t) => $t['status'] === 'FAIL')); ?>
                </div>
            </div>
            <div class="summary-item">
                <div>Errors</div>
                <div class="summary-count summary-error">
                    <?php echo count(array_filter($tests, fn($t) => $t['status'] === 'ERROR')); ?>
                </div>
            </div>
        </div>

        <div class="tests-content">
            <?php foreach ($tests as $name => $test): ?>
                <div class="test-item <?php echo strtolower($test['status']); ?>">
                    <div class="test-name">
                        <?php
                        if ($test['status'] === 'PASS') echo '✓ ';
                        elseif ($test['status'] === 'FAIL') echo '✗ ';
                        else echo '⚠ ';
                        ?>
                        <?php echo htmlspecialchars($name); ?>
                        <span class="test-status status-<?php echo strtolower($test['status']); ?>">
                            <?php echo $test['status']; ?>
                        </span>
                    </div>
                    <div class="test-message"><?php echo htmlspecialchars($test['message']); ?></div>
                    <?php if (!empty($test['details'])): ?>
                        <div class="test-details"><?php echo htmlspecialchars($test['details']); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($test['data']) && is_array($test['data'])): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <?php
                                    if (!empty($test['data'])) {
                                        foreach (array_keys($test['data'][0]) as $column) {
                                            echo '<th>' . htmlspecialchars($column) . '</th>';
                                        }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($test['data'] as $row): ?>
                                    <tr>
                                        <?php
                                        foreach ($row as $value) {
                                            echo '<td>' . htmlspecialchars($value) . '</td>';
                                        }
                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="test-footer">
            <p>Test executed on <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
  </body>
</html>