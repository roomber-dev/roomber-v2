<?php

function registerForm()
{
    echo
    '<div id="registerform">
    <p>Please log in to continue!<br><a class="register" href="?logout=true">Already have an account?</a></p>
    
    <form action="index.php?register=true" method="post">
     <input type="text" name="username" id="username" class="username" placeholder="Username" /><br>
      <input type="text" name="email" id="email" class="email" placeholder="Email" /><br>
      <input type="password" name="password" id="password" class="password" placeholder="Password" />
      <input type="password" name="repeatpassword" id="repeatpassword" class="repeatpassword" placeholder="Repeat Password" /><br>
      <label>
      <input type="checkbox" name="tos" /> I accept the Terms of Service
      </label><br>

      <div class="g-recaptcha" data-sitekey="6LdeksMbAAAAAJhmKqq7XHhy-UTZ3Z5uSN8M_n52"></div><br>
      <input type="submit" name="enter" id="enter" value="Create account" class="enter" />
    </form>
  </div>';
}