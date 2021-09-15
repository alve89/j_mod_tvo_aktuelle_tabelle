<?php
class ModTvoAktuelletabelleHelper
{
	/*
		STEPS:
		1. Abfrage der aktuellen API-Adresse

		2. Abfrage der aktuellen Mannschafts-ID

		3. Abfrage der Tabelle mit Hilfe der ID

	*/

	static $url = "";



/********************* Abfrage API Adresse **********************/

	public static function getCurrentUrl()
  {
		return 'https://api.h4a.mobi/spo/spo-proxy_public.php';

		// create curl ressource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.handball4all.de/url/spo_vereine-01.php");

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);
		// Return the result
		return $output;
  }


/********************* Abfrage Mannschafts-ID **********************/

	public function getCurrentTeamID($id)
    {
		// create curl ressource
        $ch = curl_init();

		self::$url = self::getCurrentUrl()."?cmd=data&lvTypeNext=team&lvIDNext=" . $id;
        // set url
        curl_setopt($ch, CURLOPT_URL, self::$url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
		// Return the result
		return json_decode($output)[0]->lvIDPathStr;
    }



/********************* Abfrage Liga-ID **********************/

	public function getCurrentLeagueID($id)
    {
		// create curl ressource
        $ch = curl_init();

		self::$url = self::getCurrentUrl()."?cmd=data&lvTypeNext=team&lvIDNext=" . $id;
        // set url
        curl_setopt($ch, CURLOPT_URL, self::$url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
		// Return the result
		return json_decode($output)[0]->dataList[0]->gClassID;
    }





/********************* Abfrage Tabelle **********************/

	public function getTable($id)
	{
		// create curl ressource
        $ch = curl_init();

		self::$url = self::getCurrentUrl()."?cmd=data&lvTypeNext=class&subType=table&lvIDNext=" . $id;

        // set url
        // club (!) id = 986
        curl_setopt($ch, CURLOPT_URL, self::$url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch,  CURLOPT_ENCODING, 'gzip');

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
		// Return the result
		return json_decode($output)[0];
	}



	// Alias zu getCurrentID(), da getCurrentID() nicht nur die ID, sondern den gesamten Spielplan abruft
	public static function getGames($id)
	{
		return self::getCurrentID($id);
	}





	public static function getPathToCronFile()
	{
		return __DIR__ . '/data.json';
	}

	public static function getSeasonDataFromFile($file)
	{
		return json_decode(file_get_contents($file));
	}






	public static function cmp($a, $b)
	{
		return strcmp($a->gDateTS, $b->gDateTS);
	}

	public static function getTimestamp($game)
	{

		$dateAsArr = explode(".", $game->gDate);
		$dateAsArr[2] = 2000 + $dateAsArr[2];
		$timeAsArr = explode(":", $game->gTime);

		$game->gDateTS = mktime($timeAsArr[0], $timeAsArr[1], 0, $dateAsArr[1], $dateAsArr[0], $dateAsArr[2]);

		return $game;
	}




    public static function varDump($var)
    {
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
		return;
    }

    public static function get($var)
    {
	    return self::$$var;
    }

    public static function slashAtTheEnd($path)
    {
	    if(substr($path, -1) !== '/' && substr($path, -1) != DIRECTORY_SEPARATOR && substr($path, -1) != "\\")
	    {
		    $path .= DIRECTORY_SEPARATOR;
	    }
	    return $path;
    }

	public function getIds($idFile)
	{
		// create curl ressource
        $ch = curl_init();

        // set url
        // club (!) id = 986
        curl_setopt($ch, CURLOPT_URL, $idFile);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch,  CURLOPT_ENCODING, 'gzip');

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);
		// Return the result
		return json_decode($output);
	}

}
