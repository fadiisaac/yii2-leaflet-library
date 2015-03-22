<?php
/**
 * @copyright Copyright (c) 2015 BranchenGuru GmbH
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers\tiles;

use dosamigos\leaflet\layers\TileLayer;

/**
 * An Open Street Map tile layer
 *
 * @author Albert Krewinkel <albert+php@branchen.guru>
 * @package dosamigos\leaflet\layers\tiles
 */
class OpenStreetMap extends TileLayer
{
    /**
     * @inheritdoc
     */
    public $urlTemplate = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';

    /**
     * @inheritdoc
     */
    protected static function getDefaultClientOptions()
    {
        $ccBySaLink = '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>';
        $osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
        return [
            'attribution' => "&copy; $osmLink contributors, $ccBySaLink",
        ];
    }
}