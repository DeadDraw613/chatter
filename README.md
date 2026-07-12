# Chatter

<img width="1038" height="349" alt="Image" src="https://github.com/user-attachments/assets/0db2400b-c179-48c7-a761-9104432f28df" />

---

> [!WARNING]
> Chatter is a Laravel-based chat application built as a learning project. It is not currently intended for production use or third-party installation. There are known defects, security issues, and currently only hosted in a controlled lab environment

### ToDo
---

- [x] Playwright Automation 
- [ ] Postman API test examples 
- [ ] Python Utilities and automation

---

<details>
<summary>Click here to expand advanced logs</summary>

```bash
Error: Something broke behind the scenes.
Traceback (most recent call last)...
```
</details>

---

Chatter is a full‑stack Laravel single‑page application (SPA) built as part of a homelab environment to explore web application development, API design, and security testing. The project is intentionally used as a **learning and testing platform**, rather than a production product, and is regularly modified to support experimentation with authentication flows, API endpoints, and common web security scenarios.

1) Prerequisites
2) Clone the repository
3) Environment configuration (.env)
4) Install dependencies (composer install, npm install)
5) Build assets (npm run build)
6) Database setup (migrations/seeders if applicable)
7) Apache configuration
8) Permissions
9) Verification checklist
10) Troubleshooting (common issues like vite: not found, permission errors, disk full, 302 redirects)


### 1. Development Environment

The development environment uses Laravel's built-in web server via php artisan serve. This is intended only for development and testing.

#### Purpose:
- Active development
- Debugging
- Vite hot reload (if desired)
- Artisan server

```
composer install
npm install
cp .env.example .env
php artisan migrate
php artisan serve --host=192.168.59.131 --port=8000
```

### 2. Test / Lab Server

#### Purpose:
- Integration testing
- Manual QA
- Deployment verification

```
Apache
PHP
DocumentRoot -> /var/www/html/chatter/public
```
No Artisan serve command.
Instead:
```
sudo systemctl status apache2
# or
sudo systemctl restart apache2
```

---
# Configuration

### Clone
```
git clone git@github.com:DeadDraw613/chatter.git
cd chatter

cp .env.example .env
# or copy your existing .env

composer install
npm install
npm run build

php artisan key:generate   # only for a brand-new environment
php artisan migrate

sudo chown -R www-data:www-data storage bootstrap/cache public/uploads
sudo chmod -R 775 storage bootstrap/cache public/uploads
```
Make sure to use SSH (or HTTPS with a personal access token) for push/pull access.

### Dependancies
```
cp .env.example .env
php artisan key:generate
npm install
npm run build  //for prroduction
npm run dev    //for active dev
```

User-uploaded files (profile images, message attachments) are not tracked in Git.
- To keep the directories in Git without tracking files, .gitkeep placeholders are used.
- .gitignore includes:
```
/public/uploads/*
!/public/uploads/.gitkeep
!/public/uploads/profile/.gitkeep
```
Git workflow for keeping directory structure without content files
```
git rm -r --cached public/uploads
git add public/uploads/.gitkeep public/uploads/profile/.gitkeep
git commit -m "Remove user-uploaded images from repo; preserve directory structure"
git push
```
### Serve App in Dev Server
```
php artisan serve --host=0.0.0.0 --port=8000
```
### Access app on lab server
```
http://192.168.70.89
```



---
## ✨ Project Goals

* Build and maintain a real-world Laravel SPA from scratch
* Practice API design, authentication, and authorization
* Explore common web application security vulnerabilities in a controlled environment
* Support automated testing and API experimentation
* Serve as a hands-on companion to security and development coursework

---

## 🏗️ Architecture Overview

* **Framework:** Laravel (PHP)
* **Architecture:** Single Page Application (SPA)
* **Authentication:** JWT-based authentication
* **API:** REST-style endpoints
* **Database:** MySQL / MariaDB (local homelab)
* **Hosting:** Virtualized Linux servers (ESXi)

The application is deployed within a segmented homelab network protected by pfSense, allowing safe testing of network and application-layer behavior.

---

## 🔐 Security & Testing Focus

Chatter is used to explore and understand common web application and API security issues, including:

* SQL Injection (SQLi)
* Insecure Direct Object References (IDOR)
* Local & Remote File Inclusion (LFI / RFI)
* Command Injection
* Authentication and authorization weaknesses
* API input validation and error handling

Testing and analysis tools used alongside the application include:

* Postman (manual and automated API testing)
* Selenium (basic UI automation)
* Wireshark (network traffic analysis)

> ⚠️ **Note:** Vulnerabilities are tested only in isolated lab environments and intentionally vulnerable configurations.

---

## 🧪 Development & Learning Approach

This project is actively used to:

* Reinforce concepts learned through online courses and self-study
* Experiment safely with configuration changes and security controls
* Practice debugging, refactoring, and code review
* Improve understanding of how application and network layers interact

The codebase evolves as new concepts are explored and revisited.

---

## 🚀 Getting Started (Local Development)

High-level setup steps (details may vary by environment):

1. Clone the repository
2. Install PHP dependencies via Composer
3. Configure environment variables (`.env`)
4. Run database migrations
5. Serve the application locally

This repository does **not** include environment-specific configuration or secrets.

---

## 📌 Disclaimer

Chatter is a personal learning project and homelab application. It is not intended for production use and may include experimental or intentionally insecure configurations for educational purposes.

---

## 📄 License

This project is provided for educational and learning purposes. No warranty is expressed or implied.
