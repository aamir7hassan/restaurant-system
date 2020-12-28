<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $template['title'] ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href='http://fonts.googleapis.com/css?family=Montserrat|Raleway:400,200,300,500,600,700,800,900,100' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Aladin' rel='stylesheet' type='text/css'>

        <!-- start menu -->
        <style type="text/css">
            .btn-success, .btn-success:hover, .orange{background: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>; border: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>}
        </style>
    </head>
    <body>
        <?php echo $template['partials']['header'] ?>
        <?php echo $template['body'] ?>
        <?php echo $template['partials']['footer'] ?>
        <script type="application/x-javascript" src="/assets/js/jquery-1.11.1.min.js"></script>        
        <script type="application/x-javascript" src="/assets/js/bootstrap.min.js"></script>        

    </body>
</html>