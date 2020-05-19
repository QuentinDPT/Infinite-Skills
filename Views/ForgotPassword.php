<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>


      <main class="container">
        <section class="row">
          <div class="col-md-6">
            <h2 class="primary">Reset password</h2>
            <p class="basic"></p>
            <div class="container">
                <form id="form-reset" action="/api/forgotPassword" method="post">
                    <table class="w-100">
                      <tr>
                          <td class="basic">Username or email</td>
                          <td><input type="text" name="login" class="form-control w-100" placeholder="Xx_d4rkKill3r_xX" required/></td>
                      </tr>
                      <tr>
                          <td></td>
                          <td><button type="submit" class="btn raised-primary">e-Mail me !</button></td>
                      </tr>
                    </table>
                </form>
            </div>
          </div>
        </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>
</html>
