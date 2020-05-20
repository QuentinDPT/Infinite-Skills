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
<link rel="stylesheet" href="/src/styles/main.css">
<nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-dark c box-shadow mb-3 navbar-nav raised-primary raised-primary-flat">
  <div class="container">
    <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
        <ul class="navbar-nav flex-grow-1">
            <li class="navbar-title">
                <a class="navbar-title text-white" href="./home">Infinite skills</a>
            </li>
            <li class="nav-item">
              <a class="nav-link bg-transparent <?php if($NavActive == "Acceuil") echo "active disabled" ; ?>" href="./home">Home</a>
            </li>
            <li class="nav-item dropdown">
                <input type="button" class="nav-link bg-transparent dropdown-toggle" value="Themes" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"/>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($navbarUser !== -1) { ?>
                        <?php if (count($listThemesUser) == 0) {?>
                            <form action="/themes" method="get">
                                <button type="submit" class="dropdown-item text-primary">Let's choose your themes!</a>
                            </form>
                        <?php } ?>
                        <form action="/themes" method="get">
                            <button type="submit" class="dropdown-item text-primary">Manage your themes!</a>
                        </form>
                        <hr>
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

    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : "light-blue";

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);

        if (currentTheme.startsWith('dark')) {
            toggleSwitch.checked = true;
        }
        changeColor.selectedIndex = getIndex(changeColor, getCurrentTheme());
    }

    function getTheme() {
        var val =  changeColor.selectedOptions[0].value;
        var res = "blue";
        switch (val) {
            case "blue": res = "blue"; break;
            case "green": res = "green"; break;
            default: res = "orange"; break;
        }
        return res;
    }
    function getCurrentTheme() {
        if (currentTheme.endsWith('blue')) return "blue";
        if (currentTheme.endsWith('orange')) return "orange";
        if (currentTheme.endsWith('green')) return "green";
    }
    function getIndex(select, value) {
        for (var i = 0; i < select.options.length; i++) {
            if (select.options[i].value == value) return i;
        }
        return 0;
    }
</script>
