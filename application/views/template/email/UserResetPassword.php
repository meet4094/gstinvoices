<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" hola_ext_inject="disabled"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Welcome to <?php echo PLATFORM_NAME; ?>!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
  body{
    font:14px/20px 'Helvetica', Arial, sans-serif;
    margin:0;
    text-align:center;
    -webkit-text-size-adjust:none;
  }
  @media (max-width: 620px){
    body{
      width:100%;
      -webkit-font-smoothing:antialiased;
      padding:10px 0 0 0 !important;
      min-width:300px !important;
    }
  } @media (max-width: 620px){
    #templateContainer,#templateBody,#templateContainer table{
      width:100% !important;
      -moz-box-sizing:border-box;
      -webkit-box-sizing:border-box;
      box-sizing:border-box;
    }
  }
</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="font: 16px/22px &#39;Helvetica&#39;, Arial, sans-serif;margin: 0;padding: 0 0 0 0;text-align: center;-webkit-text-size-adjust: none;background-color: #d9d9d9;" cz-shortcut-listen="true">
  <center>
    <table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%">
      <tbody><tr>
        <td align="center" valign="top">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainer" style="background-color: #ffffff;padding: 10px 0;">
            <tbody><tr>
              <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody><tr>
                    <td>
                      <h1 style="font-size: 28px;line-height: 110%;margin-bottom: 5px;margin-top: 0;padding: 0;"><div style="margin:0 25px"><a href="<?php echo WEB_URL; ?>" style="display: block;margin: 0px auto;" target="_blank"><img align="none" src="<?php echo BASE_URL.'images/logo_wide.png'; ?>" style="width: 250px;height:auto !important;margin: 0px;display: inline-block;max-width: 100%;vertical-align: bottom;" width="250"></a></div></h1>
                    </td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
            <tr>
              <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" style="background-color: #ffffff;border-top: 5px solid #000000;padding-right: 20px;padding-left: 20px;border-bottom: 5px solid #000000;">
                  <tbody><tr>
                    <td align="left" valign="top" style="line-height: 150%;font-family: Helvetica;font-size: 14px;color: #8f8f8f;padding: 20px;">
                      <h3 style="color: #000000;font-size: 22px;line-height: 28px;font-weight: 700;margin: 0 0 10px 0;">Welcome to <?php echo PLATFORM_NAME; ?>!</h3>
                      <p style="color: #000000;font-size: 18px;line-height: 22px;padding: 0 0 10px 0;">There was recently a request to change the password for your account. <br><br>If you requested this password change, please click on the following link to reset your password:</p>
                      <a class="button" href="<?php echo BASE_URL.'services/user/fpassword/'.$token; ?>" style="color: #ffffff !important;display: inline-block;font-weight: 500;font-size: 16px;line-height: 42px;font-family: &#39;Helvetica&#39;, Arial, sans-serif;width: auto;white-space: nowrap;height: 42px;margin: 12px 5px 12px 0;padding: 0 22px;text-decoration: none;text-align: center;cursor: pointer;border: 0;border-radius: 3px;vertical-align: top;background-color: #000000 !important;"><span style="display: inline-block;font-family: &#39;Helvetica&#39;, Arial, sans-serif;text-decoration: none;font-weight: 500;font-style: normal;font-size: 16px;line-height: 42px;cursor: pointer;border: none;background-color: #000000 !important;color: #ffffff !important;"><strong>Reset Password!</strong></span></a>
                      <p style="color: #000000;font-size: 18px;line-height: 22px;padding: 0 0 10px 0;">Thank you so much, we'll see you shortly!</p>
                     </td>
                   </tr>
                 </tbody></table>
               </td>
             </tr>
             <tr>
              <td align="center" valign="top">
                <table border="0" cellpadding="20" cellspacing="0" width="600">
                  <tbody><tr>
                    <td align="center" valign="top"><?php echo date("Y"); ?> &copy; <?php echo PLATFORM_NAME; ?>.</td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>
  </center>
</body></html>