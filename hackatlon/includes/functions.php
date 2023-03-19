<?php
//helper functions
function set_message($msg)
{
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

class Bcrypt
{
    private $rounds;

    public function __construct($rounds = 12)
    {
        if (CRYPT_BLOWFISH != 1) {
            throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt");
        }

        $this->rounds = $rounds;
    }

    public function hash($input)
    {
        $hash = crypt($input, $this->getSalt());

        if (strlen($hash) > 13)
            return $hash;

        return false;
    }

    public function verify($input, $existingHash)
    {
        $hash = crypt($input, $existingHash);

        return $hash === $existingHash;
    }

    private function getSalt()
    {
        $salt = sprintf('$2a$%02d$', $this->rounds);

        $bytes = $this->getRandomBytes(16);

        $salt .= $this->encodeBytes($bytes);

        return $salt;
    }

    private $randomState;

    private function getRandomBytes($count)
    {
        $bytes = '';

        if (function_exists('openssl_random_pseudo_bytes') &&
            (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL is slow on Windows
            $bytes = openssl_random_pseudo_bytes($count);
        }

        if ($bytes === '' && is_readable('/dev/urandom') &&
            ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
            $bytes = fread($hRand, $count);
            fclose($hRand);
        }

        if (strlen($bytes) < $count) {
            $bytes = '';

            if ($this->randomState === null) {
                $this->randomState = microtime();
                if (function_exists('getmypid')) {
                    $this->randomState .= getmypid();
                }
            }

            for ($i = 0; $i < $count; $i += 16) {
                $this->randomState = md5(microtime() . $this->randomState);

                if (PHP_VERSION >= '5') {
                    $bytes .= md5($this->randomState, true);
                } else {
                    $bytes .= pack('H*', md5($this->randomState));
                }
            }

            $bytes = substr($bytes, 0, $count);
        }

        return $bytes;
    }

    private function encodeBytes($input)
    {
        // The following is code from the PHP Password Hashing Framework
        $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $output = '';
        $i = 0;
        do {
            $c1 = ord($input[$i++]);
            $output .= $itoa64[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
                $output .= $itoa64[$c1];
                break;
            }

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 4;
            $output .= $itoa64[$c1];
            $c1 = ($c2 & 0x0f) << 2;

            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 6;
            $output .= $itoa64[$c1];
            $output .= $itoa64[$c2 & 0x3f];
        } while (true);

        return $output;
    }
}

function set_message_success($msg)
{
    if (!empty($msg)) {
        $_SESSION['messageSuccess'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function display_message_success()
{
    if (isset($_SESSION['messageSuccess'])) {
        echo $_SESSION['messageSuccess'];
        unset($_SESSION['messageSuccess']);
    }
}

function last_id()
{
    global $connection;
    return mysqli_insert_id($connection);
}

function redirect($location)
{
    header("Location: $location");
}

function query($sql)
{
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die("Query Failed " . mysqli_error($connection));
    }
}

function escape_string($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result)
{
    return mysqli_fetch_array($result);
}

function login_admin()
{
    if (isset($_POST['submit'])) {
        $username = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
        $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'");
        confirm($query);
        if (mysqli_num_rows($query) == 0) {
            set_message("Your Password or Username is incorrect");
            redirect("admin.php");
        } else {
            $_SESSION['username'] = $username;
            redirect("admin/index.php");
        }
    }
}

function login_company()
{
//    if (isset($_POST['submit'])) {
//        $username = escape_string($_POST['email']);
////        $password = md5($_POST['password']);
//        $password = $_POST['password'];
//        $query = query("SELECT * FROM company WHERE username = '{$username}' AND password = '{$password}'");
//        confirm($query);
//        $row = fetch_array($query);
//        if (mysqli_num_rows($query) == 0) {
//            set_message("Your Password or Username is incorrect");
//            redirect("login.php");
//        } else {
//            $_SESSION['username'] = $username;
//            $_SESSION['companyId'] = $row['id'];
//            redirect("index.php");
//        }
//    }


    if (isset($_COOKIE["username"]) && isset($_COOKIE["rememberMeToken"])) {

        $queryRememberMe = query("SELECT * FROM company WHERE email = '{$_COOKIE["username"]}' AND remember_me_token = '{$_COOKIE["rememberMeToken"]}'");
        confirm($queryRememberMe);
        if (mysqli_num_rows($queryRememberMe) == 1) {
            $_SESSION['username'] = $_COOKIE["username"];
            $rowRememberMe = fetch_array($queryRememberMe);
            $_SESSION['company_id'] = $rowRememberMe['id'];
            redirect("index.php");
        } else {
            $queryRememberMe = query("UPDATE company SET remember_me_token = '' where email = '{$_COOKIE["username"]}'");
            confirm($queryRememberMe);
        }


    }
    if (isset($_POST['submit'])) {
        $email = escape_string($_POST['email']);
        $password = md5($_POST['password']);
        $query = query("SELECT * FROM company WHERE email = '{$email}' AND password = '{$password}'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {

            set_message("Your Password or Email is incorrect");

        } else {
            $row = fetch_array($query);
            $_SESSION['username'] = $email;
            $_SESSION['company_id'] = $row['id'];
            $nfcId = $row['id'];
            if (!empty($_POST['remember'])) {
                $bcrypt = new Bcrypt(15);
                $hash = $bcrypt->hash($password);
                $isGood = $bcrypt->verify($password, $hash);
                if ($isGood) {
                    $queryRememberMe = query("UPDATE company SET remember_me_token = '{$hash}' where id = $nfcId ");
                    confirm($queryRememberMe);
                    setcookie("username", $email, time() + (10 * 365 * 24 * 60 * 60), '/');
                    setcookie("rememberMeToken", $hash, time() + (10 * 365 * 24 * 60 * 60), '/');
                } else {
                    redirect("login2.php");
                }

            } else {
                if (isset($_COOKIE["username"])) {
                    setcookie("username", "", -1, '/');
                }
                if (isset($_COOKIE["rememberMeToken"])) {
                    setcookie("rememberMeToken", "", -1, '/');
                }
            }

            redirect("index.php");
        }
    }
}

function cookie_destroy($nfcId)
{
    $queryRememberMe = query("UPDATE contact SET remember_me_token = '' where id = $nfcId ");
    confirm($queryRememberMe);
    if (isset($_COOKIE["username"])) {
        setcookie('username', null, -1, '/');
    }
    if (isset($_COOKIE["rememberMeToken"])) {
        setcookie('rememberMeToken', null, -1, '/');
    }
}

function login_user()
{
    if (isset($_COOKIE["username"]) && isset($_COOKIE["rememberMeToken"])) {

        $queryRememberMe = query("SELECT * FROM contact WHERE email = '{$_COOKIE["username"]}' AND remember_me_token = '{$_COOKIE["rememberMeToken"]}'");
        confirm($queryRememberMe);
        if (mysqli_num_rows($queryRememberMe) == 1) {
            $_SESSION['username'] = $_COOKIE["username"];
            $rowRememberMe = fetch_array($queryRememberMe);
            $_SESSION['nfc_id'] = $rowRememberMe['id'];
            redirect("users/index.php");
        } else {
            $queryRememberMe = query("UPDATE contact SET remember_me_token = '' where email = '{$_COOKIE["username"]}'");
            confirm($queryRememberMe);
        }


    }
    if (isset($_POST['submit'])) {
        $email = escape_string($_POST['email']);
        $password = md5($_POST['password']);
        $query = query("SELECT * FROM contact WHERE email = '{$email}' AND password = '{$password}'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            $query = query("SELECT * FROM contact WHERE email = '{$email}'");
            confirm($query);
            $row = fetch_array($query);
            $passwordGoogle = $row['password'];
            if ($passwordGoogle == "Google") {
                set_message("Your Email is Connected with Google");
            } else {
                set_message("Your Password or Email is incorrect");
            }


        } else {
            $row = fetch_array($query);
            $_SESSION['username'] = $email;
            $_SESSION['nfc_id'] = $row['id'];
            $nfcId = $row['id'];
            if (!empty($_POST['remember'])) {
                $bcrypt = new Bcrypt(15);
                $hash = $bcrypt->hash($password);
                $isGood = $bcrypt->verify($password, $hash);
                if ($isGood) {
                    $queryRememberMe = query("UPDATE contact SET remember_me_token = '{$hash}' where id = $nfcId ");
                    confirm($queryRememberMe);
                    setcookie("username", $email, time() + (10 * 365 * 24 * 60 * 60), '/');
                    setcookie("rememberMeToken", $hash, time() + (10 * 365 * 24 * 60 * 60), '/');
                } else {
                    redirect("login");
                }

            } else {
                if (isset($_COOKIE["username"])) {
                    setcookie("username", "", -1, '/');
                }
                if (isset($_COOKIE["rememberMeToken"])) {
                    setcookie("rememberMeToken", "", -1, '/');
                }
            }

            redirect("users/index.php");
        }
    }
}

function register_login_user()
{

    if (isset($_POST['submit'])) {
        $email = escape_string($_POST['email']);
        $password = md5($_POST['password']);
        $query = query("SELECT * FROM contact WHERE email = '{$email}' AND password = '{$password}'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            $query = query("SELECT * FROM contact WHERE email = '{$email}'");
            confirm($query);
            $row = fetch_array($query);
            $passwordGoogle = $row['password'];
            if ($passwordGoogle == "Google") {
                set_message("Your Email is Connected with Google");
                ("login.php");
            } else {
                set_message("Your Password or Email is incorrect");
                redirect("login.php");
            }
        } else if (!isset($_SESSION['cardToken'])) {
            set_message("Please scan the card before setup profile");
            redirect("register_card");
        } else {
            $row = fetch_array($query);
            $_SESSION['username'] = $email;
            $_SESSION['nfc_id'] = $row['id'];
            $user_token = $row['token'];
            $card_token = $_SESSION['cardToken'];

            $query_parent = query("SELECT * FROM card_contact_mapping WHERE card_token = '$card_token'");
            confirm($query_parent);
            $is_taken = "No";
            if (mysqli_num_rows($query_parent) > 0) {
                $is_taken = "Yes";
            }
            $query = query("INSERT INTO card_contact_mapping (user_token,card_token) VALUES('$user_token','$card_token')");
            confirm($query);
//            unset($_SESSION['cardToken']);

            redirect("token/$card_token");
        }
    }
}


//++++++++++++++++++++++++
// HOMEPAGE SLIDER ARTICLES
//++++++++++++++++++++++++

//++++++++++++++++++++++++
// ADMIN FUNCTIONS
//++++++++++++++++++++++++
function display_aliat_form()
{
    $query = query("SELECT * FROM aliatform");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $Nume = $row['Nume'];
        $Prenume = $row['Prenume'];
        $Telefon = $row['Telefon'];
        $Email = $row['Email'];
        $Oras = $row['Oras'];
        $Masina = $row['Masina'];
        $Date = $row['Date'];
        $letter = mb_substr($Prenume, 0, 1);
        $contact_form = <<<DELIMETER
      <tr>
      <td>
          <a class="d-flex align-items-center" href="mailto:$Email">
            <div class="avatar avatar-soft-primary avatar-circle">
              <span class="avatar-initials">$letter</span>
            </div>
            <div class="ms-3">
              <span class="d-block h5 text-inherit mb-0">$Nume $Prenume</span>
              <span class="d-block fs-5 text-body">$Email</span>
            </div>
          </a>
        </td>
        <td>$Telefon</td>
        <td>$Oras</td>
        <td>$Masina</td>
        <td>
        $Date
        </td>
      <td>
          <a class="btn btn-warning" href="tel:$Telefon"><i class="fa fa-phone" aria-hidden="true"></i></a>
          <a class="btn btn-danger" href="index.php?form_delete&id={$row['id']}"><i class="fa fa-trash"></i></a>
      </td>
      </tr>
DELIMETER;
        echo $contact_form;
    }
}

function display_contact_forms()
{
    $query = query("SELECT * FROM product_request_contact");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $message = $row['message'];
        $contact_form = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$email <br> $phone</td>
        <td>$message</td>
        <td>
            <a class="btn btn-warning" href="mailto:$email"><i class="fa fa-reply"></i></a>
            <a class="btn btn-danger" href="index.php?delete_contact_form&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $contact_form;
    }
}

function display_product_request()
{
    $query = query("SELECT * FROM product_request_contact");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $message = $row['message'];
        $contact_form = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$email <br> $phone</td>
        <td>$message</td>
        <td>
            <a class="btn btn-warning" href="mailto:$email"><i class="fa fa-reply"></i></a>
            
        </td>
        </tr>
DELIMETER;
        echo $contact_form;
    }
}

function display_company()
{
    $query = query("SELECT * FROM company");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $username = $row['username'];
        $companyName = $row['companyName'];

        $features = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$username</td>
        <td>$companyName</td>
        <td>
            <a class="btn btn-warning" href="index.php?company_edit&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?company_delete&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $features;
    }
}

