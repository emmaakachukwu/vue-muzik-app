<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-Requested-With, Authorrization, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");

require_once "./classfile.php";
$sql = new sqlops;
$validate = new validate;
define( 'post', json_decode(file_get_contents('php://input'), true) );
$file = $_FILES;
define('key', post['key']);

if ( isset($file['cover']) ) {
    $target_dir = "/opt/lampp/htdocs/vue-muzik-app/muzik-app/src/assets/covers/";
    $target_dir2 = "/opt/lampp/htdocs/vue-muzik-app/muzik-app-admin/src/assets/covers/";
    $target_file = $target_dir . basename($_FILES["cover"]["name"]);
    $fileType = strtolower( pathinfo($target_file,PATHINFO_EXTENSION) );
    $filename = 'cover-'.substr(MD5($file['cover']['name'] ), 0, 15).'.'.$fileType;
    $path = $target_dir.$filename;
    $path2 = $target_dir2.$filename;
    $formats = ['jpg', 'png', 'jpeg', 'jfif'];

    if ( !in_array($fileType, $formats) ) {
        $code = 10; $msg = "Please choose a valid photo";
    } else if ( getimagesize($file["cover"]["tmp_name"]) == false ) {
        $code = 10; $msg = 'File is not an image. Please choose an image';
    } else if ( $file["cover"]["size"] > 500000 ) { //check image size. limit size is 2mb
        $code = 10; $msg = 'Photo should not be higher than 500kb';
    } else {
        if ( move_uploaded_file($file['cover']['tmp_name'], $path) && copy($path, $path2) ) {
            $code = 11; $msg = $filename;
        } else {
            $code = 10; $message = "Sorry, there was an error uploading the cover photo. Try again";
        }
    }
}

if ( isset($file['audio']) ) {
    $target_dir = "/opt/lampp/htdocs/vue-muzik-app/muzik-app/src/assets/audios/";
    $target_file = $target_dir . basename($_FILES["audio"]["name"]);
    // $fileType = strtolower( explode('/', $file['audio']['type'])[1] );
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $filename = 'audio-'.substr(MD5($file['audio']['name']), 0, 20) . '.' . $fileType;
    $path = $target_dir.$filename;

    if ( explode('/', $file['audio']['type'])[0] != 'audio' ) {
        $code = 10; $msg = "Please choose a valid audio file";
    } else {
        if ( move_uploaded_file( $file['audio']['tmp_name'], $path) ) {
            $code = 11; $msg = $filename;
        } else {
            $code = 10; $message = "Sorry, there was an error uploading the audio file. Try again";
        }
    }
}

// declare variables of variables from the keys
if ( post ) {
    foreach (post as $key => $value) {
        $$key = $value;
    }
}

// fetch files where deleted is 0; 0 = not deleted; 1 = deleted
if ( key == 101 ) {
    $col = '*';
    $tab = 'files';
    $where = " WHERE deleted = 0 ";
    if ( $res = $sql->fetch_assoc($col, $tab, $where) ) {
        $code = 11; $msg = $res;
    } else {
        $code = 10; $msg = $res;
    }
}

//for fetching muzik details
if ( key == 102 ) {
    $col = '*';
    $tab = 'files';
    $where = " WHERE title = '$title' && artiste = '$artiste' && deleted = 0 ";
    if ( $res = $sql->select_fetch($col, $tab, $where) ) {
        $code = 11; $msg = $res;
    } else {
        $code = 10; $msg = $res;
    }
}

