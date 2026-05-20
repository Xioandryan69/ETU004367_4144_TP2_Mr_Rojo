# JavaScript API + FullCalendar + Controller API CodeIgniter

---

# 1. Architecture générale

```text
Frontend
├── HTML
├── CSS
├── JavaScript
├── Chart.js
└── FullCalendar
        ↓ fetch API
Backend
└── CodeIgniter API
        ↓
Database MySQL
```

---

# Quoi ?

Le frontend JavaScript :

* affiche calendrier
* affiche graphiques
* envoie requêtes API

Le backend CodeIgniter :

* récupère données
* retourne JSON
* fait CRUD

---

# 2. Documentation JavaScript API

## Documentation officielle JavaScript

* [MDN JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript?utm_source=chatgpt.com)
* [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API?utm_source=chatgpt.com)

---

# 3. Documentation FullCalendar

* [FullCalendar Docs](https://fullcalendar.io/docs?utm_source=chatgpt.com)
* [FullCalendar Events](https://fullcalendar.io/docs/events-json-feed?utm_source=chatgpt.com)

---

# 4. Documentation Chart.js

* [Chart.js Docs](https://www.chartjs.org/docs/latest/?utm_source=chatgpt.com)
* [Chart.js Samples](https://www.chartjs.org/samples/latest/?utm_source=chatgpt.com)

---

# 5. Fetch API

## Quoi ?

Permet communication :

```text
JavaScript ⇄ API Backend
```

---

# GET API

```javascript id="jlwmgj"
fetch('/api/events')
.then(response => response.json())
.then(data => {
    console.log(data);
});
```

---

# POST API

```javascript id="4vto0j"
fetch('/api/events', {

   method: 'POST',

   headers: {
      'Content-Type': 'application/json'
   },

   body: JSON.stringify({
      title: 'Cours Java',
      start: '2026-05-20'
   })

})
.then(res => res.json())
.then(data => console.log(data));
```

---

# Architecture fetch

```text
Browser
   ↓
fetch()
   ↓
API REST
   ↓
JSON
```

---

# 6. FullCalendar complet avec API

---

# HTML

```html id="yvj1y0"
<!DOCTYPE html>
<html>

<head>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css' rel='stylesheet' />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>

</head>

<body>

<div id="calendar"></div>

<script src="app.js"></script>

</body>
</html>
```

---

# app.js

```javascript id="y8y8w7"
document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        events: 'http://localhost:8080/events',

        selectable: true,

        dateClick: function(info) {

            fetch('http://localhost:8080/events', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    title: 'Nouvel événement',
                    start: info.dateStr
                })
            })
            .then(res => res.json())
            .then(data => {

                calendar.refetchEvents();

            });

        }
    });

    calendar.render();
});
```

---

# Fonctionnement

```text
Click calendrier
      ↓
fetch POST
      ↓
CodeIgniter API
      ↓
MySQL
      ↓
JSON response
      ↓
refresh calendar
```

---

# 7. CodeIgniter API

## Documentation officielle

* [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/?utm_source=chatgpt.com)

---

# Structure CodeIgniter

```text
app/
├── Controllers/
├── Models/
├── Views/
└── Config/
```

---

# 8. Migration SQL

## Table events

```sql id="w4m3ot"
CREATE TABLE events (

   id INT AUTO_INCREMENT PRIMARY KEY,

   title VARCHAR(255),

   start DATE

);
```

---

# 9. Model CodeIgniter

## app/Models/EventModel.php

```php id="v1k7f5"
<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'start'
    ];
}
```

---

# Rôle Model

```text
Database abstraction
```

Le model :

* SELECT
* INSERT
* UPDATE
* DELETE

---

# 10. Controller API

## app/Controllers/EventController.php

```php id="jlwm7w"
<?php

namespace App\Controllers;

use App\Models\EventModel;
use CodeIgniter\RESTful\ResourceController;

class EventController extends ResourceController
{
    public function index()
    {
        $model = new EventModel();

        $events = $model->findAll();

        return $this->respond($events);
    }

    public function create()
    {
        $model = new EventModel();

        $data = $this->request->getJSON(true);

        $model->insert($data);

        return $this->respond([
            'status' => 'success'
        ]);
    }
}
```

---

# Pourquoi ResourceController ?

Parce que :

* API REST
* JSON automatique
* bonnes pratiques

---

# Architecture REST

```text
GET    /events
POST   /events
PUT    /events/1
DELETE /events/1
```

---

# 11. Routes

## app/Config/Routes.php

```php id="5ig5oe"
$routes->resource('events');
```

---

# Résultat automatique

| Méthode | URL       | Action    |
| ------- | --------- | --------- |
| GET     | /events   | liste     |
| POST    | /events   | créer     |
| PUT     | /events/1 | modifier  |
| DELETE  | /events/1 | supprimer |

---

# 12. Réponse JSON

## GET /events

```json id="vlw8lx"
[
   {
      "id":1,
      "title":"Cours Java",
      "start":"2026-05-20"
   }
]
```

---

# 13. Ajouter Chart.js

---

# HTML

```html id="n5z6ft"
<canvas id="myChart"></canvas>
```

---

# JavaScript

```javascript id="6q6r4e"
fetch('http://localhost:8080/stats')

.then(res => res.json())

.then(data => {

   const labels = data.map(x => x.nom);

   const valeurs = data.map(x => x.total);

   const ctx = document.getElementById('myChart');

   new Chart(ctx, {

      type: 'pie',

      data: {

         labels: labels,

         datasets: [{
            data: valeurs
         }]
      }
   });

});
```

---

# 14. Controller statistiques

## app/Controllers/StatsController.php

```php id="w1o0c7"
<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class StatsController extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT categorie nom,
                   COUNT(*) total
            FROM produits
            GROUP BY categorie
        ");

        return $this->response->setJSON(
            $query->getResult()
        );
    }
}
```

---

# Route

```php id="73x85j"
$routes->get('stats', 'StatsController::index');
```

---

# 15. Architecture propre

```text
Frontend
├── FullCalendar
├── Chart.js
└── Fetch API
        ↓
Controller API
        ↓
Model
        ↓
MySQL
```

---

# 16. Flux réel

```text
Utilisateur clique
       ↓
JavaScript fetch()
       ↓
Controller API
       ↓
Model
       ↓
Database
       ↓
JSON
       ↓
Frontend update
```

---

# 17. Structure projet propre

```text
app/
├── Controllers/
│   ├── EventController.php
│   └── StatsController.php
│
├── Models/
│   └── EventModel.php
│
├── Views/
│   └── dashboard.php
```

---

# 18. Sécurité importante

## Toujours :

### Validation

```php id="0dk1yh"
$this->validate([
   'title' => 'required'
]);
```

---

# Allowed fields

```php id="lzxxv9"
protected $allowedFields = [
   'title',
   'start'
];
```

---

# 19. Concepts importants

| Élément      | Rôle            |
| ------------ | --------------- |
| Controller   | reçoit requête  |
| Model        | accès DB        |
| JSON         | échange données |
| Fetch API    | communication   |
| FullCalendar | calendrier      |
| Chart.js     | statistiques    |

---

# 20. APIs REST modernes

## Philosophie

```text
Backend = fournisseur données
Frontend = affichage interactif
```

---

# 21. Exemple réel entreprise

```text
ERP
├── Dashboard Chart.js
├── Planning FullCalendar
├── API CodeIgniter
└── MySQL
```

---

# Ressources avancées

## REST API

* [REST API Tutorial](https://restfulapi.net/?utm_source=chatgpt.com)

## JSON

* [JSON Org](https://www.json.org/json-en.html?utm_source=chatgpt.com)

## CodeIgniter REST

* [CodeIgniter RESTful Resource](https://codeigniter.com/user_guide/incoming/restful.html?utm_source=chatgpt.com)

> “Une API REST bien conçue sépare la logique métier de l’interface comme un cerveau sépare pensée et mouvement.”
