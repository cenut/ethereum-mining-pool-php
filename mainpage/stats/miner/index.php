<?php
error_reporting(error_reporting() & ~E_NOTICE);
$m = new Memcached();
include('/var/www4/BigInteger.php');
$config = include('../../../config.php');
$m->addServer('localhost', 11211);
$ether_wei = 1000000000000000000;

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

$real_visitor_ip = $_SERVER["REMOTE_ADDR"];
$real_visitor_ip_mem = $m->get($real_visitor_ip);
if (!$real_visitor_ip_mem) {
    $m->set($real_visitor_ip,'true',1);
} else{
   die('too many request from your ip to this endpoint');
}


function mysql_fix_escape_string($text){
    if(is_array($text)) 
        return array_map(__METHOD__, $text); 
    if(!empty($text) && is_string($text)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), 
                           array('', '', '', '', "", '', ''),$text); 
    } 
    $text = str_replace("'","",$text);
    $text = str_replace('"',"",$text);
    return $text;
}

             
$miner = $_GET['address'];
if (!$miner) {
	die('<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>Miner Statistics</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Etheruempool is stable, transparent and fair mining pool with low fee and great support! Just switch your rig to us, and see it on yourself :)">
    <meta name="author" content="Ethereumpool">
    <meta property="og:type"               content="website" />
    <meta property="og:title"              content="Ethereum Mining Pool"/>
    <meta property="og:description"        content="Ethereumpool is stable, transparent and fair mining pool with low fee and great support! Just switch your rig to us, and see it on yourself :)"/>
    <link rel="shortcut icon" href="../favicon.ico">  
    <meta name="keywords" content="eth,gpu,mining,mine,ethereum,calculator,profitability,profit,how,to,ether,ethers">
    <link href="http://fonts.googleapis.com/css?family=Merriweather+Sans:700,300italic,400italic,700italic,300,400" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">   
    <!-- Plugins CSS -->    
    <link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="/assets/css/styles-2.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head> 
<body class="blog-home-page">   
    <div class="header-wrapper header-wrapper-blog-home">
        <!-- ******HEADER****** --> 
        <header id="header" class="header navbar-fixed-top">  
            <div class="container">       
                <h1 class="logo">
                    <a href="../"><span class="highlight">Ethereum</span>pool</a>
                </h1><!--//logo-->
                <nav class="main-nav navbar-right" role="navigation">
                    <div class="navbar-header">
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button><!--//nav-toggle-->
                    </div><!--//navbar-header-->
                    <div id="navbar-collapse" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"><a href="/">Home</a></li>
                            <li class="nav-item"><a href="/stats">Stats</a></li>
                            <li class="nav-item"><a href="/charts">Charts</a></li>
                            <li class="active av-item"><a href="/stats/miner/">Miner Stats</a></li>              
                            <li class="nav-item last"><a href="/how">How to Mine?</a></li>
                            <li class="nav-item last"><a href="mailto:mail@mail.com">Support</a></li>
                        </ul><!--//nav-->
                    </div><!--//navabr-collapse-->
                </nav><!--//main-nav-->
            </div><!--//container-->
        </header><!--//header-->   
        
    
    <!-- ******Contact Section****** --> 
    <section class="contact-section section">
        <div class="container">
            <h2 class="title text-center"><br>Miner Statistics</h2>
            <p class="intro text-left"></p>
             <p class="intro text-left"><font color="F22613"></p></font>
            <form id="contact-form" class="contact-form form" method="get" action="index.php">                    
                <div class="row text-left">
                    <div class="contact-form-inner col-md-8 col-sm-12 col-xs-12 col-md-offset-2 col-sm-offset-0 xs-offset-0">
                        <div class="row">                                                                                       
                            <input type="text" class="form-control" id="address" name="address" placeholder="Put here your mining address the same as in ethminer" minlength="30" required>
                        </div><br>
                        <center>    <button type="submit" class="btn btn-block btn-cta btn-cta-primary" style="width:30%; height:120%;">Go</button>
                        <br><br><br><br></center>
 
                    </div>
                </div><!--//row-->
                <div id="form-messages"></div>
            </form><!--//contact-form-->
        </div><!--//container-->
    </section><!--//contact-section-->
    
            
   <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-3 col-sm-4 links-col">
                        <div class="footer-col-inner">
                            <h3 class="sub-title">Quick Links</h3>
                            <ul class="list-unstyled">
                                <li><a href="/">Home</a></li>
                                <li><a href="/stats">Pool statistics</a></li>
                                <li><a href="../charts">Charts</a></li>
                                <li><a href="/stats/miner/">Miner statistics</a></li>
                                <li><a href="/how">How to start mine?</a></li>                           
                                <li><a href="mailto:mail@mail.com">Support</a></li>
                            </ul>
                        </div><!--//footer-col-inner-->
                    </div><!--//foooter-col-->
                     <div class="footer-col col-md-6 col-sm-8 blog-col">
                                <br>
                            </div><!--//foooter-col--> 
                    <div class="footer-col col-md-3 col-sm-12 contact-col">
                        <div class="footer-col-inner">
                            <h3 class="sub-title"></h3>
                            <p class="intro"></p>
                            <div class="row">
                                <p class="adr clearfix col-md-12 col-sm-4">
                                    <span class="adr-group">
                                    </span>
                                </p>
                            </div> 
                        </div><!--//footer-col-inner-->            
                    </div><!--//foooter-col-->   
                </div>   
            </div>        
        </div><!--//footer-content-->    
    <script  type="text/javascript" src="/assets/plugins/jquery-1.11.2.min.js"></script>
    <script  type="text/javascript" src="/assets/plugins/jquery-migrate-1.2.1.min.js"></script>
    <script  type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
    <script  type="text/javascript" src="/assets/plugins/bootstrap-hover-dropdown.min.js"></script>       
    <script  type="text/javascript" src="/assets/plugins/back-to-top.js"></script>             
    <script  type="text/javascript" src="/assets/plugins/jquery-placeholder/jquery.placeholder.js"></script>                                                                  
    <script  type="text/javascript" src="/assets/plugins/jquery-match-height/jquery.matchHeight-min.js"></script>     
    <script  type="text/javascript" src="/assets/plugins/FitVids/jquery.fitvids.js"></script>
    <script  type="text/javascript" src="/assets/js/main.js"></script>     
    <script  type="text/javascript" src="/assets/plugins/jquery.validate.min.js"></script> 
    <script  type="text/javascript" src="/assets/js/form-validation-custom.js"></script> 
    <script  type="text/javascript" src="/assets/plugins/isMobile/isMobile.min.js"></script>
    <script  type="text/javascript" src="/assets/js/form-mobile-fix.js"></script>     
</body>
</html>');
}
$miner = mysql_fix_escape_string($miner);
$mysqli=mysqli_connect($config['host'], $config['username'], $config['password'], $config['bdd']) or die("Database Error");
$existQuery = "SELECT balance FROM miners WHERE address='$miner'";
$existResult = mysqli_query($mysqli,$existQuery)or die("Database Error");
$existRow = mysqli_fetch_array($existResult);
$balance = new Math_BigInteger($existRow[0]);
$ether = floatval($balance->toString())/$ether_wei;


$existQuery = "SELECT minerdiff FROM shares";
$existResultMinersss = mysqli_query($mysqli,$existQuery)or die("Database Error");
$count_response  = mysqli_num_rows($existResultMinersss);
$totalMinersDiffPower = new Math_BigInteger('0');
$sharesCountTotal = 0;
while ($row=mysqli_fetch_row($existResultMinersss)){
    $miner_adr_balance = new Math_BigInteger($row[0]);
    $totalMinersDiffPower = $totalMinersDiffPower->add($miner_adr_balance);
    $sharesCountTotal++;
}

$existQuery = "SELECT minerdiff FROM shares WHERE address = '$miner'";
$existResultMinersss = mysqli_query($mysqli,$existQuery)or die("Database Error");
$count_response  = mysqli_num_rows($existResultMinersss);
$ThisDiffPower = new Math_BigInteger('0');
$sharesCountTotalMiner = 0;
while ($row=mysqli_fetch_row($existResultMinersss)){
    $miner_adr_balance = new Math_BigInteger($row[0]);
    $ThisDiffPower = $ThisDiffPower->add($miner_adr_balance);
    $sharesCountTotalMiner++;
}

$result = mysqli_query($mysqli,"select count(1) FROM shares_history WHERE address='$miner'");
$sharesCount = mysqli_fetch_array($result);
$totalSharesHistory = $sharesCount[0];
$result = mysqli_query($mysqli,"select count(1) FROM shares_invalid WHERE address='$miner'");
$sharesCount = mysqli_fetch_array($result);
$totalInvalidShares = $sharesCount[0];


echo '<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>Statistics</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ethereumpool is stable, transparent and fair mining pool with low fee and great support! Just switch your rig to us, and see it on yourself :)">
    <meta name="author" content="eth">
    <meta property="og:type"               content="website" />
    <meta property="og:title"              content="Ethereum Mining Pool"/>
    <meta property="og:description"        content="ethereumpool is stable, transparent and fair mining pool with low fee and great support! Just switch your rig to us, and see it on yourself :)"/>
    <link rel="shortcut icon" href="../favicon.ico">  
    <meta name="keywords" content="eth,gpu,mining,mine,ethereum,calculator,profitability,profit,how,to,ether,ethers">
    <link href="http://fonts.googleapis.com/css?family=Merriweather+Sans:700,300italic,400italic,700italic,300,400" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">   
    <!-- Plugins CSS -->    
    <link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="/assets/css/styles-2.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        <style>
    .button-fill {
  text-align: center;
  background: #ccc;
  display: inline-block;
  position: relative;
  text-transform: uppercase;
  margin: 8px;
}
.button-fill.grey {
  background: #444B54;
  color: white;
}
.button-fill.orange .button-inside {
  color: #f26b43;
}
.button-fill.orange .button-inside.full {
  border: 1px solid #f26b43;
}
.button-text {
  padding: 0 25px;
  line-height: 56px;
  letter-spacing: .1em;
}
.button-inside {
  width: 0px;
  height: 54px;
  margin: 0;
  float: left;
  position: absolute;
  top: 1px;
  left: 50%;
  line-height: 54px;
  color: #445561;
  background: #fff;
  text-align: center;
  overflow: hidden;
  -webkit-transition: width 0.5s, left 0.5s, margin 0.5s;
  -moz-transition: width 0.5s, left 0.5s, margin 0.5s;
  -o-transition: width 0.5s, left 0.5s, margin 0.5s;
  transition: width 0.5s, left 0.5s, margin 0.5s;
}
.button-inside.full {
  width: 100%;
  left: 0%;
  top: 0;
  margin-right: -50px;
  border: 1px solid #445561;
}
.inside-text {
  text-align: center;
  position: absolute;
  right: 50%;
  letter-spacing: .1em;
  text-transform: uppercase;
  -webkit-transform: translateX(50%);
  -moz-transform: translateX(50%);
  -ms-transform: translateX(50%);
  transform: translateX(50%);
}
</style>
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head> 
<body class="blog-home-page">   
    <div class="header-wrapper header-wrapper-blog-home">
        <!-- ******HEADER****** --> 
        <header id="header" class="header navbar-fixed-top">  
            <div class="container">       
                <h1 class="logo">
                    <a href="../"><span class="highlight">Ethereum</span>Pool.co</a>
                </h1><!--//logo-->
                <nav class="main-nav navbar-right" role="navigation">
                    <div class="navbar-header">
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button><!--//nav-toggle-->
                    </div><!--//navbar-header-->
                    <div id="navbar-collapse" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"><a href="/">Home</a></li>
                            <li class="nav-item"><a href="/stats">Stats</a></li>
                            <li class="nav-item"><a href="/charts">Charts</a></li>
                            <li class="active nav-item"><a href="/miner/">Miner Stats</a></li>              
                            <li class="nav-item last"><a href="/how">How to Mine?</a></li>
                            <li class="nav-item"><a href="/forums">Forum threads</a></li>
                            <li class="nav-item last"><a href="mailto:mail@mail.com">Support</a></li>
                        </ul><!--//nav-->
                    </div><!--//navabr-collapse-->
                </nav><!--//main-nav-->
            </div><!--//container-->
        </header><!--//header-->   
        
    
    <!-- ******Contact Section****** --> 
    <section class="contact-section section">
        <div class="container">
            <h2 class="title text-center"><br>Miner Statistics</h2>
            <p class="intro text-left"></p>
             <p class="intro text-left"><font color="F22613"></p></font>
            <form id="contact-form" class="contact-form form" method="post" action="push.php">                    
                <div class="row text-left">
                    <div class="contact-form-inner col-md-8 col-sm-12 col-xs-12 col-md-offset-2 col-sm-offset-0 xs-offset-0">
                        <div class="row">
                        ';                                                                                   
if (!$totalInvalidShares) {
    $totalInvalidShares = 0;
}

$existQuery = "SELECT userid,hashrate,val_timestamp FROM stats WHERE user='$miner' ORDER BY id DESC LIMIT 150;";
$existResult = mysqli_query($mysqli,$existQuery)or die("Database Error");
$newMiners = array();
$workers_count=0;
$hashrate_real = 0;
$hashrate_est = 0;

$test = array();
while ($row=mysqli_fetch_row($existResult)){
    $cur_time = time();
    $difference = $cur_time - $row[2];
    if ($difference < 61) {
        $minerid = $row[0];
        if (!isset($test["'$minerid'"])) {
            $test["'$minerid'"] = $row[1];
            $newArr = array($row[0],$row[1]);
             array_push($newMiners, $newArr);
            $workers_count++;
            $hashrate_est = $hashrate_est + $row[0];
             $hashrate_real = $hashrate_real + $row[1];
        }
    }
}

if (!$hashrate_real || $hashrate_real == 0) {
$existQuery = "SELECT hashrate FROM miner_hashrate WHERE miner='$miner' ORDER BY id DESC LIMIT 5;";
$existResult = mysqli_query($mysqli,$existQuery)or die("Database Error");
while ($row=mysqli_fetch_row($existResult)){
    $hashrate_real = $hashrate_real + $row[0];
}
$hashrate_real = $hashrate_real / 5;
}


$firstKey = 'eth_price_current';
$eth_price_usd = $m->get($firstKey);

$firstKey = 'basic_stats';
$result1 = $m->get($firstKey);
$block_info_last = json_decode($result1, true); 
$data = $block_info_last['data']['blocks'];

$counter_d = count($data);
for ($i=0; $i < $counter_d-1; $i++) { 
    $diff_avg = $diff_avg + $data[$i]['difficulty'];
    $time_avg = $time_avg + $data[$i]['blockTime'];
}
$diff_avg = $diff_avg/$counter_d-1;
$time_avg = $time_avg/$counter_d-1;

$net_hash = $diff_avg/$time_avg;
$net_hash = $net_hash * 1.2;

$user_ratio =  $hashrate_real/$net_hash;
$blocksPerMin = 60 / $time_avg;
$ethPerMin = $blocksPerMin * 5;

$rev_min = $user_ratio * $ethPerMin;
$rev_hour =$rev_min * 60;
$rev_day = $rev_hour * 24;
$rev_week = $rev_day * 7;
$rev_month = $rev_day * 30;


echo '<center>';
echo '<a href="http://etherscan.io/address/'.$miner.'" target="_blank"><div class="button-fill grey" style="width:94%"><div class="button-text">'.$miner.'</b></div><div class="button-inside"><div class="inside-text"><font size="1.5">http://etherscan.io/address/'.$miner.'</font></div></div></div></a>';
$mhash_rl = number_format((float)$hashrate_real/1000000, 2, '.', '');
$mhash_rl_g = number_format((float)$hashrate_real/1000000000, 2, '.', '');
echo '<a href="#"><div class="button-fill grey" style="width:94%"><div class="button-text">'.$mhash_rl.' MHash/s</b></div><div class="button-inside"><div class="inside-text">'.$mhash_rl_g.' GHash/s</div></div></div></a>';
echo '<a href="#"><div class="button-fill grey" style="width:94%"><div class="button-text">'.$ether.' ether</b></div><div class="button-inside"><div class="inside-text">'.$balance->toString().' wei</div></div></div></a>';

echo '</center>';


$ratio_hashrate = $hashrate_est/$mhash_rl;
if ($ratio_hashrate > 1.4) {
  //echo '<br><font color="red" size="5"><center>Something may be wrong in your ethminer or ethminer settings! Too big difference between claimed hashrate and real hashrate. You may not mine anything or your efficiency will be bad, please set valid value!</center></font>';
}

if ($mhash_rl == '' || $mhash_rl == 0) {
    echo '<br><font size="5" color="red">MINER IS INACTIVE<br>or<br>It seems your ethminer does NOT have function called eth_submithashrate. To get valid and reliable statistics please update your ethminer to lastest version. This affects only statistics, it does NOT affect revenue! If you have any concerns please feel free to contact us.</font><br>';
}

echo '<br><div id="container"></div>';
echo '<br><div id="container_balance"></div>';

echo '<br>All Shares in this round: '.$sharesCountTotal;
echo '<br>All Shares submited by me: '.$sharesCountTotalMiner;
echo '<br><br>All Shares Power in this round: '.$totalMinersDiffPower->toString();
echo '<br>My Shares Power in this round: '.$ThisDiffPower->toString();
echo '<br>My % in unprocessed blocks: '.sprintf('%f', floatval($ThisDiffPower->toString())/floatval($totalMinersDiffPower->toString())*100).'%';
echo '<br>Shares history in total: '.$totalSharesHistory;
echo '<br>Invalid shares: '.$totalInvalidShares.' <a href="/invalid" target="_blank">(should be "0" if not click!)</a>';

echo '<br><br><b>Est. revenue with avg diff from 128 blocks</b><table border="1" style="width:100%">';
echo '<tr><td>Minute</td><td>'.sprintf('%f', $rev_min).' eth</td><td>'.sprintf('%f', $rev_min*$eth_price_usd).' $</td></tr>';
echo '<tr><td>Hour</td><td>'.sprintf('%f', $rev_hour).' eth</td><td>'.sprintf('%f', $rev_hour*$eth_price_usd).' $</td></tr>';
echo '<tr><td>Day</td><td>'.sprintf('%f', $rev_day).' eth</td><td>'.sprintf('%f', $rev_day*$eth_price_usd).' $</td></tr>';
echo '<tr><td>Week</td><td>'.sprintf('%f', $rev_week).' eth</td><td>'.sprintf('%f', $rev_week*$eth_price_usd).' $</td></tr>';
echo '<tr><td>Month</td><td>'.sprintf('%f', $rev_month).' eth</td><td>'.sprintf('%f', $rev_month*$eth_price_usd).' $</td></tr>';
echo '</table>';

echo '<br><b>Number of active workers:'.$workers_count.'</b>';
echo '<table border="1" style="width:100%">';
for ($i=0; $i < $workers_count; $i++) { 
    $mhash_rl = number_format((float)$newMiners[$i][1]/1000000, 2, '.', '');
        $miner_reference = $newMiners[$i][0].$miner;
        $miner_rig = $m->get($miner_reference);
        if (!$miner_rig) {
          $miner_rig = 'rig';
        }
        echo '<tr><td><a href="/stats/miner/worker/?address='.$miner.'&worker='.$newMiners[$i][0].'">Worker ID:<b>'.$newMiners[$i][0].'-'.$miner_rig.'</b></a><br>Real hashrate: '.round($newMiners[$i][1]).' hash/s  -> </b>'.$mhash_rl.' MHASH/s';

    echo '</td></tr>';
}

//totalInvalidShares
$existQuery = "SELECT balance,time,txid,fee FROM payout_history WHERE address='$miner' ORDER BY id DESC LIMIT 50;";
$existResult = mysqli_query($mysqli,$existQuery)or die("Database Error");

echo '<table border="1" style="width:100%">';
$r = 0;
$count_r = mysqli_num_rows($existResult);
while ($row=mysqli_fetch_row($existResult)){
    $balanceforPayment = new Math_BigInteger($row[0]);
    $etherforPayment = floatval($balanceforPayment->toString())/$ether_wei;
    $r++;
    if ($r == $count_r) {
        echo '<b><br>Last 50 Payments</b><tr><td>'.$balanceforPayment->toString().' wei<br>'.$etherforPayment.' ether<br>When: '.gmdate("Y-M-d  h:i:s", $row[1]).'<br>Ethereum network fee deduced:'.$row[3].'<br>TXID:<a href="http://etherscan.io/tx/'.$row[2].'" target="_blank">'.$row[2].'</a>';
    } else {
        echo '<tr><td>'.$balanceforPayment->toString().' wei<br>'.$etherforPayment.' ether<br>When: '.gmdate("Y-M-d  h:i:s", $row[1]).'<br>Ethereum network fee deduced:'.$row[3].'<br>TXID:<a href="http://etherscan.io/tx/'.$row[2].'" target="_blank">'.$row[2].'</a>';
    }
    echo '</td></tr>';
}



    echo '</table>             <br><br>
                        </div><!--//row-->
                    </div>
                </div><!--//row-->
                <div id="form-messages"></div>
            </form><!--//contact-form-->
        </div><!--//container-->
    </section><!--//contact-section-->
    
            
   <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-3 col-sm-4 links-col">
                        <div class="footer-col-inner">
                            <h3 class="sub-title">Quick Links</h3>
                            <ul class="list-unstyled">
                                <li><a href="/">Home</a></li>
                                <li><a href="/stats">Pool statistics</a></li>
                                <li><a href="../charts">Charts</a></li>
                                <li><a href="/stats/miner/">Miner statistics</a></li>
                                <li><a href="/how">How to start mine?</a></li>  
                                <li><a href="/forums">Forum threads</a></li>                              
                                <li><a href="mailto:mail@mail.com">Support</a></li>
                            </ul>
                        </div><!--//footer-col-inner-->
                    </div><!--//foooter-col-->
                     <div class="footer-col col-md-6 col-sm-8 blog-col">
                                <br>
                            </div><!--//foooter-col--> 
                    <div class="footer-col col-md-3 col-sm-12 contact-col">
                        <div class="footer-col-inner">
                            <h3 class="sub-title"></h3>
                            <p class="intro"></p>
                            <div class="row">
                                <p class="adr clearfix col-md-12 col-sm-4">
                                    <span class="adr-group">
                                    </span>
                                </p>
                            </div> 
                        </div><!--//footer-col-inner-->            
                    </div><!--//foooter-col-->   
                </div>   
            </div>        
        </div><!--//footer-content-->
    
 
    <!-- Main Javascript -->          
    <script  type="text/javascript" src="/assets/plugins/jquery-1.11.2.min.js"></script>
    <script  type="text/javascript" src="/assets/plugins/jquery-migrate-1.2.1.min.js"></script>
    <script  type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
    <script  type="text/javascript" src="/assets/plugins/bootstrap-hover-dropdown.min.js"></script>       
    <script  type="text/javascript" src="/assets/plugins/back-to-top.js"></script>             
    <script  type="text/javascript" src="/assets/plugins/jquery-placeholder/jquery.placeholder.js"></script>                                                                  
    <script  type="text/javascript" src="/assets/plugins/jquery-match-height/jquery.matchHeight-min.js"></script>     
    <script  type="text/javascript" src="/assets/plugins/FitVids/jquery.fitvids.js"></script>
    <script  type="text/javascript" src="/assets/js/main.js"></script>     
    
    <!-- Form Validation -->
    <script  type="text/javascript" src="/assets/plugins/jquery.validate.min.js"></script> 
    <script  type="text/javascript" src="/assets/js/form-validation-custom.js"></script> 
    
    <!-- Form iOS fix -->
    <script  type="text/javascript" src="/assets/plugins/isMobile/isMobile.min.js"></script>
    <script  type="text/javascript" src="/assets/js/form-mobile-fix.js"></script>     
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    
        <script>
        $(".button-fill").hover(function () {
        $(this).children(".button-inside").addClass("full");
        }, function() {
        $(this).children(".button-inside").removeClass("full");
        });
    </script>
<script type="text/javascript">
Plotly.d3.json("/api/get/data/index.php?data=miner_hashrate&range=max&dtx='.$miner.'", function(err, rows){
    var trace1 = {
        type: "scatter",
        mode: "lines",
        name: "Hashrate",
        x: [],
        y: [],
        line: {color: "#17BECF"}
    }
    for (var i=0; i<rows.length; i++) {
        var row = rows[i];
        trace1.x.push(row[0]);
        trace1.y.push(row[1]);
    }
    var data = [trace1];
    var layout = {
        title: "Hashrate",
        plot_bgcolor: "rgba(124, 1, 1, 0)",
        paper_bgcolor: "rgba(125,1,1,0)",
        xaxis: {
            autorange: true,
            rangeselector: {buttons: [{
                count: 1,
                label: "1h",
                step: "hour",
                stepmode: "backward"
            },{
                count: 12,
                label: "12h",
                step: "hour",
                stepmode: "backward"
            },{
                count: 1,
                label: "1d",
                step: "day",
                stepmode: "backward"
            },{
                count: 3,
                label: "3d",
                step: "day",
                stepmode: "backward",
            },{
                count: 1,
                label: "1w",
                step: "week",
                stepmode: "backward"
            },{
                count: 1,
                label: "1m",
                step: "month",
                stepmode: "backward"
            },{
                count: 6,
                label: "6m",
                step: "month",
                stepmode: "backward"
            },{
                count: 1,
                label: "1y",
                step: "year",
                stepmode: "backward"
            },{
                step: "all"
            }]},
            rangeslider: {},type: "date"
        },
        yaxis: {
            autorange: true,
            type: "linear"
        }
    };
    Plotly.newPlot("container", data, layout);
});
Plotly.d3.json("/api/get/data/index.php?data=_miner_balance&range=max&rr=1&dtx='.$miner.'", function(err, rows){
    var trace1 = {
        type: "scatter",
        mode: "lines",
        name: "Balance",
        x: [],
        y: [],
        line: {color: "#17BECF"}
    }
    for (var i=0; i<rows.length; i++) {
        var row = rows[i];
        trace1.x.push(row[0]);
        trace1.y.push(row[1]);
    }
    var data = [trace1];
    var layout = {
        title: "Balance",
        plot_bgcolor: "rgba(124, 1, 1, 0)",
        paper_bgcolor: "rgba(125,1,1,0)",
        xaxis: {
            autorange: true,
            rangeselector: {buttons: [{
                count: 1,
                label: "1h",
                step: "hour",
                stepmode: "backward"
            },{
                count: 12,
                label: "12h",
                step: "hour",
                stepmode: "backward"
            },{
                count: 1,
                label: "1d",
                step: "day",
                stepmode: "backward"
            },{
                count: 3,
                label: "3d",
                step: "day",
                stepmode: "backward",
            },{
                count: 1,
                label: "1w",
                step: "week",
                stepmode: "backward"
            },{
                count: 1,
                label: "1m",
                step: "month",
                stepmode: "backward"
            },{
                count: 6,
                label: "6m",
                step: "month",
                stepmode: "backward"
            },{
                count: 1,
                label: "1y",
                step: "year",
                stepmode: "backward"
            },{
                step: "all"
            }]},
            rangeslider: {},type: "date"
        },
        yaxis: {
            autorange: true,
            type: "linear"
        }
    };
    Plotly.newPlot("container_balance", data, layout);
});
</script>
</body>
</html>
';
?>
