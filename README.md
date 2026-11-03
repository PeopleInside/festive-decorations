# Decorazioni Festive per Flarum 2.x

Porting del plugin WordPress "Decorazioni Festive".

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
