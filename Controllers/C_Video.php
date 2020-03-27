<?php
require_once("./Models/AccessDB.php");
require_once("./Models/Video.php");

class C_Video {
    // Private ----------------------------------------------------------------
    /* GetBdd: Return a connection to a database
     *      Output:
     *          - AccessDB object: connected database
     */
    private static function GetBdd() {
        $bdd = new AccessDB("localhost", "hi5bu_infinite_skills","root","");
        $bdd->connect();
        return $bdd;
    }
    /* GenerateVideos: Create a list of Video objects from a list
     *      Input:
     *          - $videos: list of videos to transform into Video objects
     *      Output:
     *          - array: list of Video objects
     */
    private static function GenerateVideos($videos) {
        $list = [];
        for ($i=0; $i < count($videos); $i++) {
            $v = $videos[$i];
            $list[] = new Video(
                $v["Id"],
                $v["OwnerId"],
                $v["ThemeId"],
                $v["Name"],
                $v["Description"],
                $v["Publication"],
                $v["Price"],
                $v["Views"],
                $v["Url"],
                $v["Thumbnail"]
            );
        }
        return $list;
    }

    // Public -----------------------------------------------------------------
    /* GetVideos: Get all videos from database
     *      Output:
     *          - array: list of Video objects
     */
    public static function GetVideos() {
        $bdd = C_Video::GetBdd();
        $videos = $bdd->select("SELECT * FROM Video", []);
        return C_Video::GenerateVideos($videos);
    }
    /* GetVideosByThemeId: Get videos that match the theme from database
     *      Input:
     *          - $id: Theme id
     *      Output:
     *          - array: list of Video objects
     */
    public static function GetVideosByThemeId($id) {
        $bdd = C_Video::GetBdd();
        $videos = $bdd->select("SELECT * FROM Video WHERE ThemeId = :id", ["id" => $id]);
        return C_Video::GenerateVideos($videos);
    }
    /* GetVideoById: Get video that match the id
     *      Input:
     *          - $id: Video id
     *      Output:
     *          - Video: Video objects
     */
    public static function GetVideoById($id) {
        $bdd = C_Video::GetBdd();
        $videos = $bdd->select("SELECT * FROM Video WHERE Id = :id", ["id" => $id]);
        return C_Video::GenerateVideos($videos)[0];
    }
}
?>
