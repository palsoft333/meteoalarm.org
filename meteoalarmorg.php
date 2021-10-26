<?
if($_GET["country"]) {
    $c = strtolower($_GET["country"]);
    $iso = array("at"=>"austria", "be"=>"belgium", "ba"=>"bosnia-herzegovina", "bg"=>"bulgaria", "hr"=>"croatia", "cy"=>"cyprus", "cz"=>"czechia", "dk"=>"denmark", "fi"=>"finland", "fr"=>"france", "de"=>"germany", "ee"=>"estonia", "gr"=>"greece", "hu"=>"hungary", "is"=>"iceland", "ie"=>"ireland", "il"=>"israel", "it"=>"italy", "lv"=>"latvia", "lt"=>"lithuania", "lu"=>"luxembourg", "mt"=>"malta", "md"=>"moldova", "me"=>"montenegro", "nl"=>"netherlands", "mk"=>"republic-of-north-macedonia", "no"=>"norway", "pl"=>"poland", "pt"=>"portugal", "ro"=>"romania", "rs"=>"serbia", "sk"=>"slovakia", "si"=>"slovenia", "es"=>"spain", "se"=>"sweden", "ch"=>"switzerland", "gb"=>"united-kingdom");
    $c = $iso[$c];

    $feed = implode(file('https://feeds.meteoalarm.org/feeds/meteoalarm-legacy-rss-'.$c));
    $xml = simplexml_load_string($feed,'SimpleXMLElement', LIBXML_NOCDATA);
    $json = json_encode($xml);
    $meteo = json_decode($json,TRUE);

    $p=0;
    $meteo_events=array();
    foreach($meteo["channel"]["item"] as $region) {
        if(is_array($region) && strstr($region["link"], "geocode")) {
            $title = $region["title"];
            $pubDate = $region["pubDate"];
            $desc = $region["description"];
            $link = $region["link"];
            $events = explode("alt=", $desc);
            array_shift($events);
            foreach($events as $event) {
                $from_start = strpos($event, "<i>");
                $from_end = strpos($event, "</i>", $from_start);
                $until_start = strpos($event, "<i>", $from_end);
                $until_end = strpos($event, "</i>", $until_start);
                $event_from = trim(strip_tags(substr($event, $from_start, $from_end-$from_start)));
                $event_until = trim(strip_tags(substr($event, $until_start, $until_end-$until_start)));
                $awt_start = strpos($event, '"awt:')+5;
                $awt_end = strpos($event, ' ', $awt_start);
                $level_start = strpos($event, 'level:', $awt_end)+6;
                $level_end = strpos($event, '"', $level_start);
                $awt = trim(strip_tags(substr($event, $awt_start, $awt_end-$awt_start)));
                $level = trim(strip_tags(substr($event, $level_start, $level_end-$level_start)));
                $description = explode("<td>", $event);
                $description = trim(strip_tags($description[2]));
                if($awt==1) $alert_type_desc = "Wind";
                elseif($awt==2) $alert_type_desc = "Snow-Ice";
                elseif($awt==3) $alert_type_desc = "Thunderstorm";
                elseif($awt==4) $alert_type_desc = "Fog";
                elseif($awt==5) $alert_type_desc = "High temperature";
                elseif($awt==6) $alert_type_desc = "Low temperature";
                elseif($awt==7) $alert_type_desc = "Coastal event";
                elseif($awt==8) $alert_type_desc = "Forest Fire";
                elseif($awt==9) $alert_type_desc = "Avalanches";
                elseif($awt==10) $alert_type_desc = "Rain";
                elseif($awt==12) $alert_type_desc = "Flooding";
                elseif($awt==13) $alert_type_desc = "Rain Flood";
                if($level==1) { $alert_color = "Green"; $alert_desc = "Minor"; }
                elseif($level==2) { $alert_color = "Yellow"; $alert_desc = "Moderate"; }
                elseif($level==3) { $alert_color = "Orange"; $alert_desc = "Severe"; }
                elseif($level==4) { $alert_color = "Red"; $alert_desc = "Extreme"; }
                $meteo_events[$p]["locality"] = $title;
                $meteo_events[$p]["valid_from"] = $event_from;
                $meteo_events[$p]["valid_till"] = $event_until;
                $meteo_events[$p]["alert_type"] = $awt;
                $meteo_events[$p]["alert_type_desc"] = $alert_type_desc;
                $meteo_events[$p]["alert_level"] = $level;
                $meteo_events[$p]["alert_color"] = $alert_color;
                $meteo_events[$p]["alert_desc"] = $alert_desc;
                $meteo_events[$p]["description"] = $description;
                $meteo_events[$p]["condition_icon"] = "https://feeds.meteoalarm.org/images/rss/wflag-l".$level."-t".$awt.".png";
                $meteo_events[$p]["pubDate"] = $pubDate;
                $meteo_events[$p]["link"] = $link;
                $p++;
            }
        }
    }
}
else {
    die("You must specify a country in ISO format!");
}
?>