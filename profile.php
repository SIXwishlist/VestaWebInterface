<?php

session_start();

if (file_exists( 'includes/config.php' )) { require( 'includes/config.php'); }  else { header( 'Location: install' );};

if(base64_decode($_SESSION['loggedin']) == 'true') {}
else { header('Location: login.php'); }

$postvars = array(array('user' => $vst_username,'password' => $vst_password,'cmd' => 'v-list-user','arg1' => $username,'arg2' => 'json'));

$curl0 = curl_init();
$curlstart = 0; 

while($curlstart <= 0) {

    curl_setopt(${'curl' . $curlstart}, CURLOPT_URL, $vst_url);
    curl_setopt(${'curl' . $curlstart}, CURLOPT_RETURNTRANSFER,true);
    curl_setopt(${'curl' . $curlstart}, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt(${'curl' . $curlstart}, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt(${'curl' . $curlstart}, CURLOPT_POST, true);
    curl_setopt(${'curl' . $curlstart}, CURLOPT_POSTFIELDS, http_build_query($postvars[$curlstart]));

    $curlstart++;
} 

$admindata = json_decode(curl_exec($curl0), true)[$username];
if(isset($admindata['LANGUAGE'])){ $locale = $ulang[$admindata['LANGUAGE']]; }
setlocale(LC_CTYPE, $locale);
setlocale(LC_MESSAGES, $locale);
bindtextdomain('messages', 'locale');
textdomain('messages');

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/ico" href="plugins/images/favicon.ico">
        <title><?php echo $sitetitle; ?> - <?php echo _("Account"); ?></title>
        <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
        <link href="bootstrap/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="plugins/bower_components/custom-select/custom-select.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
        <link href="css/colors/<?php if(isset($_COOKIE['theme'])) { echo base64_decode($_COOKIE['theme']); } else {echo $themecolor; } ?>" id="theme" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.css" rel="stylesheet" />
        <?php if(GOOGLE_ANALYTICS_ID != ''){ echo "<script async src='https://www.googletagmanager.com/gtag/js?id=" . GOOGLE_ANALYTICS_ID . "'></script>
        <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '" . GOOGLE_ANALYTICS_ID . "');</script>"; } ?>
        <style>
            .select2-results{
                max-height: 200px;
                padding: 0 0 0 4px;
                margin: 4px 4px 4px 0;
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            }
        </style>
        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    </head>
    <body class="fix-header">
        <?php if(INTERAKT_APP_ID != ''){ echo '<script>
	window.mySettings = {
	first_name: "' . $admindata['FNAME'] . '",
	last_name: "' . $admindata['LNAME'] . '",
	suspended: "' . $admindata['SUSPENDED'] . '",
	package: "' . $admindata['PACKAGE'] . '",
	language: "' . $admindata['LANGUAGE'] . '",
	uname: "' . $username . '",
	email: "' . $admindata['CONTACT'] . '",
	created_at: ' . strtotime($admindata['DATE'] . ' ' . $admindata['TIME']) . ',
	joined_at: "' . $admindata['DATE'] . ' ' . $admindata['TIME'] . '",
	app_id: "' . INTERAKT_APP_ID . '"
	};
    </script>'; } ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.js"></script>
        <?php

        if(isset($_GET['password'])) { $pwcode = $_GET['password']; }
        if(isset($_GET['email'])) { $emailcode = $_GET['email']; }
        if(isset($_GET['lang'])) { $langcode = $_GET['lang']; }
        if(isset($_GET['ns'])) { $nscode = $_GET['ns']; }
        if(isset($_GET['name'])) { $namecode = $_GET['name']; }
        if(isset($_GET['password']) && isset($_GET['email']) && isset($_GET['lang']) && isset($_GET['ns']) && isset($_GET['name'])) { $answer1 = $pwcode + $emailcode + $langcode + $nscode + $namecode; }
        if(isset($answer1)) { $answer = (int)$answer1; }
        if(isset($pwcode) || isset($emailcode) || isset($langcode) || isset($nscode) || isset($namecode)){
            if($answer == "0") {
                echo "<script> swal({title:'" . _("Successfully Updated!") . "', type:'success'})</script>";
            } 
            if(isset($answer) && $answer == "1" || isset($answer) && $answer == "2") { echo "<script> swal('" . _("Invalid data entered in form.") . "<br>" .  _("Please try again.") . "', '<br>"; 
                                                                                      if(isset($pwcode) && $pwcode != "0"){ echo " P: " . $pwcode;}
                                                                                      if(isset($emailcode) && $emailcode != "0"){ echo " E: " . $emailcode;}
                                                                                      if(isset($langcode) && $langcode != "0"){ echo " L: " . $langcode;}
                                                                                      if(isset($nscode) && $nscode != "0"){ echo " NS: " . $nscode;}
                                                                                      if(isset($namecode) && $namecode != "0"){ echo " N: " . $namecode;}
                                                                                      echo "', 'error')</script>";}
            if(isset($answer) && $answer > "2") { echo "<script> swal('" . _("Please try again or contact support.") . "', '<br>"; 
                                                 if(isset($pwcode) && $pwcode != "0"){ echo " P: " . $pwcode;}
                                                 if(isset($emailcode) && $emailcode != "0"){ echo " E: " . $emailcode;}
                                                 if(isset($langcode) && $langcode != "0"){ echo " L: " . $langcode;}
                                                 if(isset($nscode) && $nscode != "0"){ echo " NS: " . $nscode;}
                                                 if(isset($namecode) && $namecode != "0"){ echo " N: " . $namecode;}
                                                 echo "', 'error')</script>";}
        }
        ?>

        <div id="wrapper">
            <nav class="navbar navbar-default navbar-static-top m-b-0">
                <div class="navbar-header">
                    <div class="top-left-part">
                        <a class="logo" href="index.php">
                            <b>
                                <img src="plugins/images/admin-logo.png" alt="home" class="logo-1 dark-logo" />
                                <img src="plugins/images/admin-logo-dark.png" alt="home" class="logo-1 light-logo" />
                            </b>
                            <span class="hidden-xs">
                                <img src="plugins/images/admin-text.png" alt="home" class="hidden-xs dark-logo" />
                                <img src="plugins/images/admin-text-dark.png" alt="home" class="hidden-xs light-logo" />
                            </span> 
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-left">
                        <li><a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i class="ti-close ti-menu"></i></a></li>
                    </ul>
                    <ul class="nav navbar-top-links navbar-right pull-right">

                        <li class="dropdown">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"><b class="hidden-xs"><?php print_r($uname); ?></b><span class="caret"></span> </a>
                            <ul class="dropdown-menu dropdown-user animated flipInY">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <h4>
                                                <?php print_r($uname); ?>
                                            </h4>
                                            <p class="text-muted">
                                                <?php print_r($admindata['CONTACT']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li><a href="profile.php"><i class="ti-home"></i> <?php echo _("My Account"); ?></a></li>
                                <li><a href="profile.php?settings=open"><i class="ti-settings"></i> <?php echo _("Account Settings"); ?></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="process/logout.php"><i class="fa fa-power-off"></i> <?php echo _("Logout"); ?></a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav slimscrollsidebar">
                    <div class="sidebar-head">
                        <h3>
                            <span class="fa-fw open-close">
                                <i class="ti-menu hidden-xs"></i>
                                <i class="ti-close visible-xs"></i>
                            </span> 
                            <span class="hide-menu"><?php echo _("Navigation"); ?></span>
                        </h3>
                    </div>
                    <ul class="nav" id="side-menu">
                        <li> 
                            <a href="index.php" class="waves-effect">
                                <i class="mdi mdi-home fa-fw"></i> <span class="hide-menu"><?php echo _("Dashboard"); ?></span>
                            </a> 
                        </li>

                        <li class="devider"></li>
                        <li>
                            <a href="#" class="waves-effect"><i  class="ti-user fa-fw"></i><span class="hide-menu"> <?php print_r($uname); ?><span class="fa arrow"></span></span>
                            </a>
                            <ul class="nav nav-second-level collapse" >
                                <li> <a href="profile.php" id="profileactive"><i class="ti-home fa-fw <?php if(isset($_GET['settings']) && $_GET['settings'] == "open") { echo 'text-inverse';} ?>"></i> <span style="<?php if(isset($_GET['settings']) && $_GET['settings'] == "open") { echo 'color:#54667a;font-weight:300;';} ?>" class="hide-menu"> <?php echo _("My Account"); ?></span></a></li>
                                <li> <a href="profile.php?settings=open" id="settingsactive"><i class="ti-settings fa-fw "></i> <span class="hide-menu"> <?php echo _("Account Settings"); ?></span></a></li>
                            </ul>
                        </li>
                        <?php if ($webenabled == 'true' || $dnsenabled == 'true' || $mailenabled == 'true' || $dbenabled == 'true') { echo '<li class="devider"></li>
                            <li> <a href="#" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu">' . _("Management") . '<span class="fa arrow"></span> </span></a>
                                <ul class="nav nav-second-level">'; } ?>
                        <?php if ($webenabled == 'true') { echo '<li> <a href="list/web.php"><i class="ti-world fa-fw"></i><span class="hide-menu">' . _("Web") . '</span></a> </li>'; } ?>
                        <?php if ($dnsenabled == 'true') { echo '<li> <a href="list/dns.php"><i class="fa fa-sitemap fa-fw"></i><span class="hide-menu">' . _("DNS") . '</span></a> </li>'; } ?>
                        <?php if ($mailenabled == 'true') { echo '<li> <a href="list/mail.php"><i class="fa fa-envelope fa-fw"></i><span class="hide-menu">' . _("Mail") . '</span></a> </li>'; } ?>
                        <?php if ($dbenabled == 'true') { echo '<li> <a href="list/db.php"><i class="fa fa-database fa-fw"></i><span class="hide-menu">' . _("Database") . '</span></a> </li>'; } ?>
                        <?php if ($webenabled == 'true' || $dnsenabled == 'true' || $mailenabled == 'true' || $dbenabled == 'true') { echo '</ul>
                            </li>'; } ?>
                        <li> <a href="list/cron.php" class="waves-effect"><i  class="mdi mdi-settings fa-fw"></i> <span class="hide-menu"><?php echo _("Cron Jobs"); ?></span></a> </li>
                        <li> <a href="list/backups.php" class="waves-effect"><i  class="fa fa-cloud-upload fa-fw"></i> <span class="hide-menu"><?php echo _("Backups"); ?></span></a> </li>
                        <?php if ($ftpurl == '' && $webmailurl == '' && $phpmyadmin == '' && $phppgadmin == '') {} else { echo '<li class="devider"></li>
                            <li><a href="#" class="waves-effect"><i class="mdi mdi-apps fa-fw"></i> <span class="hide-menu">' . _("Apps") . '<span class="fa arrow"></span></span></a>
                                <ul class="nav nav-second-level">'; } ?>
                        <?php if ($ftpurl != '') { echo '<li><a href="' . $ftpurl . '" target="_blank"><i class="fa fa-file-code-o fa-fw"></i><span class="hide-menu">' . _("FTP") . '</span></a></li>';} ?>
                        <?php if ($webmailurl != '') { echo '<li><a href="' . $webmailurl . '" target="_blank"><i class="fa fa-envelope-o fa-fw"></i><span class="hide-menu">' . _("Webmail") . '</span></a></li>';} ?>
                        <?php if ($phpmyadmin != '') { echo '<li><a href="' . $phpmyadmin . '" target="_blank"><i class="fa fa-edit fa-fw"></i><span class="hide-menu">' . _("phpMyAdmin") . '</span></a></li>';} ?>
                        <?php if ($phppgadmin != '') { echo '<li><a href="' . $phppgadmin . '" target="_blank"><i class="fa fa-edit fa-fw"></i><span class="hide-menu">' . _("phpPgAdmin") . '</span></a></li>';} ?>
                        <?php if ($ftpurl == '' && $webmailurl == '' && $phpmyadmin == '' && $phppgadmin == '') {} else { echo '</ul></li>';} ?>
                        <li class="devider"></li>
                        <li><a href="process/logout.php" class="waves-effect"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu"><?php echo _("Log out"); ?></span></a></li>
                        <?php if ($oldcpurl == '' || $supporturl == '') {} else { echo '<li class="devider"></li>'; } ?>
                        <?php if ($oldcpurl != '') { echo '<li><a href="' . $oldcpurl . '" class="waves-effect"> <i class="fa fa-tachometer fa-fw"></i> <span class="hide-menu"> ' . _("Control Panel v1") . '</span></a></li>'; } ?>
                        <?php if ($supporturl != '') { echo '<li><a href="' . $supporturl . '" class="waves-effect" target="_blank"> <i class="fa fa-life-ring fa-fw"></i> <span class="hide-menu">' . _("Support") . '</span></a></li>'; } ?>
                    </ul>
                </div>
            </div>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row bg-title">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <h4 class="page-title"><?php echo _("My Account"); ?></h4> </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="white-box">
                                <div class="user-bg bg-theme"> 
                                    <div class="overlay-box bg-theme">
                                        <div class="user-content"><br><br>
                                            <h4 class="text-white"><?php print_r($username); ?></h4>
                                            <h5 class="text-white"><?php print_r($admindata['CONTACT']); ?></h5> </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8 col-xs-12">
                            <div class="white-box">
                                <ul class="nav nav-tabs tabs customtab">
                                    <li class="<?php if(!isset($_GET['settings']) || isset($_GET['settings']) && $_GET['settings'] != "open") { echo "active tab"; } else { echo "tab"; } ?>" >
                                        <a href="profile.php"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs"><?php echo _("Account"); ?></span> </a>
                                    </li>
                                    <li class="<?php if(isset($_GET['settings']) && $_GET['settings'] == "open") { echo "active tab"; } else { echo "tab"; } ?>">
                                        <a href="profile.php?settings=open"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs"><?php echo _("Settings"); ?></span> </a>
                                    </li>
                                </ul>
                                <div class="tab-content ">
                                    <div class="tab-pane <?php if(!isset($_GET['settings']) || isset($_GET['settings']) && $_GET['settings'] != "open") { echo "active"; } ?>" id="profile">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"> <strong><?php echo _("Name"); ?></strong>
                                                <br>
                                                <p class="text-muted"><?php print_r($admindata['FNAME'] . ' ' . $admindata['LNAME']); ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong><?php echo _("Joined"); ?></strong>
                                                <br>
                                                <p class="text-muted"><?php $date=date_create($admindata['DATE'] . ' ' . $admindata['TIME']);
                                                    echo date_format($date,"F j, Y - g:i A"); ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong><?php echo _("Plan"); ?></strong>
                                                <br>
                                                <p class="text-muted"><?php print_r(ucfirst($admindata['PACKAGE'])); ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong><?php echo _("Language"); ?></strong>
                                                <br>
                                                <p class="text-muted"><?php if($admindata['LANGUAGE'] == ""){echo "Not Set";} else{ print_r($countries[$admindata['LANGUAGE']]);} ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <strong><?php echo _("Nameservers"); ?>:</strong><p class="m-t-30">
                                        <ul class="dashed">
                                            <?php 
                                            $nsArray = explode(',', ($admindata['NS'])); 

                                            foreach ($nsArray as &$value) {
                                                $value = "<li>" . $value . "</li>";
                                            }  
                                            foreach($nsArray as $val) {
                                                echo $val;
                                            } 
                                            ?>
                                        </ul>
                                        </p>

                                </div>
                                <div class="tab-pane <?php if(isset($_GET['settings']) && $_GET['settings'] == "open") { echo "active"; } ?>" id="settings">
                                    <form class="form-horizontal form-material" autocomplete="off" action="process/updatesettings.php" method="post">
                                        <input type="hidden" name="fname-x" value="<?php print_r($admindata['FNAME']); ?>"/>
                                        <input type="hidden" name="lname-x" value="<?php print_r($admindata['LNAME']); ?>"/>
                                        <input type="hidden" name="email-x" value="<?php print_r($admindata['CONTACT']); ?>"/>
                                        <input type="hidden" name="language-x" value="<?php print_r($admindata['LANGUAGE']); ?>"/>
                                        <input type="hidden" name="ns1-x" value="<?php print_r(explode(',', ($admindata['NS']))[0]); ?>"/>
                                        <input type="hidden" name="ns2-x" value="<?php print_r(explode(',', ($admindata['NS']))[1]); ?>"/>
                                        <input type="hidden" name="ns3-x" value="<?php print_r(explode(',', ($admindata['NS']))[2]); ?>"/>
                                        <input type="hidden" name="ns4-x" value="<?php print_r(explode(',', ($admindata['NS']))[3]); ?>"/>
                                        <input type="hidden" name="ns5-x" value="<?php print_r(explode(',', ($admindata['NS']))[4]); ?>"/>
                                        <input type="hidden" name="ns6-x" value="<?php print_r(explode(',', ($admindata['NS']))[5]); ?>"/>
                                        <input type="hidden" name="ns7-x" value="<?php print_r(explode(',', ($admindata['NS']))[6]); ?>"/>
                                        <input type="hidden" name="ns8-x" value="<?php print_r(explode(',', ($admindata['NS']))[7]); ?>"/>
                                        
                                        <div class="form-group">
                                            <label for="username" class="col-md-12"><?php echo _("Username"); ?></label>
                                            <div class="col-md-12">
                                                <input type="text" disabled value="<?php print_r($username); ?>" class="form-control form-control-line" name="username" id="username"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-md-12"><?php echo _("Password"); ?> / <a style="cursor:pointer" onclick="generatePassword(10)"> <?php echo _("Generate"); ?></a></label>
                                            <div class="col-md-12 input-group" style="padding-left: 15px;">
                                                <input type="password" class="form-control form-control-line" autocomplete="new-password" name="password" id="password">                                    <span class="input-group-btn"> 
                                                <button class="btn btn-info" style="margin-right: 15px;" name="Show" onclick="toggler(this)" id="tg" type="button"><i class="ti-eye"></i></button> 
                                                </span>  </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-sm-6 pull-left"><?php echo _("First Name"); ?></label><label class="col-sm-6 pull-right"><?php echo _("Last Name"); ?></label>
                                            <div class="col-sm-6 pull-left">
                                                <input type="text" name="fname" value="<?php print_r($admindata['FNAME']); ?>" class="form-control form-control-line"> </div>
                                            <div class="col-sm-6 pull-right">
                                                <input type="text" name="lname" value="<?php print_r($admindata['LNAME']); ?>" class="form-control form-control-line"> </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="col-md-12"><?php echo _("Email"); ?></label>
                                            <div class="col-md-12">
                                                <input type="email" value="<?php print_r($admindata['CONTACT']); ?>" class="form-control form-control-line" name="email" id="email"> </div>
                                        </div>
                                        <div class="form-group" style="overflow: visible;">
                                            <label class="col-md-12"><?php echo _("Language"); ?></label>
                                            <div class="col-md-12">
                                                <select class="form-control select2" name="language" id="select2">

                                                    <option value="ar"><?php print_r($countries['ar']); ?></option>
                                                    <option value="bs"><?php print_r($countries['bs']); ?></option>
                                                    <option value="cn"><?php print_r($countries['cn']); ?></option>
                                                    <option value="cz"><?php print_r($countries['cz']); ?></option>
                                                    <option value="da"><?php print_r($countries['da']); ?></option>
                                                    <option value="de"><?php print_r($countries['de']); ?></option>
                                                    <option value="el"><?php print_r($countries['el']); ?></option>
                                                    <option value="en"><?php print_r($countries['en']); ?></option>
                                                    <option value="es"><?php print_r($countries['es']); ?></option>
                                                    <option value="fa"><?php print_r($countries['fa']); ?></option>
                                                    <option value="fi"><?php print_r($countries['fi']); ?></option>
                                                    <option value="fr"><?php print_r($countries['fr']); ?></option>
                                                    <option value="hu"><?php print_r($countries['hu']); ?></option>
                                                    <option value="id"><?php print_r($countries['id']); ?></option>
                                                    <option value="it"><?php print_r($countries['it']); ?></option>
                                                    <option value="ja"><?php print_r($countries['ja']); ?></option>
                                                    <option value="ka"><?php print_r($countries['ka']); ?></option>
                                                    <option value="nl"><?php print_r($countries['nl']); ?></option>
                                                    <option value="no"><?php print_r($countries['no']); ?></option>
                                                    <option value="pl"><?php print_r($countries['pl']); ?></option>
                                                    <option value="pt-BR"><?php print_r($countries['pt-BR']); ?></option>
                                                    <option value="pt"><?php print_r($countries['pt']); ?></option>
                                                    <option value="ro"><?php print_r($countries['ro']); ?></option>
                                                    <option value="ru"><?php print_r($countries['ru']); ?></option>
                                                    <option value="se"><?php print_r($countries['se']); ?></option>
                                                    <option value="tr"><?php print_r($countries['tr']); ?></option>
                                                    <option value="tw"><?php print_r($countries['tw']); ?></option>
                                                    <option value="ua"><?php print_r($countries['ua']); ?></option>
                                                    <option value="vi"><?php print_r($countries['vi']); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12"><?php echo _("Default Nameservers"); ?></label>
                                            <div class="col-md-12">

                                                <div><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[0]); ?>" class="form-control form-control-line" name="ns1" id="ns1x"><br></div>

                                                <div><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[1]); ?>" class="form-control form-control-line" name="ns2" id="ns2x"><br><div id="ns2wrapper"><a style="cursor:pointer;" id="addmore" onclick="add1();"><?php echo _("Add One"); ?></a></div></div>

                                                <div id="ns3" style="display:<?php if(explode(',', ($admindata['NS']))[2] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[2]); ?>" class="form-control form-control-line" name="ns3" id="ns3x"><br><div id="ns3wrapper"><a style="cursor:pointer;" id="addmore1" onclick="add2();"><?php echo _("Add One"); ?></a> / <a style="cursor:pointer;" id="remove1" onclick="rem2();"><?php echo _("Remove One"); ?></a></div></div>

                                                <div id="ns4" style="display:<?php if(explode(',', ($admindata['NS']))[3] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[3]); ?>" class="form-control form-control-line" name="ns4" id="ns4x"><br><div id="ns4wrapper"><a style="cursor:pointer;" id="addmore2" onclick="add3();"><?php echo _("Add One"); ?></a> / <a style="cursor:pointer;" id="remove2" onclick="rem3();"><?php echo _("Remove One"); ?></a></div></div>

                                                <div id="ns5" style="display:<?php if(explode(',', ($admindata['NS']))[4] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[4]); ?>" class="form-control form-control-line" name="ns5" id="ns5x"><br><div id="ns5wrapper"><a style="cursor:pointer;" id="addmore3" onclick="add4();"><?php echo _("Add One"); ?></a> / <a style="cursor:pointer;" id="remove3" onclick="rem4();"><?php echo _("Remove One"); ?></a></div></div>

                                                <div id="ns6" style="display:<?php if(explode(',', ($admindata['NS']))[5] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[5]); ?>" class="form-control form-control-line" name="ns6" id="ns6x"><br><div id="ns6wrapper"><a style="cursor:pointer;" id="addmore4" onclick="add5();"><?php echo _("Add One"); ?></a> / <a style="cursor:pointer;" id="remove4" onclick="rem5();"><?php echo _("Remove One"); ?></a></div></div>

                                                <div id="ns7" style="display:<?php if(explode(',', ($admindata['NS']))[6] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[6]); ?>" class="form-control form-control-line" name="ns7" id="ns7x"><br><div id="ns7wrapper"><a style="cursor:pointer;" id="addmore5" onclick="add6();"><?php echo _("Add One"); ?></a> / <a style="cursor:pointer;" id="remove5" onclick="rem6();"><?php echo _("Remove One"); ?></a></div></div>

                                                <div id="ns8" style="display:<?php if(explode(',', ($admindata['NS']))[7] == ''){ echo "none"; } else { echo "block"; } ?>"><input type="text" value="<?php print_r(explode(',', ($admindata['NS']))[7]); ?>" class="form-control form-control-line" name="ns8" id="ns8x"><br><div id="ns8wrapper"><a style="cursor:pointer;" id="remove6" onclick="rem7();"><?php echo _("Remove One"); ?></a></div></div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="overflow: visible;">
                                            <label class="col-md-12"><?php echo _("Theme"); ?></label>
                                            <div class="col-md-12">
                                                <select class="form-control" name="cookie">
                                                    <option value="default" <?php if(base64_decode($_COOKIE["theme"]) == "default.css") { echo "selected"; } ?>><?php echo _("Default"); ?></option>
                                                    <option value="blue" <?php if(base64_decode($_COOKIE["theme"]) == "blue.css") { echo "selected"; } ?>><?php echo _("Blue"); ?></option>
                                                    <option value="purple" <?php if(base64_decode($_COOKIE["theme"]) == "purple.css") { echo "selected"; } ?>><?php echo _("Purple"); ?></option>
                                                    <option value="orange" <?php if(base64_decode($_COOKIE["theme"]) == "orange.css") { echo "selected"; } ?>><?php echo _("Orange"); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button class="btn btn-success"><?php echo _("Update Profile"); ?></button> &nbsp;
                                                <a href="profile.php" style="color: inherit;text-decoration: inherit;"><button class="btn btn-muted" type="button"><?php echo _("Back"); ?></button></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer text-center">&copy; <?php echo _("Copyright"); ?> <?php echo date("Y") . ' ' . $sitetitle; ?>. <?php echo _("All Rights Reserved. Vesta Web Interface"); ?> <?php require 'includes/versioncheck.php'; ?> <?php echo _("by CDG Web Services"); ?>.</footer>
        </div>
        </div>
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/waves.js"></script>
    <script src="js/custom.js"></script>
    <script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <script src="bootstrap/dist/js/bootstrap-select.min.js"></script>
    <script src="plugins/bower_components/custom-select/custom-select.min.js"></script>
    <script>
        function toggler(e) {
            if( e.name == 'Hide' ) {
                e.name = 'Show'
                document.getElementById('password').type="password";
            } else {
                e.name = 'Hide'
                document.getElementById('password').type="text";
            }
        }
        function generatePassword(length) {
            var password = '', character; 
            while (length > password.length) {
                if (password.indexOf(character = String.fromCharCode(Math.floor(Math.random() * 94) + 33), Math.floor(password.length / 94) * 94) < 0) {
                    password += character;
                }
            }
            document.getElementById('password').value = password;
            document.getElementById('tg').name='Hide';
            document.getElementById('password').type="text";
        }
        $(document).ready(function() {
            $('.select2').select2();
        });

        document.getElementById('select2').value = '<?php print_r($admindata['LANGUAGE']); ?>';  


        <?php 
        $checkcount = 2;
        $check1count = 3;

        while($checkcount <= 7) {
            echo "if( document.getElementById('ns" . $check1count . "x').value != '') {
            document.getElementById('ns" . $checkcount . "wrapper').style.display = 'none';
}";

            $checkcount++;
            $check1count++;
        }

        $addcount = 1;
        $add1count = 2; 
        $add2count = 3; 


        while($addcount <= 6) {
            echo "function add" . $addcount ."() {
if( document.getElementById('ns" . $add2count . "').style.display = 'none' ) {
            document.getElementById('ns" . $add2count . "').style.display = 'block'; 
            document.getElementById('ns" . $add1count . "wrapper').style.display = 'none';
        } 
}";
            $addcount++;
            $add1count++;
            $add2count++;
        } 

        $remcount = 2;
        $rem1count = 3; 


        while($remcount <= 7) {
            echo "function rem" . $remcount ."() {
if( document.getElementById('ns" . $rem1count . "').style.display = 'block' ) {
            document.getElementById('ns" . $rem1count . "').style.display = 'none'; 
            document.getElementById('ns" . $remcount . "wrapper').style.display = 'block';
            document.getElementById('ns" . $rem1count . "x').value = '';
        } 
}";
            $remcount++;
            $rem1count++;
        } 
            
            $returntotal = $_POST['r1'] + $_POST['r2'] + $_POST['r3'] + $_POST['r4'] + $_POST['r5'];
            if(isset($_POST['r1']) && $returntotal == 0) {
                echo "swal({title:'" . _("Successfully Updated!") . "', type:'success'});";
            } 
            if(isset($_POST['r1']) && $returntotal != 0) {
                echo "swal({title:'" . _("Error Updating Profile") . "<br>" . "(E: " . $_POST['r1'] . "." . $_POST['r2'] . "." . $_POST['r3'] . "." . $_POST['r4'] . "." . $_POST['r5'] . ")<br><br>" . _("Please try again or contact support.") . "', type:'error'});";
            }
    
    ?>
    </script>
    <?php if(INTERAKT_APP_ID != ''){ echo '
<script>
  (function() {
  var interakt = document.createElement("script");
  interakt.type = "text/javascript"; interakt.async = true;
  interakt.src = "//cdn.interakt.co/interakt/' . INTERAKT_APP_ID . '.js";
  var scrpt = document.getElementsByTagName("script")[0];
  scrpt.parentNode.insertBefore(interakt, scrpt);
  })()
</script>'; } ?>

    </body>

</html>
