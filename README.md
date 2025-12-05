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
-   (Planned) **Macro & calorie calculation** using the OpenFoodFacts API
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
-   API (OpenFoodFacts) for food search and Macros
-   Can view indivual recipe details

## Food Search & Macros API Integration (OpenFoodFacts)

This project now includes automatic ingredient lookup and macro calculation using the OpenFoodFacts API - a completely free, community-driven API.

**How It Works**
When creating a recipe:

-   Users can search for real supermarket products (e.g. Tesco Reduced Fat Cheese)
-   The app calls a custom backend route (GET /api/foods/search?q=<query>)
-   Laravel uses a dedicated service, OpenFoodFactsService, which queries the OpenFoodFacts API.
-   For each food item, the service extracts:
    -   Name
    -   Brand
    -   Barcode
    -   Calories per 100g
    -   Protein per 100g
    -   Carbs per 100g
    -   Fat per 100g
-   The Livewire/Volt component then:
    -   Lets the user add ingredients
    -   Allows editing the grams of each ingredient
    -   Automatically calculates recipe totals
    -   Stores all macros in the pivot table per ingredient

Planned improvements:

-   Better filtering and search on the homepage
-   A search bar for more specific queries
-   (if time allows) a calender which can hold a users planned meals for the week

---

## Getting Started (Local Development)

### 1 Clone the repository

```bash
git clone https://github.com/danblackmore4/scranspiration.git
cd scranspiration
```
