<?php


namespace dosamigos\leaflet\layers;


use yii\base\InvalidConfigException;
use yii\web\JsExpression;


class WMSLayer extends Layer
{

    public $urlTemplate;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->urlTemplate)) {
            throw new InvalidConfigException("'urlTemplate' cannot be empty.");
        }
    }

    /**
     * @return \yii\web\JsExpression the marker constructor string
     */
    public function encode()
    {
        $options = $this->getOptions();
        $name = $this->name;
        $map = $this->map;
        $js = "L.tileLayer.wms('$this->urlTemplate', $options)" . ($map !== null ? ".addTo($map);" : "");
        if (!empty($name)) {
            $js = "var $name = $js" . ($map !== null ? "" : ";");
            $js .= $this->getEvents();
        }

        return new JsExpression($js);
    }
}
