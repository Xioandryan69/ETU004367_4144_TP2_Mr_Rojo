# Documentation complète npm + Chart.js + FullCalendar

---

# 1. npm — Node Package Manager

## Documentation officielle

* [npm Official](https://www.npmjs.com/?utm_source=chatgpt.com)
* [npm Docs](https://docs.npmjs.com/?utm_source=chatgpt.com)
* [Node.js](https://nodejs.org/?utm_source=chatgpt.com)

---

# Quoi ?

npm = gestionnaire de paquets JavaScript.

Il permet :

* installer bibliothèques
* gérer dépendances
* lancer scripts
* construire applications
* gérer versions

---

# Architecture npm

```text
Projet
│
├── package.json
├── package-lock.json
├── node_modules/
│
├── src/
├── public/
└── dist/
```

---

# 2. Installation Node.js

## Ubuntu

```bash
sudo apt update

sudo apt install nodejs npm
```

---

# Vérification

```bash
node -v
npm -v
```

---

# 3. Créer un projet npm

## Initialisation

```bash
npm init
```

ou rapide :

```bash
npm init -y
```

---

# Résultat

Création :

```text
package.json
```

---

# package.json

## Quoi ?

Fichier principal du projet.

Contient :

* nom projet
* version
* dépendances
* scripts

---

# Exemple complet

```json id="j7tb9j"
{
  "name": "dashboard-app",
  "version": "1.0.0",
  "description": "Projet dashboard",
  "main": "index.js",

  "scripts": {
    "start": "node index.js",
    "dev": "vite",
    "build": "vite build"
  },

  "dependencies": {
    "chart.js": "^4.4.1",
    "@fullcalendar/core": "^6.1.18"
  }
}
```

---

# 4. Installer packages

## Chart.js

```bash
npm install chart.js
```

---

# FullCalendar

```bash
npm install @fullcalendar/core
npm install @fullcalendar/daygrid
```

---

# Installer plusieurs

```bash
npm install axios react vue
```

---

# Que fait npm install ?

```text
1. Lit package.json
2. Télécharge packages
3. Résout dépendances
4. Crée node_modules
5. Crée package-lock.json
```

---

# 5. node_modules

## Rôle

Contient :

* packages
* dépendances
* sous-dépendances

---

# Exemple réel

```text
node_modules/
├── chart.js
├── moment
├── luxon
└── ...
```

---

# Pourquoi énorme ?

Parce que :

```text
A dépend B
B dépend C
C dépend D
```

---

# 6. package-lock.json

## Pourquoi ?

Verrouille versions exactes.

Important pour :

* stabilité
* équipe
* production

---

# Exemple

```json id="nnq2kp"
"chart.js": {
   "version": "4.4.1"
}
```

---

# 7. Installer dépendances dev

## Quoi ?

Packages développement seulement.

---

# Exemple

```bash
npm install vite --save-dev
```

---

# Résultat

```json id="bik26w"
"devDependencies": {
   "vite": "^5.0.0"
}
```

---

# 8. Désinstaller package

```bash
npm uninstall chart.js
```

---

# 9. Mettre à jour packages

```bash
npm update
```

---

# 10. Voir packages installés

```bash
npm list
```

---

# 11. Scripts npm

## package.json

```json id="nqbx6n"
"scripts": {
   "start": "node app.js",
   "dev": "vite",
   "build": "vite build"
}
```

---

# Exécuter

```bash
npm run dev
```

---

# 12. CDN vs npm

| npm              | CDN             |
| ---------------- | --------------- |
| projet sérieux   | test rapide     |
| gestion versions | simple          |
| build moderne    | html simple     |
| offline          | internet requis |

---

# 13. Chart.js complet

## Documentation

* [Chart.js Docs](https://www.chartjs.org/docs/latest/?utm_source=chatgpt.com)
* [Chart.js Samples](https://www.chartjs.org/samples/latest/?utm_source=chatgpt.com)

---

# Installation

```bash
npm install chart.js
```

---

# Import ES6

```javascript id="p5pb5t"
import Chart from 'chart.js/auto';
```

---

# HTML

```html id="30ig75"
<canvas id="myChart"></canvas>
```

---

# Bar Chart complet

```javascript id="fdr8i7"
import Chart from 'chart.js/auto';

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',

    data: {
        labels: ['Janvier', 'Février', 'Mars'],

        datasets: [{
            label: 'Ventes',

            data: [100, 200, 150],

            borderWidth: 1
        }]
    },

    options: {
        responsive: true
    }
});
```

---

# Pie Chart

```javascript id="d3xscd"
new Chart(ctx, {
   type: 'pie',

   data: {
      labels: ['Java', 'PHP', 'Python'],

      datasets: [{
         data: [12, 19, 7]
      }]
   }
});
```

---

# Doughnut

```javascript id="2x6rd6"
type: 'doughnut'
```

---

# Types disponibles

| Type      | Usage       |
| --------- | ----------- |
| bar       | histogramme |
| line      | courbe      |
| pie       | camembert   |
| doughnut  | anneau      |
| radar     | radar       |
| polarArea | polaire     |

---

# Structure Chart.js

```text
Chart
├── type
├── data
│   ├── labels
│   └── datasets
└── options
```

---

# Dataset

```javascript id="28fzba"
datasets: [{
   label: 'Stock',
   data: [10,20,30]
}]
```

---

# Responsive

```javascript id="9ic2e9"
options: {
   responsive: true
}
```

---

# API dynamique

## Backend JSON

```json id="3vrwt2"
[
   {"nom":"Java","valeur":20},
   {"nom":"PHP","valeur":10}
]
```

---

# Fetch API

```javascript id="f8kq1n"
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

# 14. FullCalendar complet

## Documentation

* [FullCalendar Docs](https://fullcalendar.io/docs?utm_source=chatgpt.com)
* [FullCalendar Demo](https://fullcalendar.io/demos?utm_source=chatgpt.com)

---

# Installation

```bash
npm install @fullcalendar/core
npm install @fullcalendar/daygrid
```

---

# Import

```javascript id="t8xhlf"
import { Calendar } from '@fullcalendar/core';

import dayGridPlugin from '@fullcalendar/daygrid';
```

---

# HTML

```html id="kmkgto"
<div id="calendar"></div>
```

---

# Exemple complet

```javascript id="1c7nmq"
document.addEventListener('DOMContentLoaded', function() {

   const calendarEl = document.getElementById('calendar');

   const calendar = new Calendar(calendarEl, {

      plugins: [dayGridPlugin],

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
```

---

# Types de vues

| Vue          | Description |
| ------------ | ----------- |
| dayGridMonth | mois        |
| timeGridWeek | semaine     |
| timeGridDay  | jour        |
| listWeek     | liste       |

---

# Ajouter événement

```javascript id="8yx3vw"
calendar.addEvent({
   title: 'Réunion',
   start: '2026-05-21'
});
```

---

# API backend

```javascript id="0q4q8m"
events: '/api/events'
```

---

# JSON attendu

```json id="dg7i7v"
[
   {
      "title":"Cours",
      "start":"2026-05-20"
   }
]
```

---

# 15. Projet réel Dashboard

## Stack moderne

```text
Frontend
├── HTML/CSS
├── JavaScript
├── Chart.js
└── FullCalendar

Backend
├── Spring Boot
├── Django
├── Laravel
└── Node.js
```

---

# Exemple architecture

```text
Spring Boot REST API
        ↓
JSON
        ↓
Frontend JS
        ├── Chart.js
        └── FullCalendar
```

---

# Exemple concret gestion stock

## Chart.js

* statistiques ventes
* stock restant
* chiffre affaires

## FullCalendar

* planning livraison
* historique stock
* rendez-vous fournisseur

---

# 16. Git + npm

## Important

Tu pushes :

* package.json
* package-lock.json

Tu ignores :

```text
node_modules/
```

---

# .gitignore

```gitignore id="ic52mn"
node_modules/
dist/
```

---

# 17. Workflow professionnel

```bash
git clone projet

cd projet

npm install

npm run dev
```

---

# 18. Outils modernes associés

| Outil      | Rôle          |
| ---------- | ------------- |
| Vite       | build rapide  |
| Webpack    | bundler       |
| Babel      | transpilation |
| TypeScript | JS typé       |
| React      | frontend      |
| Vue        | frontend      |
| Angular    | frontend      |

---

# 19. Vite recommandé

## Création

```bash
npm create vite@latest
```

---

# Installer dépendances

```bash
npm install
```

---

# Lancer

```bash
npm run dev
```

---

# 20. Philosophie moderne frontend

```text
Ancien web :
HTML + jQuery

Web moderne :
Components + npm + build tools
```

---

> “Le développeur moderne n’écrit plus seulement du code ; il orchestre un écosystème de dépendances.”
