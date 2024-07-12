<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <link rel="stylesheet" href="/assets/css/main.css?1.0.00">
</head>

<body>
    <div class="container">
        <?php include(Utils::getComponentUi('header'));
        if( isset($fileRouterInclude) ): 
            include_once $fileRouterInclude; 
        else: ?>
            <h1>404 Not Found</h1>
        <?php endif; ?>
    </div>
</body>

</html>