function add_company()
{
    if (isset($_POST['add_company'])) {
        $username = escape_string($_POST['username']);
        $companyName = escape_string($_POST['companyName']);
        $password = md5($_POST['password']);

        $query = query("INSERT INTO company (username,password,companyName) VALUES('$username','$password','$companyName')");
        confirm($query);
        set_message("Company has been added");
        redirect("index.php?company");
    }
}


function display_features()
{
    $query = query("SELECT * FROM features");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title = $row['title'];
        $lead = $row['lead'];
        $icon = $row['icon'];
        $features = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title</td>
        <td>$icon</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_features&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_features&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $features;
    }
}

function add_features()
{
    if (isset($_POST['add_features'])) {
        $Title = escape_string($_POST['title']);
        $Lead = escape_string($_POST['lead']);
        $Icon = escape_string($_POST['icon']);
        $query = query("INSERT INTO features (title,lead,icon) VALUES('$Title','$Lead','$Icon')");
        confirm($query);
        set_message("Features has been added");
        redirect("index.php?features");
    }
}

function display_steps()
{
    $query = query("SELECT * FROM steps");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title = $row['title'];
        $lead = $row['lead'];
        $image = $row['image'];
        $steps = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title</td>
        <td><img src="../uploads/steps/$image" width="100"></td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_steps&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_steps&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $steps;
    }
}

function add_steps()
{
    if (isset($_POST['add_steps'])) {
        $Title = escape_string($_POST['title']);
        $Lead = escape_string($_POST['lead']);

        $image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($image_temp, "../uploads/steps/$image");

        $query = query("INSERT INTO steps(title,lead,image) VALUES('$Title','$Lead','$image')");
        confirm($query);
        set_message("Steps has been added");
        redirect("index.php?steps");
    }
}

function display_faq()
{
    $query = query("SELECT * FROM faq");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title = $row['title'];
        $lead = $row['lead'];
        $faq = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title</td>
        <td>$lead</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_faq&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_faq&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $faq;
    }
}

function add_faq()
{
    if (isset($_POST['add_faq'])) {
        $Title = escape_string($_POST['title']);
        $Lead = escape_string($_POST['lead']);
        $query = query("INSERT INTO faq (title,lead) VALUES('$Title','$Lead')");
        confirm($query);
        set_message("Faq has been added");
        redirect("index.php?faq");
    }
}

function display_profiles()
{
    $query = query("SELECT * FROM contact");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $token = $row['token'];
        $profile = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$first_name $last_name</td>
        <td>https://smart-card.io/token/$token</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_profile&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_profile&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $profile;
    }
}

function display_contact()
{
    $query = query("SELECT * FROM contact");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $contact = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_home&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_home&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $contact;
    }
}

function display_job()
{
    $query = query("SELECT * FROM jobs");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $parent_id = $row['parent_id'];
        $job = $row['job'];
        $query_parent = query("SELECT name FROM contact WHERE id = $parent_id");
        confirm($query_parent);
        $parent_name = fetch_array($query_parent);
        $experiences = <<<DELIMETER
      <tr>
      <td>$id</td>
      <td>{$parent_name['name']}</td>
      <td>$job</td>
      <td>

          <a class="btn btn-danger" href="index.php?delete_job&id={$row['id']}"><i class="fa fa-trash"></i></a>
      </td>
      </tr>
DELIMETER;
        echo $experiences;
    }
}

function add_job()
{
    if (isset($_POST['add_job'])) {

        $parent_id = escape_string($_POST['parent_id']);
        $job = escape_string($_POST['job']);
        $job_description = escape_string($_POST['job_description']);
        $date = escape_string($_POST['date']);

        $query = query("INSERT INTO jobs (parent_id,datee,job,job_description) VALUES($parent_id,'$date','$job','$job_description')");
        confirm($query);
        set_message("Job has been added");
        redirect("index.php?job");
    }
}

function display_experiences()
{
    $query = query("SELECT * FROM experiences");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $parent_id = $row['parent_id'];
        $experience = $row['experience'];
        $query_parent = query("SELECT name FROM contact WHERE id = $parent_id");
        confirm($query_parent);
        $parent_name = fetch_array($query_parent);
        $experiences = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>{$parent_name['name']}</td>
        <td>$experience</td>
        <td>
            <a class="btn btn-danger" href="index.php?delete_experiences&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $experiences;
    }
}


function add_experiences()
{
    if (isset($_POST['add_experiences'])) {

        $parent_id = escape_string($_POST['parent_id']);
        $job = escape_string($_POST['job']);
        $percentage = escape_string($_POST['percentage']);

        $query = query("INSERT INTO experiences (parent_id,experience,percentage) VALUES($parent_id,'$job',$percentage)");
        confirm($query);
        set_message("Experience has been added");
        redirect("index.php?experiences");
    }
}

function display_education()
{
    $query = query("SELECT * FROM education");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $parent_id = $row['parent_id'];
        $school = $row['school'];
        $query_parent = query("SELECT name FROM contact WHERE id = $parent_id");
        confirm($query_parent);
        $parent_name = fetch_array($query_parent);
        $experiences = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>{$parent_name['name']}</td>
        <td>$school </td>
        <td>

            <a class="btn btn-danger" href="index.php?delete_education&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $experiences;
    }
}

