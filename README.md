# LazySpoon Web Application

## Overview
LazySpoon is an AI-assisted recipe web application that scrapes recipes from multiple websites, organizes them into a database, and provides an interactive platform for users to search, rate, and provide feedback on meals. The app is built with PHP, MySQL, and JavaScript, leveraging AI for parsing and structuring recipe data.

## Features
- **AI-powered Web Scraper:** Collects recipes from multiple websites with different DOM structures using OpenAI for HTML parsing.
- **Structured Database:** Stores recipes in MySQL with ingredients, instructions, and metadata.
- **User Interaction:** 
  - Login/Register system for users
  - Meal rating and feedback submission
  - Search by meal name or ingredients
- **Dynamic UI:** Built with JavaScript and PHP for interactive, user-friendly pages.

## Technologies Used
- **Backend:** PHP, MySQL
- **Frontend:** JavaScript, HTML, CSS
- **AI Integration:** OpenAI API for parsing recipe pages
- **Version Control:** Git
- **Deployment:** McMaster server 

## Project Structure
LazySpoon/
├─ index.php # Main entry point
├─ src/ # Core PHP files for backend logic
├─ public/ # HTML, CSS, JS files
├─ database/ # MySQL schema and seed data
├─ tests/ # Unit and integration tests
├─ README.md # Project documentation
└─ .gitignore

## Getting Started
1. Clone the repository:  
```
https://github.com/steph-and-e/lazyspoon.git
```
2. Setup MySQL database using provided schema in database/.

3. Update database credentials in config.php.

4. Run the web server (Apache or built-in PHP server):
```
php -S localhost:8000
```
5. Open the app in your browser: http://localhost:8000

## How it Works
1. Users can search by ingredients or meal names.
2. The scraper fetches page source from recipe websites.
3. OpenAI processes the HTML and returns structured JSON with ingredients, instructions, and metadata.
4. Data is saved in the MySQL database.
5. Users can rate meals, leave feedback, and view detailed cooking instructions.
