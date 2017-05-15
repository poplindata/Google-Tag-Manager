# Google-Tag-Manager
Simple includes for dataLayer and Google Tag Manager scripts


#### Google Tag Manager
 * Replace ```XXXXX``` with your GTM ID. 
 
#### dataLayer
 Wordpress Template specific schema mapping to dataLayer
 
  * replace line 34 with ```<my_ip_address>```
 
#### Layout

```javascript
<head>
...
  <script>
    dataLayer = [{
      'x': 'x',
      'y': 'y'
    }];
  </script>
  ...
</head>
<body>
  <!-- Google Tag Manager -->
  ...
  <!-- End Google Tag Manager -->
...
```