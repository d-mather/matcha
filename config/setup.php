<?php

include 'database.php';

  try {
      $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = 'Drop DATABASE IF EXISTS matcha;';
      $conn->exec($sql);
      $sql = 'CREATE DATABASE IF NOT EXISTS matcha;';
      $conn->exec($sql);
      echo "Matcha database created\n";
      $sql = 'use matcha;';
      $conn->exec($sql);
      $sql = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username varchar(30) NOT NULL UNIQUE, fname varchar(30) NOT NULL, lname varchar(30) NOT NULL, password varchar(128) NOT NULL, email varchar(50) NOT NULL UNIQUE, hashed varchar(32) NOT NULL UNIQUE, meta INT NOT NULL DEFAULT '0');";
      $conn->exec($sql);
      echo "users table created\n";
      $sql = "CREATE TABLE IF NOT EXISTS profiles (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username varchar(30) NOT NULL UNIQUE, gender ENUM('male', 'female', 'male+female'), sex_pref ENUM('male', 'female', 'male+female'), age INT, biography varchar(10000), interests TEXT, latitude varchar(20), longitude varchar(20), hidden ENUM('no', 'yes'));";
      $conn->exec($sql);
      echo "profiles table created\n";
      $sql = 'CREATE TABLE IF NOT EXISTS pictures (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE, username varchar(30) NOT NULL, pic_path_and_name varchar(28), pic_number INT);';
      $conn->exec($sql);
      echo "pictures table created\n";
      $sql = 'CREATE TABLE IF NOT EXISTS public (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username varchar(30) NOT NULL UNIQUE, likes INT NOT NULL DEFAULT "0", who_liked TEXT, views INT NOT NULL DEFAULT "0", who_viewed TEXT, blocked TEXT, who_blocked TEXT, visited TEXT);';
      $conn->exec($sql);
      echo "public table created\n";
