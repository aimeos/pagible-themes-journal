<?php

/**
 * @license MIT, https://opensource.org/license/mit
 */


namespace Database\Seeders;

use Aimeos\Cms\Models\Element;
use Aimeos\Cms\Models\File;
use Aimeos\Cms\Models\Page;
use Aimeos\Cms\Utils;
use Aimeos\Cms\Validation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


/**
 * Journal theme demo for the fictional German business publication Kontur.
 */
class JournalDemo extends AbstractDemo
{
    /** @var array<string, string> Meta descriptions keyed by page path */
    private const DESCRIPTIONS = [
        'wirtschaft' => 'Kontur ordnet Industrie, Energie, Technologie und Mittelstand ein: gründlich recherchiert, verständlich geschrieben und ohne Börsenlärm.',
        'industrie-ohne-blaupause' => 'Wie deutsche Industrieunternehmen ihre Produktion umbauen, während Energiepreise, Lieferketten und Fachkräftemangel gleichzeitig Druck machen.',
        'wem-gehoert-der-strom' => 'Netze, Speicher und neue Kraftwerke entscheiden über die Energiewende. Kontur zeigt, wo Kapital fehlt und welche Modelle funktionieren.',
        'geld' => 'Analysen zu Geldanlage, Zinsen, Altersvorsorge und Vermögensaufbau für Menschen, die Entscheidungen verstehen statt Trends hinterherlaufen wollen.',
        'das-portfolio-ohne-modetrends' => 'Ein belastbares ETF-Portfolio braucht weniger Produkte, klare Regeln und einen Anlagehorizont, der auch schlechte Börsenmonate aushält.',
        'was-zinsen-wirklich-veraendern' => 'Was neue Zinsniveaus für Sparer, Kreditnehmer und langfristige Anleger bedeuten und welche alten Gewohnheiten jetzt teuer werden.',
        'immobilien' => 'Kontur berichtet über Wohnen, Bauen, Sanieren und Immobilienmärkte mit Blick auf Kosten, Regulierung und lebenswerte Städte.',
        'wohnen-wird-wieder-urban' => 'Warum gemischte Quartiere, kleinere Grundrisse und der Umbau bestehender Gebäude den Wohnungsmarkt stärker prägen als neue Großprojekte.',
        'sanieren-mit-plan' => 'Welche Reihenfolge bei einer energetischen Sanierung Kosten spart, Risiken begrenzt und ein Haus über Jahrzehnte verlässlich verbessert.',
        'karriere' => 'Arbeit, Führung und Unternehmenskultur jenseits schneller Karrieretipps: Kontur fragt, welche Strukturen gute Arbeit tatsächlich möglich machen.',
        'produktivitaet-braucht-ruhe' => 'Warum konzentrierte Arbeit nicht mit noch mehr Meetings, Nachrichten und Kennzahlen entsteht, sondern durch verlässliche Zeit und klare Zuständigkeit.',
        'fuehren-ohne-buehne' => 'Gute Führung zeigt sich in Entscheidungen, Vorbereitung und Verantwortung, nicht in Dauerpräsenz und großen Auftritten.',
        'ueber-kontur' => 'Kontur ist ein unabhängiges Wirtschaftsjournal aus Hamburg. Lernen Sie Redaktion, Haltung und Arbeitsweise kennen.',
        'abo' => 'Lesen Sie Kontur digital, als gedrucktes Monatsmagazin oder im Mitgliederbriefing mit zusätzlichen Dossiers und Gesprächen.',
    ];

    /**
     * Curated Unsplash photos used across the Kontur demo.
     *
     * @var array<string, array{0: string, 1: string, 2: string}>
     */
    private const PHOTOS = [
        'architecture' => ['photo-1486406146926-c627a92ad1ab', 'Neue Stadt', 'Moderne Büroarchitektur mit gerasterter Glasfassade'],
        'boardroom' => ['photo-1497366754035-f200968a6e72', 'Redaktionskonferenz', 'Helles Büro mit langem Tisch für eine gemeinsame Redaktionskonferenz'],
        'city' => ['photo-1449824913935-59a10b8d2000', 'Metropole im Wandel', 'Breite Straße zwischen dichten Hochhäusern in einer internationalen Metropole'],
        'construction' => ['photo-1504307651254-35680f356dfd', 'Sanierung', 'Baustelle eines großen Gebäudes während der Sanierung'],
        'contract' => ['photo-1450101499163-c8848c66ca85', 'Finanzplanung', 'Unterlagen, Stift und Taschenrechner auf einem Schreibtisch'],
        'desk' => ['photo-1497215728101-856f4ea42174', 'Konzentrierte Arbeit', 'Ruhiger Arbeitsplatz mit Schreibtischen und großen Fenstern'],
        'factory' => ['photo-1565793298595-6a879b1d9492', 'Industrielogistik', 'Lastwagen und Verladeflächen als Teil einer großen industriellen Lieferkette'],
        'home' => ['photo-1560518883-ce09059eeffa', 'Wohneigentum', 'Modernes Wohnhaus mit klarer Fassade und Vorgarten'],
        'market' => ['photo-1611974789855-9c2a0a7236a3', 'Kapitalmarkt', 'Digitale Kursanzeige mit Marktbewegungen und Kennzahlen'],
        'portrait' => ['photo-1556761175-b413da4baf72', 'Unternehmergespräch', 'Gespräch zwischen Führungskräften an einem hellen Besprechungstisch'],
        'savings' => ['photo-1579621970795-87facc2f976d', 'Langfristig sparen', 'Münzen und kleine Pflanze als Bild für langfristigen Vermögensaufbau'],
        'team' => ['photo-1521737711867-e3b97375f902', 'Gemeinsame Arbeit', 'Team bei einer konzentrierten Besprechung an einem Holztisch'],
        'technology' => ['photo-1535378917042-10a22c95931a', 'Automatisierung', 'Humanoider Roboter als Beispiel für neue Automatisierungstechnik'],
        'wind' => ['photo-1676749979869-81161c8824ee', 'Windkraft in der Fläche', 'Eine Reihe weißer Windräder auf einem weiten grünen Feld'],
    ];