//user signup
if ( key == 103 ) {
    foreach ( post as $key => $value ) {
        if ( (empty(str_replace(" ", "", $value)) && $key != 'password' && $key != 'cpassword') || (empty($password) || empty($cpassword)) ) {
            $code = 10; $msg = "All fields are required";
        } else {
            if ( $key == 'email' && !$validate->validateemail($value) ) {
                $code = 10; $msg = "Enter a valid email address";
            } else if( $key == 'username' && !$validate->validatealnum($value) && preg_match("/\s/", $value) ) {
                $code = 10; $msg = "Username should not contain characters and spaces";
            } else if ( $key == 'password' ) {
                if ( strlen($value) < 6 ) {
                    $code = 10; $msg = "Password must be at least six characters long";
                } else if ( $value !== $cpassword ) {
                    $code = 10; $msg = "Passwords do not match";
                }                
            }
        }
    }
    if ( !isset($code) ) {
        $col = 'email';
        $col2 = 'username';
        $tab = 'users';
        $where = " WHERE email = '$email' ";
        $where2 = " WHERE username = '$username' ";
        if ( $sql->select($col, $tab, $where) ) {
            $code = 10; $msg = "Account already exist";
        } else if ( $sql->select($col2, $tab, $where2) ) {
                $code = 10; $msg = "Username already taken";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $cols = 'email, username, password';
            $vals = " '$email', '$username', '$password' ";
            if ( $sql->insert($tab, $cols, $vals) ) {
                $code = 11; $msg = 'Signup Successfull';
            } else {
                $code = 10; $msg = 'Signup Error; retry';
            }
        }
    }
}

//user login
if ( key == 104 ) {
    foreach (post as $key => $value) {
        if ( (empty(str_replace(" ", "", $value)) && $key != 'password') || (empty($password)) ) {
            $code = 10; $msg = "All fields are required";
        }
    }
    if ( !isset($code) ) {
        $col = 'email, password';
        $tab = 'users';
        $where = " WHERE email = '$user' || username = '$user' ";
        $res = $sql->select_fetch($col, $tab, $where);
        if ( $res && password_verify($password, $res->password) ) {
            $code = 11; $msg = 'Login successfull';
        } else {
            $code = 10; $msg = 'Invalid login';
        }
    }
}

// for admin-login
if ( key == 201 ) {
    foreach( post as $key => $value ) {
        if ( empty($value) ) {
            $code = 10; $msg = "All fields are required";
        }
    }
    // if no errors ...
    if ( !isset($code) ) {
        $col = 'email, password';
        $tab = 'admins';
        $where = " WHERE email = '$user' || username = '$user' ";
        if ( $res = $sql->select_fetch($col, $tab, $where) ) {
            if ( $password === $res->password ) {
                $code = 11; $msg = 'Login Successful';
            } else {
                $code = 10; $msg = "Invalid login";
            }
        } else {
            $code = 10; $msg = "Invalid login";
        }
    }
}

// for muzik upload
if ( key == 202 ) {
    $required = ['title', 'artiste', 'audio'];
    foreach ( post as $key => $value ) {
        if ( in_array($key, $required) ) {
            if ( empty($value) ) {
                $code = 10; $msg = ucwords($key). ' is required';
            }
        }
    }
    if ( !isset($code) ) {
        $col = 'title';
        $tab = 'files';
        $set = " title = '$title', artiste = '$artiste', featured = '$featured', info = '$info', cover_path = '$cover' ";
        $where = !isset($id) ? " WHERE title = '$title' && artiste = '$artiste' " : " WHERE title = '$title' && id != '$id' ";
        if ( $sql->select($col, $tab, $where) ) {
            $code = 10; $msg = 'Muzik already exists';
        } else {
            if ( !isset($id) ) {
                $cols = " title, artiste, featured, info, uploaded_by, audio_path, cover_path";
                $vals = " '$title', '$artiste', '$featured', '$info', '$uploaded_by', '$audio', '$cover' ";
                if ( $sql->insert($tab, $cols, $vals) ) {
                    $code = 11; $msg = "Muzik uploaded";
                } else {
                    $code = 10; $msg = "Muzik upload error; retry";
                }
            } else {
                $where = " WHERE id = '$id' ";
                if ( $sql->update($tab, $set, $where) ) {
                    $code = 11; $msg = "Muzik has been updated successfully";
                } else {
                    $code = 10; $msg = "Muzik update error; retry";
                }
            }
        }
    }
}

