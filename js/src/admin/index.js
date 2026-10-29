import app from 'flarum/admin/app';

const P = 'peopleinside-festive.';
const ID = 'peopleinside-festive-decorations';

const EVENTS = {
  natale: 'Natale (1 – 27 dic)',
  capodanno: 'Capodanno (28 dic – 6 gen)',
  halloween: 'Halloween (20 ott – 2 nov)',
  sanvalentino: 'San Valentino (7 – 14 feb)',
  pride: 'Pride (1 – 30 giu)',
  carnevale: 'Carnevale (giovedì – martedì grasso)',
  festadonna: 'Festa della donna (6 – 8 mar)',
  pasqua: 'Pasqua (settimana prima – lunedì dell’Angelo)',
  festamamma: 'Festa della mamma (settimana della 2ª domenica di maggio)',
  primavera: 'Stagione: Primavera (20 mar – 20 giu)',
  estate: 'Stagione: Estate (21 giu – 22 set)',
  autunno: 'Stagione: Autunno (23 set – 30 nov)',
  inverno: 'Stagione: Inverno (1 dic – 19 mar)',
  cuori: 'Tema di riserva: Fiori e cuori',
};

const THEMES = {
  auto: 'Automatico (in base alla data e agli eventi abilitati)',
  natale: 'Natale', capodanno: 'Capodanno', halloween: 'Halloween', carnevale: 'Carnevale',
  sanvalentino: 'San Valentino', festadonna: 'Festa della donna', pasqua: 'Pasqua', festamamma: 'Festa della mamma',
  pride: 'Pride', primavera: 'Primavera', estate: 'Estate', autunno: 'Autunno', inverno: 'Inverno', cuori: 'Fiori e cuori',
};

app.initializers.add(ID, () => {
  const ext = app.extensionData.for(ID);
  const bool = (k, label, help) => ext.registerSetting({ setting: P + k, type: 'boolean', label, help });
  const text = (k, label, help) => ext.registerSetting({ setting: P + k, type: 'text', label, help });
  const num = (k, label, min, max, help) =>
    ext.registerSetting({ setting: P + k, type: 'number', label, help, min, max });
  const select = (k, label, options, help) => ext.registerSetting({ setting: P + k, type: 'select', label, options, help });

  bool('enabled', 'Decorazioni attive', 'Interruttore generale.');
  select('theme', 'Tema', THEMES, 'Con “Automatico” il tema dipende dalla data e dagli eventi spuntati qui sotto.');
  text('timezone', 'Fuso orario', 'Es. Europe/Rome');

  Object.keys(EVENTS).forEach((k) => bool('ev_' + k, EVENTS[k]));

  bool('heading', 'Emoji ai lati del titolo');
  text('heading_sel', 'Selettore titolo', 'Predefinito: .Hero-title (titolo della welcome hero in homepage)');
  bool('logo', 'Emoji sul logo');
  text('logo_sel', 'Selettore logo', 'Predefinito: .Header-title a');
  bool('menu', 'Festone sotto l’header ed emoji sulle voci del menù');
  text('menu_host', 'Selettore contenitore festone', 'Predefinito: #header');
  text('menu_items_sel', 'Selettore voci del menù', 'Predefinito: .sideNav li > a');

  select('effect', 'Comportamento effetti', {
    auto: 'Automatico (secondo i periodi sotto)',
    christmas: 'Sempre decorazioni natalizie (neve + cappelli + ghirlanda)',
    snow: 'Sempre solo neve',
    fireflies: 'Sempre solo lucciole',
    none: 'Nessun effetto',
  });
  text('xmas_start', 'Periodo natalizio: dal (GG/MM)', 'Neve + cappelli sugli avatar + ghirlanda. Sostituisce la neve normale.');
  text('xmas_end', 'Periodo natalizio: al (GG/MM)');
  bool('xmas_hats', 'Cappelli natalizi sugli avatar');
  text('snow_start', 'Solo neve: dal (GG/MM)', 'Fuori dal periodo natalizio.');
  text('snow_end', 'Solo neve: al (GG/MM)');
  num('snow_count', 'Fiocchi iniziali', 1, 500);
  num('snow_max', 'Fiocchi massimi', 1, 800);
  text('ff_start', 'Lucciole: dal (GG/MM)');
  text('ff_end', 'Lucciole: al (GG/MM)');
  num('ff_count', 'Numero di lucciole', 1, 100);
  num('ff_speed', 'Velocità lucciole', 1, 10);
  select('ff_mode', 'Lucciole: modalità', {
    always: 'Sempre visibili',
    first: 'Solo i primi 60 s della sessione, poi il pulsante lampeggia',
  });
  bool('fx_toggle', 'Mostra i pulsanti ON/OFF di neve e lucciole');

  ext.registerSetting({
    setting: P + 'custom_css',
    type: 'textarea',
    label: 'CSS personalizzato',
    help: 'Aggiunto in coda agli stili del plugin.',
  });
});