    private string $element;
    private string $logoFile;


    /**
     * Creates the publication page below the home page.
     */
    protected function addAbout( Page $home ) : static
    {
        $this->page( [
            'lang' => 'de',
            'name' => 'Über uns',
            'title' => 'Über Kontur',
            'path' => 'ueber-kontur',
            'tag' => 'page',
            'type' => 'page',
            'status' => 1,
        ], [
            ['id' => Utils::uid(), 'type' => 'hero', 'group' => 'main', 'data' => [
                'title' => 'Wirtschaft braucht Zusammenhang',
                'subtitle' => 'Die Redaktion',
                'text' => 'Kontur berichtet über Unternehmen, Geld, Immobilien und Arbeit. Uns interessiert nicht nur, was passiert, sondern wer entscheidet, wer bezahlt und was eine Entwicklung im Alltag verändert.',
                'files' => [['id' => $this->img( 'boardroom' ), 'type' => 'file']],
            ]],
            ['id' => Utils::uid(), 'type' => 'cards', 'group' => 'main', 'data' => [
                'title' => 'So arbeiten wir',
                'columns' => 3,
                'cards' => [
                    ['title' => 'Vor Ort', 'text' => 'Wir sprechen mit Menschen, die Fabriken, Portfolios, Baustellen und Teams tatsächlich verantworten.'],
                    ['title' => 'Mit Zahlen', 'text' => 'Wir prüfen Größenordnungen, Zeiträume und Interessen. Eine Zahl ohne Vergleich ist selten eine gute Erklärung.'],
                    ['title' => 'Für den zweiten Blick', 'text' => 'Wir veröffentlichen weniger, redigieren gründlich und aktualisieren Analysen, wenn sich die Fakten ändern.'],
                ],
            ]],
            ['id' => Utils::uid(), 'type' => 'image-text', 'group' => 'main', 'data' => [
                'file' => ['id' => $this->img( 'team' ), 'type' => 'file'],
                'position' => 'end',
                'ratio' => '1-2',
                'text' => "## Eine Redaktion mit Widerspruch\n\nKontur wird in Hamburg gemacht, mit Korrespondentinnen und Autoren in Berlin, Frankfurt, München, Brüssel und Zürich. In jeder Konferenz sitzt Fachwissen neben Skepsis. Gute Texte werden dadurch genauer, nicht lauter.\n\nUnsere Autorinnen legen Beteiligungen und mögliche Interessenkonflikte offen. Unternehmen sehen Zitate, aber nicht unsere Bewertungen vor der Veröffentlichung.",
            ]],
            ['id' => Utils::uid(), 'type' => 'table', 'group' => 'main', 'data' => [
                'title' => 'Das Team',
                'header' => 'row+col',
                'table' => [
                    ['Ressort', 'Verantwortung', 'Standort'],
                    ['Chefredaktion', 'Mara Feld', 'Hamburg'],
                    ['Wirtschaft', 'Jonas Ehrlich', 'Berlin'],
                    ['Geld', 'Leila Osman', 'Frankfurt'],
                    ['Immobilien', 'Hannah Voss', 'Hamburg'],
                    ['Arbeit', 'Emre Aydin', 'München'],
                ],
            ]],
            ['id' => 'kontakt', 'type' => 'contact', 'group' => 'main', 'data' => [
                'title' => 'Schreiben Sie der Redaktion',
            ]],
        ], $home );

        return $this;
    }


    /**
     * Creates an editorial section and its stories below the home page.
     *
     * @param Page $home Home page
     * @param string $id Section page ID
     * @param string $name Section name
     * @param string $path Section path
     * @param string $title Section title
     * @param string $intro Section introduction
     * @param string $photo Section photo key
     * @param array<int, array<string, mixed>> $stories Story definitions
     * @return static Same object for fluent calls
     */
    protected function addSection(
        Page $home,
        string $id,
        string $name,
        string $path,
        string $title,
        string $intro,
        string $photo,
        array $stories
    ) : static
    {
        $section = $this->page( [
            'id' => $id,
            'lang' => 'de',
            'name' => $name,
            'title' => $name . ' | Kontur',
            'path' => $path,
            'tag' => 'blog',
            'type' => 'blog',
            'status' => 1,
        ], [
            ['id' => Utils::uid(), 'type' => 'hero', 'group' => 'main', 'data' => [
                'title' => $title,
                'subtitle' => 'Kontur | ' . $name,
                'text' => $intro,
                'files' => [['id' => $this->img( $photo ), 'type' => 'file']],
            ]],
            ['id' => Utils::uid(), 'type' => 'blog', 'group' => 'main', 'data' => [
                'title' => 'Aktuelle Analysen',
                'layout' => 'default',
                'limit' => 6,
                'order' => '_lft',
                'parent-page' => ['value' => $id, 'label' => $name],
            ]],
        ], $home );

        foreach( $stories as $story )
        {
            $rows = [['Beobachtung', 'Folge']];

            foreach( $story['points'] as $point ) {
                $rows[] = $point;
            }

            $this->page( [
                'lang' => 'de',
                'name' => $story['name'],
                'title' => $story['title'],
                'path' => $story['path'],
                'tag' => 'article',
                'type' => 'blog',
                'status' => 1,
            ], [
                $this->article( $story['title'], $story['intro'], $this->img( $story['photo'] ) ),
                ['id' => Utils::uid(), 'type' => 'heading', 'group' => 'main', 'data' => [
                    'level' => 2,
                    'title' => $story['heading'],
                ]],
                ['id' => Utils::uid(), 'type' => 'image-text', 'group' => 'main', 'data' => [
                    'file' => ['id' => $this->img( $story['second'] ), 'type' => 'file'],
                    'position' => 'end',
                    'ratio' => '1-2',
                    'text' => $story['body'],
                ]],
                ['id' => Utils::uid(), 'type' => 'table', 'group' => 'main', 'data' => [
                    'title' => 'Worauf es ankommt',
                    'header' => 'row+col',
                    'table' => $rows,
                ]],
                ['id' => Utils::uid(), 'type' => 'text', 'group' => 'main', 'data' => [
                    'text' => $story['close'],
                ]],
                $this->articleHero( $name, $path ),
            ], $section );
        }

        return $this;
    }


