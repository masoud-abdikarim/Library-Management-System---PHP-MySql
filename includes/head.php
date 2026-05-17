<?php
/**
 * Shared head assets — set $page_title before include.
 * Optional: $use_datatables = true;
 */
$use_datatables = !empty($use_datatables);
$page_title = isset($page_title) ? $page_title : 'NEW HARGEISA LIBRARY';
?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="NEW HARGEISA LIBRARY Management System" />
<title><?php echo htmlentities($page_title); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<link href="assets/css/framework.css" rel="stylesheet" />
<?php if ($use_datatables): ?>
<link href="assets/css/datatables.css" rel="stylesheet" />
<?php endif; ?>
<link href="assets/css/style.css?v=2" rel="stylesheet" />
