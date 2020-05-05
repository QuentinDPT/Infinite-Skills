<?php
require_once("./Controllers/C_Theme.php");
require_once("./Controllers/C_User.php");
$navbarUser = -1;
$listThemesUser = [];
if (isset($_SESSION["User"])) {
    $navbarUser = C_User::GetUserById($_SESSION["User"]);
    $listThemesUser = C_Theme::GetThemesByUserId($navbarUser->getId());
}
?>
<link rel="stylesheet" href="/src/styles/navbar.css">
<nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-dark c border-bottom box-shadow mb-3 navbar-nav">
  <div class="container">
    <div class="navbar-brand" href=".">Infinite skills</div>
    <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
        <ul class="navbar-nav flex-grow-1">
            <li class="nav-item">
              <a class="nav-link bg-transparent <?php if($NavActive == "Acceuil") echo "active disabled" ; ?>" href="./home">Home<a/>
            </li>
            <li class="nav-item dropdown">
                <input type="button" class="nav-link bg-transparent dropdown-toggle" value="Themes" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"/>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($navbarUser !== -1) { ?>
                        <?php if (count($listThemesUser) == 0) {?>
                            <a href="#" class="dropdown-item text-primary">Let's choose your themes!</a>
                        <?php } ?>
                        <form action="/search" method="get">
                            <?php for ($i=0; $i < count($listThemesUser); $i++) { ?>
                                <button class="dropdown-item" type="submit" onclick="document.getElementById('ThemeName').value = <?php echo $listThemesUser[$i]->getId(); ?>"><?php echo $listThemesUser[$i]->getName(); ?></a>
                            <?php } ?>
                        <input type="hidden" id="ThemeName" name="t" value="">
                        </form>
                    <?php } else { ?>
                        <a class="dropdown-item text-primary" href="/connection">Login</a>
                    <?php } ?>
                </div>
            </li>
            <li class="nav-item w-100">
                <form action="/search" method="get" id="formSearch">
                    <input type="text" class="nav-link w-100 text-dark" placeholder="Search" id="searchInput" <?php echo (isset($_GET['t']) ? 'value="' . C_Theme::GetThemeById($_GET['t'])->getName() . '"' :
                    (isset($_GET['s']) ? 'value="' . $_GET['s'] . '"' : "")) ?> name="s"/>
                </form>
            </li>
            <li>
              <a class="nav-link bg-transparent <?php if($NavActive == "Connection") echo "active disabled" ; ?>" <?php if(isset($_SESSION['User'])) echo "href='./logout'> Logout" ; else echo "href='./connection'> Login" ; ?><a/>
            </li>
            <?php if ($navbarUser !== -1) { ?>
            <li class="nav-item dropdown">
                    <img src="<?php echo $navbarUser->getAvatar(); ?>" alt="Avatar" class="rounded-circle navbar-icon">
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <form action="/users" method="get">
                            <button type="submit" class="dropdown-item"><?php echo $navbarUser->getName(); ?></button>
                            <input type="hidden" name="u" value="<?php echo $navbarUser->getId(); ?>">
                        </form>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/settings">Settings</a>
                        <a class="dropdown-item" href="/logout">Logout</a>
                        <div class="dropdown-divider"></div>
                    </div>
            </li>
            <?php } ?>
        </ul>
    </div>
  </div>
</nav>

<script type="text/javascript">
    document.getElementById("searchInput").addEventListener("keyup", e => {
        if (e.keyCode == 13) {
            document.getElementById("formSearch").submit();
        }
    });
</script>
