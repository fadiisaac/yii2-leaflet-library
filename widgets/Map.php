<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\widgets;

use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\LeafLetAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Widget Map renders the map using the LeafLet component configurations for rendering on the view.
 * *Important* It is very important to specify the height of the widget, whether with a class name or through an inline
 * style. Failing to configure the height may have unexpected rendering results.
 *
 * @package dosamigos\leaflet\widgets
 */
class Map extends Widget
{
    /**
     * @var \dosamigos\leaflet\LeafLet component holding all configuration
     */
    public $leafLet;
    /**
     * @var int the height of the map, in px. Failing to configure the height
     * of the map, will result in unexpected results.
     */
    public $height = 200;
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * Initializes the widget.
     * This method will register the leaflet asset bundle. If you override
     * this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        self::checkLeafLetValidity();
        self::ensureMapId();
        self::setMapHeight();
    }

    /**
     * Check validity of the leaflet property.
     */
    private function checkLeafLetValidity()
    {
        if (empty($this->leafLet) || !($this->leafLet instanceof LeafLet)) {
            $msg ="'leafLet' attribute cannot be empty and should be of type LeafLet component.";
            throw new InvalidConfigException($msg);
        }
    }

    /**
     * Ensure that the map has an ID.
     */
    private function ensureMapId()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Set the height of the generated map.
     */
    private function setMapHeight()
    {
        $inlineStyles = ArrayHelper::getValue($this->options, 'style');
        if ($inlineStyles) {
            $styles = explode(';', $inlineStyles);
            $styles[] = "height:{$this->height}px";
            $this->options['style'] = implode(";", $styles);
        } else {
            $this->options['style'] = "height:{$this->height}px;";
        }
    }

    /**
     * Renders the map
     * @return string|void
     */
    public function run()
    {
        echo "\n" . Html::tag('div', '', $this->options);
        $this->registerScript();
    }

    /**
     * Register the script for the map to be rendered according to the configurations on the LeafLet
     * component.
     */
    public function registerScript()
    {
        $clientOptions = $this->leafLet->clientOptions;
        if ($clientOptions === false) {
            return;
        }

        $view = $this->getView();

        LeafLetAsset::register($view);
        $this->leafLet->getPlugins()->registerAssetBundles($view);

        $id = $this->options['id'];
        $name = $this->leafLet->name;
        $js = $this->leafLet->getJs();

        $options = empty($clientOptions) ? '{}' : Json::encode($clientOptions);
        array_unshift($js, "var $name = L.map('$id', $options);");
        if ($this->leafLet->getTileLayer() !== null) {
            $js[] = $this->leafLet->getTileLayer()->encode();
        }
        $clientEvents = $this->leafLet->clientEvents;

        if (!empty($clientEvents)) {
            foreach ($clientEvents as $event => $handler) {
                $js[] = "$name.on('$event', $handler);";
            }
        }
        $jsString = (YII_DEBUG ? "/* Init map {$name} */\n" : '') .
            "(function () {\n". implode("\n", $js) ."\n})();";
        $view->registerJs($jsString);
    }
} 