function add_education()
{
    if (isset($_POST['add_education'])) {

        $parent_id = escape_string($_POST['parent_id']);
        $education = escape_string($_POST['education']);
        $education_description = escape_string($_POST['education_description']);
        $date = escape_string($_POST['date']);

        $query = query("INSERT INTO education (parent_id,datee,school,school_description) VALUES($parent_id,'$date','$education','$education_description')");
        confirm($query);
        set_message("School has been added");
        redirect("index.php?education");
    }
}

function display_project()
{
    $query = query("SELECT * FROM project");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $parent_id = $row['parent_id'];
        $project = $row['title'];
        $query_parent = query("SELECT name FROM contact WHERE id = $parent_id");
        confirm($query_parent);
        $parent_name = fetch_array($query_parent);
        $experiences = <<<DELIMETER
      <tr>
      <td>$id</td>
      <td>{$parent_name['name']}</td>
      <td>$project </td>
      <td>

          <a class="btn btn-danger" href="index.php?delete_project&id={$row['id']}"><i class="fa fa-trash"></i></a>
      </td>
      </tr>
DELIMETER;
        echo $experiences;
    }
}

function add_project()
{
    if (isset($_POST['add_project'])) {

        $parent_id = escape_string($_POST['parent_id']);
        $title = escape_string($_POST['title']);
        $lead = escape_string($_POST['lead']);
        $link = escape_string($_POST['link']);

        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads/nfcproject/$post_image");

        $query = query("INSERT INTO project(parent_id,title,lead,link,image) VALUES($parent_id,'$title','$lead','$link','$post_image')");
        confirm($query);
        set_message("Project has been added");
        redirect("index.php?project");
    }
}

function display_card()
{
    $query = query("SELECT * FROM card");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $card_token = $row['card_token'];
        $type = $row['type'];
        $plan = "standard";
        $printed = $row['printed'];
        if ($type == 1) {
            $plan = "PREMIUM";
        }
        $query_parent = query("SELECT * FROM card_contact_mapping WHERE card_token = '$card_token'");
        confirm($query_parent);
        $is_taken = "No";
        $bold = "";
        if (mysqli_num_rows($query_parent) > 0) {
            $is_taken = "Yes";
            $bold = "font-weight: 900;";
        }
        $style = "";
        $buttoncolor = <<<DELIMETER
      <a class="btn btn-success" href="events/printed_card.php?id={$row['id']}"><i class="fa fa-check"></i></a>
DELIMETER;
        if ($printed == 1) {
            $style = "background-color: #f2f2f2;";
            $buttoncolor = "";
        }
        $experiences = <<<DELIMETER
      <tr style="$style $bold">
      <td>$id</td>
      <td>https://www.smart-card.io/token/$card_token</td>
      <td>$plan</td>
      <td>$is_taken</td>
      <td>
          $buttoncolor
          <a class="btn btn-danger" href="index.php?delete_card&id={$row['id']}"><i class="fa fa-trash"></i></a>
      </td>
      </tr>
DELIMETER;
        echo $experiences;
    }
}

function add_profile()
{
    $id = $_SESSION['nfc_id'];
    update_profile($id, "index.php");
}

function update_profile($id, $redirect_link)
{
    if (isset($_POST['add_profile'])) {
        $query = query("SELECT * FROM contact WHERE id = $id");
        confirm($query);
        $row = fetch_array($query);
        // $image = $row['image'];
        $token = $row['token'];
        $password = $row['password'];
        $username = $row['username'];

        $first_name = escape_string($_POST['first_name']);
        $last_name = escape_string($_POST['last_name']);

        if ($username == null) {
            $username = slugify($first_name . " " . $last_name, true);
            $select_username = query("SELECT * FROM contact WHERE username = '$username' OR username LIKE '$username-%' ");
            confirm($select_username);
            if (mysqli_num_rows($select_username) > 0) {
                $username .= "-" . mysqli_num_rows($select_username);
            }
        }

        if ($password == null) {
            $password = md5("SmartCard2022");
        }

        $first_name = escape_string($_POST['first_name']);
        $last_name = escape_string($_POST['last_name']);
        $title = escape_string($_POST['title']);
        $job = escape_string($_POST['position']);
        $company = escape_string($_POST['company']);
        $description = escape_string($_POST['description']);
        $birthday = escape_string($_POST['birthday']);
//        $country_code = escape_string($_POST['country_code']);

        $phone = escape_string($_POST['phoneall']);

        $email = escape_string($_POST['email']);
        $website = $_POST['website'];
        $country = escape_string($_POST['country']);
        $county = escape_string($_POST['county']);
        $city = escape_string($_POST['city']);
        $street = escape_string($_POST['street']);
        $label = escape_string($_POST['label']);
        $zip = escape_string($_POST['zip']);
        $facebook = escape_string($_POST['facebook']);
        $instagram = escape_string($_POST['instagram']);
        $twitter = escape_string($_POST['twitter']);
        $linkedin = escape_string($_POST['linkedin']);
        $cashlink = escape_string($_POST['cashlink']);
        $company_presentation = escape_string($_POST['company_presentation']);
        // if(escape_string($_FILES['image']['tmp_name'])==NULL && $image==NULL){
        //   $image = NULL;
        //   $base64= "";
        // } else if(escape_string($_FILES['image']['tmp_name'])==NULL && $image!=NULL){
        //   $imagedata = file_get_contents("../profile/uploads/profile/".$image);
        //   $base64 = base64_encode($imagedata);
        // } else{
        //   $image = $token.".jpg";
        //   $image_temp = escape_string($_FILES['image']['tmp_name']);
        //
        //   move_uploaded_file($image_temp, "../profile/uploads/profile/".$image);
        //
        //   // Save image to VFC in base64
        //   $imagedata = file_get_contents("../profile/uploads/profile/".$image);
        //   $base64 = base64_encode($imagedata);
        // }

        $query = query("SELECT * from contact where id = $id");
        confirm($query);
        $row = fetch_array($query);
        $image = $row['image'];
        $imagedata = file_get_contents("../profile/uploads/profile/" . $image);
        $base64 = base64_encode($imagedata);

//        $str1 = substr($phone, 1);
//        $phone_int = '+' . $country_code . $str1;

        $formattedBirthday = str_replace("-", "", $birthday);
        $vcfString =
            "BEGIN:VCARD\n" .
            "VERSION:3.0\n" .
            "FN;CHARSET=UTF-8:" . $first_name . " " . $last_name . "\n" .
            "N;CHARSET=UTF-8:" . $last_name . ";" . $first_name . ";;" . $title . " ;\n" .
            "BDAY:" . $formattedBirthday . "\n" .
            "EMAIL;CHARSET=UTF-8;type=HOME,INTERNET:" . $email . "\n" .
            "TEL;TYPE=CELL:" . $phone . "\n" .
            "LABEL;CHARSET=UTF-8;TYPE=HOME:" . $label . "\n" .
            "ADR;CHARSET=UTF-8;TYPE=HOME:;;" . $street . ";" . $city . ";" . $county . ";" . $zip . ";" . $country . "\n" .
            "ROLE;CHARSET=UTF-8:" . $job . "\n" .
            "ORG;CHARSET=UTF-8:" . $company . "\n" .
            "URL;CHARSET=UTF-8:" . $website . "\n" .
            "URL;type=WORK;CHARSET=UTF-8:" . $website . "\n" .
            "X-SOCIALPROFILE;TYPE=facebook:" . $facebook . "\n" .
            "X-SOCIALPROFILE;TYPE=twitter:" . $twitter . "\n" .
            "X-SOCIALPROFILE;TYPE=linkedin:" . $linkedin . "\n" .
            "X-SOCIALPROFILE;TYPE=instagram:" . $instagram . "\n" .
            "REV:2022-03-09T14:48:54.816Z\n" .
            "PHOTO;ENCODING=b;TYPE=JPG:" . $base64 . "\n" .
            "END:VCARD";

        $token = $row['token'];
        $file = "../profile/uploads/vcf/" . $token . ".vcf";
        $contact = fopen($file, "wb");
        fwrite($contact, $vcfString);

        $contact_link = $token . ".vcf";

        // UPDATE USER PROFILE
        $query = query("UPDATE contact SET first_name = '{$first_name}', last_name = '{$last_name}', username = '{$username}',password = '{$password}', title = '{$title}', company = '{$company}', job = '{$job}',
      description = '{$description}', birthday = '{$birthday}', phone = '{$phone}', email = '{$email}',
      country = '$country', county = '$county', city = '$city', street = '$street', label = '$label', zip='$zip',
      website = '{$website}',company_presentation = '{$company_presentation}', facebook = '{$facebook}', instagram = '{$instagram}', twitter = '{$twitter}',
      linkedin = '{$linkedin}',cashlink = '{$cashlink}', contact_link = '$contact_link', activated = 1 where id = $id  ");
        confirm($query);
        redirect($redirect_link);
    }
}

function display_orders()
{
    $query = query("SELECT * FROM orders order by create_date desc");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $transaction_id = $row['transaction_id'];
        $amount = $row['amount'];
        $status = $row['status'];
        $create_date = $row['create_date'];
        $type = $row['type'];
        $observation = $row['observation'];
        $fk_address_id = $row['fk_address_id'];
        $fk_customer_id = $row['fk_customer_id'];
        $order = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$transaction_id</td>
        <td>$amount</td>
        <td>$status</td>
        <td>$create_date</td>
        <td>$type</td>

        <td>
            <a class="btn btn-success" href="../invoice/$transaction_id.pdf" target=blank><i class="fa fa-file"></i></a>
            <a class="btn btn-info" href="index.php?info_order&id={$row['id']}"><i class="fa fa-info-circle"></i></a>
            <a class="btn btn-danger" href="index.php?delete_order&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $order;
    }
}

