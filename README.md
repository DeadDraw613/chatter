# Chatter

Chatter is a self-directed full‑stack Laravel single‑page application (SPA) built as part of a homelab environment to explore web application development, API design, and security testing.

The project is intentionally used as a **learning and testing platform**, rather than a production product, and is regularly modified to support experimentation with authentication flows, API endpoints, and common web security scenarios.

---


```
git clone git@github.com:yourusername/chatter.git
cd chatter
```
Make sure to use SSH (or HTTPS with a personal access token) for push/pull access.


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
