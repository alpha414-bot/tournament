<!DOCTYPE html>
<!--  Last Published: Wed Dec 08 2021 08:58:59 GMT+0000 (Coordinated Universal Time)  -->
<html data-wf-page="61afc720ae046c0a88089b8c" data-wf-site="61afc720ae046c6b14089b8b" lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ucwords(str_replace('-', ' ', str_replace('.', ' ', Route::currentRouteName())))}} | {{$sitename}}</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="{{Request::root()}}/css/normalize.css" rel="stylesheet" type="text/css">
  <link href="{{Request::root()}}/css/webflow.css" rel="stylesheet" type="text/css">
  <link href="{{Request::root()}}/css/tournamentz.webflow.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script type="text/javascript">WebFont.load({  google: {    families: ["Exo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic","Great Vibes:400","PT Sans:400,400italic,700,700italic"]  }});</script>
  <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
  <link href="{{$favicon}}" rel="shortcut icon" type="image/x-icon">
  <link href="{{$favicon}}" rel="apple-touch-icon">
</head>
<body>

  {{$slot}}
  
  <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=61afc720ae046c6b14089b8b" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="{{Request::root()}}/js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
</body>
</html>