function display_products()
{
    $query = query("SELECT * FROM product");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name_hu = $row['name_hu'];
        $price = $row['price'];
        $image = $row['image'];
        $type = $row['type'];
        $products = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name_hu</td>
        <td>$price</td>
        <td><img src="../uploads/product/$image" width="100"></td>
        <td>$type</td>
        <td><a class="btn btn-info" href="index.php?product_features&pid={$row['id']}"><i class="fa fa-plus"></i></td>
        <td><a class="btn btn-info" href="index.php?product_image&pid={$row['id']}"><i class="fa fa-plus"></i></td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_product&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_product&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $products;
    }
}

function display_products_features($pid)
{
    $query = query("SELECT * FROM product_features where product_id=$pid");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name_hu = $row['name_hu'];
        $products = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name_hu</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_product_features&pid=$pid&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_product_features&pid=$pid&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $products;
    }
}

function display_ordered_items($id)
{


    $query_items = query("SELECT * FROM order_item where fk_order_id = $id");
    confirm($query_items);
    while ($row_items = fetch_array($query_items)) {
        $pod_id = $row_items['fk_product_variant_id'];
        $quantity = $row_items['quantity'];
        $discount = $row_items['discount'];
        $price = $row_items['price'];


        $query_prod = query("SELECT * FROM product where id = $pod_id");
        confirm($query_prod);
        $row_prod = fetch_array($query_prod);
        $prod_name = $row_prod['name_ro'];
        $prod_image = $row_prod['image'];
        $prod_price = $row_prod['price'];
        $total_price = $quantity * $price;
        $prod = <<<DELIMETER
        <tr>
        <td><img src="../uploads/products/$prod_image" width="100"></td>
        <td>$prod_name</td>
        <td>$prod_price</td>
        <td>$discount% -> $price</td>
        <td>$quantity</td>
        <td>$total_price</td>
        </tr>
DELIMETER;
        echo $prod;
    }
}

function display_home()
{
    $query = query("SELECT * FROM home");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $subtitle_ro = $row['subtitle_ro'];
        $subtitle_en = $row['subtitle_en'];
        $title_ro = $row['title_ro'];
        $title_en = $row['title_en'];
        $button_ro = $row['button_ro'];
        $button_en = $row['button_en'];
        $image = $row['image'];
        $home = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title_en</td>
        <td><img src="../uploads/home_banner/$image" width="100"></td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_home&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_home&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $home;
    }
}


function add_home()
{
    if (isset($_POST['add_home'])) {

        $subtitle_ro = escape_string($_POST['Subtitle_ro']);
        $subtitle_en = escape_string($_POST['Subtitle_en']);
        $title_ro = escape_string($_POST['Title_ro']);
        $title_en = escape_string($_POST['Title_en']);
        $button_ro = escape_string($_POST['Button_ro']);
        $button_en = escape_string($_POST['Button_en']);

        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads/home_banner/$post_image");

        $query = query("INSERT INTO home(subtitle_ro,subtitle_en,title_ro,title_en,button_ro,button_en,image) VALUES('$subtitle_ro','$subtitle_en','$title_ro','$title_en','$button_ro','$button_en','$post_image')");
        confirm($query);
        set_message("$Banner has been added");
        redirect("index.php?home");
    }
}

function display_offers()
{
    $query = query("SELECT * FROM offers");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $subtitle_ro = $row['subtitle_ro'];
        $subtitle_en = $row['subtitle_en'];
        $title_ro = $row['title_ro'];
        $title_en = $row['title_en'];
        $text_ro = $row['text_ro'];
        $text_en = $row['text_en'];
        $image = $row['image'];
        $home = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title_en</td>
        <td><img src="../uploads/offer/$image" width="100"></td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_offers&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_offers&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $home;
    }
}

function add_offers()
{
    if (isset($_POST['add_offers'])) {

        $subtitle_ro = escape_string($_POST['Subtitle_ro']);
        $subtitle_en = escape_string($_POST['Subtitle_en']);
        $title_ro = escape_string($_POST['Title_ro']);
        $title_en = escape_string($_POST['Title_en']);
        $text_ro = escape_string($_POST['Text_ro']);
        $text_en = escape_string($_POST['Text_en']);

        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads/offer/$post_image");

        $query = query("INSERT INTO offers(subtitle_ro,subtitle_en,title_ro,title_en,text_ro,text_en,image) VALUES('$subtitle_ro','$subtitle_en','$title_ro','$title_en','$text_ro','$text_en','$post_image')");
        confirm($query);
        set_message("Offer has been added");
        redirect("index.php?offers");
    }
}

function add_product()
{
    if (isset($_POST['add_product'])) {

        $name_en = escape_string($_POST['name_en']);
        $name_ro = escape_string($_POST['name_ro']);
        $name_hu = escape_string($_POST['name_hu']);
        $desc_en = escape_string($_POST['desc_en']);
        $desc_ro = escape_string($_POST['desc_ro']);
        $desc_hu = escape_string($_POST['desc_hu']);
        $price = escape_string($_POST['price']);
        $type = escape_string($_POST['type']);
        $seo_desc = escape_string($_POST['seo_desc']);
        $seo_keywords = escape_string($_POST['seo_keywords']);


        if (strlen($name_en) > 0) {
            $page_link = slugify($name_en, true);
        } else if (strlen($name_ro) > 0) {
            $page_link = slugify($name_ro, true);
        } else if (strlen($name_hu) > 0) {
            $page_link = slugify($name_hu, true);
        } else {
            $page_link = "page";
        }
        $check_page_link = $page_link . "%";
        $select_query = query("SELECT * FROM product where slug like '$page_link%'");
        confirm($select_query);
        $len = $select_query->num_rows;
        if ($len >= 1) {
            $counter = $len + 1;
            $page_link = $page_link . "_" . $counter;
        }


        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads/product/$post_image");

        $query = query("INSERT INTO product(name_hu,name_ro,name_en,price,description_hu,description_ro,description_en,slug,seo_keywords,seo_desc,image,type)
        VALUES('$name_hu','$name_ro','$name_en','$price','$desc_hu','$desc_ro','$desc_en','$page_link','$seo_keywords','$seo_desc','$post_image','$type')");
        confirm($query);
        set_message("Product has been added");
        redirect("index.php?product");
    }
}

function add_product_feature($pid)
{
    if (isset($_POST['add_product_feature'])) {

        $name_en = escape_string($_POST['name_en']);
        $name_ro = escape_string($_POST['name_ro']);
        $name_hu = escape_string($_POST['name_hu']);

        $query = query("INSERT INTO product_features(name_hu,name_ro,name_en,product_id)
        VALUES('$name_hu','$name_ro','$name_en','$pid')");
        confirm($query);
        set_message("Product has been added");
        redirect("index.php?product_features&pid=$pid");
    }
}

function display_product_images($cat_id)
{
    $select_images = query("SELECT * FROM product_image WHERE product_id=$cat_id");
    confirm($select_images);
    while ($row = fetch_array($select_images)) {

        $id = $row['id'];
        $image = $row['image'];
        $gallery_categories = <<<DELIMETER
        <tr>
            <td>$id</td>
            <td><img src="../uploads/product/$image" width="150"></td>
            <td>
                <a class="btn btn-danger" href="../admin/events/delete_product_image.php?id=$id&pid=$cat_id"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $gallery_categories;
    }
}


function display_gallery()
{
    $query = query("SELECT * FROM gallery");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $image = $row['image'];
        $visible = $row['visible'];
        if ($visible == 0) {
            $review = <<<DELIMETER
        <tr>
            <td>$id</td>
            <td><img src="../uploads/gallery/$image" width="150"></td>
            <td>
                <a class="btn btn-success" href="index.php?add_image&id={$row['id']}"><i class="fa fa-check"></i></a>
                <a class="btn btn-danger" href="index.php?delete_gallery&id={$row['id']}"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        }

        if ($visible == 1) {
            $review = <<<DELIMETER
        <tr>
            <td>$id</td>
            <td><img src="../uploads/gallery/$image" width="150"></td>
            <td>
                <a class="btn btn-warning" href="index.php?remove_image&id={$row['id']}"><i class="fa fa-times"></i></a>
                <a class="btn btn-danger" href="index.php?delete_gallery&id={$row['id']}"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        }

        echo $review;
    }
}

function add_gallery()
{
    if (isset($_POST['add_gallery'])) {
        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads/gallery/$post_image");

        $query = query("INSERT INTO gallery(image,visible) VALUES('$post_image',0)");
        confirm($query);
        set_message("Image has been added");
        redirect("index.php?gallery");
    }
}

function display_value()
{
    $query = query("SELECT * FROM val");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title_ro = $row['title_ro'];
        $title_en = $row['title_en'];
        $text_ro = $row['text_ro'];
        $text_en = $row['text_en'];
        $value = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title_en</td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_value&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_value&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $value;
    }
}

