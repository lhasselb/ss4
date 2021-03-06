<?php

namespace Site\Templates;

use SilverStripe\Control\Controller;
use SilverStripe\View\TemplateGlobalProvider;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\Director;
use SilverStripe\Core\Path;
use SilverStripe\Core\Manifest\ManifestFileFinder;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class DeferedRequirements implements TemplateGlobalProvider
{
    private static $css = [];
    private static $js = [];
    private static $defered = false;
    private static $static_domain;
    private static $version;
    private static $nojquery = false;
    private static $nojqueryeasing = false;
    private static $nofontawesome = false;
    private static $noanimatecss = false;
    private static $novelocity = false;
    private static $nocookiesjs = false;
    private static $custom_requirements = [];

    /**
     * Attention CSS and JS need to be placed within css and js folder
     * like app/client/dist/js or app/client/dist/css
     * @return array
     */
    public static function get_template_global_variables()
    {
        return [
            'AutoRequirements' => 'Auto',
            'DeferedCSS' => 'loadCSS',
            'DeferedJS' => 'loadJS',
        ];
    }

    public static function Auto($class = false)
    {
        $config = Config::inst()->get(self::class);

        // Initialization
        Requirements::block(THIRDPARTY_DIR.'/jquery/jquery.js');
        /*if (defined('FONT_AWESOME_DIR')) {
            Requirements::block(FONT_AWESOME_DIR.'/css/lib/font-awesome.min.css');
        }*/
        Requirements::set_force_js_to_bottom(true);

        // Main libs
        if (!$config['nojquery']) {
            DeferedRequirements::loadJS('//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
        }
        // App libs
        if (!$config['nojqueryeasing']) {
            DeferedRequirements::loadJS('//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js');
        }
        if (!$config['nocookiesjs']) {
            DeferedRequirements::loadJS('//cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js');
        }

        if (!$config['novelocity']) {
            DeferedRequirements::loadJS('//cdn.jsdelivr.net/npm/velocity-animate@2.0/velocity.min.js');
            DeferedRequirements::loadJS('//cdn.jsdelivr.net/npm/velocity-animate@2.0/velocity.ui.min.js');
        }

        if (!$config['nofontawesome']) {
            DeferedRequirements::loadCSS('//use.fontawesome.com/releases/v5.6.3/css/all.css');
        }
        if (!$config['noanimatecss']) {
            DeferedRequirements::loadCSS('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css');
        }

        DeferedRequirements::loadCSS('app.css');
        DeferedRequirements::loadJS('app.js');

        // Class libs
        $class = get_class(Controller::curr());

        // Load configured custom assets (if any)
        if (isset($config['custom_requirements'][$class])) {
            Injector::inst()->get(LoggerInterface::class)
            ->debug('DeferedRequirements - custom_requirements for ' . $class);
            foreach ($config['custom_requirements'][$class] as $file) {
                Injector::inst()->get(LoggerInterface::class)->debug($file);
                if (strpos($file, '.css')) {
                    DeferedRequirements::loadCSS($file);
                }
                if (strpos($file, '.js')) {
                    DeferedRequirements::loadJS($file);
                }
            }
        }

        // Load class name based assets (if any)
        $class = str_replace('\\', '.', $class);
        $dir = Path::join(
            Director::publicFolder(),
            ManifestFileFinder::RESOURCES_DIR,
            'app',
            'client',
            'dist'
        );

        if (file_exists(Path::join($dir, 'css', $class . '.css'))) {
            //Injector::inst()->get(LoggerInterface::class)
            //->debug('DeferedRequirements add ' . Path::join($dir, 'css', $class . '.css'));
            DeferedRequirements::loadCSS($class . '.css');
        }

        if (file_exists(Path::join($dir, 'js', $class . '.js'))) {
            //Injector::inst()->get(LoggerInterface::class)
            //->debug('DeferedRequirements add ' . Path::join($dir, 'js', $class . '.js'));
            DeferedRequirements::loadJS($class . '.js');
        }

        return self::forTemplate();
    }

    public static function loadCSS($css)
    {
        //Injector::inst()->get(LoggerInterface::class)->debug('DeferedRequirements - loadCSS(' . $css . ')');
        if (self::$defered && !self::_webpackActive()) {
            self::$css[] = $css;
        } else {
            WebpackTemplateProvider::loadCSS($css);
        }
    }

    public static function loadJS($js)
    {
        //Injector::inst()->get(LoggerInterface::class)->debug('DeferedRequirements - loadJS(' . $js .  ')');
        if (self::$defered && !self::_webpackActive()) {
            self::$js[] = $js;
        } else {
            WebpackTemplateProvider::loadJS($js);
        }
    }

    protected static function _webpackActive()
    {
        return class_exists('WebpackTemplateProvider') && WebpackTemplateProvider::isActive();
    }

    public static function setDefered($bool)
    {
        self::$defered = $bool;
    }

    public static function forTemplate()
    {
        if (!self::$defered || self::_webpackActive()) {
            return false;
        }

        $result = '';
        foreach (self::$css as $css) {
            $result .= '<i class="defer-cs" data-load="' . self::get_url($css) . '"></i>';
        }
        foreach (self::$js as $js) {
            $result .= '<i class="defer-sc" data-load="' . self::get_url($js) . '"></i>';
        }

        $result .=
            '<script type="text/javascript">function lsc(a,b){var c=document.createElement("script");c.type="text/javascript",c.readyState'
            .'?c.onreadystatechange=function(){"loaded"!=c.readyState&&"complete"!=c.readyState||(c.onreadystatechange=null,b())}'
            .':c.onload=function(){b()},c.src=a,document.getElementsByTagName("body")[0].appendChild(c)}'
            .'function lscd(a){a<s.length-1&&(a++,lsc(s.item(a).getAttribute("data-load"),function(){lscd(a)}))}'
            .'for(var s=document.getElementsByClassName("defer-cs"),i=0;i<s.length;i++){var b=document.createElement("link");b.rel="stylesheet",'
            .'b.type="text/css",b.href=s.item(i).getAttribute("data-load"),b.media="all";var c=document.getElementsByTagName("body")[0];'
            .'c.appendChild(b)}var s=document.getElementsByClassName("defer-sc"),i=0;lsc(s.item(i).getAttribute("data-load"),function(){lscd(i)});'
            .'</script>';

        return $result;
    }

    private static function get_url($url)
    {
        $config = Config::inst()->get(self::class);

        // external URL
        if (strpos($url, '//') !== false) {
            return $url;
        }

        $version = $config['version'];
        $version = $version
            ? strpos($url, '?') // inner URL
                ? '&'.$version // add param
                : '?'.$version // new param
            : ''; // no version defined

        $static_domain = $config['static_domain'];
        $static_domain = $static_domain ? $static_domain : '';

        return $url.$version;
    }
}