    /**
     * Creates the subscription page below the home page.
     */
    protected function addSubscribe( Page $home ) : static
    {
        $this->page( [
            'lang' => 'de',
            'name' => 'Abo',
            'title' => 'Kontur abonnieren',
            'path' => 'abo',
            'tag' => 'page',
            'type' => 'page',
            'status' => 1,
        ], [
            ['id' => Utils::uid(), 'type' => 'hero', 'group' => 'main', 'data' => [
                'title' => 'Mehr Einordnung. Weniger Lärm.',
                'subtitle' => 'Kontur Abo',
                'text' => 'Lesen Sie alle Analysen im Web, das Monatsmagazin auf Papier oder das zusätzliche Freitagsbriefing der Redaktion.',
                'files' => [['id' => $this->img( 'contract' ), 'type' => 'file']],
            ]],
            ['id' => Utils::uid(), 'type' => 'pricing', 'group' => 'main', 'data' => [
                'title' => 'Wählen Sie Ihre Ausgabe',
                'text' => 'Monatlich kündbar. Keine Anzeigen im Mitgliederbereich.',
                'items' => [
                    [
                        'name' => 'Digital',
                        'price' => '7 €',
                        'unit' => '/Monat',
                        'text' => 'Für tägliche Leserinnen und Leser',
                        'features' => "- Alle Artikel und Dossiers\n- Merkliste und Audiofassungen\n- Kontur am Morgen",
                        'url' => 'mailto:abo@kontur.example?subject=Digital-Abo',
                        'button' => 'Digital lesen',
                    ],
                    [
                        'name' => 'Magazin',
                        'price' => '12 €',
                        'unit' => '/Monat',
                        'text' => 'Die gedruckte Monatsausgabe',
                        'features' => "- Magazin frei Haus\n- Voller Digitalzugang\n- Jahresregister",
                        'url' => 'mailto:abo@kontur.example?subject=Magazin-Abo',
                        'button' => 'Magazin bestellen',
                        'highlight' => true,
                        'badge' => 'Meistgelesen',
                    ],
                    [
                        'name' => 'Briefing',
                        'price' => '18 €',
                        'unit' => '/Monat',
                        'text' => 'Für Entscheider mit wenig Zeit',
                        'features' => "- Magazin und Digitalzugang\n- Freitagsbriefing\n- Vier Redaktionsgespräche im Jahr",
                        'url' => 'mailto:abo@kontur.example?subject=Briefing-Abo',
                        'button' => 'Briefing wählen',
                    ],
                ],
            ]],
            ['id' => Utils::uid(), 'type' => 'questions', 'group' => 'main', 'data' => [
                'title' => 'Fragen zum Abo',
                'items' => [
                    ['title' => 'Kann ich monatlich kündigen?', 'text' => 'Ja. Ihr Zugang läuft bis zum Ende des bereits bezahlten Monats weiter.'],
                    ['title' => 'Gibt es ein Probeabo?', 'text' => 'Das Digitalabo kann vier Wochen kostenlos getestet werden. Es endet automatisch, wenn Sie nicht verlängern.'],
                    ['title' => 'Wann erscheint das Magazin?', 'text' => 'Die neue Ausgabe erscheint am dritten Donnerstag jedes Monats und liegt meist am folgenden Werktag im Briefkasten.'],
                    ['title' => 'Kann ich ein Abo verschenken?', 'text' => 'Ja. Geschenkabos laufen für drei, sechs oder zwölf Monate und verlängern sich nicht automatisch.'],
                ],
            ]],
        ], $home );

        return $this;
    }


    /**
     * Creates an article lead element with the file reference used by previews.
     *
     * @param string $title Article title
     * @param string $text Article introduction
     * @param string $fileId Cover file ID
     * @return array<string, mixed> Article content element
     */
    protected function article( string $title, string $text, string $fileId ) : array
    {
        return ['id' => Utils::uid(), 'type' => 'article', 'group' => 'main', 'files' => [$fileId], 'data' => [
            'title' => $title,
            'file' => ['id' => $fileId, 'type' => 'file'],
            'text' => $text,
        ]];
    }


    /**
     * Creates a closing call to action for an article.
     *
     * @param string $section Section name
     * @param string $path Section path
     * @return array<string, mixed> Hero content element
     */
    protected function articleHero( string $section, string $path ) : array
    {
        return ['id' => Utils::uid(), 'type' => 'hero', 'group' => 'main', 'data' => [
            'title' => 'Weiterlesen in ' . $section,
            'subtitle' => 'Kontur',
            'text' => 'Analysen, Gespräche und Zahlen, die den Zusammenhang sichtbar machen.',
            'url' => '/' . $path,
            'button' => 'Zum Ressort',
            'url-alternative' => '/abo',
            'button-alternative' => 'Kontur abonnieren',
        ]];
    }


    /**
     * Creates the shared Kontur footer and returns its ID.
     *
     * @return string Element ID
     */
    protected function element() : string
    {
        if( !isset( $this->element ) )
        {
            $cards = [
                ['title' => 'Ressorts', 'text' => "- [Wirtschaft](/wirtschaft)\n- [Geld](/geld)\n- [Immobilien](/immobilien)\n- [Karriere](/karriere)"],
                ['title' => 'Kontur', 'text' => "- [Über die Redaktion](/ueber-kontur)\n- [Kontakt](/ueber-kontur#kontakt)\n- [Abo](/abo)"],
                ['title' => 'Briefings', 'text' => "- [Kontur am Morgen](/abo)\n- [Freitagsbriefing](/abo)\n- [Themen-Dossiers](/wirtschaft)"],
                ['title' => 'Redaktion', 'text' => "- [redaktion@kontur.example](mailto:redaktion@kontur.example)\n- Hamburg · Berlin · Frankfurt"],
            ];

            $element = Element::forceCreate( [
                'lang' => 'de',
                'type' => 'cards',
                'name' => 'Kontur Footer',
                'data' => ['type' => 'cards', 'data' => ['cards' => $cards]],
                'editor' => 'demo',
            ] );

            $version = $element->versions()->forceCreate( [
                'lang' => 'de',
                'data' => [
                    'lang' => 'de',
                    'type' => 'cards',
                    'name' => 'Kontur Footer',
                    'data' => ['cards' => $cards],
                ],
                'published' => true,
                'editor' => 'demo',
            ] );

            $element->forceFill( ['latest_id' => $version->id] )->saveQuietly();
            $element->publish( $version );
            $this->element = (string) $element->refresh()->id;
        }

        return $this->element;
    }


