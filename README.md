# Tendercare-website

## Simple Tech Stack Diagram

```mermaid
flowchart TD
  User["User"]
  Frontend["Frontend (HTML/CSS/JS)"]
  Backend["Backend (PHP)"]
  Database["Database (SQLite)"]

  User --> Frontend
  Frontend --> Backend
  Backend --> Database
  Backend --> Frontend
  Frontend --> User
```
