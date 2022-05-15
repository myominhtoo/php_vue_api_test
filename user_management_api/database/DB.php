<?php
  $db = new PDO("mysql:host=localhost:3306;dbname=user_management","lionel","Mmh28803#",[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
  ]);