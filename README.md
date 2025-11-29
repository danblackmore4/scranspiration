# Scranspiration – Recipe Inspiration & Macro Tracking

Scranspiration is a Pinterest-style recipe inspiration app built with **Laravel 11**, **Livewire 3**, and **Volt**.  
Users can browse meals in a card-based grid, add their own recipes, and (in future) see detailed macro breakdowns for each meal.

This project was created as part of my **CS348 – Web Application Development** coursework and will continue to be developed as a portfolio piece.

---

## Features

-   **Browse recipes** in a Pinterest-like card layout
-   **View key details** for each recipe: title, category, description, and author
-   **Authenticated recipe creation** via a Volt-powered “Add Recipe” page
-   **Ingredient management** – add/remove ingredients dynamically on the form
-   (Planned) **Macro & calorie calculation** using the CalorieNinjas API
-   **Auth scaffolding** with Laravel Breeze (login, register, password reset)

---

## Tech Stack

-   **Backend:** Laravel 11 (PHP 8.2+)
-   **Frontend:** Blade, Tailwind CSS, Vite
-   **Reactivity:** Livewire 3 + Volt (page components)
-   **Auth:** Laravel Breeze
-   **Database:** MySQL (via Laravel Sail)
-   **Environment:** Docker / Laravel Sail

---

## Current State

This project is very much **in active development** at this stage.  
What works right now:

-   Public homepage showing a grid of recipes
-   Authenticated users can visit `/recipes/create` and submit new recipes
-   Recipes are stored with relationships to **users**, **categories**, and **ingredients**

Planned improvements:

-   Image uploads for recipes
-   Better filtering and search on the homepage
-   Full macro breakdown per recipe using an external nutrition API
-   Polished responsive design and accessibility tweaks

---

## Getting Started (Local Development)

### 1 Clone the repository

```bash
git clone https://github.com/danblackmore4/scranspiration.git
cd scranspiration
```
