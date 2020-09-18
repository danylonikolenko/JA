<?php

/**
 * Class Translator
 * @property string $adsUrl
 * @property string $categoriesUrl
 */
class Translator
{
    private $adsUrl;
    private $categoriesUrl;

    public function __construct(string $adsUrl, string $categoriesUrl)
    {
        $this->adsUrl = $adsUrl;
        $this->categoriesUrl = $categoriesUrl;
    }

    public function process(): void
    {
        $ads = $this->getDataFromUrl($this->adsUrl);
        $categories = $this->getDataFromUrl($this->categoriesUrl);

        foreach ($ads as $keyAd => $ad) {
            foreach ($ad['params'] as $paramKey => $param) {
                if (is_array($param)) {
                    foreach($param as $featureKey=>$feature){
                        if(isset($categories['features']['options'][$feature])){
                            $ads[$keyAd]['params'][$paramKey][$featureKey] = $categories['features']['options'][$feature];
                        }
                    }
                } else {
                    if (isset($categories[$paramKey]['options'][$param])) {
                        $ads[$keyAd]['params'][$paramKey] = $categories[$paramKey]['options'][$param];
                    }
                }
            }
        }
        file_put_contents('translations.json', json_encode($ads));
    }

    private function getDataFromUrl(string $url): array
    {
        $data = file_get_contents($url);
        return ($data === false) ? [] : json_decode($data, true);
    }
}

$class = new Translator('http://gx.pandora.caps.pl/oto/api/ads.json', 'http://gx.pandora.caps.pl/oto/api/categories.json');
$class->process();
$var = file_get_contents("translations.json");
$var = json_decode($var);
var_dump($var);

