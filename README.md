# Tiešsaistes sociālais tīkls

## Projekta apraksts
Šī kvalifikācijas darba mērķis ir izveidot tērzēšanas sistēmu kura ļauj viegli sazināties un sūtīt failus dažādos kanālos, ka ari dod iespēju instalēt šo sistēmu lokāli.

## Izmantotās tehnoloģijas
Projektā tiek izmantots:
- LARAVEL
- VUE JS
- Bulma
- Sass
- JWT
- Axios
- Vuex
- MySQL
- Laravel WebSockets

## Izmantotie avoti
- [Laravel documentation](https://laravel.com/docs/7.x)
- [Setting up JWT authentication with Laravel and Vue JS](https://blog.peterplucinski.com/setting-up-jwt-authentication-with-laravel-and-vue-part-1/)
- [Laravel WebSockets documentation](https://docs.beyondco.de/laravel-websockets/)
- [Bulma CSS framework documentation](https://bulma.io/documentation/)

## Uzstādīšanas instrukcijas
1. Lejupielādējam [Git for windows](https://git-scm.com/download/win), lai lietotu git.
2. Instalējam git.
3. [Lejupielādējam XAMPP](https://www.apachefriends.org/download.html), lai varētu izveidot webserveri.
4. Instalējam XAMPP.
5. [Lejupielādējam PHP Thread Safe](https://windows.php.net/download#php-7.4), lai varētu izmantot Composer.
6. Instalējam PHP.
7. [Lejupielādējam Node.js](https://nodejs.org/en/), lai varētu izmantot Composer.
8. Instalējam Node.js.
9. [Lejupielādējam Composer](https://getcomposer.org/download/), lai varētu izmantot Laravel.
10. Instalējam Composer.
11. Pārliecinamies par XAMPP darbību atverot adresi http://localhost/
12. Ejam uz phpMyAdmin ar http://localhost/phpmyadmin/ adresi un veidojam jaunu tabulu ar `fleme` nosaukumu.
13. Veicam labo klikšķi un izvēlamies opciju "git bash here" un izpildam zemāk raksīto komandu.
```
git clone https://github.com/nedius/fleme.git
```
14. Iekš `fleme` mape izpildām zemāk rakstītos komandas.
```
composer install
npm install
php artisan config:cahce
```
15. Dodamies uz XAMPP atrašanās vietu un \apache\conf\httpd.conf faila 254 un 254 līnijas mājiņām ar absolūtaju ceļu uz `fleme/public`
16. Lai izveidot vajadzīgus ierakstus datubāzē iekš `fleme` mape izpildām zemāk rakstīto komandu
```
php artisan migrate:fresh --seed
```
17. Lai palaist Websocket serveru iekš `fleme` mape izpildām zemāk rakstīto komandu
```
php artisan websockets:serve
```
18. Atveram adresi http://localhost/
19. Ieejam admin konta ar `admin@admin.com` e-pastu un `123456` parole vai user konta ar `user@user.com` e-pastu un `123456` parole.