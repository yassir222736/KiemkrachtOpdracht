# KiemKracht VZW — Kassaticket Upload Applicatie

Laravel-applicatie waarmee klanten kassabonnen kunnen uploaden, met een beveiligd beheerpaneel voor interne medewerkers.

## Stack

- **Backend:** PHP 8.2 + Laravel 12
- **Frontend:** Bootstrap 5, Sass, Vite
- **Database:** SQLite
- **Authenticatie:** laravel/ui

## Vereisten

- PHP 8.2+ met extensies: `pdo_sqlite`, `fileinfo`, `gd`, `mbstring`
- Composer 2.x
- Node.js 18+ & npm

## Installatie

```bash
# 1. Afhankelijkheden installeren
composer install
npm install

# 2. Omgevingsbestand configureren
cp .env.example .env
php artisan key:generate

# 3. Database aanmaken en seeden
touch database/database.sqlite
php artisan migrate --seed

# 4. Frontend assets builden
npm run build

# 5. Applicatie starten
php artisan serve
```

De applicatie is beschikbaar op `http://localhost:8000`.

### Admin inloggegevens

| E-mail | Wachtwoord |
|--------|-----------|
| admin@kiemkracht.be | Admin@Kiemkracht2026! |

## Architectuur

### MVC + Service Layer

De controllers zijn bewust **dun gehouden** — ze behandelen alleen HTTP-verzoeken en -antwoorden. Alle business logic (bestandsverwerking, CRUD-operaties, zoekfunctionaliteit) zit in `TicketService`. Dit zorgt voor:

- **Testbaarheid**: de service is onafhankelijk van HTTP te testen
- **Herbruikbaarheid**: als we later een API toevoegen, hergebruiken we dezelfde service
- **Scheiding van verantwoordelijkheden**: elke laag heeft één duidelijke taak

## Versiebeheer

Ontwikkeld met Git. Feature branches gebruikt voor:
- Ticket upload functionaliteit
- Admin beheerpaneel
- Beveiligingsverbeteringen
- UI/UX styling met Bootstrap

## Technische Keuzes

| Keuze | Motivatie |
|-------|-----------|
| **Bootstrap 5** via **laravel/ui** | Bootstrap-native auth views; geen Tailwind overhead nodig aangezien Bootstrap vereist is |
| **SQLite** voor development | Geen externe server nodig, ideaal voor demo. In productie: MySQL of PostgreSQL aanbevolen |
| **`is_admin` boolean** i.p.v. RBAC | Er is precies één rolonderscheid. Een volledig rollen-/rechtensysteem (Spatie, Bouncer) zou overengineering zijn |
| **Service layer** | Dunne controllers, testbare business logic, herbruikbaar bij API-uitbreiding |
| **Form Request classes** | Laravel-conventie die validatie scheidt van controllers — duidelijker en herbruikbaar |
| **UUID bestandsnamen** | Uniek (geen collisies), onvoorspelbaar (bescherming tegen enumeration attacks) |
| **Privé disk** (`storage/app/private/receipts/`) | Bestanden niet direct via URL toegankelijk; worden alleen via de controller geserveerd na authenticatie |
| **Registratie uitgeschakeld** | Admin-accounts worden alleen via seeders aangemaakt — bewuste beveiligingskeuze |

## Beveiligingsmaatregelen

| Bedreiging | Maatregel |
|------------|-----------|
| CSRF | `@csrf` in alle formulieren (Laravel built-in) |
| XSS | Blade `{{ }}` auto-escaping — geen `{!! !!}` voor gebruikersdata |
| SQL Injection | Eloquent ORM met parameterized queries |
| Mass Assignment | Expliciet `$fillable` op het Ticket model |
| Ongeautoriseerde uploads | MIME-validatie (`mimes:png,jpg,jpeg,pdf`) + maximale grootte (5MB) |
| Directe bestandstoegang | Bestanden in privé-opslag, geserveerd via geauthenticeerde controller |
| Pad-manipulatie | UUID-bestandsnamen, geen gebruikersinvoer in opslagpaden |
| Ongeautoriseerde toegang | `auth` + `admin` middleware op alle beheerpagina's |
| Brute force login | Laravel's ingebouwde `ThrottleLogins` |

## Functionaliteiten

### Publiek formulier (`/tickets/create`)
- Naam, e-mailadres en kassabon (PNG/JPG/PDF) uploaden
- Client-side + server-side validatie
- Bevestigingsmelding na succesvolle inzending

### Beheerpaneel (`/admin/tickets`)
- Overzicht van alle ingediende tickets met paginering
- Zoeken op naam en e-mailadres
- Tickets bewerken (naam, e-mail, kassabon vervangen)
- Kassabonnen inline bekijken (afbeeldingen) of downloaden (PDF)

## Demo Flow

1. Ga naar `/tickets/create` — vul naam, e-mail in en upload een kassabon
2. Na succesvolle upload verschijnt een bevestigingsmelding
3. Ga naar `/login` — log in met de admin-gegevens (zie hierboven)
4. Bekijk alle ingediende tickets op `/admin/tickets`
5. Zoek, bekijk of bewerk tickets via het beheerpaneel
