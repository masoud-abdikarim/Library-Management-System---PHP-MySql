<?php
// PHP CLI utility to fix index.php head and styling
$root = dirname(__DIR__, 2);
$filePath = $root . '/index.php';

if (!file_exists($filePath)) {
    die("Error: index.php not found at {$filePath}\n");
}

$t = file_get_contents($filePath);

// Remove existing style blocks
$t = preg_replace('/<style>.*?<\/style>/is', '', $t);

$head_new = '<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NEW HARGEISA LIBRARY | Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/framework.css" rel="stylesheet" />
    <link href="css/auth.css" rel="stylesheet" />
</head>';

$t = preg_replace('/<head>.*?<\/head>/is', $head_new, $t, 1);
$t = preg_replace('/<body>/i', '<body class="auth-body">', $t, 1);

file_put_contents($filePath, $t);
echo "index.php successfully updated!\n";
?>
