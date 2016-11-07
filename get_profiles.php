<?php

include 'config/database.php';
session_start();

try {
    $login = $_SESSION['logged_on_user'];
    $users_array = array();
    $user_tmp_array = array();
    $who_liked = array();
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, fname, lname, meta FROM `users`');
    $sql->execute();
    while ($result1 = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result1['username'] == $login || $result1['meta'] != 2) {
            continue;
        } else {
            $tmp_array = array('username' => $result1['username'], 'fname' => $result1['fname'], 'lname' => $result1['lname']);
            $user_tmp_array = array_merge($user_tmp_array, $tmp_array);

            $sql1 = $conn->prepare('SELECT username, gender, sex_pref, age, biography, interests FROM `profiles`');
            $sql1->execute();
            while ($result2 = $sql1->fetch(PDO::FETCH_ASSOC)) {
                if ($login == $result2['username']) {
                    continue;
                } elseif ($result2['username'] == $result1['username']) {
                    $tmp_array = array('gender' => $result2['gender'], 'sex_pref' => $result2['sex_pref'],
                    'age' => $result2['age'], 'biography' => $result2['biography'], 'interests' => $result2['interests'], );
                    $user_tmp_array = array_merge($user_tmp_array, $tmp_array);
                    break;
                }
            }

            $sql2 = $conn->prepare('SELECT username, pic_path_and_name, pic_number FROM `pictures`');
            $sql2->execute();
            while ($result3 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                if ($login == $result3['username'] || $result3['pic_number'] != 1) {
                    continue;
                } elseif ($result3['username'] == $result1['username'] && $result3['pic_number'] == 1) {
                    $tmp_array_pic = array('pic_path_and_name' => $result3['pic_path_and_name']);
                    $user_tmp_array = array_merge($user_tmp_array, $tmp_array_pic);
                    break;
                }
            }
            if (!$tmp_array_pic) {
                $tmp_array_pic = array('pic_path_and_name' => '');
                $user_tmp_array = array_merge($user_tmp_array, $tmp_array_pic);
            }
            $tmp_array_pic = null;
            $sql2 = $conn->prepare('SELECT username, likes, views, who_liked, blocked, who_blocked, visited FROM `public`');
            $sql2->execute();
            while ($result4 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                if ($login == $result4['username']) {
                    continue;
                } elseif ($result4['username'] == $result1['username']) {
                    if (!$result4['who_liked']) {
                        $result4['who_liked'] = '';
                    }
                    if (!$result4['blocked']) {
                        $result4['blocked'] = '';
                    }
                    if (!$result4['who_blocked']) {
                        $result4['who_blocked'] = '';
                    }
                    $tmp_array = array('likes' => $result4['likes'], 'views' => $result4['views'], 'who_liked' => $result4['who_liked'], 'blocked' => $result4['blocked'], 'who_blocked' => $result4['who_blocked'], 'visited' => $result4['visited']);
                    $user_tmp_array = array_merge($user_tmp_array, $tmp_array);
                    break;
                }
            }
            $users_array[$result1['username']] = $user_tmp_array;
        }
    }
    $own_user_pro_pic = $_SESSION['pro_pic'];
    $logged_on_user = $_SESSION['logged_on_user'];
    $response = array('status' => true, 'users_array' => $users_array, 'own_user_pro_pic' => $own_user_pro_pic, 'logged_on_user' => $logged_on_user);
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
