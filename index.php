<?php
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
  require_once __DIR__ . '/vendor/autoload.php';
}

if ( !class_exists( '\Facebook\Facebook' ) ) {
  die('Missing Facebook SDK');
}

if ( !session_id() ) {
  session_start();
}

$is_ssl = false;
if ( isset( $_SERVER['HTTPS'] ) ) {
  if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
    $is_ssl = true;
  } else if ( '1' == $_SERVER['HTTPS'] ) {
    $is_ssl = true;
  }
} else if ( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == '443' ) {
  $is_ssl = true;
}

$protocol = $is_ssl ? 'https://' : 'http://';
$current_url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if ( isset( $_GET['appID'] ) && isset( $_GET['appSecret'] ) ) {
  $fb = new \Facebook\Facebook( array(
    'app_id' => $_GET['appID'],
    'app_secret' => $_GET['appSecret'],
    'default_graph_version' => 'v2.9',
  ) );

  $helper = $fb->getRedirectLoginHelper();

  if ( !isset( $_GET['code'] ) ) {

    $permission = isset( $_GET['permission'] ) ? $_GET['permission'] : array();

    if ( !is_array( $permission ) ) {
      $permission = array( $permission );
    }

    $permission = array_merge( $permission, array(
      'manage_pages', 'publish_pages'
    ) );

    header('Location: ' . $helper->getLoginUrl( $current_url, $permission ) );
    die();
  } else {
    try {
      $access_token = $helper->getAccessToken()->getValue();
    } catch( Facebook\Exceptions\FacebookResponseException $e ) {
      die( $e->getMessage() );
    } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
      die( $e->getMessage() );
    }

    if ( !$access_token ) {
      die( 'Cannot get Access Token' );
    }

    $fb->setDefaultAccessToken( $access_token );
    $test = $fb->get('/me/accounts');
    print_r( $test );
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Get Facebook Lifetime Access Token</title>
  <style type="text/css">
    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: inline-block;
      max-width: 100%;
      margin-bottom: 5px;
      font-weight: 700;
    }

    .form-group input[type="text"] {
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
      box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
      -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
      -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
      transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }

    .checkbox {
      position: relative;
      display: block;
      margin-top: 10px;
      margin-bottom: 20px;
    }

    .checkbox label {
      min-height: 20px;
      padding-left: 20px;
      margin-bottom: 0;
      font-weight: 400;
      cursor: pointer;
      display: block;
    }

    .checkbox input[type="checkbox"] {
      position: absolute;
      margin-top: 4px;
      margin-left: -20px;
      line-height: normal;
      padding: 0;
      box-sizing: border-box;
    }

    .row {
      margin-right: -15px;
      margin-left: -15px;
    }

    .col-md-4 {
      width: 33.33333333%;
      float: left;
    }

    .container {
      max-width: 600px;
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }

    .btn {
      padding: 15px 20px;
      display: inline-block;
      cursor: pointer;
      text-align: center;
      border: none;
      background-color: #204d74;
      color: #fff;
      border-radius: 4px;
      width: 100%;
      margin-top: 20px;
    }

    .clearfix {
      clear: both;
    }

    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
      color: #3c763d;
      background-color: #dff0d8;
      border-color: #d6e9c6;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <?php if ( !isset( $access_token ) ) : ?>
        <div class="alert"><strong>publish_pages</strong> and <strong>manage_pages</strong> is require for get never expires Access Token</div>
        <form>
          <div class="form-group">
            <label>App ID</label>
            <input type="text" name="appID" value="">
          </div>
          <div class="form-group">
            <label>App Secret</label>
            <input type="text" name="appSecret" value="">
          </div>
          <div class="checkbox">
            <div class="container">
              <div class="row">
                <div class="col-md-4">
                  <label>
                    <input type="checkbox" name="permission[]" value="email">email
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="publish_actions">publish_actions
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_about_me">user_about_me
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_birthday">user_birthday
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_education_history">user_education_history
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_friends">user_friends
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_games_activity">user_games_activity
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_hometown">user_hometown
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_likes">user_likes
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_location">user_location
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_photos">user_photos
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_posts">user_posts
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_relationship_details">user_relationship_details
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_relationships">user_relationships
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_religion_politics">user_religion_politics
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_status">user_status
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_tagged_places">user_tagged_places
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_videos">user_videos
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_website">user_website
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_work_history">user_work_history
                  </label>
                </div>
                <div class="col-md-4">
                  <label>
                    <input type="checkbox" name="permission[]" value="ads_management">ads_management
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="ads_read">ads_read
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="business_management">business_management
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="manage_pages" checked="checked" disabled="disabled">manage_pages
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_manage_cta">pages_manage_cta
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="publish_pages" checked="checked" disabled="disabled">publish_pages
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_messaging">pages_messaging
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_messaging_payments">pages_messaging_payments
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_messaging_phone_number">pages_messaging_phone_number
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_messaging_subscriptions">pages_messaging_subscriptions
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_show_list">pages_show_list
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="read_page_mailboxes">read_page_mailboxes
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="rsvp_event">rsvp_event
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_events">user_events
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_managed_groups">user_managed_groups
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="pages_manage_instant_articles">pages_manage_instant_articles
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_actions.books">user_actions.books
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_actions.fitness">user_actions.fitness
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_actions.music">user_actions.music
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_actions.news">user_actions.news
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="user_actions.video">user_actions.video
                  </label>
                </div>
                <div class="col-md-4">
                  <label>
                    <input type="checkbox" name="permission[]" value="read_audience_network_insights">read_audience_network_insights
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="read_custom_friendlists">read_custom_friendlists
                  </label>
                  <label>
                    <input type="checkbox" name="permission[]" value="read_insights">read_insights
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <button class="btn">GET</button>
        </form>
      <?php else : ?>
        <p class="alert">You can check your access token here: <a href="https://developers.facebook.com/tools/debug/accesstoken/?q=<?php echo $access_token ?>" target="_blank">Click me.</a></p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>