<?php namespace DeftCMS\Components\b1tc0re\Counters;


use DeftCMS\Engine;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Класс для инициализации статистики
 *
 * @package	    DeftCMS
 * @category	Component
 * @author	    b1tc0re
 * @copyright   (c) 2018-2021, DeftCMS (http://deftcms.ru/)
 * @since	    Version 0.0.9a
 */
class Counter
{
    /**
     * Instance
     * @var Counter
     */
    private static $instance;

    /**
     * Counters config
     * @var array
     */
    private $config = [];

    /**
     * Counter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Initialize counter system
     * @param array $config
     * @return Counter
     */
    public static function create(array $config)
    {
        NULL === self::$instance && self::$instance = new static($config);
        return self::$instance;
    }

    /**
     *
     */
    public function initialize()
    {
        $counters = FALSE;

        if( array_key_exists('yandex', $this->config) && $this->config['yandex']['enabled'] === true )
        {
            $counters .= $this->initializeYandexMetrika($this->config['yandex']);
        }

        if( array_key_exists('google', $this->config) && $this->config['google']['enabled'] === true )
        {
            $counters .= $this->initializeGoogleAnalytics($this->config['google']);
        }

        $counters !== FALSE && $this->injectOutput($counters);
    }

    /**
     * Initialize bem counter yandex metrika
     * @param array $config
     * @return string
     */
    protected function initializeYandexMetrika(array $config)
    {
        $params = json_encode($config);
        return "<i class='counter__metrika i-bem' data-bem='{\"counter__metrika\": ". $params ."}'>
                        <noscript><img alt='' src='//mc.yandex.ru/watch/". $config['id'] ."' /></noscript>
                    </i>";
    }

    /**
     * Initialize bem counter GoogleAnalytics
     * @param array $config
     * @return string
     */
    protected function initializeGoogleAnalytics(array $config)
    {
        $params = json_encode($config);
        return "<i class='counter__google i-bem' data-bem='{\"counter__google\": ". $params ."}'></i>";
    }

    /**
     * Inject to output countres
     * @param string $counters
     */
    protected function injectOutput($counters)
    {
        $counters = sprintf('<i class="counter">%s</i>', $counters);

        Engine::$DT->template->registerCallbackOutput( function($output) use ($counters) {
            // Inject into <body>
            $output = preg_replace('/(<\/body>)/',
                $counters . '$0',
                $output);

            return $output;
        });
    }

}