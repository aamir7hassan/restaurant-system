<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $template['title'] ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <?php echo $template['metadata'] ?>

        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href='http://fonts.googleapis.com/css?family=Montserrat|Raleway:400,200,300,500,600,700,800,900,100' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Aladin' rel='stylesheet' type='text/css'>

        <!-- start menu -->
       
    </head>
    <body>
        <?php echo $template['partials']['header'] ?>
        <?php echo $template['body'] ?>
        <?php echo $template['partials']['footer'] ?>
    </body>
</html>