    /**
     * Returns the ID of the primary Kontur image.
     *
     * @return string File ID
     */
    protected function file() : string
    {
        return $this->img( 'city' );
    }


    /**
     * Creates the Kontur home page and returns it.
     *
     * @param array<string, string> $sections Section IDs keyed by path
     * @return Page Home page
     */
    protected function home( array $sections ) : Page
    {
        $elementId = $this->element();
        $fileId = $this->file();
        $logoId = $this->logoFile();

        $config = [
            'logo' => [
                'type' => 'logo',
                'files' => [$logoId],
                'data' => ['file' => ['id' => $logoId, 'type' => 'file']],
            ],
            'logo-alternative' => [
                'type' => 'logo-alternative',
                'files' => [$logoId],
                'data' => ['file' => ['id' => $logoId, 'type' => 'file']],
            ],
        ];

        $content = [
            ['id' => Utils::uid(), 'type' => 'hero', 'group' => 'main', 'data' => [
                'title' => 'Die Wirtschaft verändert sich. Wir zeigen, wohin.',
                'subtitle' => 'Kontur | Ausgabe 04.26',
                'text' => 'Reportagen, Analysen und Gespräche über Unternehmen, Geld und Arbeit—mit Ruhe für die Fakten und Blick für die Folgen.',
                'url' => '/wirtschaft',
                'button' => 'Aktuelle Analysen',
                'url-alternative' => '/abo',
                'button-alternative' => 'Ausgabe testen',
                'files' => [['id' => $fileId, 'type' => 'file']],
            ]],
            ['id' => Utils::uid(), 'type' => 'cards', 'group' => 'main', 'data' => [
                'title' => 'Heute wichtig',
                'columns' => 4,
                'cards' => [
                    ['title' => 'Industrie ohne Blaupause', 'text' => "Fabriken müssen gleichzeitig sauberer, digitaler und unabhängiger werden. Der Umbau beginnt im laufenden Betrieb.\n\n[Analyse lesen](/industrie-ohne-blaupause)", 'file' => ['id' => $this->img( 'factory' ), 'type' => 'file']],
                    ['title' => 'Wem gehört der Strom?', 'text' => "Die Energiewende braucht Netze, Speicher und Geduld. Beim Kapital dafür beginnt der eigentliche Konflikt.\n\n[Zum Energiedossier](/wem-gehoert-der-strom)", 'file' => ['id' => $this->img( 'wind' ), 'type' => 'file']],
                    ['title' => 'Das Portfolio ohne Modetrends', 'text' => "Ein robustes Depot ist oft unspektakulär. Genau darin liegt seine Stärke, wenn die Börse unruhig wird.\n\n[Die Regeln ansehen](/das-portfolio-ohne-modetrends)", 'file' => ['id' => $this->img( 'market' ), 'type' => 'file']],
                    ['title' => 'Wohnen wird wieder urban', 'text' => "Gemischte Quartiere und umgebaute Büros verändern die Stadt schneller als neue Siedlungen am Rand.\n\n[Reportage öffnen](/wohnen-wird-wieder-urban)", 'file' => ['id' => $this->img( 'architecture' ), 'type' => 'file']],
                ],
            ]],
            ['id' => Utils::uid(), 'type' => 'image-text', 'group' => 'main', 'data' => [
                'file' => ['id' => $this->img( 'technology' ), 'type' => 'file'],
                'position' => 'end',
                'ratio' => '1-2',
                'text' => "## Der neue Industrieatlas\n\nWo entstehen Batterien, Chips, Wärmepumpen und Rechenzentren? Kontur verfolgt 180 Investitionsprojekte und zeigt, welche Regionen vom Umbau profitieren—und wo Netze, Flächen oder Fachkräfte fehlen.\n\nDie interaktive Karte verbindet angekündigte Milliarden mit sichtbaren Baufortschritten. Denn ein Spatenstich ist noch keine Fabrik.\n\n[Den Industrieatlas einordnen](/industrie-ohne-blaupause)",
            ]],
            ['id' => Utils::uid(), 'type' => 'blog', 'group' => 'main', 'data' => [
                'title' => 'Wirtschaft im Zusammenhang',
                'layout' => 'default',
                'limit' => 2,
                'order' => '_lft',
                'parent-page' => ['value' => $sections['wirtschaft'], 'label' => 'Wirtschaft'],
            ]],
            ['id' => Utils::uid(), 'type' => 'cards', 'group' => 'main', 'data' => [
                'title' => 'Geld, das zu Ihrem Leben passt',
                'columns' => 3,
                'cards' => [
                    ['title' => 'Zinsen neu sortieren', 'text' => "Tagesgeld, Anleihen, Kredite: Die alte Nullzinslogik gilt nicht mehr.\n\n[Was sich verändert](/was-zinsen-wirklich-veraendern)", 'file' => ['id' => $this->img( 'contract' ), 'type' => 'file']],
                    ['title' => 'Langfristig schlägt laut', 'text' => "Warum Sparregeln mehr bewirken als die nächste heiße Aktie.\n\n[Portfolio aufräumen](/das-portfolio-ohne-modetrends)", 'file' => ['id' => $this->img( 'savings' ), 'type' => 'file']],
                    ['title' => 'Sanieren in der richtigen Reihenfolge', 'text' => "Erst messen, dann planen, zuletzt bauen: So bleiben Kosten und Komfort im Blick.\n\n[Sanierungsplan lesen](/sanieren-mit-plan)", 'file' => ['id' => $this->img( 'home' ), 'type' => 'file']],
                ],
            ]],
            ['id' => Utils::uid(), 'type' => 'testimonial', 'group' => 'main', 'data' => [
                'title' => 'Stimmen aus der Wirtschaft',
                'items' => [
                    ['name' => 'Dr. Nika Salem', 'role' => 'Energieökonomin', 'text' => 'Die schwierige Frage ist nicht, ob wir investieren. Es geht darum, wer welches Risiko über zwanzig Jahre trägt.'],
                    ['name' => 'Arne Lorenz', 'role' => 'Familienunternehmer', 'text' => 'Transformation klingt nach Projekt. In der Produktion ist sie eine Folge sehr konkreter Entscheidungen an jedem Montagmorgen.'],
                    ['name' => 'Miriam Paul', 'role' => 'Stadtplanerin', 'text' => 'Wohnraum entsteht schneller, wenn wir bestehende Gebäude als Material begreifen und nicht als Hindernis.'],
                ],
            ]],
            ['id' => Utils::uid(), 'type' => 'heading', 'group' => 'footer', 'data' => ['level' => 2, 'title' => 'Kontur']],
            ['type' => 'reference', 'refid' => $elementId, 'group' => 'footer'],
        ];

        $meta = [
            'meta-tags' => Validation::entry( 'meta-tags', [
                'description' => 'Kontur ist ein unabhängiges Wirtschaftsjournal mit Reportagen und Analysen zu Unternehmen, Geld, Immobilien und Arbeit.',
                'keywords' => 'Wirtschaftsjournal, Unternehmen, Geldanlage, Immobilien, Karriere, Analysen',
            ], 'meta' ),
            'social-media' => Validation::entry( 'social-media', [
                'title' => 'Kontur | Wirtschaft im Zusammenhang',
                'description' => 'Reportagen, Analysen und Gespräche über Unternehmen, Geld und Arbeit—gründlich recherchiert und verständlich geschrieben.',
                'file' => ['id' => $fileId, 'type' => 'file'],
            ], 'meta' ),
        ];

        $page = Page::forceCreate( [
            'lang' => 'de',
            'name' => 'Start',
            'title' => 'Kontur | Wirtschaft im Zusammenhang',
            'path' => '',
            'tag' => 'root',
            'theme' => $this->theme,
            'status' => 1,
            'cache' => 5,
            'editor' => 'demo',
            'config' => $config,
            'meta' => $meta,
            'content' => $content,
        ] );

        $version = $page->versions()->forceCreate( [
            'lang' => 'de',
            'data' => [
                'name' => 'Start',
                'title' => 'Kontur | Wirtschaft im Zusammenhang',
                'path' => '',
                'tag' => 'root',
                'domain' => '',
                'theme' => $this->theme,
                'status' => 1,
                'cache' => 5,
            ],
            'aux' => [
                'config' => $config,
                'meta' => $meta,
                'content' => $content,
            ],
            'published' => true,
            'editor' => 'demo',
        ] );

        $version->files()->attach( array_unique( array_merge( [$fileId], $this->ids( $config ), $this->ids( $content ), $this->ids( $meta ) ) ) );
        $version->elements()->attach( $elementId );
        $page->forceFill( ['latest_id' => $version->id] )->saveQuietly();
        $page->publish( $version );

        return $page;
    }