function add_value()
{
    if (isset($_POST['add_value'])) {

        $title_ro = escape_string($_POST['title_ro']);
        $title_en = escape_string($_POST['title_en']);
        $text_ro = escape_string($_POST['text_ro']);
        $text_en = escape_string($_POST['text_en']);
        $icon = escape_string($_POST['icon']);


        $query = query("INSERT INTO val (title_ro,title_en,text_ro,text_en,icon) VALUES ('$title_ro','$title_en','$text_ro','$text_en','$icon')");
        confirm($query);
        set_message("Value has been added");
        redirect("index.php?value");
    }
}

function display_newsletter()
{
    $query = query("SELECT * FROM newsletter");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $email = $row['email'];
        $newsletter = <<<DELIMETER
        <tr>
            <td>$id</td>
            <td>$email</td>
            <td>
                <a class="btn btn-warning" href="../admin/index.php?edit_newsletter&id=$id"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" href="../admin/events/delete_newsletter.php?id=$id"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $newsletter;
    }
}

function display_review()
{
    $query = query("SELECT * FROM review");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name_en'];
        $image_be = $row['image_before'];
        $image_af = $row['image_after'];
        $home = <<<DELIMETER
        <tr>
        <td>$name</td>
        <td><img src="../uploads/review/$image_be" width="100"></td>
        <td><img src="../uploads/review/$image_af" width="100"></td>
        <td>
            <a class="btn btn-warning" href="index.php?edit_review&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="index.php?delete_review&id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $home;
    }
}


function add_review()
{
    if (isset($_POST['add_review'])) {

        $name_ro = escape_string($_POST['Name_ro']);
        $name_en = escape_string($_POST['Name_en']);
        $text_ro = escape_string($_POST['Text_ro']);
        $text_en = escape_string($_POST['Text_en']);
        $star = escape_string($_POST['Star']);
        $before_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $before_image_temp = escape_string($_FILES['image_before']['tmp_name']);
        copy($before_image_temp, "../uploads/review/$before_image");
        $after_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $after_image_temp = escape_string($_FILES['image_after']['tmp_name']);
        copy($after_image_temp, "../uploads/review/$after_image");

        $query = query("INSERT INTO review(name_ro,name_en,text_ro,text_en,star,image_before,image_after) VALUES('$name_ro','$name_en','$text_ro','$text_en','$star','$before_image','$after_image')");
        confirm($query);
        set_message("$Review has been added");
        redirect("index.php?review");
    }
}


function add_category()
{
    if (isset($_POST['add_category'])) {
        $cat_title = escape_string($_POST['cat_title']);
        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        move_uploaded_file($post_image_temp, "../uploads/$post_image");
        $query = query("INSERT INTO categories(cat_title, image) VALUES('$cat_title','$post_image')");
        confirm($query);
        set_message("$cat_title has been added");
        redirect("index.php?categories");
    }
}

function add_photo()
{
    if (isset($_POST['create_media'])) {
        $media_category_id = $_GET['id'];
        $color = escape_string($_POST['color']);
        $product_quantity = escape_string($_POST['product_quantity']);
        if (isset($_POST['is_color'])) {
            $is_color = '1';
        } else {
            $is_color = '0';
        }
        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        move_uploaded_file($post_image_temp, "../uploads/$post_image");

        $price = escape_string($_POST['price']);
        $sale = escape_string($_POST['sale']);

        $query = query("INSERT INTO mediatar(media_image,color,is_color,price,sale,product_quantity,media_category_id) VALUES('{$post_image}','{$color}','{$is_color}',$price,$sale,$product_quantity,{$media_category_id }) ");
        confirm($query);
        set_message("<h4 class='bg-success'>Photo(s) added!</h4>");
        redirect("index.php?photos&id={$_GET['id']}");
    }
}

function add_service()
{
    if (isset($_POST['add_category'])) {
        $title = escape_string($_POST['title']);
        $short_desc = escape_string($_POST['short_desc']);
        $data = escape_string($_POST['data']);
        $order_int = escape_string($_POST['order_int']);
        $description = escape_string($_POST['description']);

        if (isset($_POST['future_party'])) {
            $future_party = 1;
        } else {
            $future_party = 0;
        }

        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        move_uploaded_file($post_image_temp, "../uploads/$post_image");

        $query = query("INSERT INTO events VALUES(NULL, $order_int,'$title','$description','$short_desc','$data','$post_image',$future_party)");
        confirm($query);
        set_message("$title has been added");
        redirect("index.php?services");
    }
}

function add_rev()
{
    if (isset($_POST['add_review'])) {
        $name = escape_string($_POST['name']);
        $description = escape_string($_POST['description']);

        $query = query("INSERT INTO revs(name,description) VALUES('$name','$description')");
        confirm($query);
        set_message("Review has been added");
        redirect("index.php?revs");
    }
}

function add_team()
{
    if (isset($_POST['create_editor'])) {

        $name = escape_string($_POST['name']);
        $post = escape_string($_POST['post']);
        $phone = escape_string($_POST['phone']);

        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        move_uploaded_file($post_image_temp, "../uploads/$post_image");

        $query = query("INSERT INTO team VALUES(NULL,'$name','$phone','$post','$post_image')");
        confirm($query);
        set_message("$name has been added");
        redirect("index.php?team");
    }
}

function show_categories_in_admin()
{
    $query = query("SELECT * FROM categories");
    confirm($query);
    while ($row = fetch_array($query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        $image = $row['image'];
        $category = <<<DELIMETER
        <tr>
        <td>$cat_id</td>
        <td>$cat_title</td>
        <td><img src="../uploads/$image" width="100"></td>
		<td>
            <a class="btn btn-warning" href="../admin/index.php?edit_category&id={$row['cat_id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="../admin/events/delete_category.php?id={$row['cat_id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $category;
    }
}

function display_team_in_admin()
{
    $query = query("SELECT * FROM team");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $image = $row['image'];
        $post = $row['post'];
        $category = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name</td>
        <td><img src="../uploads/$image" width="100"></td>
        <td>$post</td>
		<td>
            <a class="btn btn-warning" href="index.php?edit_team&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="events/delete_team.php?id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $category;
    }
}

function show_services_in_admin()
{
    $query = query("SELECT * FROM events");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title = $row['title'];
        $data = $row['data'];
        $image = $row['image'];
        $future_party = $row['future_party'];
        $category = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$title</td>
        <td>$data</td>
        <td>$future_party</td>
        <td><img src="../uploads/$image" width="200"></td>
        <td>
            <a class="btn btn-info" href="index.php?photos2&id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-plus"></i></a>
        </td>
		<td>
            <a class="btn btn-warning" href="../admin/index.php?edit_service&id={$row['id']}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger" href="../admin/events/delete_service.php?id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $category;
    }
}

function show_revs_in_admin()
{
    $query = query("SELECT * FROM revs");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $product_id = $row['product_id'];
        $name = $row['name'];
        $email = $row['email'];
        $rating = $row['rating'];
        $rating_date = date("d/m/Y", strtotime($row['rating_date']));
        $description = $row['description'];

        $select_name = query("SELECT title FROM products WHERE id = $product_id");
        confirm($select_name);
        if (mysqli_num_rows($select_name) > 0) {
            $product_row = fetch_array($select_name);
            $product_title = $product_row['title'];
        } else {
            $product_title = "Produsul nu mai exista.";
        }

        $category = <<<DELIMETER
        <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$email</td>
        <td>$product_title</td>
        <td>$rating/5</td>
        <td>$rating_date</td>
        <td>$description</td>
		<td>
            <a class="btn btn-danger" href="../admin/events/delete_rev.php?id={$row['id']}"><i class="fa fa-trash"></i></a>
        </td>
        </tr>
DELIMETER;
        echo $category;
    }
}

function show_categories_in_add_post()
{
    $query = query("SELECT * FROM categories");
    confirm($query);
    while ($row = fetch_array($query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        $category = <<<DELIMETER
<option value="$cat_id">$cat_title</option>
DELIMETER;
        echo $category;
    }
}

function show_agents_in_add_post()
{
    $query = query("SELECT * FROM team");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $name = $row['name'];
        $category = <<<DELIMETER
<option value="$id">$name</option>
DELIMETER;
        echo $category;
    }
}

//++++++++++++++++++++++++++++
//EDITOR ADMIN
//++++++++++++++++++++++++++++
function display_general_page()
{
    $query = query("SELECT * FROM general_page");
    confirm($query);
    while ($row = fetch_array($query)) {
        $id = $row['id'];
        $title = $row['title'];
        $slug = $row['slug'];
        $page = <<<DELIMETER
        <tr>
            <td>$id</td>
            <td>$title</td>
            <td>$slug</td>
            <td>
                <a class="btn btn-warning" href="../admin/index.php?general_page_edit&id=$id"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" href="../admin/index.php?general_page_delete&id=$id"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $page;
    }
}


