<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container" style="padding: 20px 0">
        <p>Версия Redis: <?= $redis_verion ?></p>
        <p>Версия PHP: <?= $php_verion ?></p>
        <div id="app"></div>
    </div>
  <script src="/app.js"></script>
  </body>
</html>