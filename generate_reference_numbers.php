<?php
// Database configuration
$host = 'localhost';
$dbname = 'agri_certify';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Generating Reference Numbers for Existing Applications</h3>";
    
    // Function to generate reference number
    function generateReferenceNumber($type, $id, $created_at) {
        $prefixes = [
            'coffee_nursery' => 'CN',
            'commercial_milling' => 'CM', 
            'warehouse' => 'WH',
            'coffee_roaster' => 'CR',
            'grow_miller' => 'GM',
            'pulping_station' => 'PS',
            'grower_notification' => 'GN'
        ];
        
        $prefix = $prefixes[$type] ?? 'AP';
        $date = date('Ymd', strtotime($created_at));
        
        return $prefix . '-' . $date . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    }
    
    // Update coffee_nursery_applications
    $stmt = $pdo->query("SELECT id, created_at FROM coffee_nursery_applications WHERE ref_number IS NULL OR ref_number = ''");
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ref_number = generateReferenceNumber('coffee_nursery', $row['id'], $row['created_at']);
        $update = $pdo->prepare("UPDATE coffee_nursery_applications SET ref_number = ? WHERE id = ?");
        $update->execute([$ref_number, $row['id']]);
        $count++;
    }
    echo "Updated $count coffee nursery applications<br>";
    
    // Update commercial_coffee_milling_applications
    $stmt = $pdo->query("SELECT id, created_at FROM commercial_coffee_milling_applications WHERE ref_number IS NULL OR ref_number = ''");
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ref_number = generateReferenceNumber('commercial_milling', $row['id'], $row['created_at']);
        $update = $pdo->prepare("UPDATE commercial_coffee_milling_applications SET ref_number = ? WHERE id = ?");
        $update->execute([$ref_number, $row['id']]);
        $count++;
    }
    echo "Updated $count commercial milling applications<br>";
    
    // Update other tables similarly...
    // Add similar blocks for warehouse_applications, coffee_roaster_applications, etc.
    
    echo "<h3 style='color: green;'>Reference numbers generated successfully!</h3>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
}
?>