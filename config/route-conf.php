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

// ---- END PAGE ROUTE ----