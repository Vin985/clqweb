
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>The elm-mdl library</title>

    <!-- MDL
      The library relies on Google's Material Design fonts, icons, and the CSS
      of Google's Material Design Lite implementation. Load these as follows.
    -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500|Roboto+Mono|Roboto+Condensed:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.0/material.light_blue-light_green.min.css" />
    <link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clq.css" type="text/css">

  </head>
  <body>
    <!-- elm -->
    <script src="<?php get_theme_url(); ?>/js/elm.js"></script>
    <script>
      var nav = JSON.parse('<?php echo json_encode(return_i18n_menu_data(return_page_slug(), 0, 0, I18N_SHOW_MENU)); ?>');
      var app = Elm.Main.fullscreen({"tabs": nav, "siteurl" : '<?php get_site_url(); ?>'});
    /*  app.ports.getSlug.subscribe(function (test) {
        console.log(test);
      setTimeout(function(){ app.ports.slug.send("<?php get_page_title(); ?>"); }, 0);
    });*/
      window.app = app;
    </script>
  </body>
