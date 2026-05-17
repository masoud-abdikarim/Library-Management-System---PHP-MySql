<?php
// Refactor Script for Library Management System
$base = dirname(__DIR__, 2);

function mkdir_if_not_exists($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

mkdir_if_not_exists("$base/php/admin/includes");
mkdir_if_not_exists("$base/php/includes");
mkdir_if_not_exists("$base/css/admin");
mkdir_if_not_exists("$base/js/admin");
mkdir_if_not_exists("$base/images/admin");
mkdir_if_not_exists("$base/fonts/admin");

function move_dir_contents($src, $dest) {
    if (!is_dir($src)) return;
    $files = scandir($src);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $srcPath = "$src/$file";
        $destPath = "$dest/$file";
        if (is_dir($srcPath)) {
            mkdir_if_not_exists($destPath);
            move_dir_contents($srcPath, $destPath);
        } else {
            rename($srcPath, $destPath);
        }
    }
}

// 1. Move assets
move_dir_contents("$base/assets/css", "$base/css");
move_dir_contents("$base/assets/js", "$base/js");
move_dir_contents("$base/assets/img", "$base/images");
move_dir_contents("$base/assets/fonts", "$base/fonts");

move_dir_contents("$base/admin/assets/css", "$base/css/admin");
move_dir_contents("$base/admin/assets/js", "$base/js/admin");
move_dir_contents("$base/admin/assets/img", "$base/images/admin");
move_dir_contents("$base/admin/assets/fonts", "$base/fonts/admin");

// Delete old asset dirs
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
rrmdir("$base/assets");
rrmdir("$base/admin/assets");

// 2. Move includes
move_dir_contents("$base/includes", "$base/php/includes");
move_dir_contents("$base/admin/includes", "$base/php/admin/includes");
rrmdir("$base/includes");
rrmdir("$base/admin/includes");

// 3. Move PHP files
$rootFiles = scandir($base);
foreach ($rootFiles as $f) {
    if ($f === '.' || $f === '..' || $f === 'index.php') continue;
    if (is_file("$base/$f") && pathinfo($f, PATHINFO_EXTENSION) === 'php') {
        rename("$base/$f", "$base/php/$f");
    }
}

$adminFiles = is_dir("$base/admin") ? scandir("$base/admin") : [];
foreach ($adminFiles as $f) {
    if ($f === '.' || $f === '..') continue;
    if (is_file("$base/admin/$f") && pathinfo($f, PATHINFO_EXTENSION) === 'php') {
        rename("$base/admin/$f", "$base/php/admin/$f");
    }
}
rrmdir("$base/admin");

// 4. Update references in files
function update_file_contents($dir, $isRoot, $isAdmin) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = "$dir/$file";
        if (is_dir($path) && $file !== 'tools' && $file !== 'php/tools' && $file !== '.git') {
            update_file_contents($path, false, $isAdmin || $file === 'admin');
        } elseif (is_file($path) && in_array(pathinfo($path, PATHINFO_EXTENSION), ['php', 'html', 'css', 'js'])) {
            $content = file_get_contents($path);
            $newContent = $content;

            if ($isRoot) {
                // index.php
                $newContent = str_replace('assets/css/', 'css/', $newContent);
                $newContent = str_replace('assets/js/', 'js/', $newContent);
                $newContent = str_replace('assets/img/', 'images/', $newContent);
                $newContent = str_replace('assets/fonts/', 'fonts/', $newContent);
                
                // replace root php files links
                $newContent = preg_replace('/href="(?!(http|#|javascript|mailto|css|js|images|fonts|php))([a-zA-Z0-9_-]+\.php)/', 'href="php/$2', $newContent);
                $newContent = preg_replace('/action="(?!(http|#|javascript|mailto|css|js|images|fonts|php))([a-zA-Z0-9_-]+\.php)/', 'action="php/$2', $newContent);
                $newContent = preg_replace('/include\(\'(?!(php|css|js|images|fonts))([a-zA-Z0-9_-]+\.php)\'\)/', 'include(\'php/$2\')', $newContent);
                $newContent = preg_replace('/include\(\'(?!(php|css|js|images|fonts))includes\//', 'include(\'php/includes/', $newContent);
                $newContent = str_replace('admin/', 'php/admin/', $newContent);
                $newContent = preg_replace('/header\(\'location:([a-zA-Z0-9_-]+\.php)\'\)/i', "header('location:php/$1')", $newContent);

            } else {
                if ($isAdmin) {
                    $newContent = str_replace('assets/css/', '../../css/admin/', $newContent);
                    $newContent = str_replace('assets/js/', '../../js/admin/', $newContent);
                    $newContent = str_replace('assets/img/', '../../images/admin/', $newContent);
                    $newContent = str_replace('assets/fonts/', '../../fonts/admin/', $newContent);
                    
                    $newContent = str_replace('../assets/css/', '../../css/', $newContent);
                    $newContent = str_replace('../assets/js/', '../../js/', $newContent);
                    $newContent = str_replace('../assets/img/', '../../images/', $newContent);
                    $newContent = str_replace('../assets/fonts/', '../../fonts/', $newContent);
                    
                    // fixing include paths
                    $newContent = str_replace('include(\'../includes/', 'include(\'../includes/', $newContent); // this remains same relatively
                    $newContent = str_replace('include(\'includes/', 'include(\'includes/', $newContent);
                    
                    // index.php is now ../../index.php
                    $newContent = preg_replace('/href="(\.\.\/)?index\.php"/', 'href="../../index.php"', $newContent);
                    $newContent = preg_replace('/header\(\'location:(\.\.\/)?index\.php\'\)/i', "header('location:../../index.php')", $newContent);

                } else {
                    // in php/ (user level)
                    $newContent = str_replace('assets/css/', '../css/', $newContent);
                    $newContent = str_replace('assets/js/', '../js/', $newContent);
                    $newContent = str_replace('assets/img/', '../images/', $newContent);
                    $newContent = str_replace('assets/fonts/', '../fonts/', $newContent);
                    
                    $newContent = preg_replace('/href="index\.php"/', 'href="../index.php"', $newContent);
                    $newContent = preg_replace('/header\(\'location:index\.php\'\)/i', "header('location:../index.php')", $newContent);
                }
            }
            
            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
            }
        }
    }
}

update_file_contents($base, true, false);

echo "Refactoring completed.\n";
