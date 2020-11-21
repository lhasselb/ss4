if (typeof(ss) === 'undefined' || typeof(ss.i18n) === 'undefined') {
    if (typeof(console) !== 'undefined') { // eslint-disable-line no-console
        console.error('Class ss.i18n not defined');  // eslint-disable-line no-console
    }
} else {
    ss.i18n.addDictionary('de', {
        "CMS.LINKLABEL_PAGE": "Link auf eine Seite innerhalb dieser Website",
        "CMS.LINKLABEL_ANCHOR": "Link auf einen Anker in dieser Seite",
        "Admin.LINKLABEL_EXTERNALURL": "Link auf eine externe Seite (URL)",
        "Admin.LINKLABEL_EMAIL": "Link auf eine E-Mail",
        "AssetAdmin.LINKLABEL_FILE": "Link auf eine Datei"
    });
}
