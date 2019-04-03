<?php

namespace Jimev\Forms\HTMLEditor;

use SilverStripe\Forms\HTMLEditor\TinyMCEConfig;

class HTMLEditorFieldLocation_Config implements HTMLEditorFieldLocation_ConfigInterface
{
    public function getNumberOfRows() : int
    {
        return 5;
    }

    /**
     * Create a configuration for given name
     *    See _config.php in silverstripe/admin/
     *    Silverstripe default
     *    TinyMCEConfig::get('cms')
     *     ->setOptions([
     *         'friendly_name' => 'Default CMS',
     *         'priority' => '50',
     *         'skin' => 'silverstripe',
     *         'body_class' => 'typography',
     *         'contextmenu' => "sslink ssmedia ssembed inserttable | cell row column deletetable",
     *         'use_native_selects' => false,
     *         'valid_elements' => "@[id|class|style|title],a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title"
     *             . "|class],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],"
     *             . "-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align|data*],"
     *             . "-sub[class],-sup[class],-blockquote[dir|class],-cite[dir|class|id|title],"
     *             . "-table[cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],"
     *             . "-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],"
     *             . "tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],"
     *             . "#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],"
     *             . "-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],"
     *             . "-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],"
     *             . "-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],"
     *             . "-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],"
     *             . "dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir]",
     *         'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|name"
     *             . "|usemap|data*],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],"
     *             . "object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]"
     *     ]);
     *
     *    // Re-enable media dialog
     *    $module = ModuleLoader::inst()->getManifest()->getModule('silverstripe/admin');
     *    TinyMCEConfig::get('cms')
     *        ->enablePlugins([
     *            'contextmenu' => null,
     *            'image' => null,
     *            'sslink' => $module->getResource('client/dist/js/TinyMCE_sslink.js'),
     *            'sslinkexternal' => $module->getResource('client/dist/js/TinyMCE_sslink-external.js'),
     *            'sslinkemail' => $module->getResource('client/dist/js/TinyMCE_sslink-email.js'),
     *        ])
     *        ->setOption('contextmenu', 'sslink ssmedia ssembed inserttable | cell row column deletetable');

     *
     * @param string $name
     * @return void
     */
    public function setConfig($name = 'cms')
    {
        TinyMCEConfig::get($name)
            // Add a specific stylesheet
            ->setContentCSS([ '/app/client/dist/css/LocationPageTinyMCE.css' ])
            ->setOption('importcss_append', true)
            // Added to remove default styles
            ->setOption('style_formats', [])
            ->setOption('valid_styles', ['*' => 'color,font-weight,font-style,text-decoration'])
            ->setOption('paste_as_text', true)
            ->setOption('paste_text_sticky', true)
            ->setOption('paste_text_sticky_default', true)
            ->setOption('theme_advanced_blockformats', 'Paragraph=p;Header 1=h1;Header 2=h2;Header 3=h3;quote=blockquote')
            ->setButtonsForLine(1, 'bold', 'sslink', 'code')
            ->setButtonsForLine(2, []) // no button
            ->setButtonsForLine(3, []) // no button
            ->setOption('width', '702px')
            ->removeButtons(
                'outdent',
                'indent',
                'numlist',
                'hr',
                'pastetext',
                'pasteword',
                'visualaid',
                'anchor',
                'tablecontrols',
                'justifyleft',
                'justifycenter',
                'justifyright',
                'strikethrough',
                'justifyfull',
                'underline'
            );
    }
}
