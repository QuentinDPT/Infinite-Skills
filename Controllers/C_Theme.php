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
                $t["Description"]
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
        return C_Theme::GenerateThemes($themes)[0];
    }
    public static function GetThemesByUserId($id) {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT t.*
                                FROM Theme t
                                INNER JOIN UserTheme ut
                                ON t.Id = ut.ThemeId
                                WHERE ut.UserId = :id", ["id" => $id]);
        return C_Theme::GenerateThemes($themes);
    }
    public static function GetThemesShuffle() {
        $bdd = C_Theme::GetBdd();
        $themes = $bdd->select("SELECT * FROM Theme", []);
        shuffle($themes);
        $nbOfThemes = 5;
        $themes = array_slice($themes, 0, 5);
        return C_Theme::GenerateThemes($themes);
    }
}
?>