    /**
     * Returns file IDs referenced anywhere in the given data.
     *
     * @param mixed $value Content or metadata
     * @return array<int, string> File IDs
     */
    protected function ids( mixed $value ) : array
    {
        $ids = [];

        if( is_array( $value ) )
        {
            if( ( $value['type'] ?? null ) === 'file' && is_string( $value['id'] ?? null )
                && !isset( $value['data'] ) && !isset( $value['group'] )
            ) {
                $ids[] = $value['id'];
            }

            foreach( $value as $item ) {
                $ids = array_merge( $ids, $this->ids( $item ) );
            }
        }

        return $ids;
    }


    /**
     * Returns the file ID for a curated demo photo.
     *
     * @param string $key Photo key from self::PHOTOS
     * @return string File ID
     */
    protected function img( string $key ) : string
    {
        [$photo, $name, $desc] = self::PHOTOS[$key];
        return $this->image( $photo, $name, $desc, 'de' );
    }


    /**
     * Creates the Kontur SVG logo and returns its file ID.
     *
     * @return string File ID
     */
    protected function logoFile() : string
    {
        if( !isset( $this->logoFile ) )
        {
            $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 96" role="img" aria-labelledby="title desc">
  <title id="title">Kontur Logo</title>
  <desc id="desc">Kontur Wortmarke mit goldener Akzentlinie</desc>
  <rect x="2" y="16" width="10" height="64" fill="#9A7112"/>
  <text x="30" y="68" fill="#1F1E1C" font-family="Georgia, 'Times New Roman', serif" font-size="64" font-weight="700" letter-spacing="-3">KONTUR</text>
  <path d="M32 80h354" stroke="#9A7112" stroke-width="3"/>
  <text x="400" y="79" fill="#65615B" font-family="Arial, Helvetica, sans-serif" font-size="10" font-weight="700" letter-spacing="2">JOURNAL</text>
</svg>
SVG;

            $disk = Storage::disk( config( 'cms.disk', 'public' ) );
            $path = rtrim( 'cms/' . $this->tenant, '/' ) . '/kontur-logo.svg';

            if( !$disk->put( $path, $svg ) ) {
                throw new \Aimeos\Cms\Exception( sprintf( 'Unable to store logo "%s"', $path ) );
            }

            $data = [
                'mime' => 'image/svg+xml',
                'lang' => 'de',
                'name' => 'Kontur Logo',
                'path' => $path,
                'previews' => ['500' => $path],
                'description' => ['de' => 'Kontur Wortmarke mit goldener Akzentlinie'],
            ];

            $file = File::forceCreate( $data + ['editor' => 'demo'] );
            $version = $file->versions()->forceCreate( [
                'lang' => 'de',
                'data' => $data,
                'published' => true,
                'editor' => 'demo',
            ] );

            $file->forceFill( ['latest_id' => $version->id] )->saveQuietly();
            $file->publish( $version );
            $this->logoFile = (string) $file->refresh()->id;
        }

        return $this->logoFile;
    }


