
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
      <?php if (function_exists('get_custom_title_tag')) {
                    echo(get_custom_title_tag());
} else {
    get_page_clean_title();
    echo"&nbsp;&mdash;&nbsp;";
    get_site_name();
}?>
    </title>

    <!-- MDL
      The library relies on Google's Material Design fonts, icons, and the CSS
      of Google's Material Design Lite implementation. Load these as follows.
    -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500|Roboto+Mono|Roboto+Condensed:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?php get_theme_url(); ?>/css/material.min.css" />
    <link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clq.css" type="text/css">
    <link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clqless.css" type="text/css">

  </head>
  <body>
    <!-- elm -->
    <script src="<?php get_theme_url(); ?>/js/elm.js"></script>
    <script>
      var app = Elm.Main.fullscreen({"siteUrl" : '<?php get_site_url(); ?>'});
    /*  app.ports.getSlug.subscribe(function (test) {
        console.log(test);
      setTimeout(function(){ app.ports.slug.send("<?php get_page_title(); ?>"); }, 0);
    });*/
      window.app = app;
    </script>
  </body>
