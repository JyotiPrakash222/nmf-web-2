<?php

namespace App\Utility;

/**
 * Description of Utility
 *
 * @author nazmulislam
 */
class Utility
{
    public static $sort_by_param;

    const EMAIL_TYPE_WELCOME = 1;
    /**
     *
     * @param type $index
     * @param type $config
     * @return type
     */
    public static function getArrayValueWithDot($index, $config)
    {
        return self::getValue(explode('.', $index), $config);
    }

    private static function getValue($index, $value)
    {
        if (
            is_array($index) and
            count($index)
        )
        {
            $current_index = array_shift($index);
        }
        if (
            is_array($index) and
            count($index) and
            is_array($value[$current_index]) and
            count($value[$current_index])
        )
        {
            return self::getValue($index, $value[$current_index]);
        }
        else
        {
            return $value[$current_index];
        }
    }

    /**
     *
     * @param string $file
     * @param array $data
     * @return string
     * @throws \Exception
     */

    public static function emailTemplate(string $file, array $data = []): string
    {
        if (empty($file))
        {
            throw new \Exception('Param $path cannot be empty');
        }

        if (strpos($file, '.php') === false)
        {
            $file = $file . '.php';
        }

        if (!\file_exists(__DIR__ . '/../../Templates/Email/' . $file))
        {
            throw new \Exception('The Template Path: ' . $file . ' does not exist');
        }
        \ob_start();
        include(__DIR__ . '/../../Templates/Email/' . $file);

        return ob_get_clean();
    }
    /**
     *
     * @param type $companyId
     * @param type $memberId
     * @param array $data
     * @param type $actionsAfterService
     * @return array
     */
    public static function addBackgroundAndNotificationData($companyId, $creatorId, array $data, array $actionsAfterService = [], array $notificationObjects = []): array
    {
        if (isset($actionsAfterService) && isset($actionsAfterService['notification']))
        {
            $options  = [
                'user_ids' => [$creatorId],
                'channels' => [],
            ];
            $addtionalOptions = $actionsAfterService['notification'];
            /**
             * Covert message variables to actual dynamic values.
             */
            if (isset($addtionalOptions['message']) && !empty($addtionalOptions['message']) && isset($notificationObjects) && count($notificationObjects) > 0)
            {
                foreach ($notificationObjects as $key => $value)
                {
                    //don not use replace function for keys containing array
                    if (isset($value) && $value != null && is_string($value))
                    {
                        $addtionalOptions['message'] = str_replace('{{' . $key . '}}', $value, $addtionalOptions['message']);
                    }
                }
            }
            $optionsFinal = array_merge($options, $addtionalOptions);
            $data['notification'] =  $optionsFinal;
        }
        $data['company_id'] = $companyId;
        $data['creator_id'] = $creatorId;
        return $data;
    }

    /**
     *
     * @param array $data (data array)
     * @param string $queue
     * @param string $jobController
     * @param string $server
     * @param int $port
     * @param int $database
     */
    public static function backgroundQueueTask(array $queueData, string $queue, string $jobController, string $host = null, int $port = 6379, int $database = 0)
    {
        try
        {
            /**
             * Add the tenant name if its missing
             */
            if (!array_key_exists('tenant', $queueData))
            {
                if (php_sapi_name() !== 'cli')
                {
                    $queueData['tenant'] = self::getTenantName();


                    if (empty($queueData['tenant']))
                    {
                        throw new \Exception('Tenant name cannot be empty');
                    }
                    /*
                * Add logic here to check if tenant exists
                */
                }
                elseif (php_sapi_name() === 'cli')
                {
                    throw new \Exception('TENANT IS NOT SET IN CLI');
                }
            }
            if (!array_key_exists('httpHost', $queueData))
            {
                if (isset($_SERVER['HTTP_HOST']) && php_sapi_name() !== 'cli')
                {
                    $queueData['httpHost'] = $_SERVER['HTTP_HOST'];
                }
            }
            if (!array_key_exists('httpScheme', $queueData))
            {
                if (isset($_SERVER['REQUEST_SCHEME']) && php_sapi_name() !== 'cli')
                {
                    $queueData['httpScheme'] = $_SERVER['REQUEST_SCHEME'];
                }
            }
            if (!isset($_ENV['REDIS_PASSWORD']) || empty($_ENV['REDIS_PASSWORD']))
            {
                throw new \Exception('REDIS ENVIRONMENT PASSWORD IS NOT SET');
            }
            $pass = $_ENV['REDIS_PASSWORD'];

            if (empty($jobController))
            {
                throw new \RuntimeException('Parameter $jobController cannot be empty');
            }

            $server = !empty($host) ? $host : $pass;

            \Resque::setBackend("redis://ignored:{$pass}@{$server}:{$port}", $database);

            if (isset($queueData['notification']))
            {
                $queueData['notification_id'] = \App\Model\Eloquent\Notifications::create([
                    'company_id' => isset($queueData['company_id']) ? $queueData['company_id'] : 0,
                    'notification' => json_encode($queueData['notification'])
                ])->id;

                //$queueData['notification'] = NULL;
            }

            return \Resque::enqueue($queue, $jobController, $queueData, true);
        }
        catch (\Exception $ex)
        {
//            \App\Core\Logger\AppLogger::error($ex->getMessage(), $ex->getTrace());
            echo $ex->getTraceAsString();
            //            throw new \Whoops\Exception\ErrorException;
        }
    }

    public static function getTenantName()
    {

        // extract username
        if (!empty($_SERVER['HTTP_HOST']))
        {
            $hostInfo = explode('.', $_SERVER['HTTP_HOST']);


            return array_shift($hostInfo);
        }
        else
        {
            $headers = getallheaders();
            if (isset($headers['Host']))
            {
                $hostInfo = strtolower(trim(explode('.', $headers['Host'])));

                echo $hostInfo . '----------Host';
                die;
                return array_shift($hostInfo);
            }

            return '';
        }
    }

    /**
     * @description Parses a user agent string into its important parts
     * @param array $data
     * @return array
     */
    public static function getBrowserFromUserAgent(array $serverData): array
    {
        $u_agent = null;


        if (is_null($u_agent))
        {
            $u_agent = (isset($serverData['HTTP_USER_AGENT'])) ? $serverData['HTTP_USER_AGENT'] : '';
        }


        $data['platform'] = null;
        $data['browser'] = null;
        $data['version'] = null;


        if (preg_match('/\((.*?)\)/im', $u_agent, $regs))
        {

            # (?<platform>Android|iPhone|iPad|Windows|Linux|Macintosh|Windows Phone OS|Silk|linux-gnu|BlackBerry)(?: x86_64)?(?: NT)?(?:[ /][0-9._]+)*(;|$)
            preg_match_all('%(?P<platform>Android|iPhone|iPad|Windows|Linux|Macintosh|Windows Phone OS|Silk|linux-gnu|BlackBerry)(?: x86_64)?(?: NT)?(?:[ /][0-9._]+)*(;|$)%im', $regs[1], $result, PREG_PATTERN_ORDER);
            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1)
            {
                if (($key = array_search('Android', $result['platform'])) !== false)
                {
                    $data['platform'] = $result['platform'][$key];
                }
            }
            elseif (isset($result['platform'][0]))
            {
                $data['platform'] = $result['platform'][0];
            }
        }

        # (?<browser>Camino|Kindle|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget)(?:[/ ])(?<version>[0-9.]+)
        preg_match_all('%(?P<browser>Camino|Kindle|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget|curl)(?:[/ ])(?P<version>[0-9.]+)%im', $u_agent, $result, PREG_PATTERN_ORDER);

        if (isset($data['platform']) && $data['platform'] == 'linux-gnu')
        {
            $data['platform'] = 'Linux';
        }