function add_general_page()
{
    if (isset($_POST['add_general_page'])) {
        $title = escape_string($_POST['title']);
        $content = escape_string($_POST['content']);
        $seo_keywords = strip_tags(escape_string($_POST["seo_keywords"]));
        $seo_desc = mb_strimwidth(strip_tags(escape_string($_POST['seo_desc'])), 0, 157, "...");
        if (strlen($title) > 0) {
            $page_link = slugify($title, true);
        } else {
            $page_link = "page";
        }

        $check_page_link = $page_link . "%";
        $select_query = query("SELECT * FROM general_page where slug like '$page_link%'");
        confirm($select_query);
        $len = $select_query->num_rows;
        if ($len >= 1) {
            $counter = $len + 1;
            $page_link = $page_link . "_" . $counter;
        }

        $query = query("INSERT INTO general_page(title,content,slug,seo_keywords,seo_desc)
                        VALUES('$title', '$content','$page_link','$seo_keywords', '$seo_desc')");
        confirm($query);
        set_message("$name has been added");
        redirect("index.php?general_page");
    }
}

function display_posts_editor_page()
{
    $select_query = query("SELECT * FROM products");
    confirm($select_query);
    while ($row = fetch_array($select_query)) {
        $id = $row['id'];
        $title = $row['title'];
        $image = $row['image'];
        $price = $row['price'];

        $query = query("SELECT * FROM mediatar WHERE media_category_id=$id");
        confirm($query);
        $nr_of_photos = mysqli_num_rows($query);
        $post = <<<DELIMETER
        <tr>
            <td>$id</td>
			<td>$title</td>
			<td>$price</td>
            <td><img src="../uploads/{$image}" width="100"></td>
            <td>
                <a class="btn btn-info" href="index.php?photos&id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-plus"></i></a>
                <span>Nr: $nr_of_photos</span>
            </td>
            <td>{$row['sort']}</td>
            <td>
                <a class="btn btn-success" href="events/edit_prio.php?pid={$row['id']}" style="margin:0px 5px;"><i class="fa fa-plus"></i></a>
                <a class="btn btn-danger" href="events/edit_prio.php?mid={$row['id']}" style="margin:0px 5px;"><i class="fa fa-minus"></i></a>
            </td>
			<td>
                <a class="btn btn-warning" href="index.php?edit_post&id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" href="events/delete_news.php?id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $post;
    }
}


function display_pages_admin()
{
    $select_query = query("SELECT * FROM pages");
    confirm($select_query);
    while ($row = fetch_array($select_query)) {
        $id = $row['id'];
        $title = $row['title'];
        $post = <<<DELIMETER
        <tr>
            <td>$id</td>
			<td>$title</td>
			<td>
                <a class="btn btn-warning" href="index.php?edit_page&id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" href="events/delete_page.php?id={$row['id']}" style="margin:0px 5px;"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $post;
    }
}


function display_medias_in_admin()
{
    $query = query("SELECT * FROM mediatar WHERE media_category_id=" . escape_string($_GET['id']) . "");
    confirm($query);
    while ($row = fetch_array($query)) {
        $media_id = $row['media_id'];
        $media_image = $row['media_image'];
        $media = <<<DELIMETER
        <tr>
            <td>{$row['media_id']}</td>
            <td><img src="../uploads/{$row['media_image']}" width="100"></td>
			<td><a class="btn btn-danger" href="events/delete_photo.php?id={$row['media_id']}&name={$row['media_image']}&p_id={$_GET['id']}"><i class="fa fa-trash"></i></a></td>
        </tr>
DELIMETER;
        echo $media;
    }
}

function display_colors_in_admin()
{
    $query = query("SELECT * FROM mediatar WHERE media_category_id=" . escape_string($_GET['id']) . "");
    confirm($query);
    while ($row = fetch_array($query)) {
        $media_id = $row['media_id'];
        $media_image = $row['media_image'];
        $color = $row['color'];
        $product_quantity = $row['product_quantity'];

        if ($row["is_color"]) {
            $is_color = "Culoare";
            $product_price = $row['price'];
            $product_sale = $row['sale'] . "%";
        } else {
            $is_color = "Numai poza";
            $product_quantity = "-";
            $product_price = "-";
            $product_sale = "-";
        }
        $media = <<<DELIMETER
        <tr>
            <td>{$row['media_id']}</td>
            <td>$color</td>
            <td>$is_color</td>
            <td>$product_price</td>
            <td>$product_sale</td>
            <td>$product_quantity</td>
            <td><img src="../uploads/{$row['media_image']}" width="100"></td>
			<td>
                <a class="btn btn-warning" href="index.php?edit_photo&id={$row['media_id']}&p_id={$_GET['id']}"><i class="fa fa-edit"></i></a>
                <a class="btn btn-danger" href="events/delete_photo.php?id={$row['media_id']}&name={$row['media_image']}&p_id={$_GET['id']}"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
DELIMETER;
        echo $media;
    }
}

function display_medias2_in_admin()
{
    $query = query("SELECT * FROM mediatar2 WHERE media_category_id=" . escape_string($_GET['id']) . "");
    confirm($query);
    while ($row = fetch_array($query)) {
        $media_id = $row['media_id'];
        $media_image = $row['media_image'];
        $media = <<<DELIMETER
        <tr>
            <td>{$row['media_id']}</td>
            <td><img src="../events/{$row['media_image']}" width="100"></td>
			<td><a class="btn btn-danger" href="events/delete_photo2.php?id={$row['media_id']}&name={$row['media_image']}&p_id={$_GET['id']}"><i class="fa fa-trash"></i></a></td>
        </tr>
DELIMETER;
        echo $media;
    }
}

function reArrayFiles($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_key as $val) {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}

function add_media()
{
    if (isset($_POST['create_media'])) {
        $media_category_id = $_GET['id'];
        $img = $_FILES['img'];
        if (!empty($img)) {
            $img_desc = reArrayFiles($img);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], '../uploads/' . $newname);

                $query = query("INSERT INTO mediatar(media_image,media_category_id) VALUES('{$newname}','{$media_category_id }') ");
                confirm($query);
            }
        }
        set_message("<h4 class='bg-success'>Photo(s) added!</h4>");
        redirect("index.php?photos&id={$_GET['id']}");
    }
}

function add_media2()
{
    if (isset($_POST['create_media'])) {
        $media_category_id = $_GET['id'];
        $img = $_FILES['img'];
        if (!empty($img)) {
            $img_desc = reArrayFiles($img);

            foreach ($img_desc as $val) {
                $newname = date('YmdHis', time()) . mt_rand() . '.jpg';
                move_uploaded_file($val['tmp_name'], '../events/' . $newname);

                $query = query("INSERT INTO mediatar2(media_image,media_category_id) VALUES('{$newname}','{$media_category_id }') ");
                confirm($query);
            }
        }
        set_message("<h4 class='bg-success'>Photo(s) added!</h4>");
        redirect("index.php?photos2&id={$_GET['id']}");
    }
}

function get_cart_item_nr()
{
    $cart_nr = 0;
    foreach ($_SESSION as $name => $value) {
        if (substr($name, 0, 5) == "card_") {
            $card = $value;
            if ($card->quantity > 0) {
                $cart_nr += $card->quantity;
            }
        }
        if (substr($name, 0, 6) == "stand_") {
            $card = $value;
            if ($card->quantity > 0) {
                $cart_nr += $card->quantity;
            }
        }
    }
    echo $cart_nr;
}

function get_preview_products()
{
    foreach ($_SESSION as $name => $value) {

        if ($value > 0) {

            if (substr($name, 0, 8) == "product_") {
                $sessionSplit = explode("_", $name);
                $id = $sessionSplit[1];
                $color = $sessionSplit[2];

                $sel_col = query("SELECT * FROM mediatar WHERE media_id=$color");
                confirm($sel_col);
                $col_row = fetch_array($sel_col);
                $color_name = $col_row['color'];
                $product_image = $col_row['media_image'];
                $product_price = $col_row['price'];
                $product_sale = $col_row['sale'];
                $product_price = ($product_price * (100 - $product_sale)) / 100;
                $product_price = round($product_price, 1);
                $product_price = number_format($product_price, 2);

                $query = query("SELECT * FROM products WHERE id=" . escape_string($id) . " ");
                confirm($query);
                $row = fetch_array($query);
                $product_id = $row['id'];
                $product_title = $row['title'];
                $product_category_id = $row['post_category_id'];

                $product_price = $product_price . " RON";

                $product = <<<DELIMETER
                <div class="navbar-cart-product">
                        <div class="d-flex align-items-center">
                            <a href="product.php?id=$product_id">
                                <img class="img-fluid navbar-cart-product-image" src="uploads/$product_image" alt="...">
                            </a>
                            <div class="w-100">
                                <a class="close text-sm mr-2" href="order.php?delete=$product_id&c=$color">
                                    <i class="fa fa-times"></i>
                                </a>
                                <div class="pl-3"><a class="navbar-cart-product-link" href="product.php?id=$product_id">$product_title (Culoare: $color_name)</a><small class="d-block text-muted">Cantitate: $value </small><strong class="d-block text-sm">$product_price RON</strong>
                                </div>
                            </div>
                        </div>
                  </div>
DELIMETER;


                echo $product;


            }

        }
    }
}

