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
        
        
        <script src="<?php echo base_url('assets/sweet/sweetalert.min.js') ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url('assets/sweet/sweetalert.css') ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/toastr.min.css') ?>">
		<script src="<?php echo base_url('assets/js/toastr.min.js') ?>"></script>
        <style type="text/css">
            .open{background: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>}
            .price{color:<?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>}
            .btn-true, .btn-true:hover, .btn-info, .btn-info:hover, .btn-danger, .btn-danger:hover, .btn-default, .btn-default:hover, .btn-warning, .btn-warning:hover, .btn-info, .btn-info:hover{ background: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>; border: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>}
            .container{ background:#FFF; }
            .submit:hover{color:#FFFFFF; filter: brightness(85%);}
            .custom-header-area{margin-bottom:32px; padding: 144px 0 0px 0; background-repeat:repeat-x; 
                               
                                   background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo base_url("assets/images/backgrounds/".$this->config->item('store_background')); ?>);}
            div.header_section, .button{background: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#fab219"; ?>;}
            img.header-img{border:3px solid #FFFFFF;}
            button.submit {background:<?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>; border: 1px solid <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>; border-radius:0; color:#FFF;}
            .take_away_section button{border:1px solid <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>;}
            .take_away_section .buttons{ font-weight:700; color: <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>;}
            .text-area input[type="text"], .text-area input[type="number"]{border-radius:0; border:1px solid <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>;}
            .button-area{border:1px solid <?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>;}
            .sweet-alert .btn-no, .sweet-alert .btn-no:hover, .sweet-alert .btn-yes, .sweet-alert .btn-yes:hover{color:#FFFFFF; background:<?php echo $this->config->item("system_colour") ? $this->config->item("system_colour") : "#EE9F2F"; ?>;} 
        </style>
        <!-- start menu -->
        
    </head>
    <body>
        <?php echo $template['partials']['header'] ?>
        <?php echo $template['body'] ?>
        <?php echo $template['partials']['footer'] ?>
        
    </body>
</html>