    /**
     * Creates a Journal demo page below the given parent and returns it.
     *
     * @param array<string, mixed> $data Page attributes
     * @param array<int, array<string, mixed>> $content Content elements
     * @param Page $parent Parent page
     * @param array<int, string> $fileIds Additional file IDs to attach
     * @param array<string, array<string, mixed>|object> $meta Meta entries keyed by type
     * @return Page Created page
     */
    protected function page( array $data, array $content, Page $parent, array $fileIds = [], array $meta = [] ) : Page
    {
        $elementId = $this->element();
        $fileId = $this->file();
        $description = self::DESCRIPTIONS[$data['path'] ?? ''] ?? $data['title'] ?? '';

        $meta = $data['meta'] ?? $meta ?: [
            'meta-tags' => Validation::entry( 'meta-tags', [
                'description' => $description,
                'keywords' => 'Kontur, Wirtschaftsjournal, Unternehmen, Geld, Immobilien, Karriere',
            ], 'meta' ),
            'social-media' => Validation::entry( 'social-media', [
                'title' => $data['title'] ?? '',
                'description' => $description,
                'file' => ['id' => $fileId, 'type' => 'file'],
            ], 'meta' ),
        ];

        $content[] = ['id' => Utils::uid(), 'type' => 'heading', 'group' => 'footer', 'data' => ['level' => 2, 'title' => 'Kontur']];
        $content[] = ['type' => 'reference', 'refid' => $elementId, 'group' => 'footer'];

        $page = Page::forceCreate( $data + [
            'theme' => $this->theme,
            'editor' => 'demo',
            'meta' => $meta,
            'content' => $content,
        ] );
        $page->appendToNode( $parent )->save();

        $version = $page->versions()->forceCreate( [
            'lang' => $data['lang'] ?? 'de',
            'data' => array_diff_key( $data, ['content' => 1, 'meta' => 1, 'id' => 1] ) + [
                'domain' => '',
                'theme' => $this->theme,
            ],
            'aux' => ['meta' => $meta, 'content' => $content],
            'published' => true,
            'editor' => 'demo',
        ] );

        $version->elements()->attach( $elementId );
        $version->files()->attach( array_unique( array_merge( [$fileId], $fileIds, $this->ids( $content ), $this->ids( $meta ) ) ) );

        $page->forceFill( ['latest_id' => $version->id] )->saveQuietly();
        $page->publish( $version );

        return $page;
    }


