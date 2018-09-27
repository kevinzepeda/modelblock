<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>AgileTeam Project Management Tool Setup</title>

    <!-- Bootstrap 3.3.2 -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="'/assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>

    <link href="/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/dist/css/app.css" rel="stylesheet" type="text/css"/>
</head>
<body class="login-page board" style="background-color:#eaeaea;">
<div class="login-box">
    <div class="login-logo">
        <b>Agile</b>Team Setup
        <div style="font-size:14px;">Just a few fields and you are ready to go!</div>
    </div><!-- /.login-logo -->
    <div class="login-box-body">

        <div class="row" style="margin:0px;">

            <?php if(count($file_premissions_issues) > 0){  ?>
            <form role="form" method="POST" action="http://{{$_SERVER['HTTP_HOST']}}/install">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="event" name="event" value="priv"/>
                <div class="col-xs-12">
                    <div class="alert alert-success" style="font-size:11px;">
                        Before you can continue with the installation you need to change the priviledges for the
                        listed directories below to 777. You can try to do that by clicking on the button below or
                        manually:
                        <ul style="padding:15px;font-size:9px;">
                            <?php
                            foreach($file_premissions_issues as $directory) {
                            ?>
                            <li><?php echo $directory['name'] . ' (' . $directory['premissions'] . ')';?></li>
                            <?php
                            }
                            ?>
                        </ul>
                        In case if you are still not able to continue with the installation please double check if
                        there are any issues on the server side and if the priviledges were changed, if not you will
                        need to change them manually for each listed directory. Once you make these changes manually
                        just refresh this page.
                    </div>
                </div><!-- /.col -->
            </form>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-flat">Try to change the priviledges</button>
        </form>
        <?php } else if (!$installed ){ ?>

        <?php if(count($error) > 0 || $premissions_issue !== false){  ?>
        <div class="col-xs-12">
            <div class="alert alert-danger" style="font-size:11px;">
                Uppss something is not right, please try to fix the issues below:
                <ul style="padding:15px;font-size:9px;">
                    <?php
                    if($premissions_issue !== false){
                        echo "The Final step of the setup has failed due to a server side priviledges setup. In order to complete the installation please manually create a file called '.env' in the root of the AgileTeam Directory with this content:<br/>";
                        echo $premissions_issue;
                    }else{
                    foreach($error as $errorno) {
                    ?>
                    <li><?php echo $errorno; ?></li>
                    <?php
                    }
                    }
                    ?>
                </ul>
            </div>
        </div><!-- /.col -->
    </div>
    <?php } ?>

    <form role="form" method="POST" action="http://{{$_SERVER['HTTP_HOST']}}/install">
        <input type="hidden" id="event" name="event" value="install"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h4 class="page-header" style="width:100%;text-align:center;">Database</h4>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="dbhost" value="" placeholder="Database Host : Port"/>
        </div>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="dbname" value="" placeholder="Database Name"/>
        </div>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="dbuser" value="" placeholder="Database User"/>
        </div>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="dbpassword" value="" placeholder="Database Password"/>
        </div>
        <h4 class="page-header" style="width:100%;text-align:center;">Admin User</h4>
        <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email_address" value="" placeholder="Admin Email"/>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" value="" placeholder="Admin Password"/>
        </div>

        <div class="row" style="margin:0px;">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Install</button>
            </div><!-- /.col -->
            <div class="col-xs-2">
                &nbsp;
            </div><!-- /.col -->
        </div>

    </form>

    <?php } else {?>
    <div style="text-align:center;">
        You have successfully installed the AgileTeam. Enjoy using it and good luck with your business !<br/><br/>
        <a type="submit" href="/" class="btn btn-primary btn-block btn-flat">Login to AgileTeam</a>
    </div>
    <?php } ?>
</div><!-- /.login-box-body -->
</body>
</html>