        if (($key = array_search('Kindle', $result['browser'])) !== false || ($key = array_search('Silk', $result['browser'])) !== false)
        {
            $data['browser'] = $result['browser'][$key];
            $data['platform'] = 'Kindle';
            $data['version'] = $result['version'][$key];
        }
        elseif (isset($result['browser'][0]) and $result['browser'][0] == 'AppleWebKit')
        {
            if ((isset($data['platform']) && $data['platform'] == 'Android' && !($key = 0)) || $key = array_search('Chrome', $result['browser']))
            {
                $data['browser'] = 'Chrome';
                if (($vkey = array_search('Version', $result['browser'])) !== false)
                {
                    $key = $vkey;
                }
            }
            elseif (isset($data['platform']) && $data['platform'] == 'BlackBerry')
            {
                $data['browser'] = 'BlackBerry Browser';
                if (($vkey = array_search('Version', $result['browser'])) !== false)
                {
                    $key = $vkey;
                }
            }
            elseif ($key = array_search('Kindle', $result['browser']))
            {
                $data['browser'] = 'Kindle';
            }
            elseif ($key = array_search('Safari', $result['browser']))
            {
                $data['browser'] = 'Safari';
                if (($vkey = array_search('Version', $result['browser'])) !== false)
                {
                    $key = $vkey;
                }
            }
            else
            {
                $key = 0;
            }

            $data['version'] = $result['version'][$key];
        }
        elseif (($key = array_search('Opera', $result['browser'])) !== false)
        {
            $data['browser'] = $result['browser'][$key];
            $data['version'] = $result['version'][$key];
            if (($key = array_search('Version', $result['browser'])) !== false)
            {
                $data['version'] = $result['version'][$key];
            }
        }
        elseif (isset($result['browser'][0]) and $result['browser'][0] == 'MSIE')
        {
            if ($key = array_search('IEMobile', $result['browser']))
            {
                $data['browser'] = 'IEMobile';
            }
            else
            {
                $data['browser'] = 'MSIE';
                $key = 0;
            }
            $data['version'] = isset($result['version'][$key]) ? $result['version'][$key] : '';
        }
        elseif ($key = array_search('Kindle', $result['browser']))
        {
            $data['browser'] = 'Kindle';
            $data['platform'] = 'Kindle';
        }
        else
        {
            $data['browser'] = isset($result['browser'][0]) ? $result['browser'][0] : '';
            $data['version'] = isset($result['browser'][0]) ? $result['browser'][0] : '';
        }
        if (!isset($data['browser']) || empty($data['browser']))
        {
            $data['browser'] = $u_agent;
        }
        return $data;
    }

    /**
     * @Description : Convert Number into words
     * @param type $num
     * @return string
     */
    public function numberToWords($num)
    {
        $ones = [
            0 => "ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            "014" => "FOURTEEN"
        ];
        $tens = [
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY",
            4 => "FORTY",
            5 => "FIFTY",
            6 => "SIXTY",
            7 => "SEVENTY",
            8 => "EIGHTY",
            9 => "NINETY"
        ];
        $hundreds = [
            "HUNDRED",
            "THOUSAND",
            "MILLION",
            "BILLION",
            "TRILLION",
            "QUARDRILLION"
        ]; /* limit t quadrillion */
        $num = number_format($num, 2, ".", ",");
        $num_arr = explode(".", $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr, 1);
        $rettxt = "";
        foreach ($whole_arr as $key => $i)
        {
            while (substr($i, 0, 1) == "0")
            {
                $i = substr($i, 1, 5);
            }
            if ($i < 20)
            {
                /* echo "getting:".$i; */
                $rettxt .= $ones[$i];
            }
            elseif ($i < 100)
            {
                if (substr($i, 0, 1) != "0")
                {
                    $rettxt .= $tens[substr($i, 0, 1)];
                }
                if (substr($i, 1, 1) != "0")
                {
                    $rettxt .= " " . $ones[substr($i, 1, 1)];
                }
            }
            else
            {
                if (substr($i, 0, 1) != "0")
                {
                    $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                }
                if (substr($i, 1, 1) != "0")
                {
                    $rettxt .= " " . $tens[substr($i, 1, 1)];
                }
                if (substr($i, 2, 1) != "0")
                {
                    $rettxt .= " " . $ones[substr($i, 2, 1)];
                }
            }
            if ($key > 0)
            {
                $rettxt .= " " . $hundreds[$key] . " ";
            }
        }
        if ($decnum > 0)
        {
            $rettxt .= " and ";
            if ($decnum < 20)
            {
                $rettxt .= $ones[$decnum];
            }
            elseif ($decnum < 100)
            {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= " " . $ones[substr($decnum, 1, 1)];
            }
        }
        return $rettxt;
    }

    public static function getAlphanumericString(string $data): string
    {
        return preg_replace('/[^a-zA-Z0-9]+/', '', strtolower($data));
    }


    /**
     * @description check visitor is a bot
     * @param array $data
     * @return bool
     */
    public static function isBot(array $data): bool
    {
        $botlist = [
            "Teoma", "alexa", "froogle", "Gigabot", "inktomi",
            "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
            "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
            "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
            "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
            "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
            "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler", "TweetmemeBot",
            "Butterfly", "Twitturls", "Me.dium", "Twiceler", "ABCdatos BotLink", "Acme.Spider", "Alkaline", "Anthill", "Walhello appie", "Arachnophilia", "Arale", "Araneo", "AraybOt", "ArchitextSpider", "Aretha", "ARIADNE", "arks", "AskJeeves", "ASpider", "ATN Worldwide",
            "Atomz.com", "AURESYS", "BackRub", "Bay Spider", "Big Brother", "Bjaaland", "BlackWidow", "Die Blinde Kuh", "Bloodhound", "Borg-Bot", "BoxSeaBot", "bright.net", "BSpider", "CACTVS", "Calif", "Cassandra", "Digimarc Marcspider/CGI", "Checkbot", "ChristCrawler.com", "churl", "cIeNcIaFiCcIoN.nEt", "CMC/0.01", "Collective", "Combine System", "Conceptbot", "ConfuzzledBot", "CoolBot", "Web Core / Roots", "XYLEME Robot", "Internet Cruiser Robot", "Cusco", "CyberSpyder Link Test", "CydralSpider", "Desert Realm Spider", "DeWeb(c) Katalog/Index", "DienstSpider", "Digger", "Digital Integrity Robot", "Direct Hit Grabber", "DNAbot", "DownLoad Express", "DragonBot", "DWCP", "e-collector", "EbiNess", "EIT Link Verifier Robot", "ELFINBOT", "Emacs-w3 Search Engine", "ananzi", "esculapio", "Esther", "Evliya Celebi", "FastCrawler", "Fluid Dynamics Search Engine robot", "Felix IDE", "Wild Ferret Web Hopper", "FetchRover", "fido", "Hämähäkki", "KIT-Fireball", "Fish search", "Fouineur", "Robot Francoroute", "Freecrawl", "FunnelWeb", "gammaSpider", "FocusedCrawler", "gazz", "GCreep", "GetBot", "GetURL", "Golem", "Grapnel/0.01 Experiment", "Griffon", "Gromit", "Northern Light Gulliver", "Gulper Bot", "HamBot", "Harvest", "havIndex", "HI (HTML Index) Search", "Hometown Spider Pro", "ht://Dig", "HTMLgobble", "Hyper-Decontextualizer", "iajaBot", "IBM_Planetwide", "Popular Iconoclast", "Ingrid", "Imagelock", "IncyWincy", "Informant", "InfoSeek Robot 1.0", "Infoseek Sidewinder", "InfoSpiders", "Inspector Web", "IntelliAgent", "Iron33", "Israeli-search", "JavaBee", "JBot Java Web Robot", "JCrawler", "Jeeves", "JoBo Java Web Robot", "Jobot", "JoeBot", "The Jubii Indexing Robot", "JumpStation", "image.kapsi.net", "Katipo", "KDD-Explorer", "Kilroy", "KO_Yappo_Robot", "LabelGrabber", "larbin", "legs", "Link Validator", "LinkScan", "LinkWalker", "Lockon", "logo.gif Crawler", "Lycos", "Mac WWWWorm", "Magpie", "marvin/infoseek", "Mattie", "MediaFox", "MerzScope", "NEC-MeshExplorer", "MindCrawler", "mnoGoSearch search engine software", "moget", "MOMspider", "Monster", "Motor", "MSNBot", "Muncher", "Muninn", "Muscat Ferret", "Mwd.Search", "Internet Shinchakubin", "NDSpider", "Nederland.zoek", "NetCarta WebMap Engine", "NetMechanic", "NetScoop", "newscan-online", "NHSE Web Forager", "Nomad", "The NorthStar Robot", "nzexplorer", "ObjectsSearch", "Occam", "HKU WWW Octopus", "OntoSpider", "Openfind data gatherer", "Orb Search", "Pack Rat", "PageBoy", "ParaSite", "Patric", "pegasus", "The Peregrinator", "PerlCrawler 1.0", "Phantom", "PhpDig", "PiltdownMan", "Pimptrain.com's robot", "Pioneer", "html_analyzer", "Portal Juice Spider", "PGP Key Agent", "PlumtreeWebAccessor", "Poppi", "PortalB Spider", "psbot", "GetterroboPlus Puu", "The Python Robot", "Raven Search", "RBSE Spider", "Resume Robot", "RoadHouse Crawling System", "RixBot", "Road Runner: The ImageScape Robot", "Robbie the Robot", "ComputingSite Robi/1.0", "RoboCrawl Spider", "RoboFox", "Robozilla", "Roverbot", "RuLeS", "SafetyNet Robot", "Scooter", "Sleek", "Search.Aus-AU.COM", "SearchProcess", "Senrigan", "SG-Scout", "ShagSeeker", "Shai'Hulud", "Sift", "Simmany Robot Ver1.0", "Site Valet", "Open Text Index Robot", "SiteTech-Rover", "Skymob.com", "SLCrawler", "Inktomi Slurp", "Smart Spider", "Snooper", "Solbot", "Spanner", "Speedy Spider", "spider_monkey", "SpiderBot", "Spiderline Crawler", "SpiderMan", "SpiderView(tm)", "Spry Wizard Robot", "Site Searcher", "Suke", "suntek search engine", "Sven", "Sygol", "TACH Black Widow", "Tarantula", "tarspider", "Tcl W3 Robot", "TechBOT", "Templeton", "TeomaTechnologies", "TITAN", "TitIn", "The TkWWW Robot", "TLSpider", "UCSD Crawl", "UdmSearch", "UptimeBot", "URL Check", "URL Spider Pro", "Valkyrie", "Verticrawl", "Victoria", "vision-search", "void-bot", "Voyager", "VWbot", "The NWI Robot", "W3M2", "WallPaper (alias crawlpaper)", "the World Wide Web Wanderer", "w@pSpider by wap4.com", "WebBandit Web Spider", "WebCatcher", "WebCopy", "webfetcher", "The Webfoot Robot", "Webinator", "weblayers", "WebLinker", "WebMirror", "The Web Moose", "WebQuest", "Digimarc MarcSpider", "WebReaper", "webs", "Websnarf", "WebSpider", "WebVac", "webwalk", "WebWalker", "WebWatch", "Wget", "whatUseek Winona", "WhoWhere Robot", "Wired Digital", "Weblog Monitor", "w3mir", "WebStolperer", "The Web Wombat", "The World Wide Web Worm", "WWWC Ver 0.2.5", "WebZinger", "XGET"
        ];
        foreach ($botlist as $bot)
        {
            if (isset($data['HTTP_USER_AGENT']) && strpos($data['HTTP_USER_AGENT'], $bot) !== false)
            {
                return true;
            }
        }
        return false;
    }


    /**
     * convert bytes to readable format
     * @param type $bytes
     * @param type $precision
     * @return type
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * Output scripts memory usage
     */
    public static function echo_memory_usage()
    {
        $mem_usage = memory_get_usage(true);

        if ($mem_usage < 1024)
        {
            return $mem_usage . " bytes";
        }
        elseif ($mem_usage < 1048576)
        {
            return round($mem_usage / 1024, 2) . " kilobytes";
        }
        else
        {
            return round($mem_usage / 1048576, 2) . " megabytes";
        }

        return "<br/>";
    }

    public static function echo_script_time()
    {
        $seconds = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

        if ($seconds >= 3600)
        {
            if ($seconds / 3600 > 1)
            {
                return ($seconds / 3600) . " hrs";
            }
            else
            {
                return ($seconds / 3600) . " hr";
            }
        }
        elseif ($seconds >= 60)
        {
            if ($seconds / 60 > 1)
            {
                return ($seconds / 60) . " mins";
            }
            else
            {
                return ($seconds / 60) . " min";
            }
        }
        else
        {
            return $seconds . " Seconds";
        }
    }