/*
      if (is_dir('uploads')) {
          function Delete($path)
          {
              if (is_dir($path) === true) {
                  $files = array_diff(scandir($path), array('.', '..'));

                  foreach ($files as $file) {
                      Delete(realpath($path).'/'.$file);
                  }

                  return rmdir($path);
              } elseif (is_file($path) === true) {
                  return unlink($path);
              }

              return false;
          }
          if (Delete('uploads')) {
              echo "uploads directory deleted\n";
          } else {
              echo "uploads directory not deleted\n";
          }
      }
*/
      $a_user = 'admin';
      $a_fname = 'Flip';
      $a_lname = 'Mather';
      $a_passwd = hash('whirlpool', 'admin');
      $a_email = 'yourmom@me.com';
      $a_hashed = md5('admin');
      $a_meta = 2;
      $a_gender = 'male';
      $a_sex_pref = 'female';
      $a_age = 18;
      $a_bio = 'The Boss of this site!';
      $a_interests = '#Flips#FlatCaps#SnapBacks#SugarBay#You#RightBicept#Rats';
      $a_interests = preg_split("/[\s,#]+/", $a_interests);
      $a_interests = array_filter($a_interests);
      $a_interests = implode(", \n", $a_interests);
      $a_pic_path_and_name = './uploads/1.jpg';
      $a_pic_number = 1;

      $sql = $conn->prepare('INSERT IGNORE INTO users (username, fname, lname, password, email, hashed, meta) VALUES (?, ?, ?, ?, ?, ?, ?);');
      $sql->execute([$a_user, $a_fname, $a_lname, $a_passwd, $a_email, $a_hashed, $a_meta]);
      $sql = $conn->prepare('INSERT IGNORE INTO profiles (username, gender, sex_pref, age, biography, interests) VALUES (?, ?, ?, ?, ?, ?);');
      $sql->execute([$a_user, $a_gender, $a_sex_pref, $a_age, $a_bio, $a_interests]);
      $sql = $conn->prepare('INSERT IGNORE INTO public (username) VALUES (?);');
      $sql->execute([$a_user]);
      $sql = $conn->prepare('INSERT IGNORE INTO pictures (username, pic_path_and_name, pic_number) VALUES (?, ?, ?);');
      $sql->execute([$a_user, $a_pic_path_and_name, $a_pic_number]);

      echo "admin user inserted\n";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $v_user = 'asdf';
      $v_fname = 'Angus';
      $v_lname = 'Bromilow';
      $v_passwd = hash('whirlpool', 'asdf');
      $v_email = 'me@guy.com';
      $v_hashed = md5('asdf');
      $v_meta = 2;
      $v_gender = 'male+female';
      $v_sex_pref = 'male+female';
      $v_age = 20;
      $v_bio = 'crazy';
      $v_interests = '#BlondHair#BlackShoes#SeaGulls#Pears#SkyDiving#CuttingPaper';
      $v_interests = preg_split("/[\s,#]+/", $v_interests);
      $v_interests = array_filter($v_interests);
      $v_interests = implode(", \n", $v_interests);
      $v_pic_path_and_name = './uploads/2.jpg';
      $v_pic_number = 1;

      $sql = $conn->prepare('INSERT IGNORE INTO pictures (username, pic_path_and_name, pic_number) VALUES (?, ?, ?);');
      $sql->execute([$v_user, $v_pic_path_and_name, $v_pic_number]);
      $sql = $conn->prepare('INSERT IGNORE INTO users (username, fname, lname, password, email, hashed, meta) VALUES (?, ?, ?, ?, ?, ?, ?);');
      $sql->execute([$v_user, $v_fname, $v_lname, $v_passwd, $v_email, $v_hashed, $v_meta]);
      $sql = $conn->prepare('INSERT IGNORE INTO profiles (username, gender, sex_pref, age, biography, interests) VALUES (?, ?, ?, ?, ?, ?);');
      $sql->execute([$v_user, $v_gender, $v_sex_pref, $v_age, $v_bio, $v_interests]);
      $sql = $conn->prepare('INSERT IGNORE INTO public (username) VALUES (?);');
      $sql->execute([$v_user]);

      echo "asdf user inserted\n";

      $g_user = 'qwer';
      $g_fname = 'Justin';
      $g_lname = 'Bieber';
      $g_passwd = hash('whirlpool', 'qwer');
      $g_email = 'you@guy.com';
      $g_hashed = md5('qwer');
      $g_meta = 2;
      $g_gender = 'male';
      $g_sex_pref = 'female';
      $g_age = 22;
      $g_bio = 'I run like the wind';
      $g_interests = '#StrongArms#BigElbows#Carrots#GolfBalls#Chimneys#MickeyMouse';
      $g_interests = preg_split("/[\s,#]+/", $g_interests);
      $g_interests = array_filter($g_interests);
      $g_interests = implode(", \n", $g_interests);
      $g_pic_path_and_name = './uploads/3.jpg';
      $g_pic_number = 1;

      $sql = $conn->prepare('INSERT IGNORE INTO pictures (username, pic_path_and_name, pic_number) VALUES (?, ?, ?);');
      $sql->execute([$g_user, $g_pic_path_and_name, $g_pic_number]);
      $sql = $conn->prepare('INSERT IGNORE INTO users (username, fname, lname, password, email, hashed, meta) VALUES (?, ?, ?, ?, ?, ?, ?);');
      $sql->execute([$g_user, $g_fname, $g_lname, $g_passwd, $g_email, $g_hashed, $g_meta]);
      $sql = $conn->prepare('INSERT IGNORE INTO profiles (username, gender, sex_pref, age, biography, interests) VALUES (?, ?, ?, ?, ?, ?);');
      $sql->execute([$g_user, $g_gender, $g_sex_pref, $g_age, $g_bio, $g_interests]);
      $sql = $conn->prepare('INSERT IGNORE INTO public (username) VALUES (?);');
      $sql->execute([$g_user]);

      echo "qwer user inserted\n";

      $c_user = 'zxcv';
      $c_fname = 'Emma';
      $c_lname = 'Watson';
      $c_passwd = hash('whirlpool', 'zxcv');
      $c_email = 'it@girl.com';
      $c_hashed = md5('zxcv');
      $c_meta = 2;
      $c_gender = 'female';
      $c_sex_pref = 'male';
      $c_age = 24;
      $c_bio = 'really sexy';
      $c_interests = '#Flips#SugarBayCounsellors#Coders#TheMoon#Lamas#Tacos';
      $c_interests = preg_split("/[\s,#]+/", $c_interests);
      $c_interests = array_filter($c_interests);
      $c_interests = implode(", \n", $c_interests);
      $c_pic_path_and_name = './uploads/4.jpg';
      $c_pic_number = 1;

      $sql = $conn->prepare('INSERT IGNORE INTO pictures (username, pic_path_and_name, pic_number) VALUES (?, ?, ?);');
      $sql->execute([$c_user, $c_pic_path_and_name, $c_pic_number]);
      $sql = $conn->prepare('INSERT IGNORE INTO users (username, fname, lname, password, email, hashed, meta) VALUES (?, ?, ?, ?, ?, ?, ?);');
      $sql->execute([$c_user, $c_fname, $c_lname, $c_passwd, $c_email, $c_hashed, $c_meta]);
      $sql = $conn->prepare('INSERT IGNORE INTO profiles (username, gender, sex_pref, age, biography, interests) VALUES (?, ?, ?, ?, ?, ?);');
      $sql->execute([$c_user, $c_gender, $c_sex_pref, $c_age, $c_bio, $c_interests]);
      $sql = $conn->prepare('INSERT IGNORE INTO public (username) VALUES (?);');
      $sql->execute([$c_user]);

      echo "zxcv user inserted\n";

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  } catch (PDOException $e) {
      echo 'Connection failed: '.$e->getMessage();
  }
