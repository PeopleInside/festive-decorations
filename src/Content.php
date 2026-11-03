<?php

namespace PeopleInside\FestiveDecorations;

use DateTimeImmutable;
use DateTimeZone;
use Flarum\Frontend\Document;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class Content
{
    public function __construct(protected SettingsRepositoryInterface $settings)
    {
    }

    private function load(): array
    {
        $defaults = Decor::defaults();
        $booleanKeys = Decor::booleanKeys();
        $o = [];
        foreach ($defaults as $k => $d) {
            $v = $this->settings->get(Decor::P . $k);
            if (in_array($k, $booleanKeys, true)) {
                // If setting was never stored in DB, fallback to default.
                // If stored in DB, '', '0', 0, false all represent disabled (false).
                if ($v === null) {
                    $o[$k] = $d;
                } else {
                    $o[$k] = self::b($v) ? '1' : '0';
                }
            } else {
                $o[$k] = ($v === null || ($v === '' && $k !== 'custom_css')) ? $d : $v;
            }
        }
        return $o;
    }

    private static function b($v): bool
    {
        return in_array($v, [1, '1', true, 'true'], true);
    }

    private static function sel($v): string
    {
        return trim(str_replace(['{', '}', '<', ';', '\\', '/*', '*/'], '', strip_tags((string) $v)));
    }

    private static function n($v, int $min, int $max): int
    {
        return max($min, min($max, abs((int) $v)));
    }

    private static function asset(string $file): string
    {
        $p = dirname(__DIR__) . '/resources/' . $file;
        return is_file($p) ? (string) file_get_contents($p) : '';
    }

    public function __invoke(Document $document, ServerRequestInterface $request): void
    {
        $o = $this->load();
        if (! self::b($o['enabled'])) {
            return;
        }

        try {
            $tz = new DateTimeZone($o['timezone']);
        } catch (\Exception $e) {
            $tz = new DateTimeZone('Europe/Rome');
        }
        $today = new DateTimeImmutable('today', $tz);
        $md = Decor::md($today);

        $themes = Decor::themes();
        $enabled = array_values(array_filter(Decor::eventKeys(), fn ($k) => self::b($o['ev_' . $k])));
        $key = $o['theme'] === 'auto'
            ? Decor::themeForToday($today, $enabled)
            : (isset($themes[$o['theme']]) ? $o['theme'] : null);
        $t = $key ? $themes[$key] : null;
        $accent = $t ? $t['accent'] : '#e91e63';

        /* Effetti: Natale > Neve > Lucciole */
        $effect = $o['effect'];
        $xmas = $snow = $ff = false;
        if ($effect === 'auto') {
            $isNataleOn = self::b($o['ev_natale']);
            $isWinterOn = self::b($o['ev_inverno']);
            $isAutumnOn = self::b($o['ev_autunno']);
            $isSpringOn = self::b($o['ev_primavera']);
            $isSummerOn = self::b($o['ev_estate']);

            // Natale: solo se l'evento Natale è abilitato e siamo nelle date natalizie
            $xmas = $isNataleOn && Decor::inRange($md, Decor::parseMd($o['xmas_start'], 1, 12), Decor::parseMd($o['xmas_end'], 6, 1));

            // Neve: solo se la stagione attiva corrente è abilitata
            $inSnowSeason = ($md >= 1201 || $md <= 107) ? $isWinterOn : $isAutumnOn;
            $snow = ! $xmas && $inSnowSeason && Decor::inRange($md, Decor::parseMd($o['snow_start'], 25, 10), Decor::parseMd($o['snow_end'], 7, 1));

            // Lucciole: solo se la stagione attiva corrente è abilitata
            if ($md >= 320 && $md <= 620) {
                $inFfSeason = $isSpringOn;
            } elseif ($md >= 621 && $md <= 922) {
                $inFfSeason = $isSummerOn;
            } elseif ($md >= 923 && $md <= 1130) {
                $inFfSeason = $isAutumnOn;
            } else {
                $inFfSeason = $isWinterOn;
            }
            $ff = ! $xmas && ! $snow && $inFfSeason && Decor::inRange($md, Decor::parseMd($o['ff_start'], 8, 1), Decor::parseMd($o['ff_end'], 24, 10));
        } elseif ($effect === 'christmas') {
            $xmas = true;
        } elseif ($effect === 'snow') {
            $snow = true;
        } elseif ($effect === 'fireflies') {
            $ff = true;
        }

        // Se nessun tema ed effetto è attivo e non c'è CSS personalizzato, non iniettare nulla
        if (! $t && ! $xmas && ! $snow && ! $ff && trim($o['custom_css']) === '') {
            return;
        }

        $config = [
            'theme' => $t ? [
                'emojis'    => array_values(array_filter(explode(' ', $t['strip']))),
                'mark'      => $t['menu'],
                'logoEmoji' => $t['logo'][0],
                'logoPos'   => $t['logo'][1],
            ] : null,
            'heading'      => self::b($o['heading']),
            'headingSel'   => self::sel($o['heading_sel']),
            'logo'         => self::b($o['logo']),
            'logoSel'      => self::sel($o['logo_sel']),
            'menu'         => self::b($o['menu']),
            'menuHost'     => self::sel($o['menu_host']),
            'menuItemsSel' => self::sel($o['menu_items_sel']),
            'christmas'    => $xmas,
            'snow'         => ['initial' => self::n($o['snow_count'], 1, 500), 'max' => self::n($o['snow_max'], 1, 800)],
            'ff'           => ['count' => self::n($o['ff_count'], 1, 100), 'speed' => self::n($o['ff_speed'], 1, 10), 'mode' => $o['ff_mode'] === 'first' ? 'first' : 'always'],
        ];

        /* CSS */
        $css = ':root{--dfest-accent:' . $accent . ';}';
        if ($t) {
            $q = fn ($e) => 'content:"' . $e . '";content:"' . $e . '" / "";';
            $css .= '.dfest-h1::before{' . $q($t['title'][0]) . 'display:inline-block;margin-right:.35em;transform-origin:50% 90%;}';
            $css .= '.dfest-h1::after{' . $q($t['title'][1]) . 'display:inline-block;margin-left:.35em;transform-origin:50% 90%;}';
            $css .= self::asset('css/decor.css');
        }
        if ($xmas) {
            $css .= self::asset('css/christmas.css');
        }
        if ($snow || $xmas) {
            $css .= self::asset('css/snow.css');
        }
        if ($ff) {
            $css .= self::asset('css/fireflies.css');
        }
        if (! self::b($o['fx_toggle'])) {
            $css .= '#toggleSnow,#magicToggle{display:none!important;}';
        }
        if ($o['custom_css'] !== '') {
            $css .= "\n" . strip_tags($o['custom_css']);
        }
        $document->head[] = '<style id="dfest-css">' . $css . '</style>';

        /* JS */
        $js = 'window.DFEST=' . json_encode($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) . ';';
        if ($t) {
            $js .= self::asset('js/decor.js');
        }
        if ($snow || $xmas) {
            $js .= self::asset('js/snow.js');
        }
        if ($xmas && self::b($o['xmas_hats'])) {
            $js .= self::asset('js/hats.js');
        }
        if ($ff) {
            $js .= self::asset('js/fireflies.js');
        }
        $document->foot[] = '<script id="dfest-js">' . str_replace('</script', '<\/script', $js) . '</script>';
    }
}
