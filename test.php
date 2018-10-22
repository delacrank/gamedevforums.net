<form class = 'form  login_form dip' name='forgot_pass' action='' method='post' onsubmit='return forgot_password_form()'>
        <div>
        <fieldset>
        <p>
            <label for='xusername'>Username: </label> 
            <input type='text' id='xusername' name='fUsername' size='20' maxlength='20'/>
            <span id='fpUsername_err'></span>
        </p>
        <p>
            <label for ='mother_name'>Mother or Grandmothers Maiden Name: </label>
            <input type='text' id='mother_name' name='mother_name' size='25' maxlength='25'/>
            <span id='fpMother_name_err'></span>
        </p>
    <!-- <?php /*
    require_once('./recaptchalib.php');
    $publickey = '6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W';
    echo recaptcha_get_html($publickey); */
    ?>
    <div class="g-recaptcha" data-sitekey="6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W"></div> -->
        
        <input type='hidden' name='fSubmitted' value='TRUE' />
        <input type='submit' name='submit' value='Reset My Password' />
    </fieldset>
    </div>
    
</form>

