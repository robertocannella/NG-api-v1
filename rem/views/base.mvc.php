<!DOCTYPE html>
<html>
<head>
    <title>{% yield title %}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Viewport meta tag for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

{% yield body %}

</body>
</html>