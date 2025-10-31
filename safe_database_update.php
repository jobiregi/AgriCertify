<?php
// Database configuration
$host = 'localhost';
$dbname = 'agri_certify';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tables to check
    $tables = [
        'coffee_nursery_applications',
        'commercial_coffee_milling_applications', 
        'warehouse_applications',
        'coffee_roaster_applications',
        'grow_millers_licence',
        'pulping_station_applications',
        'coffee_grower_notifications'
    ];
    
    echo "<h3>Database Update Report</h3>";
    
    foreach ($tables as $table) {
        echo "<h4>Table: $table</h4>";
        
        // Check current columns
        $stmt = $pdo->query("SHOW COLUMNS FROM $table");
        $existing_columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existing_columns[$row['Field']] = true;
        }
        
        // Add missing columns
        if (!isset($existing_columns['ref_number'])) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN ref_number VARCHAR(50) UNIQUE");
            echo "✓ Added ref_number column<br>";
        } else {
            echo "✓ ref_number column already exists<br>";
        }
        
        if (!isset($existing_columns['status'])) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending'");
            echo "✓ Added status column<br>";
        } else {
            echo "✓ status column already exists<br>";
        }
        
        if (!isset($existing_columns['officer_notes'])) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN officer_notes TEXT");
            echo "✓ Added officer_notes column<br>";
        } else {
            echo "✓ officer_notes column already exists<br>";
        }
        
        if (!isset($existing_columns['assigned_officer'])) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN assigned_officer VARCHAR(100)");
            echo "✓ Added assigned_officer column<br>";
        } else {
            echo "✓ assigned_officer column already exists<br>";
        }
        
        if (!isset($existing_columns['updated_at'])) {
            $pdo->exec("ALTER TABLE $table ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
            echo "✓ Added updated_at column<br>";
        } else {
            echo "✓ updated_at column already exists<br>";
        }
        
        echo "<br>";
    }
    
    echo "<h3 style='color: green;'>Database update completed successfully!</h3>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
}
?>