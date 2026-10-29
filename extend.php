<?php

use Flarum\Extend;
use PeopleInside\FestiveDecorations\Content;
use PeopleInside\FestiveDecorations\Decor;

// Caricamento esplicito: funziona anche se l'autoload di Composer non è aggiornato.
require_once __DIR__ . '/src/Decor.php';
require_once __DIR__ . '/src/Content.php';

$settings = new Extend\Settings();
foreach (Decor::defaults() as $key => $value) {
    $settings->default(Decor::P . $key, $value);
}

return [
    (new Extend\Frontend('forum'))->content(Content::class),
    (new Extend\Frontend('admin'))->js(__DIR__ . '/js/dist/admin.js'),
    $settings,
];
