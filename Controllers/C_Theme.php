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
}
?>
