<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Simple Weather Site</title>
    <link rel="stylesheet" href="css/default.css">
</head>
<body>
<?php
    /**
     * Created by IntelliJ IDEA.
     * User: Flores
     * Date: 10/20/2017
     * Time: 8:18 PM
     */

    /** NOTE- Houston Code: 4699066
        London: 2643743
    */


    // Building url for api call
    $strURL = "http://api.openweathermap.org/data/2.5/weather?id=4699066&appid=" . FindValue("OpenWeatherAPI", "Keys.txt") . "&units=imperial";

    // Getting JSON data
    $data = get_data($strURL);

    // show data (debugging only)
    //var_dump($data);

    // converting JSON to php object
    $jparse = json_decode($data, true);

    echo "<form id='frmMain' name='frmMain'>";
    echo "<h2>Important Weather Information</h2>";
    echo "<br />";

    // description
    echo "<p>Current weather for Houston: <b>" . $jparse['weather'][0]['description'] . "</b></p>";


    //echo "<br /><br />";

    // temperature
    $temp = $jparse['main']['temp'];

    echo "<p>Temperature: " . $temp . " degrees Fahrenheit</p>";

    //        if ($temp >= 90)
    //        { echo "It's hot!"; }
    //        elseif (($temp < 90) && ($temp >= 80))
    //        { echo "It's warm";}
    //        else
    //        { echo "Stop sweating, it's not too bad!";}

    echo "<hr>";

    // *** RSS feed from Harris Country flood watch is too infrequent as of 9-7-16
    // *** RSS feed from Fox Hurricane news not possible without correct URL

    //$data2 = new SimpleXMLElement("http://www.nhc.noaa.gov/index-at.xml");
    $data2 = simplexml_load_file('http://www.nhc.noaa.gov/index-at.xml');
    //echo "<br /><p>******Data call one******</p><br />"; // TESTING ONLY

    echo "<h3>" . $data2->channel->item[1]->title . "</h3>";
    //echo $data2->channel->item[1]->title;

    //echo "<br /><p>******Data call two******</p><br />"; // TESTING ONLY

    echo "<p>" . $data2->channel->item[1]->description . "</p>";

    //echo var_dump($data2); // show data (debugging only)

    echo "<hr>";

    echo "<p>Click <a href=\"http://www.harriscountyfws.org/\">here</a> to see detailed flood information for Harris County.</p>";
    echo "</form>";

    // Formatting the result
    function format_result($input)
    {
        return strtolower(str_replace(array(' ', '(',')'), array('-', '-', ''), $input));
    }

    function get_match($regex, $content)
    {
        preg_match($regex, $content, $matches);
        return $matches[1];
    }

    // most of the hard work is taken care of by the php_curl.dll
    function get_data($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $xml = curl_exec($ch);
        curl_close($ch);
        return $xml;
    }

    function FindValue($strVariable, $strFileName)
    {
        $txt_file = file_get_contents($strFileName);
        $rows = explode("\n", $txt_file);

        foreach($rows as $row => $data)
        {
            // getting row data
            $row_data = explode('|', $data);

            $info[$row]['Name'] = $row_data[0];
            $info[$row]['Value'] = $row_data[1];

            if ($row_data[0] == "$strVariable")
            {
                return $row_data[1];
            }
        }
    }
?>

<!--Disabled as of 9-7-16 due to color scheme not matching -->
<!--<iframe id="nhc" width="320" height="280" scrolling="no" src="http://www.nhc.noaa.gov/widgets/nhc_widget.shtml"></iframe>-->

</body>
</html>




