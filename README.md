# FolioForge

A platform for freelancers and creators to build and showcase professional portfolios.

## Features

- User registration and authentication
- Customizable portfolio creation
- Work samples and client testimonials
- Industry and skill-based filtering
- Responsive design for all devices

## Tech Stack

- Frontend: HTML, CSS (Tailwind), JavaScript
- Backend: PHP
- Database: MySQL

## Setup

1. Clone to your server directory (xampp/htdocs/)
2. Create MySQL database named `portfolio_db`
3. Configure database settings in `config.php` if needed
4. Access via localhost
5. Run `db_update.php` to add required columns

## Core Files

- `index.php`: Home page with filtering
- `about.php`: Team information page
- `register.php`/`login.php`: User authentication
- `create-portfolio.php`: Portfolio creation
- `edit-portfolio.php`: Portfolio management
- `portfolio-template.php`: Portfolio display
- `config.php`: Database configuration
- `db_update.php`: Updates database schema

## Usage

### For Freelancers
1. Register and log in
2. Create your portfolio with work samples and testimonials
3. Specify industry, services, and skills
4. Share with potential clients

### For Clients
- Browse portfolios using industry/skill filters
- View work samples and testimonials
- Contact professionals directly

## Customization

- Edit `css/style.css` to modify color palette
- Modify forms in `create-portfolio.php` to adjust sections