function calculate_the_cart()
{
    $prev_nr = 0;
    $prev_total = 0;
    $prev_sale = 0;
    $taxx = 0;
    $sale = 0;
    foreach ($_SESSION as $name => $value) {
        if (substr($name, 0, 5) == "card_") {
            $card = $value;
            if ($card->quantity > 0) {
                $sub = $card->price * $card->quantity;
                $_SESSION['total_preview'] = $prev_total += $sub;
                $_SESSION['nr_preview'] = $prev_nr += $card->quantity;
            }
        }
        if (substr($name, 0, 6) == "stand_") {
            $card = $value;
            if ($card->quantity > 0) {
                $sub = $card->price * $card->quantity;
                $_SESSION['total_preview'] += $prev_total += $sub;
                $_SESSION['nr_preview'] += $prev_nr += $card->quantity;
            }
        }
    }
    $_SESSION['total_preview'] = $prev_total;
}

function generate_invoice_pdf($get_order_id)
{
    require('pdf/tcpdf.php');

    $query = query("SELECT * FROM invoice WHERE fk_order_id=$get_order_id");
    confirm($query);
    $row = fetch_array($query);
    $series = $row['series'];
    $number = $row['number'];
    $fk_address_id = $row['fk_address_id'];
    $fk_order_id = $row['fk_order_id'];
    $amount = $row['amount'];
    $c_date = $row['c_date'];


    $query = query("SELECT * FROM orders where id = $get_order_id");
    confirm($query);
    $row = fetch_array($query);
    $idorder = $row['id'];
    $transaction_id = $row['transaction_id'];
    $amount = $row['amount'];
    $status = $row['status'];
    $create_date = $row['create_date'];
    $type = $row['type'];
    $observation = $row['observation'];
    $fk_address_id = $row['fk_address_id'];
    $fk_customer_id = $row['fk_customer_id'];

    $query_customer = query("SELECT * FROM customer where id = $fk_customer_id");
    confirm($query_customer);
    $row_customer = fetch_array($query_customer);
    $name = $row_customer['name'];
    $email = $row_customer['email'];
    $phone = $row_customer['phone'];

    $query_settings = query("SELECT * FROM settings where id = 1");
    confirm($query_settings);
    $row_settings = fetch_array($query_settings);
    $name_company = $row_settings['name'];
    $billing_country_company = $row_settings['billing_country'];
    $billing_state_company = $row_settings['billing_state'];
    $billing_city_company = $row_settings['billing_city'];
    $billing_address_company = $row_settings['billing_address'];
    $billing_zip_company = $row_settings['billing_zip'];
    $billing_cui_company = $row_settings['billing_cui'];
    $phone_company = $row_settings['phone'];
    $register_nr_company = $row_settings['register_nr'];
    $iban_company = $row_settings['iban'];


    $query_address = query("SELECT * FROM address where id = $fk_address_id");
    confirm($query_address);
    $row_address = fetch_array($query_address);
    $shipping_country = $row_address['shipping_country'];
    $shipping_city = $row_address['shipping_city'];
    $shipping_state = $row_address['shipping_state'];
    $shipping_address_line1 = $row_address['shipping_address_line1'];
    $shipping_address_line2 = $row_address['shipping_address_line2'];
    $shipping_zip = $row_address['shipping_zip'];
    $billing_name = $row_address['billing_name'];
    $billing_company_name = $row_address['billing_company_name'];
    $billing_company_tax_id = $row_address['billing_company_tax_id'];
    $billing_rc = $row_address['billing_rc'];
    $billing_country = $row_address['billing_country'];
    $billing_city = $row_address['billing_city'];
    $billing_state = $row_address['billing_state'];
    $billing_address_line1 = $row_address['billing_address_line1'];
    $billing_address_line2 = $row_address['billing_address_line2'];
    $billing_zip = $row_address['billing_zip'];


    $factura_catre = 'Factura catre: ';
    if ($billing_company_name) {
        $factura_catre .= $billing_company_name . '<br/>C.I.F.: ';
        $factura_catre .= $billing_company_tax_id . '<br/>Nr. Reg. Com.: ';
        $factura_catre .= $billing_rc . '<br/>';
    } else {
        $factura_catre .= $name . "<br/>";
    }

    $billing_address = 'Adresa: ' . $billing_address_line1 . "<br/>" . $billing_city . " " . $billing_zip . "<br/>" . $billing_state . " " . $billing_country;
    //cegadatok
    $adresa_company = 'Adresa: ' . $billing_country_company . ', ' . $billing_state_company . ', <br/>' . $billing_city_company . ', ' . $shipping_zip_company . ', <br/>' . $billing_address_company . '<br/>';

    $html = '<h1 style="text-align:center">Factura Fiscala<br/>' . $series . $number . '<br/>' . date("d.m.Y", strtotime($c_date)) . '</h1>';
    $html .= '<table>';
    $html .= '<tr style="background-color:#f8fafd;color:black;">';

    //cegadatok
    $html .= '<td width="280"><h3>Furnizor: Prisma Platform Solutions S.R.L.<br/>' . "C.I.F.: " . $billing_cui_company . '<br/>' . "Nr. Reg. Com.: " . $register_nr_company . '<br/>';
    $html .= $adresa_company;
    $html .= 'Banca: Banca Transilvania <br/>';
    $html .= 'Cont:' . $iban_company . '<br/>';
    $html .= 'TVA la încasare<br/><br/><br/>';
    $html .= '</h3></td>';


    $html .= '<td style="text-align:right;"><h3>' . $factura_catre . "<br/>" . $billing_address;
    $html .= '</h3></td>';


    $html .= '</tr>';
    $html .= '</table> <br/><br/><br/>';

    $html .= '<table border="1">';
    $html .= '<tr style="background-color:#f8fafd;color:black;">';
    $html .= '<th align="center" width="20" height="64">Nr.<br/>crt.<br/><br/><br/><br/>0</th>';
    $html .= '<th align="center" width="120" height="64">Denumire produselor sau a serviciilor<br/><br/><br/><br/>1</th>';
    $html .= '<th align="center" width="25" height="64">U.M.<br/><br/><br/><br/><br/>2</th>';
    $html .= '<th align="center" width="35" height="64">Cant.<br/><br/><br/><br/><br/>3</th>';
    $html .= '<th align="center" width="50" height="64">Pret unitar<br/>(fara T.V.A.)<br/> -lei-.<br/><br/><br/>4</th>';
    $html .= '<th align="center" width="50" height="64">Cota<br/>T.V.A.<br/> -%-.<br/><br/><br/>5</th>';
    $html .= '<th align="center" width="50" height="64">Discount<br/> -%-.<br/><br/><br/><br/>6</th>';
    $html .= '<th align="center" width="80" height="64">Pret unitar (fara T.V.A.) <br/> cu discount <br/> -lei-. <br/><br/>7</th>';
    $html .= '<th align="center" width="50" height="64">Valoare<br/> -lei-.<br/><br/><br/><br/>8(3X7) </th>';
    $html .= '<th align="center" width="70" height="64">Valoare <br/> T.V.A. <br/> -lei-. <br/><br/><br/>9</th>';
    $html .= '</tr>';
    $row_count = 0;

    $subtotal = 0;
    $tva = 0;
    $total = 0;

    $query_item = query("SELECT * FROM order_item where order_id = $get_order_id");
    confirm($query_item);
    $itemordered = 1;
    while ($row_item = fetch_array($query_item)) {
        $card_plan = $row_item['plan'];
        $card_name = $row_item['name'];
        $card_position = $row_item['position'];
        $quantity = $row_item['quantity'];
        if ($card_plan == "Standard") {
            $price = 150;
        } else {
            $price = 240;
        }
        $discount = 0;
        $pret_unitar_fara_tva = $price - ($price * 19) / (100 + 19);
        $pret_unitar_fara_tva_cu_discount = ($pret_unitar_fara_tva * (100 - $discount)) / 100;
        $subtotal = $subtotal + $pret_unitar_fara_tva_cu_discount * $quantity;


        $valoare_tva = ($quantity * $pret_unitar_fara_tva_cu_discount * 19) / 100;
        $tva = $tva + $valoare_tva;

        $row_count = $row_count + 1;
        if ($row_count % 7 == 0 && $row_count <= 7) {
            $html .= '</table><br/><table border="1">';
        } else if (($row_count - 8) % 11 == 0 && $row_count > 7) {
            $html .= '</table><br/><br/><br/><br/><br/><table border="1">';
        }
        $html .= '<tr>';
        $html .= '<td align="center" width="20" height="64">' . $row_count . '</td>';
        $html .= '<td align="center" width="120" height="64">' . $card_plan . "<br/>Name: " . $card_name . "<br/>Job: " . $card_position . '</td>';
        $html .= '<td align="center" width="25" height="64">BUC</td>';
        $html .= '<td align="center" width="35" height="64">' . $quantity . '</td>';
        $html .= '<td align="center" width="50" height="50">' . number_format($pret_unitar_fara_tva, 2) . '</td>';
        $html .= '<td align="center" width="50" height="50">19</td>';
        $html .= '<td align="center" width="50" height="50">' . number_format(0, 2) . '</td>';
        $html .= '<td align="center" width="80" height="50">' . number_format($pret_unitar_fara_tva_cu_discount, 2) . '</td>';
        $html .= '<td align="center" width="50" height="50">' . number_format($quantity * $pret_unitar_fara_tva_cu_discount, 2) . '</td>';
        $html .= '<td align="center" width="70" height="50">' . number_format($valoare_tva, 2) . '</td>';
        $html .= '</tr>';
    }

    $total = $subtotal + $tva;

    $html .= '</table>';
    $html .= '<br/><br/><br/><br/>';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td width="300" align="right">';
    $html .= '</td>';
    $html .= '<td width="100" align="left">';
    $html .= 'Subtotal:<br/><br/>';
    $html .= 'Total TVA:<br/><br/>';
    $html .= 'Total General:';
    $html .= '</td>';
    $html .= '<td width="150" align="right">';
    $html .= '<strong>' . number_format($subtotal, 2) . '</strong><br/><br/>';
    $html .= '<strong>' . number_format($tva, 2) . '</strong><br/><br/>';
    $total = $subtotal + $tva;
    $html .= '<strong>' . number_format($total, 2) . '</strong>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td width="180">';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= 'Va multumim!<br/>';
    $html .= 'Conform art. 319 alin. (29) din Legea nr. 227/2015 privind Codul Fiscal, factura este valabila fara semnatura si stampila <br/>';
    $html .= '© 2022 Prisma PLatform Solutions S.R.L.';


    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->AddPage('P', "A4");
    $pdf->SetFont('DejaVuSans', '', 8);
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output(__DIR__ . '/../invoice/' . $transaction_id . '.pdf', 'F');
}

