<?php session_start() ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>
      <main class="container">
        <section class="row">
          <div class="col-md-6">
            <h2 class="primary">Connection</h2>
            <p class="basic">Do you want to have access to all your purchases? Just login!</p>
            <div class="container">
                <form id="form-auth" action="" method="post">
                    <table class="w-100">
                      <tr>
                          <td class="basic">Username or email</td>
                          <td><input type="text" name="login" class="form-control w-100" placeholder="Xx_d4rkKill3r_xX" required/></td>
                      </tr>
                      <tr>
                          <td class="basic">Password</td>
                          <td><input type="password" name="password" class="form-control w-100" placeholder="******" required/></td>
                      </tr>
                      <tr>
                          <td><button type="submit" class="btn raised-primary">Connect!</button>
                          <td>
                            <a href="/forgotPassword">Forgot password ?</a>
                          </td>
                      </tr>
                    </table>
                </form>
            </div>
          </div>
          <div class="col-md-6">
            <h2 class="primary">Sign up</h2>
            <p class="basic">Access Infinite skills content from anywhere.</p>
            <p class="basic">A free premium week is offered for opening an account!</p>
            <div class="container">
              <form id="form-register" action="/api/signup" method="post">
                <table class="w-100">
                  <tr>
                      <td><label class="basic" for="register_pseudo">Username</label></td>
                      <td><input id="register_pseudo" name="login" type="text" class="form-control w-100" placeholder="XxDarkKiller_67xX" required/></td>
                  </tr>
                  <tr>
                      <td><label class="basic" for="register_mail">Mail</label></td>
                      <td><input id="register_mail" name="mail" type="text" class="form-control w-100" placeholder="alain-chabbat@gmail.com" required/></td>
                  </tr>
                  <tr>
                      <td><label class="basic" for="register_mail_confirm">Confirm mail</label></td>
                      <td><input id="register_mail_confirm" name="mail_confirm" type="text" class="form-control w-100" placeholder="alain-chabbat@gmail.com" required/></td>
                  </tr>
                  <tr>
                      <td><label class="basic" for="register_password">Password</label></td>
                      <td><input id="register_password" name="password" type="password" class="form-control w-100" placeholder="password" required/></td>
                  </tr>
                  <tr>
                      <td><label class="basic" for="register_password_confirm">Confirm password</label></td>
                      <td><input id="register_password_confirm" name="password_confirm" type="password" class="form-control w-100" placeholder="password" required/></td>
                  </tr>
                  <tr>
                      <td><button type="submit" class="btn raised-primary" name="button">Sign up!</button>
                      <td></td>
                  </tr>
                </table>
              </form>
            </div>
          </div>
        </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>
  <script type="text/javascript">
    $("#form-auth").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
           type: "POST",
           url: "/api/authenticate",
           data: data,
           success: function(res){
               if(res == 1){
                   location.href = "/home";
               }else{
                   $("#auth-error").remove();
                   $("<span id='auth-error' class='badge badge-danger'>identifiant ou mot de passe incorrect</span>").insertBefore("#form-auth");
               }
           }
        });
    });

    $("#form-register").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        $.ajax({
           type: "POST",
           url: "/api/signup",
           data: data,
           success: function(res){
               if(res == 1){
                   location.href = "/home";
               }else{
                   $("#reg-error").remove();
                   $("<span id='reg-error' class='badge badge-danger'>la création du compte a échoué</span>").insertBefore("#form-register");
               }
           }
        });
    });
  </script>
</html>