    /**
     * Builds the Journal business publication demo page tree.
     */
    protected function pages() : void
    {
        $sections = [
            'wirtschaft' => (string) Str::uuid7(),
            'geld' => (string) Str::uuid7(),
            'immobilien' => (string) Str::uuid7(),
            'karriere' => (string) Str::uuid7(),
        ];
        $home = $this->home( $sections );

        $this->addSection(
            $home,
            $sections['wirtschaft'],
            'Wirtschaft',
            'wirtschaft',
            'Unternehmen unter Druck. Ideen im Aufbruch.',
            'Wir begleiten den Umbau der Industrie, neue Energiemärkte und einen Mittelstand, der seine Stärke nicht aus Schlagzeilen bezieht.',
            'factory',
            [
                [
                    'name' => 'Industrie ohne Blaupause',
                    'title' => 'Industrie ohne Blaupause: Umbau im laufenden Betrieb',
                    'path' => 'industrie-ohne-blaupause',
                    'photo' => 'factory',
                    'second' => 'technology',
                    'intro' => "Die deutsche Industrie soll klimaneutraler, digitaler und unabhängiger werden—gleichzeitig. In den Werkhallen ist daraus kein Masterplan geworden, sondern eine Folge harter Entscheidungen über Maschinen, Energie und Menschen.",
                    'heading' => 'Investieren, bevor Gewissheit da ist',
                    'body' => "## Neue Technik trifft alte Abhängigkeiten\n\nEine Produktionslinie läuft zwanzig Jahre. Wer heute eine Anlage ersetzt, legt sich auf Energiepreise, Software und Lieferanten fest, die niemand zuverlässig vorhersagen kann. Erfolgreiche Betriebe teilen den Umbau deshalb in kleine, messbare Schritte.\n\nSie beginnen dort, wo Verbrauchsdaten fehlen, Abwärme ungenutzt bleibt oder ein einzelnes Bauteil die gesamte Linie abhängig macht.",
                    'points' => [
                        ['Energie wird einzeln gemessen', 'Investitionen lassen sich nach tatsächlichem Verbrauch priorisieren'],
                        ['Anlagen bleiben modular', 'Technik kann ausgetauscht werden, ohne die ganze Linie stillzulegen'],
                        ['Beschäftigte testen früh', 'Fehler werden sichtbar, bevor Prozesse im großen Maßstab wechseln'],
                    ],
                    'close' => 'Transformation ist kein Zustand nach dem Projekt. Sie wird zur Fähigkeit, Technik und Abläufe regelmäßig zu verändern, ohne Qualität und Lieferfähigkeit zu verlieren.',
                ],
                [
                    'name' => 'Wem gehört der Strom?',
                    'title' => 'Wem gehört der Strom? Der Kampf um Netze und Speicher',
                    'path' => 'wem-gehoert-der-strom',
                    'photo' => 'wind',
                    'second' => 'architecture',
                    'intro' => "Wind- und Solarparks liefern immer öfter günstigen Strom. Doch zwischen Erzeugung und Verbrauch fehlen Leitungen, Speicher und flexible Tarife. Genau dort entscheidet sich, wer an der Energiewende verdient.",
                    'heading' => 'Die Rendite liegt in der Verbindung',
                    'body' => "## Infrastruktur braucht lange Zusagen\n\nEin Batteriespeicher kann in zwei Jahren stehen. Eine neue Stromtrasse braucht oft ein Jahrzehnt. Kommunen, Netzbetreiber, Industrie und Anleger rechnen deshalb mit völlig verschiedenen Zeiträumen.\n\nNeue Modelle teilen Erlöse aus Netzentlastung, Stromhandel und Reserveleistung. Entscheidend ist, dass Risiken offen verteilt werden und nicht erst bei der ersten Engpassstunde auftauchen.",
                    'points' => [
                        ['Speicher reagieren in Sekunden', 'Sie stabilisieren Preise, ersetzen aber kein belastbares Netz'],
                        ['Netze werden lokal knapp', 'Standort und Anschluss sind wertvoller als reine Erzeugungsleistung'],
                        ['Industrie wird flexibler', 'Verbrauch verschiebt sich in Stunden mit reichlich Strom'],
                    ],
                    'close' => 'Die Energiewende wird nicht allein auf Feldern und Dächern gebaut. Ihr ökonomischer Kern liegt in den unsichtbaren Verbindungen dazwischen.',
                ],
            ]
        )->addSection(
            $home,
            $sections['geld'],
            'Geld',
            'geld',
            'Vermögen braucht Regeln, keine Vorhersagen.',
            'Wir erklären Zinsen, Märkte und Vorsorge so, dass Entscheidungen auch dann tragen, wenn die Schlagzeile von morgen anders ausfällt.',
            'market',
            [
                [
                    'name' => 'Das Portfolio ohne Modetrends',
                    'title' => 'Das Portfolio ohne Modetrends',
                    'path' => 'das-portfolio-ohne-modetrends',
                    'photo' => 'market',
                    'second' => 'savings',
                    'intro' => "Ein gutes Depot muss nicht jede Zukunftswette enthalten. Es braucht eine breite Basis, verlässliche Kosten und Regeln für den Moment, in dem die Kurse fallen und der eigene Plan plötzlich alt wirkt.",
                    'heading' => 'Einfach heißt nicht gedankenlos',
                    'body' => "## Erst das Ziel, dann das Produkt\n\nWer in zehn Jahren eine Wohnung kaufen will, braucht einen anderen Aktienanteil als jemand mit dreißig Jahren bis zur Rente. Die passende Mischung beginnt deshalb mit Zeit, Rücklagen und Verlusttoleranz.\n\nErst danach kommen ETFs, Anleihen oder Tagesgeld. Produkte sind Werkzeuge. Kein Ticker kann eine unklare Entscheidung reparieren.",
                    'points' => [
                        ['Ein Welt-ETF bildet den Kern', 'Regionale Wetten bleiben klein und bewusst'],
                        ['Die Reserve liegt separat', 'Ein Börsenminus erzwingt keinen Verkauf für laufende Ausgaben'],
                        ['Einmal pro Jahr wird geprüft', 'Handeln folgt einer Regel und nicht der Stimmung'],
                    ],
                    'close' => 'Ein robustes Portfolio fühlt sich in guten Jahren manchmal langweilig an. In schlechten Jahren zeigt sich, warum genau das ein Vorteil ist.',
                ],
                [
                    'name' => 'Was Zinsen wirklich verändern',
                    'title' => 'Was Zinsen wirklich verändern',
                    'path' => 'was-zinsen-wirklich-veraendern',
                    'photo' => 'contract',
                    'second' => 'home',
                    'intro' => "Zinsen sind zurück, aber nicht für alle im gleichen Maß. Sparer erhalten wieder Ertrag, Kredite bleiben teuer und viele Verträge reagieren langsamer als der Leitzins.",
                    'heading' => 'Jede Laufzeit hat ihren Preis',
                    'body' => "## Sicherheit ist wieder sichtbar bepreist\n\nTagesgeld bleibt flexibel, kann aber schnell weniger abwerfen. Festgeld bindet Kapital. Anleihen schwanken im Kurs, wenn sich Marktzinsen bewegen. Wer nur auf den höchsten Prozentwert schaut, übersieht oft die Laufzeit und den Zugriff.\n\nBei Krediten lohnt der gleiche Blick: Sondertilgung, Zinsbindung und Restschuld sind wichtiger als ein einzelner Vergleichszins.",
                    'points' => [
                        ['Liquidität hat einen Wert', 'Nicht jeder Euro sollte für den höchsten Zins gebunden werden'],
                        ['Lange Bindung schafft Ruhe', 'Sie kann teuer sein, wenn die persönliche Planung unsicher bleibt'],
                        ['Schulden werden neu bewertet', 'Sichere Tilgung konkurriert wieder ernsthaft mit der Geldanlage'],
                    ],
                    'close' => 'Das neue Zinsniveau verlangt keine spektakuläre Strategie. Es belohnt, wer Verträge, Laufzeiten und die eigene Flexibilität sauber nebeneinanderlegt.',
                ],
            ]
        )->addSection(
            $home,
            $sections['immobilien'],
            'Immobilien',
            'immobilien',
            'Bauen ist teuer. Bestehendes wird kostbar.',
            'Kontur untersucht, wie Städte wachsen, Gebäude weitergenutzt werden und Eigentümer Sanierungen finanzierbar organisieren.',
            'architecture',
            [
                [
                    'name' => 'Wohnen wird wieder urban',
                    'title' => 'Wohnen wird wieder urban',
                    'path' => 'wohnen-wird-wieder-urban',
                    'photo' => 'city',
                    'second' => 'architecture',
                    'intro' => "Neue Wohnungen entstehen nicht nur auf freiem Feld. Leere Büros, Parkplätze und eingeschossige Märkte werden zu Bauland mitten in der Stadt—wenn Planung und Eigentümer zusammenfinden.",
                    'heading' => 'Die zweite Chance der Bestandsstadt',
                    'body' => "## Umbau spart nicht automatisch Geld\n\nBestehende Tragwerke, Leitungen und Schadstoffe machen Projekte kompliziert. Gleichzeitig sind Straßen, Schulen und Nahverkehr oft schon vorhanden. Die Bilanz wird gut, wenn Planer früh prüfen, was bleiben kann und welche Nutzung zum Gebäude passt.\n\nKleine Grundrisse, gemeinschaftliche Räume und gemischte Erdgeschosse schaffen mehr Wohnwert als reine Quadratmetermaximierung.",
                    'points' => [
                        ['Bürotiefen werden geprüft', 'Nicht jedes Raster eignet sich für belichtete Wohnungen'],
                        ['Erdgeschosse bleiben flexibel', 'Läden, Praxen und Arbeit beleben das Quartier'],
                        ['Parkflächen werden geteilt', 'Mehr Fläche bleibt für Wohnungen und Grün'],
                    ],
                    'close' => 'Die Stadt der Zukunft wird selten komplett neu gebaut. Sie entsteht in den Zwischenräumen, Aufstockungen und Umnutzungen der Gegenwart.',
                ],
                [
                    'name' => 'Sanieren mit Plan',
                    'title' => 'Sanieren mit Plan: Erst verstehen, dann bauen',
                    'path' => 'sanieren-mit-plan',
                    'photo' => 'home',
                    'second' => 'construction',
                    'intro' => "Fenster, Heizung, Dach, Fassade: Wer alles gleichzeitig denkt, verliert schnell den Überblick. Eine gute Sanierung beginnt mit dem Gebäude und einer Reihenfolge, die spätere Arbeiten nicht wieder zerstört.",
                    'heading' => 'Das Haus als System lesen',
                    'body' => "## Messen vor dem Angebot\n\nVerbrauchsdaten, Wärmebilder und eine Bestandsaufnahme zeigen, wo Energie tatsächlich verloren geht. Erst dann lässt sich entscheiden, ob eine kleinere Heizung genügt, welche Bauteile zusammengehören und wann Bewohner ausweichen müssen.\n\nFörderung gehört in den Finanzplan, sollte aber keine Maßnahme rechtfertigen, die technisch wenig bewirkt.",
                    'points' => [
                        ['Die Hülle kommt vor der Heizung', 'Der spätere Wärmebedarf bestimmt die richtige Leistung'],
                        ['Feuchtigkeit wird mitgedacht', 'Dichte Fenster brauchen ein belastbares Lüftungskonzept'],
                        ['Etappen folgen einem Zielbild', 'Jeder Bauabschnitt bereitet den nächsten vor'],
                    ],
                    'close' => 'Eine Sanierung ist dann gelungen, wenn Kosten, Komfort und Technik auch zehn Jahre später noch zusammenpassen.',
                ],
            ]
        )->addSection(
            $home,
            $sections['karriere'],
            'Karriere',
            'karriere',
            'Gute Arbeit entsteht nicht im Dauerlauf.',
            'Wir sprechen über Führung, Konzentration und Organisation—ohne die Verantwortung für schlechte Strukturen beim Einzelnen abzuladen.',
            'team',
            [
                [
                    'name' => 'Produktivität braucht Ruhe',
                    'title' => 'Produktivität braucht Ruhe',
                    'path' => 'produktivitaet-braucht-ruhe',
                    'photo' => 'desk',
                    'second' => 'team',
                    'intro' => "Viele Unternehmen messen Aktivität und hoffen auf Leistung. Doch konzentrierte Arbeit entsteht erst, wenn Kalender, Zuständigkeiten und digitale Werkzeuge eine Aufgabe schützen statt sie ständig zu unterbrechen.",
                    'heading' => 'Zeit ist Teil der Infrastruktur',
                    'body' => "## Fokus lässt sich organisieren\n\nZwei meetingfreie Vormittage helfen mehr als ein weiterer Kurs zum Zeitmanagement. Klare Entscheidungswege verhindern, dass jede Frage in fünf Chats landet. Teams mit verlässlicher Ruhezeit arbeiten nicht weniger zusammen—sie kommen nur besser vorbereitet zusammen.\n\nDie wichtigste Kennzahl ist nicht die Zahl gesendeter Nachrichten, sondern die Zeit bis zu einer guten Entscheidung.",
                    'points' => [
                        ['Kalender haben gemeinsame Regeln', 'Konzentrierte Zeit wird nicht bei jeder Anfrage neu verhandelt'],
                        ['Entscheidungen haben Eigentümer', 'Fragen kreisen nicht zwischen Gruppen ohne Mandat'],
                        ['Status wird schriftlich geteilt', 'Meetings bleiben für Konflikte, Ideen und echte Abwägungen'],
                    ],
                    'close' => 'Ruhe ist kein Bonus für wenige. Sie ist eine betriebliche Voraussetzung für Arbeit, die Urteil, Sorgfalt und Verantwortung verlangt.',
                ],
                [
                    'name' => 'Führen ohne Bühne',
                    'title' => 'Führen ohne Bühne',
                    'path' => 'fuehren-ohne-buehne',
                    'photo' => 'portrait',
                    'second' => 'boardroom',
                    'intro' => "Sichtbarkeit kann Orientierung geben. Dauerpräsenz ersetzt jedoch keine Entscheidung. Gute Führung zeigt sich oft dort, wo Rollen geklärt, Konflikte bearbeitet und andere Menschen handlungsfähig werden.",
                    'heading' => 'Verantwortung wird konkret',
                    'body' => "## Klarheit vor Charisma\n\nBeschäftigte brauchen keine perfekte Persönlichkeit an der Spitze. Sie brauchen nachvollziehbare Prioritäten, Schutz vor widersprüchlichen Aufträgen und eine Führungskraft, die Fehler nicht nach unten weiterreicht.\n\nWer gut führt, baut eine Organisation, die auch während der eigenen Abwesenheit vernünftige Entscheidungen treffen kann.",
                    'points' => [
                        ['Prioritäten werden begründet', 'Teams verstehen, was warten darf und warum'],
                        ['Konflikte werden früh benannt', 'Sachliche Differenzen werden nicht zu persönlicher Unsicherheit'],
                        ['Erfolg wird verteilt', 'Verantwortung und Anerkennung liegen bei den Menschen, die die Arbeit tragen'],
                    ],
                    'close' => 'Führung ohne Bühne ist nicht unsichtbar. Sie wird an der Qualität der Entscheidungen und an der Selbstständigkeit des Teams erkennbar.',
                ],
            ]
        )->addAbout( $home )
            ->addSubscribe( $home );
    }
}
