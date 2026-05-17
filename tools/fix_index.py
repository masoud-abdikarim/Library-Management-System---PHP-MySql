import re

p = r'c:\xampp\htdocs\Library-Management-System\index.php'
with open(p, encoding='utf-8') as f:
    t = f.read()

t = re.sub(r'<style>.*?</style>', '', t, flags=re.DOTALL)
head_new = """<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NEW HARGEISA LIBRARY | Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/framework.css" rel="stylesheet" />
    <link href="assets/css/auth.css" rel="stylesheet" />
</head>"""
t = re.sub(r'<head>.*?</head>', head_new, t, count=1, flags=re.DOTALL | re.I)
t = t.replace('<body>', '<body class="auth-body">', 1)

with open(p, 'w', encoding='utf-8') as f:
    f.write(t)
print('ok')