// to delete muzik
if ( key == 203 ) {
    $tab = 'files';
    $col = 'uploaded_by';
    $set = " deleted = 1";
    $where = " WHERE uploaded_by = '$email' && id = '$id' ";
    if ( $sql->select($col, $tab, $where) ) {
        $where = " WHERE id = '$id' ";
        if ( $sql->update($tab, $set, $where) ) {
            $code = 11; $msg = "Muzik has been deleted";
        } else {
            $code = 10; $msg = "error; retry";
        }
    } else {
        $code = 10; $msg = " You do not have the permission to delete this file as you did not upload it";
    }
}

//to fetch news
if ( key == 204 ) {
    $col = '*';
    $tab = 'news';
    $where = " WHERE deleted = 0 ";
    if ( $res = $sql->fetch_assoc($col, $tab, $where) ) {
        $code = 11; $msg = $res;
    } else {
        $code = 10; $msg = $res;
    }
}

// for uploading news
if ( key == 205 ) {
    // die(var_dump(post));
    $required = ['title', 'info'];
    foreach ( post as $key => $value ) {
        if ( in_array($key, $required) ) {
            if ( empty($value) ) {
                $code = 10; $msg = ucwords($key). ' is required';
            }
        }
    }
    if ( !isset($code) ) {
        $col = 'title';
        $tab = 'news';
        $set = " title = '$title', info = '$info', cover = '$cover' ";
        $where = !isset($id) ? " WHERE title = '$title' && info = '$info' " : " WHERE title = '$title' && id != '$id' ";
        if ( $sql->select($col, $tab, $where) ) {
            $code = 10; $msg = 'News already uploaded';
        } else {
            if ( !isset($id) ) {
                $cols = " title, info, uploaded_by, cover";
                $vals = " '$title', '$info', '$uploaded_by', '$cover' ";
                if ( $sql->insert($tab, $cols, $vals) ) {
                    $code = 11; $msg = "News uploaded";
                } else {
                    $code = 10; $msg = "News upload error; retry";
                }
            } else {
                $where = " WHERE id = '$id' ";
                if ( $sql->update($tab, $set, $where) ) {
                    $code = 11; $msg = "News has been updated successfully";
                } else {
                    $code = 10; $msg = "News update error; retry";
                }
            }
        }
    }
}

// to delete news
if ( key == 206 ) {
    $tab = 'news';
    $col = 'uploaded_by';
    $set = " deleted = 1";
    $where = " WHERE uploaded_by = '$email' && id = '$id' ";
    if ( $sql->select($col, $tab, $where) ) {
        $where = " WHERE id = '$id' ";
        if ( $sql->update($tab, $set, $where) ) {
            $code = 11; $msg = "News has been deleted";
        } else {
            $code = 10; $msg = "error; retry";
        }
    } else {
        $code = 10; $msg = " You do not have the permission to delete this file as you did not upload it";
    }
}

// fetch news details
if ( key == 105 ) {
    $col = '*';
    $tab = 'news';
    $where = " WHERE title = '$title' && deleted = 0 ";
    if ( $res = $sql->select_fetch($col, $tab, $where) ) {
        $code = 11; $msg = $res;
    } else {
        $code = 10; $msg = $res;
    }
}

// fetch both news and files
if ( key == 106 ) {
    $col = '*';
    $tab = 'files';
    $tab2 = 'news';
    $where = " WHERE deleted = 0 ";
    if ( ($res = $sql->fetch_assoc($col, $tab, $where)) && ($res2 = $sql->fetch_assoc($col, $tab2, $where)) ) {
        foreach ($res2 as $key => $value) {
            array_push($res, $value);
        }
        $code = 11; $msg = $res;
    } else {
        $code = 10; $msg = null;
    }
}

echo json_encode( ['code' => $code, 'msg' => $msg] );