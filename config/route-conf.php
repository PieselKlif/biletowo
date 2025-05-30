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

route('/buy/final', function () {
  get_final_page();
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

  route('/api/event/:id/sectors', function ($id) {
    api_get_event_sectors($id);
  });

  route('/api/venue/sector/:id/rows', function ($id) {
    api_get_venue_sector_rows($id);
  });

  route('/api/venue/row/:rid/time/:tid', function ($rid, $tid) {
    api_get_seats($rid, $tid);
  });

  route('/api/tickets/type/event/:eid/sector/:sid', function ($eid, $sid) {
    api_get_ticket_price($eid, $sid);
  });
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  route('/api/table', function () {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    echo api_post_get_table_data($data);
  });

  route('/api/buy/ticket', function () {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    api_post_buy_ticket($data);
  });
}

// ---- END API ----