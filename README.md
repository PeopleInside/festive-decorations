# Decorazioni Festive per Flarum 2.x

Porting del plugin WordPress "Decorazioni Festive".

[![Latest Stable Version](https://img.shields.io/packagist/v/peopleinside/festive-decorations?label=packagist&color=f28d1a)](https://packagist.org/packages/peopleinside/festive-decorations)
[![Total Downloads](https://img.shields.io/packagist/dt/peopleinside/festive-decorations)](https://packagist.org/packages/peopleinside/festive-decorations)
[![License](https://img.shields.io/packagist/l/peopleinside/festive-decorations)](https://github.com/PeopleInside/festive-decorations/blob/main/LICENSE)
[![PHP Version](https://img.shields.io/packagist/dependency-v/peopleinside/festive-decorations/php)](https://packagist.org/packages/peopleinside/festive-decorations)
[![Flarum](https://img.shields.io/badge/Flarum-2.x-e7672e)](https://flarum.org)

## Disclaimer

This software is provided **"AS IS"**, without any warranty. While it has been tested and reasonable efforts are made to ensure security and reliability, no guarantees are provided. As an open project, anyone may contribute or report issues, but this does not imply endorsement or liability from the maintainers.

**You use this software entirely at your own risk.** The authors and contributors are not liable for any damages, data loss, or unexpected behavior resulting from its use, modification, or distribution. Always review and test the code independently before deploying it in critical or production environments.

## Installazione
Copia la cartella in `extensions/festive-decorations` (nella root di Flarum), poi:

    composer config repositories.festive path extensions/festive-decorations
    composer require peopleinside/festive-decorations
    php flarum cache:clear

Abilita l'estensione dal pannello admin e configurala dalla sua pagina impostazioni.

## Come funzionano gli effetti (modalità Automatico)
1. Periodo natalizio (default 01/12 – 06/01): neve + cappelli sugli avatar + ghirlanda SVG nell'header (il tuo script natalizio). Sostituisce la neve normale.
2. Fuori dal Natale, nel periodo neve (default 25/10 – 07/01): solo neve (CANVAS NEVE).
3. Periodo lucciole (default 08/01 – 24/10): script lucciole.
Titolo, logo, menù e festone emoji seguono il tema del giorno (festività/stagioni), come nel plugin WordPress.

## Se qualcosa non compare
Adatta i selettori CSS dalle impostazioni (titolo, logo, voci menù, contenitore festone).

## Ricompilare la parte admin (opzionale)
    cd js && npm install && npm run build
