<?php

namespace Jimev\Pages;

use \Page;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
//use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\Requirements;
/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use Jimev\Forms\HTMLEditor\HTMLEditorFieldLocation;

/**
 * LocationPage
 *
 * @package Jimev
 * @subpackage Pages
 * @author Lars Hasselbach <lars.hasselbach@gmail.com>
 * @since 15.03.2016
 * @copyright 2016 [sybeha]
 * @license see license file in modules root directory
 */
class LocationPage extends Page
{
    private static $singular_name = 'Treffpunkt';
    private static $description = 'Seite für einen Treffpunkt in den Jongliertreffen';
    //private static $icon = 'mysite/images/treffen.png';
    private static $can_be_root = false;
    private static $allowed_children = 'none';

    private static $db = [
       'Schedule' => 'HTMLVarchar()',        // Wann
       'Location' => 'HTMLVarchar()',        // Wo
       'Contact' => 'HTMLVarchar()',         // Ansprechpartner
       'Remark' => 'HTMLText()',             // Bemerkung (für die Uebersicht)
       'LocationDescription' => 'Varchar()', // Beschreibung
       'Map' => 'Text()'                     // Karte
    ];

    /*
     * Important: Please note: It is strongly recommended to define a table_name for all namespaced models.
     * Not defining a table_name may cause generated table names to be too long
     * and may not be supported by your current database engine.
     * The generated naming scheme will also change when upgrading to SilverStripe 5.0 and potentially break.
     *
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'LocationPage';

    private static $casting = [
        'ExistingGoogleMap' => 'HTMLText'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'Content');

        //Replace default HtmlEditorField with HtmlEditorFieldLocation
        $remark = HtmlEditorFieldLocation::create('Remark', 'Bemerkung für die Übersicht')->setRows(5);
        $intro = HtmlEditorFieldLocation::create('Content', 'Informationen')->setRows(5);
        $schedule = HtmlEditorFieldLocation::create('Schedule', 'Wann')->setRows(5);
        $location = HtmlEditorFieldLocation::create('Location', 'Wo')->setRows(5);
        $contact = HtmlEditorFieldLocation::create('Contact', 'Ansprechpartner')->setRows(5);

        //$tabset = TabSet::create();
        $tabSet = new TabSet(
            $name = 'Location',
            new Tab($title = 'Übersicht', $remark),
            new Tab($title = 'Informationen', $intro),
            new Tab($title = 'Wann', $schedule),
            new Tab($title = 'Wo', $location),
            new Tab($title = 'Kontakt', $contact)
        );

        $fields->addFieldsToTab('Root.Main', [$tabSet], 'Metadata');
        //$fields->addFieldsToTab('Root.Main', [$remark, $intro, $schedule, $location, $contact], 'Metadata');

        // Info
        $fields->addFieldToTab('Root.Landkarte', new LiteralField(
            'Info',
            '<p><span style="color:red;">Achtung: </span>
            Den Inhalt ür dieses Feld setzt man per HTML-Tag iframe, <br/>
            z.B. &lt;iframe src="https://mapsengine.google.com/map/embed?mid=zc1l0sHie8lY.kjVxveFjAD0Q"
                width="100%" height="550" &gt;&lt;/iframe&gt;.
            <br/>Die Breite (width) immer auf 100% setzen und die Höhe (height) auf 550.<br/>
            Für neue Karten (src="https://LINK_ZU_GOOGLE") kann man den <a href="https://www.google.de/mapmaker"
            target="_blank">Mapmaker</a> nutzen.</p>'
        ));
        // Description
        $description = new TextField('LocationDescription', 'Beschreibung');
        $fields->addFieldToTab('Root.Landkarte', $description);
        // Map
        $map = TextAreaField::create('Map', 'Google-IFrame');
        $fields->addFieldToTab('Root.Landkarte', $map);
        return $fields;
    }

    public function getName()
    {
        return $this->LocationName;
    }
}
