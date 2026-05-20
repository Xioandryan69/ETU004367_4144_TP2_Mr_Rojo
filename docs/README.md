Voici les documentations officielles et les points importants pour bien commencer avec [Chart.js](https://www.chartjs.org/?utm_source=chatgpt.com) et [FullCalendar](https://fullcalendar.io/?utm_source=chatgpt.com).

---

# 1. Chart.js — Graphiques JavaScript

## Documentation officielle

* [Chart.js Docs](https://www.chartjs.org/docs/latest/?utm_source=chatgpt.com)
* [Chart.js Samples](https://www.chartjs.org/samples/latest/?utm_source=chatgpt.com)
* [Chart.js GitHub](https://github.com/chartjs/Chart.js?utm_source=chatgpt.com)

---

# Pourquoi utiliser Chart.js ?

## Quoi ?

Bibliothèque JavaScript pour créer :

* Bar chart
* Pie chart
* Doughnut chart
* Line chart
* Radar
* Polar Area
* Mixed chart

## Pourquoi ?

Parce que :

* simple
* léger
* responsive
* animations intégrées
* fonctionne très bien avec :

  * PHP
  * Spring Boot
  * Django
  * Laravel
  * Node.js
  * React
  * Vue
  * Angular

> “Un bon graphique transforme des nombres morts en décisions vivantes.”

---

# Installation rapide

## CDN

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

---

# Pie Chart (camembert)

## Exemple simple

```html
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<canvas id="myPie"></canvas>

<script>
const ctx = document.getElementById('myPie');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Java', 'PHP', 'Python'],
        datasets: [{
            label: 'Votes',
            data: [12, 19, 7],
            borderWidth: 1
        }]
    }
});
</script>

</body>
</html>
```

---

# Doughnut Chart

## Différence

* `pie` = cercle plein
* `doughnut` = cercle avec trou

```javascript
type: 'doughnut'
```

---

# Bar Chart

```javascript
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar'],
        datasets: [{
            label: 'Ventes',
            data: [100, 200, 150]
        }]
    }
});
```

---

# Concepts importants dans Chart.js

| Élément  | Rôle              |
| -------- | ----------------- |
| type     | type de graphique |
| labels   | noms affichés     |
| datasets | données           |
| data     | valeurs           |
| options  | configuration     |

---

# Architecture mentale

```text
Canvas HTML
   ↓
Chart.js
   ↓
Dataset
   ↓
Render graphique
```

---

# Exemple dynamique avec API

## Backend JSON

```json
[
   {"nom":"Java","valeur":10},
   {"nom":"PHP","valeur":20}
]
```

## Frontend

```javascript
fetch('/api/stats')
.then(res => res.json())
.then(data => {

   const labels = data.map(x => x.nom);
   const valeurs = data.map(x => x.valeur);

   new Chart(ctx,{
      type:'pie',
      data:{
         labels:labels,
         datasets:[{
            data:valeurs
         }]
      }
   });
});
```

---

# 2. FullCalendar.js

## Documentation officielle

* [FullCalendar Docs](https://fullcalendar.io/docs?utm_source=chatgpt.com)
* [FullCalendar Examples](https://fullcalendar.io/demos?utm_source=chatgpt.com)
* [FullCalendar GitHub](https://github.com/fullcalendar/fullcalendar?utm_source=chatgpt.com)

---

# Quoi ?

Bibliothèque calendrier JavaScript.

Permet :

* agenda
* événements
* planning
* réservation
* emploi du temps
* calendrier scolaire
* gestion salle
* gestion stock
* rendez-vous

---

# Installation CDN

```html
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>
```

---

# Exemple FullCalendar

```html
<!DOCTYPE html>
<html>
<head>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css' rel='stylesheet' />

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>

</head>

<body>

<div id='calendar'></div>

<script>

document.addEventListener('DOMContentLoaded', function() {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        events: [
            {
                title: 'Cours Java',
                start: '2026-05-20'
            },
            {
                title: 'Examen',
                start: '2026-05-25'
            }
        ]
    });

    calendar.render();
});

</script>

</body>
</html>
```

---

# Types de vues FullCalendar

| Vue          | Description |
| ------------ | ----------- |
| dayGridMonth | mois        |
| timeGridWeek | semaine     |
| timeGridDay  | jour        |
| listWeek     | liste       |

---

# Architecture FullCalendar

```text
Events JSON
      ↓
FullCalendar
      ↓
Render calendrier
```

---

# Exemple API Backend

## JSON

```json
[
   {
      "title":"Réunion",
      "start":"2026-05-20"
   }
]
```

## JavaScript

```javascript
events: '/api/events'
```

---

# Cas réels

## Gestion stock

* entrée stock
* sortie stock
* historique

## Université

* emploi du temps
* examens
* soutenances

## Entreprise

* rendez-vous
* tâches
* planning équipe

---

# Combiner Chart.js + FullCalendar

Très utilisé dans les dashboards :

```text
Dashboard Admin
├── FullCalendar → planning
├── Pie Chart → répartition
├── Bar Chart → statistiques
└── Line Chart → évolution
```

---

# Conseils professionnels

## Pour apprendre vite

Ordre conseillé :

1. HTML/CSS
2. JavaScript DOM
3. fetch API
4. Chart.js
5. FullCalendar
6. API backend

---

# Exemple stack moderne

| Backend     | Frontend     |
| ----------- | ------------ |
| Spring Boot | Chart.js     |
| Django      | FullCalendar |
| Laravel     | Chart.js     |
| Node.js     | FullCalendar |

---

# Ressources utiles

## Tutoriels

* [MDN JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript?utm_source=chatgpt.com)
* [Chart.js Getting Started](https://www.chartjs.org/docs/latest/getting-started/?utm_source=chatgpt.com)
* [FullCalendar Getting Started](https://fullcalendar.io/docs/getting-started?utm_source=chatgpt.com)

> “Les données racontent une histoire. Les graphiques la rendent visible.”
