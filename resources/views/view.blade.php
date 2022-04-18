<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8"/>
      <title>{{\App\Models\Link::where('tournament_id', $id)->first()->tournament_name}}</title>
      <meta content="width=device-width, initial-scale=1" name="viewport"/>
      <meta content="Webflow" name="generator"/>
      <link href="https://uploads-ssl.webflow.com/61ae575033cd4347e860ff19/css/calandax.webflow.6d1e67609.css" rel="stylesheet" type="text/css"/>
      <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif]--><script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
      <link href="https://uploads-ssl.webflow.com/img/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
      <link href="https://uploads-ssl.webflow.com/img/webclip.png" rel="apple-touch-icon"/>
   </head>
   <body>
      <div class="x-framecontain">
         <div class="w-embed w-iframe"><iframe src="https://tournej.it/showit.php?id={{$id}}" class="frame" frameborder="0"></iframe></div>
      </div>
      <div class="frame"></div>
      <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=61ae575033cd4347e860ff19" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://uploads-ssl.webflow.com/61ae575033cd4347e860ff19/js/webflow.f0a5689e5.js" type="text/javascript"></script><!--[if lte IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]--><script>
         document.getElementById('width').innerHTML = screen.width;
      </script>
   </body>
</html>