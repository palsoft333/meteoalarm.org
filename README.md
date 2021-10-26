# meteoalarm.org
Simple PHP warning and hazard scraper from the RSS of the newest website of EUMETNET - meteoalarm.org. Feel free to pull new requests and/or fork this git.

## Usage
```
$_GET['country'] = "sk";
include("meteoalarmorg.php");

echo "<pre>";
print_r($meteo_events); // array of all events for the specified country
echo "</pre>";
```

## Example output
```
Array
(
    [0] => Array
        (
            [locality] => Calabria
            [valid_from] => 2021-10-26T00:00:00+00:00
            [valid_till] => 2021-10-26T23:59:00+00:00
            [alert_type] => 10
            [alert_type_desc] => Rain
            [alert_level] => 4
            [alert_color] => Red
            [alert_desc] => Extreme
            [description] => English(en-GB): southern part  (DISCLAIMER: "Information provided on METEOALARM for Italy regard only the intensity and recurrence of the phenomena, further details can be found at www.meteoam.it. METEOALARM information do not provide the assessment of impact on the territory and they do not represent the Official Alerts messages that are issued by the National Civil Protection Service www.protezionecivile.it") TAKE precautionary ACTION, remain vigilant and act on advice given by authorities. Keep up to date with the latest weather forecast, and expect significant disruption to daily routines. Only travel if your journey is essential.
Italian(it-IT): parte meridionale  (DISCLAIMER: "Le informazioni fornite su METEOALARM per l'Italia riguardano esclusivamente l'intensita' e la ricorrenza dei fenomeni, maggiori dettagli sono disponibili su www.meteoam.it. Le informazioni METEOALARM non forniscono la valutazione di impatto sul territorio e non rappresentano i messaggi di Allerta Ufficiali che vengono emessi dal Servizio Nazionale di Protezione Civile www.protezionecivile.it") INTRAPRENDERE AZIONI CAUTELATIVE. Rimanere vigili e agire in accordo con i consigli (gli ordini) emessi da parte delle autorità. Tenersi aggiornati con le previsioni del tempo più recenti, ed aspettarsi significativi disagi per le normali attività quotidiane. Intraprendere un viaggio solo se indispensabile.
            [condition_icon] => https://feeds.meteoalarm.org/images/rss/wflag-l4-t10.png
            [pubDate] => Mon, 25 Oct 21 09:12:15 +0000
            [link] => https://meteoalarm.org?geocode=EMMA_ID:IT001
        )

    [1] => Array
        (
            [locality] => Calabria
            [valid_from] => 2021-10-26T00:00:00+00:00
            [valid_till] => 2021-10-26T23:59:00+00:00
            [alert_type] => 3
            [alert_type_desc] => Thunderstorm
            [alert_level] => 4
            [alert_color] => Red
            [alert_desc] => Extreme
            [description] => English(en-GB): southern part  (DISCLAIMER: "Information provided on METEOALARM for Italy regard only the intensity and recurrence of the phenomena, further details can be found at www.meteoam.it. METEOALARM information do not provide the assessment of impact on the territory and they do not represent the Official Alerts messages that are issued by the National Civil Protection Service www.protezionecivile.it") TAKE precautionary ACTION, remain vigilant and act on advice given by authorities. Keep up to date with the latest weather forecast, and expect significant disruption to daily routines. Only travel if your journey is essential.
Italian(it-IT): parte meridionale  (DISCLAIMER: "Le informazioni fornite su METEOALARM per l'Italia riguardano esclusivamente l'intensita' e la ricorrenza dei fenomeni, maggiori dettagli sono disponibili su www.meteoam.it. Le informazioni METEOALARM non forniscono la valutazione di impatto sul territorio e non rappresentano i messaggi di Allerta Ufficiali che vengono emessi dal Servizio Nazionale di Protezione Civile www.protezionecivile.it") INTRAPRENDERE AZIONI CAUTELATIVE. Rimanere vigili e agire in accordo con i consigli (gli ordini) emessi da parte delle autorità. Tenersi aggiornati con le previsioni del tempo più recenti, ed aspettarsi significativi disagi per le normali attività quotidiane. Intraprendere un viaggio solo se indispensabile.
            [condition_icon] => https://feeds.meteoalarm.org/images/rss/wflag-l4-t3.png
            [pubDate] => Mon, 25 Oct 21 09:12:15 +0000
            [link] => https://meteoalarm.org?geocode=EMMA_ID:IT001
        )
)
```
