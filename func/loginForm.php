<?php

function loginForm()
{
    echo
    '<div id="loginform">
    <p>Please log in to continue!<br><a class="register" href="?register=true">Create an account</a></p>
    
    <form action="index.php" method="post">
      <input type="text" name="name" id="name" class="name" placeholder="Email" />
      <input type="password" name="password" id="password" class="password" placeholder="Password" />
      <input type="submit" name="enter" id="enter" value="Log in" class="enter" />
    </form>
  </div>';
}