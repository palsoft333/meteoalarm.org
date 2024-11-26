<?
include("libs/simple_html_dom.php");
header('Content-Type: application/json');
if($_GET["country"]) {
    $c = strtolower($_GET["country"]);
    $iso = array("at"=>"austria", "be"=>"belgium", "ba"=>"bosnia-herzegovina", "bg"=>"bulgaria", "hr"=>"croatia", "cy"=>"cyprus", "cz"=>"czechia", "dk"=>"denmark", "fi"=>"finland", "fr"=>"france", "de"=>"germany", "ee"=>"estonia", "gr"=>"greece", "hu"=>"hungary", "is"=>"iceland", "ie"=>"ireland", "il"=>"israel", "it"=>"italy", "lv"=>"latvia", "lt"=>"lithuania", "lu"=>"luxembourg", "mt"=>"malta", "md"=>"moldova", "me"=>"montenegro", "nl"=>"netherlands", "mk"=>"republic-of-north-macedonia", "no"=>"norway", "pl"=>"poland", "pt"=>"portugal", "ro"=>"romania", "rs"=>"serbia", "sk"=>"slovakia", "si"=>"slovenia", "es"=>"spain", "se"=>"sweden", "ch"=>"switzerland", "gb"=>"united-kingdom");
    $c = $iso[$c];

    $feed = implode(file('https://feeds.meteoalarm.org/feeds/meteoalarm-legacy-rss-'.$c));
    $xml = simplexml_load_string($feed,'SimpleXMLElement', LIBXML_NOCDATA);
    $json = json_encode($xml);
    $meteo = json_decode($json,TRUE);

    $alertTypes = [
        1 => "Wind",
        2 => "Snow-Ice",
        3 => "Thunderstorm",
        4 => "Fog",
        5 => "High temperature",
        6 => "Low temperature",
        7 => "Coastal event",
        8 => "Forest Fire",
        9 => "Avalanches",
        10 => "Rain",
        12 => "Flooding",
        13 => "Rain Flood"
    ];

    $alertLevels = [
        1 => ["color" => "Green", "desc" => "Minor"],
        2 => ["color" => "Yellow", "desc" => "Moderate"],
        3 => ["color" => "Orange", "desc" => "Severe"],
        4 => ["color" => "Red", "desc" => "Extreme"]
    ];

    $p=0;
    $meteo_events=array();
    foreach($meteo["channel"]["item"] as $region) {
        if(is_array($region) && strstr($region["link"], "geocode")) {
            $title = $region["title"];
            $pubDate = $region["pubDate"];
            $desc = $region["description"];
            $link = $region["link"];
            $html = str_get_html($desc);

            $events = [];
            foreach ($html->find('td[data-awareness-level]') as $event) {
                $currentRow = $event->parent();
                $nextRow = $currentRow->next_sibling();
                $events[] = [
                    'event' => $currentRow->outertext,
                    'details' => $nextRow ? $nextRow->outertext : null
                ];
            }

            foreach ($events as $eventData) {
                $eventHtml = str_get_html($eventData['event']);
                $detailsHtml = str_get_html($eventData['details']);

                $fromDate = null;
                $untilDate = null;
                if ($eventHtml->find('td', 1)) {
                    $fromUntilText = $eventHtml->find('td', 1)->plaintext;       
                    preg_match('/From:\s*(\S+)/', $fromUntilText, $fromMatch);
                    preg_match('/Until:\s*(\S+)/', $fromUntilText, $untilMatch);
                    $fromDate = $fromMatch[1] ?? null;
                    $untilDate = $untilMatch[1] ?? null;
                }

                $awt = null;
                $level = null;
                if ($eventHtml->find('td[data-awareness-level]', 0)) {
                    $awtText = $eventHtml->find('td[data-awareness-level]', 0)->innertext;
                    preg_match('/awt:(\d+)/', $awtText, $awtMatch);
                    preg_match('/level:(\d+)/', $awtText, $levelMatch);
                    $level = $levelMatch[1] ?? null;
                    $awt = $awtMatch[1] ?? null;
                }

                $description = null;
                if ($detailsHtml->find('td', 1)) {
                    $descriptionText = $detailsHtml->find('td', 1)->plaintext;
                    $description = trim($descriptionText);
                }

                $alert_type_desc = $alertTypes[$awt] ?? "Unknown";
                $alert_color = $alertLevels[$level]['color'] ?? "Unknown";
                $alert_desc = $alertLevels[$level]['desc'] ?? "Unknown";

                $meteo_events[] = [
                    "locality" => $title,
                    "valid_from" => $fromDate,
                    "valid_till" => $untilDate,
                    "alert_type" => $awt,
                    "alert_type_desc" => $alert_type_desc,
                    "alert_level" => $level,
                    "alert_color" => $alert_color,
                    "alert_desc" => $alert_desc,
                    "description" => $description,
                    "condition_icon" => "https://feeds.meteoalarm.org/images/rss/wflag-l".$level."-t".$awt.".png",
                    "pubDate" => $pubDate,
                    "link" => $link
                ];
            }
        }
    }

    $json = json_encode($meteo_events);
    echo $json;
}
else {
    die("You must specify a country in ISO format!");
}
?>
