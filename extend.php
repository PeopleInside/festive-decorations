<?php

use Flarum\Extend;
use PeopleInside\FestiveDecorations\Content;
use PeopleInside\FestiveDecorations\Decor;

$settings = new Extend\Settings();
foreach (Decor::defaults() as $key => $value) {
    $settings->default(Decor::P . $key, $value);
}

return [
    (new Extend\Frontend('forum'))->content(Content::class),
    (new Extend\Frontend('admin'))->js(__DIR__ . '/js/dist/admin.js'),
    $settings,
];
