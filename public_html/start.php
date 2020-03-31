<?php
include(__DIR__ . '/db.php');

try {
  $conn->query('DROP DATABASE world');
  $conn->query('CREATE DATABASE world');
  $conn->query('USE world');

  $conn->query("
  CREATE TABLE particles (
      id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      m1 INT(11) NOT NULL,
      m2 INT(11) NOT NULL,
      new INT(11) NOT NULL DEFAULT '0',
      `counter` INT(11) NOT NULL DEFAULT '0'
  ) ENGINE=MEMORY
  ");

  //print_r($conn->error_list);

  $conn->query("
  CREATE TABLE `image` (
      `id` INT(11) NOT NULL,
      `m1` INT(11) NOT NULL,
      `counter` INT(11) NOT NULL DEFAULT '0'
  ) ENGINE=MEMORY;
  ");

  $conn->query("INSERT INTO particles (id, m1, m2, new, counter) VALUES (1, 2, 0, 0, 0);");
  $conn->query("INSERT INTO particles (id, m1, m2, new, counter) VALUES (2, 1, 0, 0, 0);");

  $conn->query("
  CREATE PROCEDURE Step()
  BEGIN
      DECLARE _id INT DEFAULT NULL;
      DECLARE _m1 INT;
      DECLARE _m2 INT;
      DECLARE _new INT;

      REPEAT
          SET @_id = NULL;
          SELECT id, m1, m2 INTO @_id, @_m1, @_m2 FROM particles WHERE `new` = 0 LIMIT 1;

          SELECT m1 INTO @_new FROM particles WHERE id = @_m1;
          UPDATE particles SET counter = counter+1 WHERE id = @_m1;
          IF (@_new = @_id OR @_new = @_m1 OR @_new = @_m2) THEN
              INSERT INTO particles (m1,m2,new,counter) VALUES (@_id,0,@_m1,0);
              UPDATE particles SET new=LAST_INSERT_ID() WHERE id = @_id;
          ELSE
              UPDATE particles SET new=@_new WHERE id = @_id;
          END IF;
      UNTIL (@_id IS NULL)
      END REPEAT;

      CALL Shift();
  END
  ");

  $conn->query("
  CREATE PROCEDURE Shift()
  BEGIN
      TRUNCATE image;
      INSERT INTO image (id, m1, counter) SELECT id, m1, counter FROM particles;
      UPDATE particles SET m2 = m1, m1 = new, counter = 0, new = 0;
  END
  ");

  $conn->query("
  CREATE PROCEDURE Clear()
  BEGIN
      TRUNCATE image;
      TRUNCATE particles;
      INSERT INTO particles (id, m1, m2, new, counter) VALUES (1, 2, 0, 0, 0);
      INSERT INTO particles (id, m1, m2, new, counter) VALUES (2, 1, 0, 0, 0);
  END
  ");

} catch (Exception $e) {
  var_dump($e);
}

if ($conn->error_list === []) {
    echo 'World restarted';
    $_SESSION['coords'] = [];
    $_SESSION['coords'][1] = [-1, 1];
    $_SESSION['coords'][2] = [1, -1];
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
} else {
    print_r($conn->error_list);
}



?>
