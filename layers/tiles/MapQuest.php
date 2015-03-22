<?php
/**
 * @copyright Copyright (c) 2015 BranchenGuru GmbH, 2amigOS! Consulting Group LLC
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace dosamigos\leaflet\layers\tiles;

use dosamigos\leaflet\layers\TileLayer;

/**
 * An MapQuest tile layer
 *
 * @author Albert Krewinkel <albert+php@branchen.guru>
 * @link http://developer.mapquest.com
 * @package dosamigos\leaflet\layers\tiles
 */
class MapQuest extends TileLayer
{
    /**
     * @inheritdoc
     */
    public $urlTemplate = 'http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg';

    /**
     * @inheritdoc
     */
    protected static function getDefaultClientOptions()
    {
        $mapQuest = '<a href="http://www.mapquest.com/" target="_blank">MapQuest</a> ' .
            '<img src="http://developer.mapquest.com/content/osm/mq_logo.png">';
        $osmLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
        $ccBySaLink = '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>';
        return [
            'attribution' => "&copy; {$mapQuest}, Map data &copy; {$osmLink} contributors, {$ccBySaLink}",
            'subdomains' => '1234',
        ];
    }
}