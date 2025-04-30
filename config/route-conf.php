<?php
if (!defined("ABSPATH")) die("Brak dostępu");

route('/', function () {
  home();
});

route('/404', function () {
  is404();
});

// ---- START PAGE ROUTE ----

route('/about', function () {
  get_page('about_content');
});

route('/kontakt', function () {
  get_page('kontakt_content');
});

route('/polityka-prywatnosci', function () {
  get_page('polityka-prywatnosci_content');
});

route('/regulamin', function () {
  get_page('regulamin_content');
});

route('/faq', function () {
  get_page('faq_content');
});

route('/zwroty-reklamacje', function () {
  get_page('zwroty-reklamacje_content');
});

route('/metody-platnosci', function () {
  get_page('metody-platnosci_content');
});

route('/wsparcie-klienta', function () {
  get_page('wsparcie-klienta_content');
});

route('/artysci', function () {
  category("Artyści", get_artist());
});

route('/artysci/:slug', function ($slug) {
  get_event_by_artist($slug);
});

route('/miejsca', function () {
  category("Miejsca", get_venues());
});

route('/miejsca/:slug', function ($slug) {
  get_event_by_venues($slug);
});

route('/wydarzenia', function () {
  category("Wydarzenia", get_event());
});

route('/wydarzenia/:slug', function ($slug) {
  get_event_site($slug);
});

route('/wydarzenia/:slug/buy', function ($slug) {
  get_buy_page($slug);
});

// ---- END PAGE ROUTE ----

// ---- START API ----

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  route('/api/event/:id/time', function ($id) {
    api_get_event_time($id);
  });

  route('/api/event/:id/tickets', function ($id) {
    api_get_event_tickets($id);
  });
}

// ---- END API ----