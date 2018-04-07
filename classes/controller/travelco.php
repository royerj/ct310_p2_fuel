<?php

class Controller_TravelCo extends Controller {

    /**
    * index
    */
    public function action_index() {
        // load tables if they dont exist
        $this->loadTables();
        // init views array
        $views = array();
        // load index view into content
        $views['content'] = View::forge('travelco/index');
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * about
    */
    public function action_about() {
      // init views array
      $views = array();
      // load about view into content
      $views['content'] = View::forge('travelco/about');
      // return final view
      return View::forge('travelco/layout', $views);
    }

    /**
    * login
    */
    public function action_login() {
        // setup array for final views
        $views = array();
        // setup array for initial views
        $loginViews = array();
        // load the login_form view
        $loginViews['login_form'] = View::forge('travelco/login_form');
        // set default value for auth_success
        $loginViews['auth_success'] = "";
        // load login view into content
        $views['content'] = View::forge('travelco/login', $loginViews);
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * my account
    */
    public function action_account() {
        // setup array for final views
        $views = array();
        // load account view into content
        $views['content'] = View::forge('travelco/account');
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * attractions
    */
    public function action_attractions() {
        // setup array for final views
        $views = array();
        // load attractions view into content
        $views['content'] = View::forge('travelco/attractions');
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * add attraction
    */
    public function action_add_attraction() {
        // setup array for final views
        $views = array();
        // load add_attraction view into content
        $views['content'] = View::forge('travelco/add_attraction');
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * forgot password
    */
    public function action_forgot() {
        // setup array for final views
        $views = array();
        // setup data array and initialize success to null
        $data = array();
        $data['success'] = "";
        // load forgot view into content
        $views['content'] = View::forge('travelco/forgot', $data);
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * reset password
    */
    public function action_reset() {
        // setup array for final views
        $views = array();
        // setup data array
        $data = array();
        // set message display to blank
        $data['msg'] = '';
        // load forgot view into content
        $views['content'] = View::forge('travelco/reset', $data);
        // return final view
        return View::forge('travelco/layout', $views);
    }

    /**
    * login POST
    */
    public function post_login() {
      // setup array for final views
      $views = array();
      // setup array for initial views
      $loginViews = array();
      // grab login form view
      $loginViews['login_form'] = View::forge('travelco/login_form');
      // set to default
      $loginViews['auth_success'] = false;
      // sanitize
      $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
      // try to login
      if (Auth::login($username, $password) || Auth::login($username, md5($password))) {
        $loginViews['auth_success'] = true;
      }
      // render layout
      // load login view into content
      $views['content'] = View::forge('travelco/login', $loginViews);
      // return final view
      return View::forge('travelco/layout', $views);
    }

    /**
    * logout GET
    */
    public function get_logout() {
      // logout
      Auth::logout();
      // load index
      Response::redirect('index.php/travelco/index');
    }

    /**
    * load tables
    * check if SQL tables exist, if not; create them
    */
    public function loadTables() {
      if (!DBUtil::table_exists('users')) {
        // User table
        DBUtil::create_table('users', array(
          'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
          'username' => array('type' => 'text'),
          'password' => array('constraint' => 125, 'type' => 'varchar'),
          'group' => array('constraint' => 50, 'type' => 'varchar'),
          'email' => array('constraint' => 50, 'type' => 'varchar'),
          'last_login' => array('constraint' => 11, 'type' => 'int'),
          'current_login' => array('type' => 'text'),
          'login_hash' => array('constraint' => 50, 'type' => 'varchar'),
          'profile_fields' => array('constraint' => 50, 'type' => 'varchar'),
          'created_at' => array('constraint' => 11, 'type' => 'int'),
          'updated_at' => array('constraint' => 11, 'type' => 'int'),
          'recovery_key' => array('constraint' => 50, 'type' => 'varchar')
        ), array('id'));
        $this->createUsers();
      }
    }

    /**
    * createUsers
    * create all of the default users if they don't already exist
    * admin = 10
    * customer = 1
    */
    public function createUsers() {
      Auth::create_user('aaronper', '449a36b6689d841d7d27f31b4b7cc73a', 'aaronper@cs.colostate.edu', 1, array());
      Auth::create_user('aaronperadmin', 'd31bfd85d0a81046f70304ebfecdffbf', 'Aaron.Pereira@colostate.edu     ', 10, array());
      Auth::create_user('bsay', '790f6b6cf6a6fbead525927d69f409fe', 'bsay@cs.colostate.edu    ', 1, array());
      Auth::create_user('ct310', 'a6cebbf02cc311177c569525a0f119d7', 'ct310@cs.colostate.edu  ', 10, array());
      Auth::create_user('isaac', 'admin', 'isaac.hall@colostate.edu', 10, array());
      Auth::create_user('customer', 'test', 'iyzik@aol.com', 1, array());
      Auth::create_user('jacob', 'admin', 'jacob.royer@rams.colostate.edu', 10, array());
    }

    /**
    * forgot password POST
    */
    public function post_forgot() {
      // sanitize
      $emailaddress = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
      // query database to get some more info about the user
      $result = DB::select('username','email')->from('users')->where('email', $emailaddress)->execute();
      $username = $result[0]['username'];
      $useremail = $result[0]['email'];
      // setup a data array for our forgot view
      $data = array();
      $data['success'] = false;
      // verify email and try to send it
      if ($emailaddress === $useremail && $emailaddress != null) {
        // send email
        $this->reset_password_email($username, $useremail);
        // set success parameter true
        $data['success'] = true;
      }
      // setup views
      // setup array for final views
      $views = array();
      // load forgot view into content
      $views['content'] = View::forge('travelco/forgot', $data);
      // return final view
      return View::forge('travelco/layout', $views);
    }

    private function reset_password_email($username, $useremail) {
      // reset password
      $newpass = Auth::reset_password($username);
      // create email
      $email = Email::forge();
      $email->from('ct310p2@cs.colostate.edu', 'Ct310 P2');
      $email->to($useremail, $username);
      $email->subject('Reset your password');
      $email->body('Hello, ' . $username . '
      You have requested a password reset.' . '
      Your temporary password is: ' . $newpass . '
      Please visit ' . Uri::Create('index.php/travelco/login') . ' and login using your temporary password to reset your password.'
      );
      // try to send the email
      try{
          $email->send();
      } catch(\EmailValidationFailedException $e) {
          //validation failed
      } catch(\EmailSendingFailedException $e) {
          // the driver could not send the email
      }
    }

    /**
    * reset POST
    */
    public function post_reset() {
      // sanitize
      $oldpass = filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING);
      $newpass = filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING);
      $newpassrepeat = filter_var($_POST['new_pass_repeat'], FILTER_SANITIZE_STRING);
      // setup data array for view
      $data = array();
      // set success to false
      $resetsuccess = false;
      // check that passwords match
      if ($newpass === $newpassrepeat) {
        $resetsuccess = Auth::change_password($oldpass, $newpass, Auth::get('username'));
      } else {
        $data['msg'] = "<div class=\"red\">Passwords do not match!</div>";
      }
      // setup messages for the view
      if ($resetsuccess) {
        $data['msg'] = "Password successfully reset!";
      } else {
        $data['msg'] = "<div class=\"red\">Failed to reset password!</div>";
      }
      // setup some views
      $views = array();
      // load reset view into content
      $views['content'] = View::forge('travelco/reset', $data);
      // return final view
      return View::forge('travelco/layout', $views);
    }

}
