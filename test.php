<html>
 <head>
  <title>Тестируем PHP</title>
 </head>
 <body>
 <?php echo '<p>Привет, мир!</p>'; 
 $now = new DateTime();
 echo $now->format('Y-m-d H:i:s');    // MySQL datetime format
?>
 </body>
</html>