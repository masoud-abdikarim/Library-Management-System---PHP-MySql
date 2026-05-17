<?php
// PHP CLI utility to recursively scan and update legacy <head> blocks to unified PHP include statements
$root = dirname(__DIR__, 2);
$skip = ['index.php', 'signup.php', 'admin/a.php', 'admin/b.php', 'tools/update_heads.py', 'tools/update_heads.php', 'php/tools/update_heads.php'];

function updateFile($path, $isAdmin) {
    $content = file_get_contents($path);

    if (strpos($content, 'includes/head.php') !== false) {
        return false;
    }

    if (!preg_match('/<head>\s*(.*?)\s*<\/head>/is', $content, $matches)) {
        return false;
    }

    $headInner = $matches[1];
    $pageTitle = 'NEW HARGEISA LIBRARY';
    if (preg_match('/<title>([^<]*)<\/title>/i', $headInner, $titleMatch)) {
        $pageTitle = trim($titleMatch[1]);
    }
    
    $useDt = (strpos($headInner, 'datatables.css') !== false || strpos($headInner, 'dataTables.bootstrap.css') !== false);
    
    $includePath = $isAdmin ? 'includes/head.php' : 'includes/head.php';
    
    $phpSetup = "<?php\n\$page_title = " . var_export($pageTitle, true) . ";\n";
    if ($useDt) {
        $phpSetup .= "\$use_datatables = true;\n";
    }
    $phpSetup .= "?>";

    $newHead = "<head>\n" . $phpSetup . "\n<?php include('" . $includePath . "'); ?>\n</head>";
    $newContent = str_replace($matches[0], $newHead, $content);

    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        return true;
    }
    return false;
}

function scanDirRecursive($dir, $root, $skip) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            scanDirRecursive($path, $root, $skip);
        } else {
            if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }
            $rel = str_replace('\\', '/', substr($path, strlen($root) + 1));
            if (in_array($rel, $skip) || strpos($rel, 'tools/') === 0 || strpos($rel, 'php/tools/') === 0) {
                continue;
            }
            if (strpos($rel, 'config.php') !== false || strpos($rel, 'header.php') !== false || strpos($rel, 'head.php') !== false) {
                continue;
            }
            if (strpos($file, 'get_') === 0 || in_array($file, ['captcha.php', 'check_availability.php', 'temp.php', 'bgwork.php', 'logout.php', 'adminlogin.php'])) {
                continue;
            }
            $isAdmin = (strpos($rel, 'php/admin/') === 0 || strpos($rel, 'admin/') === 0);
            if (updateFile($path, $isAdmin)) {
                echo "Updated: {$rel}\n";
            }
        }
    }
}

scanDirRecursive($root, $root, $skip);
echo "Scan and header standardizations completed successfully!\n";
?>
