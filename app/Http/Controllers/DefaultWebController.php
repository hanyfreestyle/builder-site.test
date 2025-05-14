<?php

namespace App\Http\Controllers;

use App\Helpers\Admin\Minifier\MinifyTools;
use App\Traits\Web\LoadWebSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class DefaultWebController extends Controller {
    use LoadWebSettings;

    public bool $is_cash = true;
    public mixed $webConfig;
    protected mixed $minifyTools;
    protected bool $cssReBuild;
    protected string $cssMinifyType;
    protected bool $httpsSecure;
    protected array $pageView;
    protected mixed $defPhotoList;
    protected mixed $agent;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function __construct() {
        $sharedData = [
            'minifyTools' => new MinifyTools(),
            'agent' => new Agent(),
            'cssMinifyType' => 'Seo', // Web , WebMini , Seo
            'cssReBuild' => true,
            'httpsSecure' => true,
            'webConfig' => self::getWebSettingsCash($this->is_cash),
            'defPhotoList' => self::getDefPhotoCash($this->is_cash),
        ];
        foreach ($sharedData as $key => $value) {
            $this->$key = $value;
            View::share($key, $value);
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function UnderConstruction() {
        $config = $this->webConfig;
        if ($config->web_status_date == null || strtotime($config->web_status_date) <= strtotime('today')) {
            $newDate = now()->addDays(3); // تاريخ بعد 3 أيام
            $config->web_status_date = $newDate;
            $config->save();
            Cache::forget('WebSettings_CashList_ar');
            Cache::forget('WebSettings_CashList_en');
        }
        $formattedDate = Carbon::parse($config->web_status_date)->format('Y/m/d');

        if ($config->web_status == 1 or Auth::check()) {
            return redirect()->route('web.index');
        }
        $meta = self::getMeatByCatId('home');
        self::printSeoMeta($meta, 'web.index');
        return view('under', compact('formattedDate'));
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function abortError404($from) {
        $Meta = self::getMeatByCatId('err_404');
        self::printSeoMeta($Meta, null, null, array('ErrorPage' => true));
        $pageView = [
            'SelMenu' => '',
            'show_fix' => true,
            'slug' => null,
            'go_home' => route('web.index'),
        ];
        View::share('pageView', $pageView);
        abort(404);
    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function printSeoMeta($row, $route = null, $defPhoto = "logo", $sendArr = array()) {
        $lang = thisCurrentLocale();
//        $type = AdminHelper::arrIsset($sendArr, 'type', 'website');
//        $ErrorPage = AdminHelper::arrIsset($sendArr, 'ErrorPage', false);
        if (isset($row->photo)) {
            $defImage = $row->photo;
        } else {
            $GetdefImage = self::getDefPhotoById($defPhoto);
            $defImage = optional($GetdefImage)->photo;
        }

        if ($defImage) {
            $defImage = defImagesDir($defImage);
        }


        $TitleInfo = self::TitleInfo($row, $route, $sendArr);

        $setTitle = $TitleInfo['Title'];
        $setDescription = $TitleInfo['Description'];

        SEOMeta::setTitle($TitleInfo['Title']);
        SEOMeta::setDescription($TitleInfo['Description']);

//        self::Urlinfo($row, $route);
        OpenGraph::setTitle($setTitle);
        OpenGraph::setDescription($setDescription);
        OpenGraph::addProperty('type', "website");
        OpenGraph::setUrl(url()->current());
        OpenGraph::addImage($defImage);
        OpenGraph::setSiteName($this->webConfig->name);

        TwitterCard::setTitle($setTitle);
        TwitterCard::setDescription($setDescription);
        TwitterCard::setUrl(url()->current());
        TwitterCard::setImage($defImage);
        TwitterCard::setImage($defImage);
        TwitterCard::setType('summary_large_image');

//        if ($ErrorPage != true) {

//        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function TitleInfo($row, $route, $sendArr): array {
        $setTitle = ($row->g_title ?? $row->name ?? '');
        $setDescription = ($row->g_des ?? $row->name ?? '');
        $rep1 = array("[WebSiteName]");
        $rep2 = array($this->webConfig->name);
        $setTitle = str_replace($rep1, $rep2, $setTitle);
        $setDescription = str_replace($rep1, $rep2, $setDescription);

        return [
            'Title' => $setTitle,
            'Description' => $setDescription,
        ];
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function Urlinfo($row, $route) {
        $lang = thisCurrentLocale();
        $alternatUrl = null;
        $pages = null;

        if ($lang == 'en') {
            $alternateLang = 'ar';
        } elseif ($lang == 'ar') {
            $alternateLang = 'en';
        }

        if (isset($_GET['page'])) {
            $pages = "page=" . $_GET['page'];
        }

        switch ($route) {
            case 'web.index':
                $Url = urldecode(LaravelLocalization::getLocalizedURL($lang, route('web.index')));
                $alternatUrl = urldecode(LaravelLocalization::getLocalizedURL($alternateLang, route('web.index')));
                break;

            default:
//                $Url = urldecode(LaravelLocalization::getLocalizedURL($lang, route($route, $pages)));
//                $alternatUrl = urldecode(LaravelLocalization::getLocalizedURL($alternateLang, route($route, $pages)));
                $Url = null;
                $alternatUrl = null;
                break;

        }

        if ($route != null) {
            SEOMeta::addAlternateLanguage($lang, $Url);
            if ($alternatUrl != null and count(config('app.web_lang')) > 1) {
                SEOMeta::addAlternateLanguage($alternateLang, $alternatUrl);
            }
            SEOMeta::setCanonical($Url);
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function getDefPhotoById($cat_id) {
        $defPhoto = self::getDefPhotoCash();
        if ($defPhoto->has($cat_id)) {
            return $defPhoto[$cat_id];
        } else {
            return $defPhoto['logo'] ?? '';
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    static function getMeatByCatId($cat_id) {
        $metaTags = self::getMetaTagCash();
        if ($metaTags->has($cat_id)) {
            return $metaTags[$cat_id];
        } else {
            return $metaTags['home'] ?? '';
        }
    }
}
