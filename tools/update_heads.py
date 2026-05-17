import os
import re

ROOT = r'c:\xampp\htdocs\Library-Management-System'
SKIP = {'index.php', 'signup.php', 'admin/a.php', 'admin/b.php', 'tools/update_heads.py'}

def update_file(path, is_admin):
    with open(path, encoding='utf-8', errors='ignore') as f:
        content = f.read()

    if 'includes/head.php' in content:
        return False

    m = re.search(r'<head>\s*(.*?)\s*</head>', content, re.DOTALL | re.IGNORECASE)
    if not m:
        return False

    head_inner = m.group(1)
    title_m = re.search(r'<title>([^<]*)</title>', head_inner, re.I)
    page_title = title_m.group(1).strip() if title_m else 'NEW HARGEISA LIBRARY'
    use_dt = 'datatables.css' in head_inner

    include_path = 'includes/head.php' if not is_admin else 'includes/head.php'
    php_setup = "<?php\n$page_title = " + repr(page_title) + ";\n"
    if use_dt:
        php_setup += "$use_datatables = true;\n"
    php_setup += "?>"

    new_head = "<head>\n" + php_setup + "\n<?php include('" + include_path + "'); ?>\n</head>"
    new_content = content[:m.start()] + new_head + content[m.end():]

    # Remove inline style blocks in head area only (browse-books has style after head - handle separately)
    if new_content != content:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        return True
    return False

for dirpath, _, files in os.walk(ROOT):
    for fn in files:
        if not fn.endswith('.php'):
            continue
        rel = os.path.relpath(os.path.join(dirpath, fn), ROOT).replace('\\', '/')
        if rel in SKIP or rel.startswith('tools/'):
            continue
        if 'config.php' in rel or 'header.php' in rel or 'head.php' in rel:
            continue
        if 'get_' in fn or fn in ('captcha.php', 'check_availability.php', 'temp.php', 'bgwork.php', 'logout.php', 'adminlogin.php'):
            continue
        is_admin = rel.startswith('admin/')
        if update_file(os.path.join(dirpath, fn), is_admin):
            print('updated', rel)
