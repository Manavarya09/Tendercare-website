<?php
// export-demo-bookings.php: Export all demo bookings as CSV
$dbFile = __DIR__ . '/book-demo.db';
$db = new SQLite3($dbFile);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="demo_bookings.csv"');

$out = fopen('php://output', 'w');

// Column headers
$headers = [
    'id', 'customerName', 'orgName', 'contactDetails', 'countryCode', 'preferredContact', 'numUsers', 'numDoctors', 'specialties', 'numBranches',
    'newPurchase', 'demoRequest', 'softwareUpgrade', 'integrationInquiry', 'pricingRequest', 'customizationRequest', 'otherInquiry', 'otherInquiryText',
    'existingSoftware', 'serverPref', 'demoDate', 'budget', 'created_at'
];
fputcsv($out, $headers);

$results = $db->query('SELECT * FROM demo_bookings ORDER BY created_at DESC');
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    fputcsv($out, $row);
}
fclose($out);
exit; 