//    public static function generateUniqueTrackingCode(int $limit = 8)
//    {
//        if (!isset($limit))
//        {
//            $limit = 8;
//        }
//        do
//        {
//            $trackingCode = \App\Utility\Utility::uniqueCode($limit);
//        } while (empty($tracking = \App\Model\Eloquent\EmailTracking::where('tracking_code', '=', $trackingCode)));
//
//        return $trackingCode;
//    }

    public static function uniqueCode(int $limit = 8)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    /**
     *
     * @param type $directory
     * @param type $results
     * @return array
     */
    public function getAllDirectoryDocuments($directory, &$results = []): array
    {
        if (file_exists($directory))
        {
            $files = scandir($directory);
            foreach ($files as $key => $value)
            {
                $path = realpath($directory . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path) && $value != "." && $value != ".." && $value != '.DS_Store')
                {
                    $results[][pathinfo($path, PATHINFO_DIRNAME)] = $value;
                }
                elseif ($value != "." && $value != ".." && $value != '.DS_Store')
                {
                    self::getAllDirectoryDocuments($path, $results);
                }
            }
        }
        return $results;
    }
    /**
     * @Description: It takes Eloquent model object and convert it to array.
     * @param \App\Model\Eloquent $eloquent
     * @return array
     */
    public static function getArray($eloquent): array
    {
        return (isset($eloquent) && !empty($eloquent)) ? $eloquent->toArray() : [];
    }

    /**
     * @Description : removes stock keywords from string
     * @global type $con1
     * @param type $search_word
     * @return type
     */
    public static function filter_keywords($search_word = "")
    {
        $result = self::getArray(\App\Model\Eloquent\FilterKeyword::where('deleted', 0)->get());
        $search_wor = "";
        $search_word = \trim($search_word);
        $pieces = \explode(" ", $search_word);
        $search_word = "";
        if (!empty($pieces))
        {
            foreach ($pieces as $strvalue)
            {
                $bflag = 0;
                foreach ($result as $row)
                {
                    if (strcasecmp(trim($strvalue), trim($row['keyword'])) == 0)
                    {
                        $bflag = 1;
                    }
                }
                if (!$bflag)
                {
                    $search_word .= $strvalue . ' ';
                }
            }
        }

        $keyString = array('~', '@', '#', '[', ']', '{', '}', '^', ':', '.', '+');
        $search_word = str_replace($keyString, ' ', $search_word);
        $search_word = \str_replace("&", "", $search_word);
        $search_word = \str_replace("(", "", $search_word);
        $search_word = \str_replace(")", "", $search_word);
        $search_word = \str_replace("-", " ", $search_word);
        $pieces = null;
        $pieces = \explode(" ", $search_word);
        $strtemp = '';

        if (!empty($pieces))
        {
            foreach ($pieces as $strvalue)
            {
                $strsb = '';
                if ($strvalue != '')
                {
                    if ($strtemp != '')
                    {
                        $strsb = "'" . $strtemp . ' ' . $strvalue . "'";
                        $search_wor .= "+" . $strsb . " ";
                        $strsb = $strtemp = '';
                    }
                    else
                    {
                        if (strlen($strvalue) <= 2)
                        {
                            $search_wor .= $strvalue . " ";
                        }
                        else
                        {
                            if (stripos($strvalue, '-'))
                            {
                                $strsb .= '"' . $strvalue . '"';
                            }
                            else
                            {
                                $strsb .= $strvalue;
                            }
                            if (!stripos($strvalue, '.'))
                            {
                                $search_wor .= "+" . $strsb . " ";
                            }
                            else
                            {
                                $search_wor .= $strsb . " ";
                            }
                        }
                    }
                }
            }
        }
        return trim($search_wor);
    }

    /**
     * Deletes files and inside a directory
     * @param type $target
     */
    public static function deleteFiles($target)
    {
        if (is_dir($target))
        {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file)
            {
                self::deleteFiles($file);
            }
        }
        elseif (is_file($target))
        {
            unlink($target);
        }
    }

    public static function notification(array $data)
    {
        $socketComponent = new \App\Core\Components\WebSocketComponent();
        $socketComponent->sendNotifications([$data]);
    }

    public static function removeAllNull($data)
    {
        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                self::removeAllNull($value);
            }
            elseif (!isset($value) || is_null($value))
            {
                $data[$key] = "";
            }
        }
        return $data;
    }

    public static function throwWhoopsException(array $dataError)
    {
        $whoops = new \Whoops\Run();
        if ('production' != $_ENV['APPLICATION_ENVIRONMENT'])
        {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        }
        $whoops->pushHandler(function ($dataError)
        {
            new DB;
            $browser = self::getBrowserFromUserAgent($_SERVER);
            ApplicationCodeException::create(
                [
                    'request' => !empty($_REQUEST) ? serialize($_REQUEST) : null,
                    'method' => null,
                    'class' => null,
                    'line' =>  null,
                    'file' =>  null,
                    'session_id' => session_id(),
                    'user_id' => isset($_SESSION['member_id']) ? $_SESSION['member_id'] : null,
                    'message' => isset($dataError) ? $dataError['ack'] : null,
                    'code' => null,
                    'previous' => null,
                    'trace' => null,
                    'trace_as_string' => null,
                    'created' => date('Y-m-d H:i:s'),
                    'status' => 'open'
                ]
            );

            $user = \App\Model\Eloquent\IbfMember::find(isset($_SESSION['member_id']) ? $_SESSION['member_id'] : 'NULL');
            $company = \App\Model\Eloquent\VendorEdu::find(isset($_SESSION['company_id']) ? $_SESSION['company_id'] : 'NULL');



            $templateData = [];
            $templateData['name'] = 'Developers';
            $templateData['errorDescription'] = $error;
            $templateData['userName'] = isset($user->name) ? $user->name : 'CRON JOB';

            $templateData['userEmail'] = isset($user->email) ? $user->email : 'CRON JOB';
            $templateData['Url'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CRON JOB';
            $templateData['IPAddress'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CRON JOB';
            $templateData['userId'] = isset($_SESSION['member_id']) ? $_SESSION['member_id'] : 'CRON JOB';
            $templateData['companyId'] = isset($_SESSION['company_id']) ? $_SESSION['company_id'] : 'CRON JOB';
            $templateData['companyName'] = isset($company->company_name) ? $company->company_name : 'CRON JOB';
            $templateData['browser'] = isset($browser['browser']) ? $browser['browser'] : 'CRON JOB';
            $templateData['browserVersion'] = isset($browser['version']) ? $browser['version'] : 'CRON JOB';
            $templateData['OSPlatform'] = isset($browser['platform']) ? $browser['platform'] : 'CRON JOB';
            $templateData['date'] = date('Y-m-d H:i:s');

            $data = [];
            $data['to'] = 'ebapplicationerror@ethixbase.com';
            $data['subject'] = 'Application Error';

            if ($_ENV['EMAIL_DYNAMIC'] == true)
            {
                //DynamicEmail
                $emailComponent = new EmailComponent('Exceptions/exception');
                $emailComponent->send($templateData);
            }
            else
            {
                $data['message'] = Utility::emailTemplate('Exceptions/exception', $templateData);
                \App\Services\Email\SendEmailService::sendEmail($data);
            }
        });
        $whoops->register();
    }

    /**
     *  @date input date
     *  @dateFormat pass date format
     */

    public static function setDateTimeAsPerTimeZone(\DateTime $date, \DateTimeZone $timeZone, string  $dateFormat = 'Y-m-d H:i:s')
    {
        return $date->setTimezone($timeZone)->format($dateFormat);
    }

    public static function castObjectToArray($obj = [])
    {
        if (!is_null($obj) && !empty($obj))
        {
            return json_decode(json_encode($obj), true);
        }
        else
        {
            return $obj;
        }
    }

    public static function castObjectToSingletonArray($obj = [])
    {
        $data =  json_decode(json_encode($obj), true);
        if (isset($data[0]) && !empty($data[0]))
        {
            $data = isset($data[0]) ? $data[0] : [];
        }
        return $data;
    }

    /* Get header Authorization
    * */
    public static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization']))
        {
            $headers = trim($_SERVER["Authorization"]);
        }
        elseif (isset($_SERVER['HTTP_AUTHORIZATION']))
        { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        elseif (function_exists('apache_request_headers'))
        {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization']))
            {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        else
        {
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    public static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        //echo "<pre>",print_r(getallheaders());die;
        // HEADER: Get the access token from the header
        if (!empty($headers))
        {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches))
            {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @param string $searchword
     * @param array $dataArray
     * @return array
     */
    public static function getHeaderValue(string $searchword, array $dataArray)
    {
        $filteredArray = array_filter(
            array_unique($dataArray),
            function ($var) use ($searchword)
            {
                return preg_match("/\b$searchword\b/i", $var);
            }
        );

        if (count($filteredArray) > 0)
        {
            return str_replace($searchword, "", $filteredArray[array_keys($filteredArray)[0]]);
        }
        else
        {
            return false;
        }
    }

    static function getUniqueArray($dataArray)
    {
        if (isset($dataArray) && is_array($dataArray))
        {
            return array_values(array_unique($dataArray));
        }
        return isset($dataArray) ? $dataArray : [];
    }

    public static function checkCSFRToken()
    {
        if (php_sapi_name() !== "cli")
        {
            return true;
        }
        elseif (trim(self::getHeaderValue('X-CSRF-TOKEN-APP: ', headers_list())) == $_SESSION['csrf-token'])
        {
            return true;
        }
        else
        {
            \App\Core\Logger\AppLogger::alert('INVALID CSRF-TOKEN', [
                'session' => $_SESSION,
                'server' => $_SERVER,
                'request' => $_REQUEST
            ]);
            return false;
        }
    }

    public static function getFormElementConfigurationFromSchema($formSchema, $sectionId, $elementId): array
    {
        $formSections = isset($formSchema['formSection']) ? $formSchema['formSection'] : [];
        $filterFormSection = self::filterArrayByValue($formSections, 'id', $sectionId);
        if (isset($filterFormSection) && is_array($filterFormSection) && count($filterFormSection) > 0)
        {
            $formSection = $filterFormSection[0];
            $filterFormElement = self::filterArrayByValue($formSection['formElements'], 'id', $elementId);
            if (isset($filterFormElement) && is_array($filterFormElement) && count($filterFormElement) > 0)
            {
                return $filterFormElement[0];
            }
        }
        return [];
    }

    public static function sanitizeDirectoryName(string $string): string
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        //        $string = strtolower($string); // Convert to lowercase
        return $string;
    }

    static public function keepAlphanumericCharacterOnly(string $string)
    {
        return preg_replace("/[^a-zA-Z0-9 ]+/", "", $string);
    }

    static public function sanitizeDocumentFileName(string $string): string
    {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-\_]/', '', $string); // Removes special chars.
        return $string;
    }

    public static function sortArrayBy($array, $sort_by_key, $sort_by_order = 'asc')
    {
        self::$sort_by_param = [
            'key' => isset($sort_by_key) ? $sort_by_key : '',
            'order' => $sort_by_order
        ];
        usort($array, ['\App\Utility\Utility', 'sortBy']);
        return $array;
    }

    private static function sortBy($element1, $element2)
    {
        if (self::$sort_by_param['order'] == 'desc')
        {
            return ($element1[self::$sort_by_param['key']] >= $element2[self::$sort_by_param['key']]) ? -1 : 1;
        }
        return ($element1[self::$sort_by_param['key']] <= $element2[self::$sort_by_param['key']]) ? -1 : 1;
    }

    private static function removeByValue($arrayValue)
    {
        if (isset($arrayValue[self::$sort_by_param['key']]) && $arrayValue[self::$sort_by_param['key']] == self::$sort_by_param['value'])
        {
            return false;
        }
        return true;
    }

    public static function removeFromArrayByValues($array, $filter_key, array $filter_values)
    {
        $filteredArray = [];
        for ($x = 0; $x < count($filter_values); $x++)
        {
            if (isset($filter_values[$x]) && isset($filter_values[$x]))
            {
                self::$sort_by_param = [
                    'key' => isset($filter_key) ? $filter_key : '',
                    'value' => isset($filter_values[$x]) ? $filter_values[$x] : ''
                ];
            }
            $filteredArray = array_merge(array_values(array_filter($array, "\App\Utility\Utility::removeByValue")), $filteredArray);
        }
        return $filteredArray;
    }

    public static function removeFromArrayByValue($array, $filter_key, $filter_value)
    {
        if (isset($filter_value) && !empty($filter_value) && is_array($filter_value) && count($filter_value) > 0)
        {
            return self::removeFromArrayByValues($array, $filter_key, $filter_value);
        }
        self::$sort_by_param = [
            'key' => isset($filter_key) ? $filter_key : '',
            'value' => isset($filter_value) ? $filter_value : ''
        ];
        return array_values(array_filter($array, "\App\Utility\Utility::removeByValue"));
    }

    public static function replaceFromArrayByValue($array, $filter_key, $filter_value, $replaceObject)
    {
        if (isset($filter_value) && !empty($filter_value) && is_array($filter_value) && count($filter_value) > 0)
        {
            return self::removeFromArrayByValues($array, $filter_key, $filter_value);
        }
        self::$sort_by_param = [
            'key' => isset($filter_key) ? $filter_key : '',
            'value' => isset($filter_value) ? $filter_value : ''
        ];
        $array = array_values(array_filter($array, "\App\Utility\Utility::removeByValue"));
        $array[] = $replaceObject;
        return $array;
    }

    /**
     *
     * @param type $array
     * @param type $filter_keys
     * @param type $filter_values
     * @return Description : $filter_keys & $filter_values should have same number of objects.
     * @return Data with multiple values with all values from main array (OR)
     * @concept : OR
     */
    public static function filterArrayByKeysAndValues($array, $filter_keys, $filter_values)
    {
        $filteredArray = $array;
        if (count($filter_keys) == count($filter_values))
        {
            for ($x = 0; $x < count($filter_keys); $x++)
            {
                if (isset($filter_keys[$x]) && isset($filter_values[$x]))
                {
                    self::$sort_by_param = [
                        'key' => isset($filter_keys[$x]) ? $filter_keys[$x] : '',
                        'value' => isset($filter_values[$x]) ? $filter_values[$x] : ''
                    ];
                }
                $filteredArray = array_values(array_filter($filteredArray, "\App\Utility\Utility::filterByValue"));
            }
        }
        return $filteredArray;
    }

    static function findItemIndexInArray($array, $filter_key, $filter_value)
    {
        if(isset($array) && !empty($array)) {
            foreach ($array as $index => $record)
            {
                if (isset($record[$filter_key]) && $record[$filter_key] == $filter_value)
                {
                    return $index;
                }
            }
        }
        return -1;
    }
    
    static function findItemAndIndexInArray($array, $filter_key, $filter_value)
    {
        if(isset($array) && !empty($array)) {
            foreach ($array as $index => $record)
            {
                if (isset($record[$filter_key]) && $record[$filter_key] == $filter_value)
                {
                    return ['index' => $index, 'item' => $record];
                }
            }
        }
        return [];
    }

    /**
     *
     * @param type $array
     * @param type $filter_keys
     * @param type $filter_values
     * @return Description : $filter_keys & $filter_values should have same number of objects.
     * @return Only give array with all keys and values will be there (AND)
     * @concept : AND
     */
    public static function filterArrayWithKeysAndValues($array, $filter_keys, $filter_values)
    {
        $filteredArray = $array;
        if (count($filter_keys) == count($filter_values))
        {
            for ($x = 0; $x < count($filter_keys); $x++)
            {
                if (isset($filter_keys[$x]) && isset($filter_values[$x]))
                {
                    $filteredArray = self::filterArrayByValue($filteredArray, $filter_keys[$x], $filter_values[$x]);
                }
            }
        }
        return $filteredArray;
    }

    public static function filterArrayByValue($array, $filter_key, $filter_value)
    {
        if (isset($filter_value) && !empty($filter_value) && is_array($filter_value) && count($filter_value) > 0)
        {
            return self::filterArrayByValues($array, $filter_key, $filter_value);
        }
        self::$sort_by_param = [
            'key' => isset($filter_key) ? $filter_key : '',
            'value' => isset($filter_value) ? $filter_value : ''
        ];
        return array_values(array_filter($array, "\App\Utility\Utility::filterByValue"));
    }

    public static function filterArrayByValues($array, $filter_key, array $filter_values)
    {
        $filteredArray = [];
        for ($x = 0; $x < count($filter_values); $x++)
        {
            if (isset($filter_values[$x]) && isset($filter_values[$x]))
            {
                self::$sort_by_param = [
                    'key' => isset($filter_key) ? $filter_key : '',
                    'value' => isset($filter_values[$x]) ? $filter_values[$x] : ''
                ];
            }
            $filteredArray = array_merge(array_values(array_filter($array, "\App\Utility\Utility::filterByValue")), $filteredArray);
        }
        return $filteredArray;
    }

    private static function filterByValue($arrayValue)
    {
        if (isset($arrayValue[self::$sort_by_param['key']]) && $arrayValue[self::$sort_by_param['key']] == self::$sort_by_param['value'])
        {
            return true;
        }
        return false;
    }

    public static function groupBy($key, $data, $showKeyAsIndex = true)
    {
        $result = [];
        foreach ($data as $val)
        {
            //            if (array_key_exists($key, $val)) {
            if (isset($val[$key]))
            {
                $result[$val[$key]][] = $val;
            }
            else
            {
                $result[""][] = $val;
            }
        }
        if ($showKeyAsIndex == true)
        {
            return $result;
        }
        else
        {
            return self::replaceKeyWithIndex($result);
        }
    }

    public static function groupByKeyAndValue($key, $data)
    {
        $result = [];
        foreach ($data as $val)
        {
            if (isset($val[$key]))
            {
                $result[$val[$key]][] = $val;
            }
            else
            {
                $result[""][] = $val;
            }
        }
        return self::replaceKeyAndValue($result);
    }

    public static function replaceKeyAndValue($result): array
    {
        $formatResponse = [];
        foreach ($result as $section => $sectionData)
        {
            $formatResponse[] = [
                'key' => $section,
                'value' => $sectionData
            ];
        }
        return $formatResponse;
    }

    public static function replaceKeyWithIndex($result): array
    {
        $formatResponse = [];
        foreach ($result as $section => $sectionData)
        {
            $formatResponse[] = $sectionData;
        }
        return $formatResponse;
    }

    public static function sendTestEmailInBackground($message)
    {
        self::backgroundQueueTask([
            'to' => NO_REPLY_EMAIL,
            'subject' => 'Test Email',
            'message' => $message

        ], $queue = 'Email', '\\App\\Jobs\\SendEmail', getenv('REDIS_HOST'), getenv('REDIS_PORT'), $database = 0);
    }
    public static function sendNovartisReport($message)
    {
        self::backgroundQueueTask([
            'to' => NO_REPLY_EMAIL,
            'subject' => 'Test Email',
            'message' => $message

        ], $queue = 'Email', '\\App\\Jobs\\NovartisReport', getenv('REDIS_HOST'), getenv('REDIS_PORT'), $database = 0);
    }

    public static function sendTestEmail($message)
    {
        if (!is_string($message))
        {
            $message = json_encode($message);
        }
        
        // send email
        mail("rocking.gjindal786@gmail.com","Test Email",$message);
//        \App\Services\Email\SendEmailService::sendEmail([
//            'to' => TEST_INT_EMAIL,
//            'subject' => 'Test Email',
//            'message' => $message
//
//        ]);
    }


    /**
     *  @date input string
     *  @dateFormat pass string and get date in required format
     */

    public static function stringtoDate(string $date, string  $dateFormat = 'Y-m-d H:i:s'): string
    {
        $newDate = date($dateFormat, strtotime($date));
        return $newDate;
    }

    /**
     *
     * @param string $filename
     * @return type
     * @throws \Exception
     */
    public static function readCsvFile(string $filepath): array
    {
        try
        {
            $csvData = [];

            /**
             * Allowed format to upload files
             */

            $allowed_extension = array('xls', 'csv', 'xlsx');
            $file_array = explode(".", $filepath);

            $file_extension = end($file_array);

            if (in_array($file_extension, $allowed_extension))
            {
                //p($file_extension);
                $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filepath);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
                $spreadsheet = $reader->load($filepath);
                $data = $spreadsheet->getActiveSheet()->toArray();
                foreach ($data as $row)
                {
                    $csvData[] = $row;
                }


                return $csvData;
            }
            else
            {
                return [];
            }
        }
        catch (\Exception $ex)
        {
            \App\Core\Logger\AppLogger::error($ex->getMessage(), $ex->getTrace());
            return [];
        }
    }

    /**
     *
     * @param string $filename
     * @return array
     */
    public static function readPdfFile(string $filename): array
    {
        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();

        $pdf = $parser->parseFile($filename);
        // Retrieve all details from the pdf file.
        $details = $pdf->getDetails();

        // Loop over each property to extract values (string or array).
        /**
          foreach ($details as $property => $value)

          {
          if (is_array($value))
          {
          $value = implode(', ', $value);
          }
          $properties[] =  $property . ' => ' . $value . "\n";
          }
         *
         */
        $text = $pdf->getText();
        $textToArray = [];
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $text) as $line)
        {
            if ($line != '' || !empty($line) || !isset($line))
            {
                $textToArray[] = $line;
            }
        }
        return $textToArray;
    }


    public static function fileUploadOnServer($files)
    {
        $file = (isset($files['file']['tmp_name']) ? $files['file']['tmp_name'] : '');
        $file_csvfile_name = (isset($files['file']['name']) ? $files['file']['name'] : '');
        $filetype = \pathinfo($file_csvfile_name, PATHINFO_EXTENSION);
        $target_dir = PROJECT_ROOT . DS . UPLOAD_SESSION_FOLDER_PATH;
        $output_file = PROJECT_ROOT . DS . UPLOAD_SESSION_FOLDER_PATH . DS . 'output.csv';
        if (!file_exists($target_dir))
        {
            mkdir($target_dir, 0775, true);
        }
        $filename = md5(time());
        $target_file = $target_dir . DS . $filename . "." . $filetype;
        $file = $filename . "." . $filetype;
        $success = false;
        if (move_uploaded_file($files["file"]["tmp_name"], $target_file))
        {
            return ['status' => true, 'filename' => $filename, 'targetFile' => $target_file];
        }
        else
        {
            return ['status' => false];
        }
    }

    /**
     * @desc this function return the index of word from array
     * @param array $data
     * @param string $findWord
     * @return type
     */
    public static function getDataIndex(array $data, string $findquestion)
    {
        $k = 0;
        foreach ($data as $record)
        {
            if ($record['question'] == $findquestion)
            {
                return $k;
            }
            $k++;
        }
        return -1;
    }

    /**
     */

    public static function removeDataKeyFromArray(array $data, string  $key): array
    {
        $updated = [];
        foreach ($data as $dataObj)
        {
            unset($dataObj[$key]);
            $updated[] = $dataObj;
        }
        return $updated;
    }

    /**
     * function used to decode alerts for Public APIs
     * @param array $data
     * @return array
     */
    public static function jsonDecodeAlerts(array $data): array
    {
        if (isset($data['event']))
        {
            $data['event'] = json_decode($data['event']);
        }

        if (isset($data['address']))
        {
            $data['address'] = json_decode($data['address']);
        }
        if (isset($data['attribute']))
        {
            $data['attribute'] = json_decode($data['attribute']);
        }

        if (isset($data['alias']))
        {
            $data['alias'] = json_decode($data['alias']);
        }
        if (isset($data['sources']))
        {
            $data['sources'] = json_decode($data['sources']);
        }
        return $data;
    }

    public static function getCountryCodeByName(string $countryName): string
    {
        $isoCountries = \App\Model\Eloquent\IsoCountry::where('country_name', '=', $countryName)->orWhere('cpi_country_name', '=', $countryName)->first();
        if (!is_null($isoCountries) && !empty($isoCountries) && $isoCountries->count() > 0)
        {
            $isoCountries->toArray();
            $country_code = isset($isoCountries['country_code']) ? $isoCountries['country_code'] : '';
            return $country_code;
        }
        return $countryName;
    }
    public static function getCountryName(string $countryCode): string
    {
        //return $countryCode;
        if (strlen($countryCode) > 0)
        {
            if (strpos($countryCode, '_') !== false)
            {
                $res_states = \App\Model\Eloquent\OcState::where('code', $countryCode)->get();
                $res_states = Utility::getArray($res_states);
                if (count($res_states) > 0)
                {
                    $states = isset($res_states[0]['state']) ? $res_states[0]['state'] : '';
                    $res_country = \App\Model\Eloquent\OcCountry::where('code', (isset($res_states[0]['country_code']) ? $res_states[0]['country_code'] : ''))->get();
                    if (count($res_country) > 0)
                    {
                        $states = isset($res_country[0]['country']) ? $res_country[0]['country'] : '';
                    }
                    return $states;
                }
            }
            else
            {
                $country = $countryCode;

                $res_country = \App\Model\Eloquent\OcCountry::where('code', strtolower($country))->get();
                $res_country = Utility::getArray($res_country);
                if (count($res_country) > 0)
                {
                    $country = isset($res_country[0]['country']) ? $res_country[0]['country'] : '';
                    return $country;
                }
                else
                {
                    $res_country = \App\Model\Eloquent\IsoCountry::where('country_code', strtolower($country))->get();
                    $res_country = Utility::getArray($res_country);
                    if (count($res_country) > 0)
                    {
                        $country = isset($res_country[0]['cpi_country_name']) ? $res_country[0]['cpi_country_name'] : '';
                        return $country;
                    }
                    else
                    {
                        return $country;
                    }
                }
            }
        }
        else
        {
            return $countryCode;
        }
    }


    
    public static function getLanguageCodeByName($languageName = 'english')
    {
        $languageName = trim(strtolower($languageName));

        $language['english'] = LANGUAGE_CODE_ENGLISH;
        $language['spanish'] = LANGUAGE_CODE_SPANISH;
        $language['arabic'] = LANGUAGE_CODE_ARABIC;
        $language['portuguese'] = LANGUAGE_CODE_BRAZILIAN_PORTUGUESE;
        $language['portuguese brazilian'] = LANGUAGE_CODE_BRAZILIAN_PORTUGUESE;
        $language['chinese'] = LANGUAGE_CODE_CHINESE;
        $language['chinese simplified'] = LANGUAGE_CODE_CHINESE;
        $language['chinese simplified (中文)'] = LANGUAGE_CODE_CHINESE;
        
        $language['czech'] = LANGUAGE_CODE_CZECH;
        $language['dutch'] = LANGUAGE_CODE_DUTCH;
        $language['french'] = LANGUAGE_CODE_FRENCH;
        $language['greek'] = LANGUAGE_CODE_GREEK;
        $language['germen'] = LANGUAGE_CODE_GERMAN;
        $language['german'] = LANGUAGE_CODE_GERMAN;
        $language['hungarian'] = LANGUAGE_CODE_HUNGARIAN;
        $language['italian'] = LANGUAGE_CODE_ITALIAN;
        $language['japanese'] = LANGUAGE_CODE_JAPANESE;
        $language['korean'] = LANGUAGE_CODE_KOREAN;
        $language['korean'] = LANGUAGE_CODE_KOREAN;
        $language['polish'] = LANGUAGE_CODE_POLISH;
        $language['romanian'] = LANGUAGE_CODE_ROMANIAN;
        $language['russian'] = LANGUAGE_CODE_RUSSIAN;
        $language['slovenian'] = LANGUAGE_CODE_SLOVENIAN;
        $language['portuguese'] = LANGUAGE_CODE_PORTUGUESE;
        $language['turkish'] = LANGUAGE_CODE_TURKISH;
        return isset($language[$languageName]) ? $language[$languageName] : LANGUAGE_CODE_ENGLISH;
    }
    /* Convert Date format with given language
        $shortMonth: true if month format Jan-Dec
        $shortDays: true if date format Mon-Sun
        Example: Utility::convertDateFormatWithLanguage('2021-01-01 00:00:00', LANGUAGE_CODE_SPANISH, 'M-d-Y', true, true);
    */
    public static function convertDateFormatWithLanguage($date, $languageCode = LANGUAGE_CODE_ENGLISH, $dateFormat = 'm-d-Y', $shortMonth = false, $shortDays = false)
    {
        if (empty($date))
        {
            return "";
        }
        $convertedDate = date($dateFormat, strtotime($date));

        $englishMonthArr = self::getMonthsArrWithLanguage(LANGUAGE_CODE_ENGLISH, $shortMonth);
        $convertedMonthArr = self::getMonthsArrWithLanguage($languageCode, $shortMonth);
        foreach ($englishMonthArr as $key => $value)
        {
            $convertedDate = str_replace($value, $convertedMonthArr[$key], $convertedDate);
        }
        $englisDaysArr = self::getDaysArrWithLanguage(LANGUAGE_CODE_ENGLISH, $shortDays);
        $convertedDaysArr = self::getDaysArrWithLanguage($languageCode, $shortDays);
        foreach ($englisDaysArr as $key => $value)
        {
            $convertedDate = str_replace($value, $convertedDaysArr[$key], $convertedDate);
        }
        return $convertedDate;
    }

    public static function getMonthsArrWithLanguage($languageCode, $shortMonth = false)
    {
        $months[LANGUAGE_CODE_ENGLISH] = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $months[LANGUAGE_CODE_SPANISH] = ["enero", "febrero", "marzo", "abril", "Mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
        $months[LANGUAGE_CODE_ARABIC] = ["كانون الثاني", "شهر فبراير", "مارس", "أبريل", "قد", "يونيو", "تموز", "شهر اغسطس", "سبتمبر", "اكتوبر", "شهر نوفمبر", "ديسمبر"];
        $months[LANGUAGE_CODE_BRAZILIAN_PORTUGUESE] = ["Janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"];
        $months[LANGUAGE_CODE_CHINESE] = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];
        $months['cn'] = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];
        $months[LANGUAGE_CODE_CZECH] = ["Leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
        $months['cs'] = ["Leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
        $months[LANGUAGE_CODE_DUTCH] = ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"];
        $months[LANGUAGE_CODE_FRENCH] = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        $months[LANGUAGE_CODE_GREEK] = ["Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος", "Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος"];
        $months[LANGUAGE_CODE_GERMAN] = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
        $months[LANGUAGE_CODE_HUNGARIAN] = ["Január", "február", "március", "április", "május", "június", "július", "augusztus", "szeptember", "október", "november", "december"];
        $months[LANGUAGE_CODE_ITALIAN] = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
        $months[LANGUAGE_CODE_JAPANESE] = ["1月", "2月", "行進", "4月", "5月", "六月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months['ja'] = ["1月", "2月", "行進", "4月", "5月", "六月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months['jp'] = ["1月", "2月", "行進", "4月", "5月", "六月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months[LANGUAGE_CODE_KOREAN] = ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"];
        $months[LANGUAGE_CODE_POLISH] = ["styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień"];
        $months[LANGUAGE_CODE_ROMANIAN] = ["Ianuarie", "februarie", "martie", "aprilie", "mai", "iunie", "iulie", "august", "septembrie", "octombrie", "noiembrie", "decembrie"];
        $months[LANGUAGE_CODE_RUSSIAN] = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
        $months[LANGUAGE_CODE_SLOVENIAN] = ["Januar", "februar", "marec", "april", "maj", "junij", "julij", "avgust", "september", "oktober", "november", "december"];
        $months[LANGUAGE_CODE_PORTUGUESE] = ["Janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"];
        $months[LANGUAGE_CODE_TURKISH] = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
        $months['tu'] = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];

        $months[LANGUAGE_CODE_ENGLISH]['shortMonth'] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $months[LANGUAGE_CODE_SPANISH]['shortMonth'] = ["Ene", "Feb", "Mar", "Abr", "Mayo", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
        $months[LANGUAGE_CODE_ARABIC]['shortMonth'] = ["يناير", "فبراير", "مارس", "أبريل", "قد", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];
        $months[LANGUAGE_CODE_BRAZILIAN_PORTUGUESE]['shortMonth'] = ["Jan", "Fev", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
        $months[LANGUAGE_CODE_CHINESE]['shortMonth'] = ["简", "二月", "三月", "四月", "可能", "君", "七月", "八月", "九月", "十月", "十一月", "十二月"];
        $months['cn']['shortMonth'] = ["简", "二月", "三月", "四月", "可能", "君", "七月", "八月", "九月", "十月", "十一月", "十二月"];
        $months[LANGUAGE_CODE_CZECH]['shortMonth'] = ["Jan", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
        $months['cs']['shortMonth'] = ["Jan", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec"];
        $months[LANGUAGE_CODE_DUTCH]['shortMonth'] = ["Jan", "feb", "maart", "april", "mei", "jun", "juli", "aug", "september", "oktober", "nov", "dec"];
        $months[LANGUAGE_CODE_FRENCH]['shortMonth'] = ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"];
        $months[LANGUAGE_CODE_GREEK]['shortMonth'] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $months[LANGUAGE_CODE_GERMAN]['shortMonth'] = ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt ", "Nov", "Dez"];
        $months[LANGUAGE_CODE_HUNGARIAN]['shortMonth'] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $months[LANGUAGE_CODE_ITALIAN]['shortMonth'] = ["Gen", "Feb", "Mar", "Apr", "Maggio", "Giugno", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
        $months[LANGUAGE_CODE_JAPANESE]['shortMonth'] = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months['ja']['shortMonth'] = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months['jp']['shortMonth'] = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
        $months[LANGUAGE_CODE_KOREAN]['shortMonth'] = ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"];
        $months[LANGUAGE_CODE_POLISH]['shortMonth'] = ["sty", "lut", "mar", "kwiecień", "maj", "czer", "lip", "sierpień", "wrzesień", "październik", "listopad", "grudzień"];
        $months[LANGUAGE_CODE_ROMANIAN]['shortMonth'] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $months[LANGUAGE_CODE_RUSSIAN]['shortMonth'] = ["Янв", "Фев", "Мар", "Апр", "Май", "Июнь", "Июл", "Авг", "Сен", "Октябрь", "Ноябрь", "Дек"];
        $months[LANGUAGE_CODE_SLOVENIAN]['shortMonth'] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $months[LANGUAGE_CODE_PORTUGUESE]['shortMonth'] = ["Jan", "Fev", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
        $months[LANGUAGE_CODE_TURKISH]['shortMonth'] = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "ağustos", "Eylül", "kasım", "kasım", "Aralık"];
        $months['tu']['shortMonth'] = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "ağustos", "Eylül", "kasım", "kasım", "Aralık"];

        if ($shortMonth)
        {
            return $months[$languageCode]['shortMonth'];
        }
        return $months[$languageCode];
    }
    public static function getDaysArrWithLanguage($languageCode, $shortDays = false)
    {
        $days[LANGUAGE_CODE_ENGLISH] = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        $days[LANGUAGE_CODE_SPANISH] = ["lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo"];
        $days[LANGUAGE_CODE_ARABIC] = ["الإثنين", "يوم الثلاثاء", "الأربعاء", "يوم الخميس", "جمعة", "السبت", "يوم الأحد"];
        $days[LANGUAGE_CODE_BRAZILIAN_PORTUGUESE] = ["Segunda", "terça", "quarta", "quinta", "sexta", "sábado", "domingo"];
        $days[LANGUAGE_CODE_CHINESE] = ["周一", "周二", "周三", "周四", "星期五", "周六", "星期日"];
        $days['cn'] = ["周一", "周二", "周三", "周四", "星期五", "周六", "星期日"];
        $days[LANGUAGE_CODE_CZECH] = ["pondělí", "úterý", "středa", "Čtvrtek", "pátek", "sobota", "Neděle"];
        $days['cs'] = ["pondělí", "úterý", "středa", "Čtvrtek", "pátek", "sobota", "Neděle"];
        $days[LANGUAGE_CODE_DUTCH] = ["Maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag", "Zondag"];
        $days[LANGUAGE_CODE_FRENCH] = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
        $days[LANGUAGE_CODE_GREEK] = ["Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή", "Σάββατο", "Κυριακή"];
        $days[LANGUAGE_CODE_GERMAN] = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"];
        $days[LANGUAGE_CODE_HUNGARIAN] = ["Hétfő", "kedd", "szerda", "csütörtök", "péntek", "szombat", "vasárnap"];
        $days[LANGUAGE_CODE_ITALIAN] = ["Lunedì", "martedì", "mercoledì", "giovedì", "venerdì", "sabato", "domenica"];
        $days[LANGUAGE_CODE_JAPANESE] = ["月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日", "日曜日"];
        $days['ja'] = ["月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日", "日曜日"];
        $days['jp'] = ["月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日", "日曜日"];
        $days[LANGUAGE_CODE_KOREAN] = ["월요일", "화요일", "수요일", "목요일", "금요일", "토요일", "일요일"];
        $days[LANGUAGE_CODE_POLISH] = ["poniedziałek", "Wtorek", "Środa", "czwartek", "piątek", "sobota", "niedziela"];
        $days[LANGUAGE_CODE_ROMANIAN] = ["luni", "marţi", "miercuri", "joi", "vineri", "sâmbătă", "duminică"];
        $days[LANGUAGE_CODE_RUSSIAN] = ["понедельник", "вторник", "среда", "четверг", "Пятница", "Суббота", "Воскресенье"];
        $days[LANGUAGE_CODE_SLOVENIAN] = ["Ponedeljek", "Torek", "Sreda", "Četrtek", "Petek", "Sobota", "Nedelja"];
        $days[LANGUAGE_CODE_PORTUGUESE] = ["Segunda", "terça", "quarta", "quinta", "sexta", "sábado", "domingo"];
        $days[LANGUAGE_CODE_TURKISH] = ["Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi", "Pazar"];
        $days['tr'] = ["Pazartesi", "Salı", "Çarşamba", "Perşembe", "Cuma", "Cumartesi", "Pazar"];

        $days[LANGUAGE_CODE_ENGLISH]['shortDays'] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $days[LANGUAGE_CODE_SPANISH]['shortDays'] = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
        $days[LANGUAGE_CODE_ARABIC]['shortDays'] = ["الإثنين", "الثلاثاء", "تزوج", "خميس", "الجمعة", "جلس", "الشمس"];
        $days[LANGUAGE_CODE_BRAZILIAN_PORTUGUESE]['shortDays'] = ["Seg", "Ter", "Quarta", "Qui", "Sex", "Sáb", "Dom"];
        $days[LANGUAGE_CODE_CHINESE]['shortDays'] = ["周一", "周二", "周三", "周四", "周五", "周六", "周日"];
        $days['cn']['shortDays'] = ["周一", "周二", "周三", "周四", "周五", "周六", "周日"];
        $days[LANGUAGE_CODE_CZECH]['shortDays'] = ["Po", "Út", "St", "Čt", "Pá", "So", "Slunce"];
        $days['cs']['shortDays'] = ["Po", "Út", "St", "Čt", "Pá", "So", "Slunce"];
        $days[LANGUAGE_CODE_DUTCH]['shortDays'] = ["Ma", "Di", "Woe", "Do", "Vrij", "Zat", "Zo"];
        $days[LANGUAGE_CODE_FRENCH]['shortDays'] = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];
        $days[LANGUAGE_CODE_GREEK]['shortDays'] = ["Δευτ", "Τρί", "Τετ", "Πέμ", "Παρ", "Σαβ", "Sunλιος"];
        $days[LANGUAGE_CODE_GERMAN]['shortDays'] = ["Mon", "Die", "Mit", "Don", "Fre", "Sam", "Son"];
        $days[LANGUAGE_CODE_HUNGARIAN]['shortDays'] = ["H", "K", "Sze", "Cs", "P", "Szo", "V"];
        $days[LANGUAGE_CODE_ITALIAN]['shortDays'] = ["Lun", "Mar", "Mer", "Gio", "Ven", "Sab", "Dom"];
        $days[LANGUAGE_CODE_JAPANESE]['shortDays'] = ["月曜日", "火曜日", "結婚した", "木", "金", "土", "太陽"];
        $days['ja']['shortDays'] = ["月曜日", "火曜日", "結婚した", "木", "金", "土", "太陽"];
        $days['jp']['shortDays'] = ["月曜日", "火曜日", "結婚した", "木", "金", "土", "太陽"];
        $days[LANGUAGE_CODE_KOREAN]['shortDays'] = ["월", "화", "수", "목", "금", "토", "일"];
        $days[LANGUAGE_CODE_POLISH]['shortDays'] = ["Pon", "Wt", "Śr", "Cz", "Pt", "Sob", "Nd"];
        $days[LANGUAGE_CODE_ROMANIAN]['shortDays'] = ["Luni", "Marți", "Miercuri", "Joi", "Vineri", "Sâmbătă", "Duminică"];
        $days[LANGUAGE_CODE_RUSSIAN]['shortDays'] = ["Пн", "Вт", "Мы б", "Чт", "Пт", "Суббота", "солнце"];
        $days[LANGUAGE_CODE_SLOVENIAN]['shortDays'] = ["Pon", "torek", "sreda", "čet", "pet", "sobota", "ned"];
        $days[LANGUAGE_CODE_PORTUGUESE]['shortDays'] = ["Seg", "Ter", "Quarta", "Qui", "Sex", "Sáb", "Dom"];
        $days[LANGUAGE_CODE_TURKISH]['shortDays'] = ["Pzt", "sal", "evlenmek", "Per", "Cuma", "Oturdu", "Güneş"];
        $days['tr']['shortDays'] = ["Pzt", "sal", "evlenmek", "Per", "Cuma", "Oturdu", "Güneş"];
        if ($shortDays)
        {
            return $days[$languageCode]['shortDays'];
        }
        return $days[$languageCode];
    }

    public static function getFormatedErpFromArray($erpArr = array(), $isPartial = 0)
    {
        if (empty($erpArr))
        {
            return "";
        }
        if (count($erpArr) > 1)
        {
            $strGetClientIdentifiers = implode(", ", $erpArr);
        }
        else
        {
            $strGetClientIdentifiers = isset($erpArr[0]) ? $erpArr[0] : '';
        }
        if ($isPartial == 1)
        {
            $erp = substr($strGetClientIdentifiers, 0, 100);
            if (strlen($strGetClientIdentifiers) > 100)
            {
                $erp .= '...';
            }
            return $erp;
        }
        return $strGetClientIdentifiers;
    }

    public static function getEmailSubjectByLanguageCode($emailType = self::EMAIL_TYPE_WELCOME, $languageCode = LANGUAGE_CODE_ENGLISH, $thirdPartyName = '')
    {
        $languageCode = self::getLanguageCodeByNameReturnSameIfNotFound($languageCode);

        if ($languageCode == 'jp' || $languageCode == 'ja')
        {
            $languageCode = LANGUAGE_CODE_JAPANESE;
        }
        if ($languageCode == 'tr')
        {
            $languageCode = LANGUAGE_CODE_TURKISH;
        }
        if ($languageCode == 'cn')
        {
            $languageCode = LANGUAGE_CODE_CHINESE;
        }
        $subject[self::EMAIL_TYPE_WELCOME] = array(
            LANGUAGE_CODE_ENGLISH => '[Action Required]: Please Complete Novartis Due Diligence Requirements ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_SPANISH => '[Acción requerida]: Complete los Requisitos de Diligencia Debida de Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_CHINESE =>  '[需要采取行动]：请完成 Novartis 尽职调查要求  ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            'zh' =>  '[需要采取行动]：请完成 Novartis 尽职调查要求  ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            'cn' =>  '[需要采取行动]：请完成 Novartis 尽职调查要求  ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_ARABIC =>  'إجراء مطلوب:يرجى إكمال متطلبات فحوصات التقييم والتقصي لمكافحة الرشوة بشركة  ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : '') . ' (Novartis)',
            LANGUAGE_CODE_FRENCH =>  '[Action requise] : Veuillez remplir les exigences de Novartis en matière de diligence raisonnable ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_GREEK =>  '[Απαιτούμενη ενέργεια]: Παρακαλείσθε να συμπληρώσετε τις απαιτήσεις ειδικού ελεγχου της Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_JAPANESE =>  '[アクションが必要です]:ノバルティス社のデューデリジェンス要件をご記入ください ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            'jp' =>  '[アクションが必要です]:ノバルティス社のデューデリジェンス要件をご記入ください ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            'ja' =>  '[アクションが必要です]:ノバルティス社のデューデリジェンス要件をご記入ください ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_KOREAN =>  '[조치 필요]: 노바티스 실사 요구 사항을 완료해주세요 ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_PORTUGUESE =>  '[Ação Requerida]: Complete os requisitos de diligência solicitados pela Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_POLISH =>  '[Wymagane działanie]: Wypełnij wymagania Novartis dotyczące należytej staranności ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_ROMANIAN =>  '[Acțiune necesară]: Completați cerințele de due diligence solicitate de Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_RUSSIAN =>  '[Требуется действие]: Пожалуйста, выполните требования компании "Новартис" к должной осмотрительности ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_DUTCH =>  '[Action Required]: Voltooi de Novartis Due Diligence-vereisten ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_CZECH =>  '[Je vyžadována akce]: Vyplňte požadavky na náležitou péči společnosti Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_CZECH_CS =>  '[Je vyžadována akce]: Vyplňte požadavky na náležitou péči společnosti Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_HUNGARIAN =>  '[Teendő szükséges]: Töltse ki a Novartis átvilágítási követelményeket ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_SLOVENIAN =>  '[Zahtevano dejanje]: Izpolnite Novartisove zahteve skrbnega pregleda poslovanja ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_TURKISH =>  '[İşlem Gerekli]: Lütfen Novartis Due Diligence Gereksinimlerini Tamamlayın ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            'tr' =>  '[İşlem Gerekli]: Lütfen Novartis Due Diligence Gereksinimlerini Tamamlayın ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_GERMAN =>  '[Action Required]: Bitte vervollständigen Sie die Novartis Due Diligence Anforderungen ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : ''),
            LANGUAGE_CODE_ITALIAN =>  '[Azione richiesta]: completare i requisiti di due diligence Novartis ' . (isset($thirdPartyName) ? '(' . $thirdPartyName . ')' : '')
        );
        return $subject[$emailType][$languageCode];
    }

    public static function getLanguageNameByCode($languageCode)
    {
        $languageCode = strtolower($languageCode);
        $language[LANGUAGE_CODE_ENGLISH] = 'English';
        $language[LANGUAGE_CODE_SPANISH] = 'Spanish';
        $language[LANGUAGE_CODE_ARABIC] = 'Arabic';
        $language[LANGUAGE_CODE_BRAZILIAN_PORTUGUESE] = 'Portuguese';
        $language[LANGUAGE_CODE_CHINESE] = 'Chinese Simplified';
        $language[LANGUAGE_CODE_CZECH] = 'Czech';
        $language[LANGUAGE_CODE_DUTCH] = 'Dutch';
        $language[LANGUAGE_CODE_FRENCH] = 'French';
        $language[LANGUAGE_CODE_GREEK] = 'Greek';
        $language[LANGUAGE_CODE_GERMAN] = 'German';
        $language[LANGUAGE_CODE_HUNGARIAN] = 'Hungarian';
        $language[LANGUAGE_CODE_ITALIAN] = 'Italian';
        $language[LANGUAGE_CODE_JAPANESE] = 'Japanese';
        $language[LANGUAGE_CODE_KOREAN] = 'Korean';
        $language[LANGUAGE_CODE_POLISH] = 'Polish';
        $language[LANGUAGE_CODE_ROMANIAN] = 'Romanian';
        $language[LANGUAGE_CODE_RUSSIAN] = 'Russian';
        $language[LANGUAGE_CODE_SLOVENIAN] = 'Slovenian';
        $language[LANGUAGE_CODE_PORTUGUESE] = 'Portuguese';
        $language[LANGUAGE_CODE_TURKISH] = 'Turkish';
        return isset($language[$languageCode]) ? $language[$languageCode] : $languageCode;
        // $languageRow = EmailLanguages::select('languages')->where('language_code',$languageCode)->first();
        // if(isset($languageRow->languages)){
        //     return $languageRow->languages;
        // }
        // return "";
    }

    
    

    /**
     * 
     */

    public static function updateEmailForThirdPartyContact(string $vid, string $member_id, string $email, string $existingEmail, string $contactName): string
    {
        if (!empty($vid) && !empty($email))
        {
            $thirdPartyContact = \App\Model\Eloquent\ThirdPartyContact::where(['third_party_id' => $vid])->first();

            $existingEmail = strtolower($existingEmail);


            if (isset($thirdPartyContact->ibf_member_id) && !empty($thirdPartyContact->ibf_member_id))
            {
                $ibfMember = \App\Model\Eloquent\IbfMember::where('member_id', $thirdPartyContact->ibf_member_id)->where('member_group_id', 16)->first();
                if (isset($ibfMember) && !empty($ibfMember))
                {
                    if (isset($thirdPartyContact->ibf_member_id) && !empty($thirdPartyContact->ibf_member_id))
                    {
                        if (isset($ibfMember->registration_status) &&  $ibfMember->registration_status == 1)
                        {
                            $thirdPartyContact->firstname = '';
                            $thirdPartyContact->lastname = '';
                        }
                        $thirdPartyContact->email_address = $email;
                        $thirdPartyContact->save();
                    }

                    if (isset($ibfMember->registration_status) && $ibfMember->registration_status == 1)
                    {
                        $ibfMember->firstname = '';
                        $ibfMember->lastname = '';
                        $ibfMember->name = $contactName;
                        $ibfMember->registration_status = 0;
                    }
                    $ibfMember->email = $email;
                    $ibfMember->save();
                }
            }
            else if (empty($thirdPartyContact))
            {
                $ibfMember = \App\Model\Eloquent\IbfMember::whereRaw("LOWER(email) = '$existingEmail'", [])->where('member_group_id', 16)->first();
                if (isset($ibfMember->member_id) && !empty($ibfMember))
                {
                    if ($ibfMember->registration_status == 1)
                    {
                        $ibfMember->firstname = '';
                        $ibfMember->lastname = '';
                        $ibfMember->name = $contactName;
                        $ibfMember->registration_status = 0;
                    }
                    $ibfMember->email = $email;
                    $ibfMember->save();
                    $memberId  =  $ibfMember->member_id;

                    // else
                    // {
                    //     $memberData = [];
                    //     $memberData['firstname'] = '';
                    //     $memberData['lastname'] = '';
                    //     $memberData['name'] = '';
                    //     $memberData['registration_status'] = 0;
                    //     $memberData['member_group_id'] = 16;
                    //     $memberData['email'] = $email;
                    //     $memberId  = \App\Model\Eloquent\IbfMember::create($memberData)->member_id ;

                    // }

                    $thirdPartyContactData = [];
                    $thirdPartyContactData['firstname'] = '';
                    $thirdPartyContactData['third_party_id'] = $vid;
                    $thirdPartyContactData['lastname'] = '';
                    $thirdPartyContactData['email_address'] = $email;
                    $thirdPartyContactData['ibf_member_id'] = $memberId;

                    \App\Model\Eloquent\ThirdPartyContact::create($thirdPartyContactData);
                }
            }

            return true;
        }
        return false;
    }

    public static function getLanguageCodeByNameReturnSameIfNotFound($languageName = 'english')
    {
        $languageName = (isset($languageName) && !empty($languageName))?strtolower($languageName):'english'; 
        $language['english'] = LANGUAGE_CODE_ENGLISH;
        $language['spanish'] = LANGUAGE_CODE_SPANISH;
        $language['arabic'] = LANGUAGE_CODE_ARABIC;
        $language['portuguese'] = LANGUAGE_CODE_BRAZILIAN_PORTUGUESE;
        $language['chinese'] = LANGUAGE_CODE_CHINESE;
        $language['chinese simplified'] = LANGUAGE_CODE_CHINESE;
        $language['czech'] = LANGUAGE_CODE_CZECH;
        $language['dutch'] = LANGUAGE_CODE_DUTCH;
        $language['french'] = LANGUAGE_CODE_FRENCH;
        $language['greek'] = LANGUAGE_CODE_GREEK;
        $language['germen'] = LANGUAGE_CODE_GERMAN;
        $language['german'] = LANGUAGE_CODE_GERMAN;
        $language['hungarian'] = LANGUAGE_CODE_HUNGARIAN;
        $language['italian'] = LANGUAGE_CODE_ITALIAN;
        $language['japanese'] = LANGUAGE_CODE_JAPANESE;
        $language['korean'] = LANGUAGE_CODE_KOREAN;
        $language['polish'] = LANGUAGE_CODE_POLISH;
        $language['romanian'] = LANGUAGE_CODE_ROMANIAN;
        $language['russian'] = LANGUAGE_CODE_RUSSIAN;
        $language['slovenian'] = LANGUAGE_CODE_SLOVENIAN;
        $language['portuguese'] = LANGUAGE_CODE_PORTUGUESE;
        $language['turkish'] = LANGUAGE_CODE_TURKISH;
        return isset($language[$languageName]) ? $language[$languageName] : $languageName;
    }
    
    static function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    
    public static function filterArrayToRemoveAllEmptyOrNullKeysAndValues(array $array = [])
    {
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $value = self::filterArrayToRemoveAllEmptyOrNullKeysAndValues($value);
                if (!isset($value) || is_null($value) || (isset ($value) && empty ($value)))
                {
                    unset($array[$key]);
                }
            }
            else if (!isset($value) || is_null($value) || (isset ($value) && empty ($value)))
            {
                unset($array[$key]);
            }
        }
        return array_values($array);
    }
}
