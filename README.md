# Multi Crypto Price Converter

A WordPress plugin that displays cryptocurrency prices and performs instant price conversions.

This project demonstrates **senior-level WordPress development** practices, bridging the gap between traditional WordPress architecture and modern software engineering principles. It serves as a portfolio piece showcasing Dependency Injection, SOLID principles, and React-based Gutenberg blocks.

## 🚀 Technical Overview

### Architecture
The plugin is architected to be modular, testable, and scalable. Unlike typical "spaghetti code" plugins, this project treats WordPress as an application framework.

*   **Dependency Injection (DI):** Utilizes `League\Container` to manage dependencies. Classes like `Admin_Settings` and `Price_Rest_Controller` receive their dependencies (e.g., `Crypto_Client_Factory`) via constructor injection, making the codebase decoupled and unit-testable.
*   **SOLID Principles:**
    *   **Single Responsibility:** Distinct classes for Settings, API Clients, Caching, and REST Controllers.
    *   **Open/Closed:** The `Crypto_Client_Factory` allows adding new crypto data sources (e.g., Binance, Coinbase) without modifying existing client logic.
    *   **Interface Segregation:** Clients implement strict interfaces for fetching prices and coin lists.
*   **Modern PHP (8.0+):** Extensively uses strict typing (`declare(strict_types=1)`), typed properties, constructor property promotion, and named arguments.
*   **Caching Strategy:** Implements a caching layer (PSR-16 style) to store API responses, ensuring the site remains fast and stays within API rate limits.

### Tech Stack
*   **Backend:** PHP 8.0+, WordPress Plugin API.
*   **Frontend:** React, TypeScript, WordPress Block Editor (Gutenberg).
*   **Build Tools:** Composer (PHP), NPM/Webpack (JS/Assets).
*   **External API:** CoinGecko (Configurable via Factory pattern).

## 🛠️ Installation & Build

If you are cloning this repository for review or development, follow these steps to build the artifacts.

### Prerequisites
*   PHP 8.0 or higher
*   Composer
*   Node.js & NPM

### Setup
1.  **Clone the repository** into your WordPress plugins directory:
    ```bash
    cd wp-content/plugins
    git clone https://github.com/c24o/multi-crypto-convert-wp-plugin.git
    cd multi-crypto-convert-wp-plugin
    ```

2.  **Install PHP Dependencies:**
    This plugin uses Composer for autoloading and dependency management.
    ```bash
    composer install --no-dev --optimize-autoloader
    ```

3.  **Install & Build Frontend Assets:**
    The React block needs to be compiled.
    ```bash
    npm install
    npm run build
    ```

4.  **Activate:**
    Log in to your WordPress admin panel and activate **Multi Crypto Price Converter**.

## 📂 Project Structure

```text
multi-crypto-convert-wp-plugin/
├── src/
│   ├── blocks/            # React/Gutenberg Block components (TypeScript)
│   ├── includes/          # PHP Classes
│   │   ├── Cache/         # Caching logic
│   │   ├── Clients/       # API Clients (CoinGecko, etc.)
│   │   ├── Controllers/   # REST API Controllers
│   │   ├── Entities/      # DTOs (Data Transfer Objects)
│   │   └── Settings/      # Admin Page logic
│   └── index.php
├── build/                 # Compiled assets (JS/CSS)
├── vendor/                # Composer dependencies
├── multi-crypto-convert.php # Plugin entry point & DI Container setup
├── composer.json          # PHP dependencies
├── package.json           # JS dependencies
└── README.md
```

## 🧪 Code Quality Standards

*   **WordPress Coding Standards (WPCS):** The code follows strict WPCS formatting and best practices.
*   **Security:**
    *   Nonces for verification.
    *   `esc_attr`, `esc_html` for output escaping.
    *   `sanitize_text_field` for input sanitization.
    *   Capability checks (`current_user_can`).
*   **Performance:**
    *   Server-side caching of external API calls.
    *   Block metadata registration for optimal asset loading (WP 6.7+ standards).
