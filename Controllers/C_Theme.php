<?php
require_once("./Models/AccessDB.php");
require_once("./Models/Theme.php");

class C_Theme {
    // Private ----------------------------------------------------------------
    /* GetBdd: Return a connection to a database
     *      Output:
     *          - AccessDB object: connected database
     */
    private static function GetBdd() {
        $bdd = new AccessDB();
        $bdd->connect();
        return $bdd;
    }
    /* GenerateThemes: Create a list of Theme objects from a list
     *      Input:
     *          - $themes: list of themes to transform into Theme objects
     *      Output:
     *          - array: list of Theme objects
     */
    private static function GenerateThemes($themes) {
        $list = [];
        for ($i=0; $i < count($themes); $i++) {
            $t = $themes[$i];
            $list[] = new Theme(
                $t["Id"],
                $t["Name"],
                $t["Description"],
                $t["Thumbnail"]
            );
        }
        return $list;
    }

    // Public -----------------------------------------------------------------
    /* GetThemes: Get all themes from database
     *      Output:
     *          - array: list of Theme objects
     */
    public static function GetThemes() {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT * FROM Theme", []);
        return C_Theme::GenerateThemes($themes);
    }
    /* GetThemeById: Get theme that match the id
     *      Input:
     *          - $id: Theme id
     *      Output:
     *          - Theme: Theme objects
     */
    public static function GetThemeById($id) {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT * FROM Theme WHERE Id = :id", ["id" => $id]);
        return (!empty($themes) ? C_Theme::GenerateThemes($themes)[0] : null);
    }
    /* GetThemesByUserId: Get themes chosen by a user
     *      Input:
     *          - $id: User Id
     *      Output:
     *          - Array: Array of Theme object
     */
    public static function GetThemesByUserId($id) {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT t.*
                                FROM Theme t
                                INNER JOIN UserTheme ut
                                ON t.Id = ut.ThemeId
                                WHERE ut.UserId = :id", ["id" => $id]);
        return C_Theme::GenerateThemes($themes);
    }
    /* GetThemesShuffle: Select random themes
     *      Output:
     *          - Array: Array of Theme object
     */
    public static function GetThemesShuffle() {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT * FROM Theme", []);
        shuffle($themes);
        $nbOfThemes = 5;
        $themes = array_slice($themes, 0, 5);
        return C_Theme::GenerateThemes($themes);
    }
    /* SaveUserThemes: Save into database user's selected themes
     *      Input:
     *          - $userId: User Id
     *          - $list  : Array of themes id
     */
    public static function SaveUserThemes($userId, $list) {
        $bdd = C_Theme::GetBdd();

        // First we look for already added themes
        $userThemes = $bdd->select("SELECT * FROM UserTheme WHERE UserId = $userId", []);

        // Look if theme already exists, else add it
        $addList = [];
        for ($i=0; $i < count($list); $i++) {
            $add = true;
            for ($j=0; $j < count($userThemes); $j++) {
                if ($userThemes[$j]["ThemeId"] == $list[$i]) {
                    $add = false;
                    break;
                }

            }
            if ($add) $addList[] = $list[$i];
        }

        // Look if theme needs to be removed
        $deleteList = [];
        for ($i=0; $i < count($userThemes); $i++) {
            $delete = true;
            for ($j=0; $j < count($list); $j++) {
                if ($userThemes[$i]["ThemeId"] == $list[$j]) {
                    $delete = false;
                    break;
                }
            }
            if ($delete) $deleteList[] = $userThemes[$i]["ThemeId"];
        }

        // Then do requests
        $strAdd = "";
        for ($i=0; $i < count($addList); $i++) {
            $strAdd .= "($userId, " . $addList[$i] . "),";
        }
        $reqAdd = "INSERT INTO UserTheme (UserId, ThemeId) VALUES " . substr($strAdd, 0, -1);

        $strDel = "";
        for ($i=0; $i < count($deleteList); $i++) {
            $strDel .= $deleteList[$i] . ",";
        }
        $reqDel = "DELETE FROM UserTheme WHERE UserId = $userId AND ThemeId IN (" . substr($strDel, 0, -1) . ")";

        $bdd->insert($reqAdd, []);
        $bdd->delete($reqDel, []);
    }
    /* SaveTheme: Save a new theme in database
     *      Input:
     *          - $name: theme name
     *          - $img : theme thumbnail
     */
    public static function SaveTheme($name, $img) {
        $name = ucfirst($name);
        $bdd = C_Theme::GetBdd();
        $nextId = 0;
        $reqNextId = $bdd->select("SELECT MAX(Id) + 1 FROM Theme", []);
        if ($reqNextId[0]["MAX(Id) + 1"] != null) $nextId = $reqNextId[0]["MAX(Id) + 1"];

        $req = "INSERT INTO Theme VALUES ($nextId, '$name', '$name', '$img')";
        $bdd->insert($req, []);
    }
}
?>