function cart()
{
    $total = 0;
    $item_quantity = 0;
    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity = 1;
    $_SESSION['ordered_products'] = "";
    foreach ($_SESSION as $name => $value) {
        if ($value > 0) {
            if (substr($name, 0, 8) == "product_") {
                $sessionSplit = explode("_", $name);
                $id = $sessionSplit[1];
                $color = $sessionSplit[2];
                $query = query("SELECT * FROM products WHERE id=" . escape_string($id) . " ");
                confirm($query);
                while ($row = fetch_array($query)) {
                    $product_id = $row['id'];
                    $product_title = $row['title'];
                    $product_category_id = $row['post_category_id'];
                    $product_price = $row['price'];
                    $product_sale = $row['sale'];
                    $product_price = ($product_price * (100 - $product_sale)) / 100;
                    $product_price = round($product_price, 1);
                    $product_price = number_format($product_price, 2);

                    $product_price_row = $product_price . " RON";

                    $url = str_replace(" ", "_", $product_title);
                    $_SESSION['ordered_products'] .= $value . "x " . $product_title . ", ";
                    $product_image = $row['image'];
                    $sub = $product_price * $value;
                    $sub_prev = "$sub";

                    $sub_prev = $sub_prev . " RON";

                    $item_quantity += $value;
                    $product = <<<DELIMITER
        <tr class="table-row">
                        <td class="column-1">
                                <div class="cart-img-product b-rad-4 o-f-hidden">
                                    <img src="uploads/$product_image" alt="$product_title"/>
                                </div>
                        </td>
                        <td class="column-2"><a href="product.php?id=$product_id">$product_title ($size)</a></td>
                        <td class="column-3">$product_price_row</td>
                        <td class="column-4">
                            <div class="flex-w bo5 of-hidden w-size17">
                                <a href="order.php?remove=$product_id&s=$size"><button class="color1 flex-c-m size7 bg8 eff2">
                                    <i class="fs-12 fa fa-minus" aria-hidden="true"></i>
                                </button></a>

                                <input class="size8 m-text18 t-center num-product" type="number" name="num-product1" value="$value">

                                <a href="order.php?add=$product_id&q=1&s=$size"><button class="color1 flex-c-m size7 bg8 eff2">
                                    <i class="fs-12 fa fa-plus" aria-hidden="true"></i>
                                </button></a>
                            </div>
                        </td>
                        <td class="column-5">$sub_prev</td>
                        <td class="column-6">
                        <a href="order.php?delete=$product_id&s=$size"><button class="color1 flex-c-m size7 bg8 eff2">
                                  <i class="fs-12 fa fa-remove" aria-hidden="true"></i>
                                </button></a>
                        </td>
        </tr>

        <input type="hidden" name="item_name_{$item_name}" value="$product_title">
        <input type="hidden" name="item_number_{$item_number}" value="$product_id">
        <input type="hidden" name="amount_{$amount}" value="$product_price">
        <input type="hidden" name="quantity_$quantity" value="{$value}">

DELIMITER;
                    echo $product;
                    $item_name++;
                    $item_number++;
                    $amount++;
                    $quantity++;
                }

                $_SESSION['item_total'] = $total += $sub;
                $_SESSION['item_warenwert'] = $_SESSION['item_total'];
                $_SESSION['item_quantity'] = $item_quantity;

            }

        }
    }

    if ($total == 0) {
        echo '<tr><td colspan="6"><center>No items in the cart.</center></td></tr>';
    }


}

function isValidJSON($str)
{
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

//company
function add_event()
{
    if (isset($_POST['add_event'])) {

        $name = escape_string($_POST['name']);
        $formText = escape_string($_POST['formText']);
        $endDate = escape_string($_POST['endDate']);
        $post_image = date('YmdHis', time()) . mt_rand() . '.jpg';
        $post_image_temp = escape_string($_FILES['image']['tmp_name']);
        copy($post_image_temp, "../uploads_company/events/$post_image");
        $token = md5('transaction' . rand());

        $companyid = $_SESSION['companyId'];
        if (strlen($name) > 0) {
            $page_link = slugify($name, true);
        }

        $check_page_link = $page_link . "%";
        $select_query = query("SELECT * FROM companyevent where companyid = $companyid and formtoken like '$page_link%'");
        confirm($select_query);
        $len = $select_query->num_rows;
        if ($len >= 1) {
            $counter = $len + 1;
            $page_link = $page_link . "_" . $counter;
        }


        $query = query("INSERT INTO companyevent(companyid,name,createDate,endDate,formText,image,formtoken) VALUES($companyid,'$name',now(),'$endDate','$formText','$post_image','$page_link')");
        confirm($query);
        set_message("Event has been added");
        redirect("index.php?event");
    }
}

function display_chestionar_export($token)
{
    $query = query("SELECT * FROM companyform where eventid = $token order by date desc");
    confirm($query);
    while ($row = fetch_array($query)) {
        $answer = $row['answer'];
        $Date = $row['date'];
        $datas = json_decode($answer, true);
        $contact_form = <<<DELIMETER
    <tr>
DELIMETER;
        echo $contact_form;
        foreach ($datas as $data) {
            $name = $data['name'];
            $value = $data['value'];
            if ($name == "acord") {

            } else {
                $contact_form = <<<DELIMETER
          <td>
            $value
          </td>
DELIMETER;
                echo $contact_form;
            }

        }
        $contact_form = <<<DELIMETER
      </tr>
DELIMETER;
        echo $contact_form;
    }
}

function display_chestionar($token)
{
    $query = query("SELECT * FROM companyform where eventid = $token order by date desc");
    confirm($query);
    while ($row = fetch_array($query)) {
        $answer = $row['answer'];
        $Date = $row['date'];
        $dateformatted = date("d.m.Y", strtotime($Date));
        $datas = json_decode($answer, true);

        foreach ($datas as $data) {
            $name = $data['name'];
            $value = $data['value'];
            if ($name == "Nume") {
                $displaynume = $value;
            }
            if ($name == "Prenume") {
                $displayprenume = $value;
            }
            if ($name == "Email") {
                $displayemail = $value;
            }
            if ($name == "Telefon") {
                $displaytelefon = $value;
            }
        }
        $letter = mb_substr($displayprenume, 0, 1);
        $contact_form = <<<DELIMETER
    <tr>
    <td>
        <a class="d-flex align-items-center" href="mailto:$displayemail">
          <div class="avatar avatar-soft-primary avatar-circle">
            <span class="avatar-initials">$letter</span>
          </div>
          <div class="ms-3">
            <span class="d-block h5 text-inherit mb-0">$displaynume $displayprenume</span>
            <span class="d-block fs-5 text-body">$displayemail</span>
          </div>
        </a>
      </td>
      <td><a href="tel:$displaytelefon">$displaytelefon</a></td>
      <td>
      $dateformatted
      </td>
    <td>
        <a class="btn btn-success" href="index.php?chestionar_info&id={$row['id']}&eventid={$row['eventid']}"><i class="fa fa-info" aria-hidden="true"></i></a>
        <a class="btn btn-danger" href="index.php?form_delete&id={$row['id']}&eventid={$row['eventid']}"><i class="fa fa-trash"></i></a>
    </td>
    </tr>
DELIMETER;
        echo $contact_form;
    }
}


// HELPER FUNCTIONS
function slugify($text, $strict = false)
{
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d.]+~u', '-', $text);

    // trim
    $text = trim($text, '-');
    setlocale(LC_CTYPE, 'en_GB.utf8');
    // transliterate
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }

    // lowercase
    $text = strtolower($text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w.]+~', '', $text);
    if (empty($text)) {
        return 'empty_$';
    }
    if ($strict) {
        $text = str_replace(".", "_", $text);
    }
    return $text;
}


// function getClientIP()
// {
//     if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//         $ip = $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     } else {
//         $ip = $_SERVER['REMOTE_ADDR'];
//     }
//     return $ip;
// }

// function ip_details($ip)
// {
//     $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
//     $details = json_decode($json, true);
//     return $details;
// }
