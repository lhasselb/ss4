<?php

use SilverStripe\Security\PasswordValidator;
use SilverStripe\Security\Member;

//use SilverStripe\ORM\Search\FulltextSearchable;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;
use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;
use SilverStripe\View\Parsers\ShortcodeParser;

/* Logging */
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

// Date
use SilverStripe\Core\Config\Config;
use SilverStripe\i18n\i18n;

/* Set Global Date and Time */
i18n::config()
    ->set('date_format', 'dd.MM.yyyy')
    ->set('time_format', 'HH:mm');

// remove PasswordValidator for SilverStripe 5.0
$validator = PasswordValidator::create();
// Settings are registered via Injector configuration - see passwords.yml in framework
Member::set_password_validator($validator);

$config = HtmlEditorConfig::get('cms');
/*
$config->enablePlugins([
    //'template',
    //'fullscreen',
    //'hr',
    //'contextmenu',
    //'charmap',
    //'visualblocks',
    //'lists',
    //'charcount' => ModuleResourceLoader::resourceURL('drmartingonzo/ss-tinymce-charcount:client/dist/js/bundle.js'),
]);
*/
$config->addButtonsToLine(2, 'hr');
$config->setOption('block_formats', 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Address=address;Pre=pre');
$config->setOption('invalid_elements', 'h1');

/*
HtmlEditorConfig::get('cms')
    ->enablePlugins('template')
    ->insertButtonsAfter('code', 'template');
$templatesPath = dirname(__FILE__) . '/templates/Helper/pdfIcon.html';
//Injector::inst()->get(LoggerInterface::class)->debug('_config.php - templatesPath() = ' . $templatesPath);
*/


// NO Search yet - future ?
//FulltextSearchable::enable();
