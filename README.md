# Skin Care Clinic — Management System

A full-featured clinic management web application built with **Laravel 13** and **Tailwind CSS**. Designed for skin care clinics to manage patients, doctors, appointments, invoices, and reports — all from a single system with role-based access control.

---

## Table of Contents

- [Tech Stack](#tech-stack)
- [Features](#features)
- [Modules](#modules)
- [Role-Based Access](#role-based-access)
- [Installation](#installation)
- [Default Accounts](#default-accounts)
- [Database Schema](#database-schema)
- [Route Reference](#route-reference)
- [Project Structure](#project-structure)
- [Screenshots Overview](#screenshots-overview)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13.x |
| PHP | ^8.3 |
| Frontend | Tailwind CSS (via Vite) |
| Database | SQLite (default) / MySQL |
| Barcode | picqer/php-barcode-generator |
| Charts | Chart.js 4.4 (CDN) |
| Auth | Laravel built-in session auth |

---

## Features

- **Role-based authentication** — Admin, Doctor, Receptionist
- **Patient management** — registration with NIC auto-fill, profile photo (upload or webcam), barcode generation
- **Doctor management** — profiles with specialization, qualification, registration number
- **Treatment catalogue** — reusable treatment list linked to appointments and invoices
- **Appointment scheduling** — interactive time slot picker, daily calendar view, booking receipts
- **Invoicing** — multi-line invoices with discount, payment tracking, print-ready A4 layout
- **Reports** — Daily Summary, Monthly Revenue (with charts), Outstanding Balances — all printable
- **Treatment progress photos** — before/after photo tracking per patient per treatment
- **Sidebar navigation** — responsive with mobile hamburger, active state highlighting
- **Flash messages** — success/error banners on all actions

---

## Modules

### 1. Authentication
- Split-screen login page (branded left panel + form right)
- Session-based auth with remember me
- Role badge displayed in top bar and sidebar
- Inactive account blocking

### 2. Dashboard
- Greeting banner with time-of-day message
- 6 live stat cards (patients, doctors, treatments, appointments, invoices, revenue)
- Module cards linking to all sections
- Quick action buttons (role-aware — only shows what the user can do)
- Recent patients mini list

### 3. Patient Management
- Register with full personal details, NIC (auto-fills DOB + gender for Sri Lankan NICs), skin type, allergies, medical history
- Profile photo via file upload or live webcam capture
- Auto-generated patient ID (`SCC-0001` format)
- SVG barcode generated on registration, printable
- Patient profile page with:
  - Medical information
  - Emergency contact
  - Treatment history (all appointments grouped by treatment)
  - Before/after progress photos
  - Barcode card with print/regenerate

### 4. Doctor Management
- Register with specialization, qualification, medical council registration number, experience
- Status: Active / Inactive / On Leave
- Auto-generated doctor ID (`DOC-0001` format)
- Profile view with all details

### 5. Treatment Catalogue
- Simple name + description list
- Active/inactive toggle
- Used as line items in invoices and linked to appointments

### 6. Appointment Scheduling
- Select patient → doctor → treatment (optional) → date → time slot
- Time slot grid (08:00–17:00, 30-min intervals) — booked slots greyed out in real time via AJAX
- Auto-generated booking number (daily counter, resets each day: `1, 2, 3…`)
- Daily view grouped by doctor with appointment count per doctor
- Date navigator (prev/next/today + date picker)
- Status management: Scheduled → Completed / Cancelled / No Show
- Appointment history with date-range, doctor, status, search filters
- Printable appointment receipt with patient barcode

### 7. Invoicing
- Select patient → link to appointment (optional) → add line items
- Each line item: treatment (dropdown), description, quantity, unit price
- Live calculation: subtotal → discount (fixed Rs. or %) → total → paid → balance
- Status auto-set: Draft / Partial / Paid based on paid amount
- Payment methods: Cash, Card, Bank Transfer, Other
- Invoice history with search, status, date range filters
- Revenue stats: total invoiced, collected, outstanding
- Printable A4 invoice with patient barcode

### 8. Reports *(Admin only)*

#### Daily Summary
- Date navigator
- Stats: appointments (by status), revenue collected, invoices issued, new patients
- Payment method breakdown table
- Full appointments list for the day
- Full invoices table with totals
- New patients registered
- Printable A4 report

#### Monthly Revenue
- Month/year selector with prev/next navigation
- Stats: revenue collected, invoices, appointments, new patients
- Daily revenue bar chart + invoiced line overlay (Chart.js)
- 12-month revenue trend line chart
- Top 5 treatments by revenue with progress bars
- Printable A4 report with all tables and treatment breakdown

#### Outstanding Balances
- Summary: total outstanding, patients owing, overdue 7+ days, overdue 30+ days
- Filterable by search and overdue age (7/14/30/60 days)
- Age-coded rows: white (<7d), yellow (7–29d), red (30+d)
- Printable A4 report

---

## Role-Based Access

| Feature | Admin | Doctor | Receptionist |
|---|:---:|:---:|:---:|
| Dashboard | ✅ | ✅ | ✅ |
| View Patients | ✅ | ✅ | ✅ |
| Register / Edit Patients | ✅ | ❌ | ✅ |
| View Doctors | ✅ | ✅ | ✅ |
| Register / Edit Doctors | ✅ | ❌ | ❌ |
| View Treatments | ✅ | ✅ | ✅ |
| Create / Edit Treatments | ✅ | ✅ | ❌ |
| View Appointments | ✅ | ✅ | ✅ |
| Book / Edit Appointments | ✅ | ❌ | ✅ |
| View Invoices | ✅ | ✅ | ✅ |
| Create Invoices | ✅ | ❌ | ✅ |
| Cancel Invoices | ✅ | ❌ | ❌ |
| Reports | ✅ | ❌ | ❌ |

---

## Installation

### Requirements
- PHP >= 8.3
- Composer
- Node.js & npm
- SQLite (default) or MySQL

### Steps

```bash
# 1. Clone the repository
git clone <repository-url>
cd skin_care_clinic

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# Default uses SQLite — no changes needed
# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=skin_care_clinic
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. Seed default users
php artisan db:seed

# 9. Create storage symlink
php artisan storage:link

# 10. Build frontend assets
npm run build

# 11. Start the development server
php artisan serve
```

Visit `http://127.0.0.1:8000` and sign in with one of the default accounts below.

---

## Default Accounts

| Role | Email | Password |
|---|---|---|
| Administrator | admin@clinic.com | password |
| Doctor | doctor@clinic.com | password |
| Receptionist | receptionist@clinic.com | password |

> **Important:** Change these passwords immediately in a production environment.

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| password | string | hashed |
| role | enum | admin, doctor, receptionist |
| is_active | boolean | default true |

### `patients`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| patient_id | string | unique, e.g. SCC-0001 |
| full_name | string | |
| date_of_birth | date | |
| gender | enum | male, female, other |
| phone | string | |
| email | string | nullable, unique |
| address | text | |
| nic | string | unique |
| emergency_contact_name | string | |
| emergency_contact_phone | string | |
| skin_type | enum | normal, dry, oily, combination, sensitive |
| known_allergies | text | nullable |
| medical_history | text | nullable |
| profile_photo | string | nullable, storage path |
| barcode_value | string | nullable |
| barcode_svg | text | nullable |
| is_active | boolean | default true |

### `doctors`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| doctor_id | string | unique, e.g. DOC-0001 |
| full_name | string | |
| date_of_birth | date | |
| gender | enum | male, female, other |
| phone | string | |
| email | string | nullable, unique |
| nic | string | unique |
| specialization | string | |
| qualification | string | e.g. MBBS, MD |
| registration_number | string | unique |
| experience_years | integer | |
| bio | text | nullable |
| profile_photo | string | nullable |
| status | enum | active, inactive, on_leave |

### `treatments`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| name | string | |
| description | text | nullable |
| is_active | boolean | default true |

### `appointments`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| booking_number | string | unique, daily counter |
| patient_id | FK | → patients |
| doctor_id | FK | → doctors |
| treatment_id | FK | nullable → treatments |
| appointment_date | date | |
| appointment_time | time | |
| status | enum | scheduled, completed, cancelled, no_show |
| notes | text | nullable |
| unique constraint | | doctor_id + date + time |

### `invoices`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| invoice_number | string | unique, INV-YYYYMMDD-NNNN |
| patient_id | FK | → patients |
| appointment_id | FK | nullable → appointments |
| subtotal | decimal(10,2) | |
| discount | decimal(10,2) | fixed amount |
| discount_percent | decimal(5,2) | |
| total | decimal(10,2) | |
| paid_amount | decimal(10,2) | |
| balance | decimal(10,2) | |
| payment_method | enum | cash, card, bank_transfer, other |
| status | enum | draft, paid, partial, cancelled |
| notes | text | nullable |

### `invoice_items`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| invoice_id | FK | → invoices |
| treatment_id | FK | nullable → treatments |
| description | string | |
| quantity | integer | |
| unit_price | decimal(10,2) | |
| line_total | decimal(10,2) | |

### `patient_treatment_photos`
| Column | Type | Notes |
|---|---|---|
| id | bigint | PK |
| patient_id | FK | → patients |
| treatment_id | FK | → treatments |
| photo_path | string | storage path |
| taken_on | date | |
| notes | text | nullable |

---

## Route Reference

### Auth
| Method | URI | Description |
|---|---|---|
| GET | /login | Login page |
| POST | /login | Submit login |
| POST | /logout | Logout |

### Dashboard
| Method | URI | Description |
|---|---|---|
| GET | / | Dashboard |

### Patients
| Method | URI | Access | Description |
|---|---|---|---|
| GET | /patients | All | List patients |
| GET | /patients/create | Admin, Receptionist | Registration form |
| POST | /patients | Admin, Receptionist | Store patient |
| GET | /patients/{id} | All | Patient profile |
| GET | /patients/{id}/edit | Admin, Receptionist | Edit form |
| PUT | /patients/{id} | Admin, Receptionist | Update patient |
| DELETE | /patients/{id} | Admin, Receptionist | Deactivate |
| POST | /patients/{id}/barcode | Admin, Receptionist | Regenerate barcode |
| GET | /patients/{id}/treatment-photos/add | All | Add photo form |
| POST | /patients/{id}/treatment-photos | All | Store photo |
| DELETE | /patients/{id}/treatment-photos/{photo} | All | Delete photo |
| GET | /patients/{id}/treatment-photos/compare | All | Compare photos |

### Doctors
| Method | URI | Access | Description |
|---|---|---|---|
| GET | /doctors | All | List doctors |
| GET | /doctors/create | Admin | Registration form |
| POST | /doctors | Admin | Store doctor |
| GET | /doctors/{id} | All | Doctor profile |
| GET | /doctors/{id}/edit | Admin | Edit form |
| PUT | /doctors/{id} | Admin | Update doctor |
| DELETE | /doctors/{id} | Admin | Deactivate |

### Treatments
| Method | URI | Access | Description |
|---|---|---|---|
| GET | /treatments | All | List treatments |
| GET | /treatments/create | Admin, Doctor | Create form |
| POST | /treatments | Admin, Doctor | Store treatment |
| GET | /treatments/{id} | All | View treatment |
| GET | /treatments/{id}/edit | Admin, Doctor | Edit form |
| PUT | /treatments/{id} | Admin, Doctor | Update |
| DELETE | /treatments/{id} | Admin, Doctor | Delete |

### Appointments
| Method | URI | Access | Description |
|---|---|---|---|
| GET | /appointments | All | Daily view |
| GET | /appointments/history | All | History list |
| GET | /appointments/create | Admin, Receptionist | Booking form |
| POST | /appointments | Admin, Receptionist | Store booking |
| GET | /appointments/booked-slots | All | JSON: booked slots |
| GET | /appointments/{id} | All | Detail view |
| GET | /appointments/{id}/receipt | All | Print receipt |
| GET | /appointments/{id}/edit | Admin, Receptionist | Edit form |
| PUT | /appointments/{id} | Admin, Receptionist | Update |
| PATCH | /appointments/{id}/status | Admin, Receptionist | Quick status update |

### Invoices
| Method | URI | Access | Description |
|---|---|---|---|
| GET | /invoices | All | Invoice history |
| GET | /invoices/create | Admin, Receptionist | Create form |
| POST | /invoices | Admin, Receptionist | Store invoice |
| GET | /invoices/patient-appointments | All | JSON: patient appointments |
| GET | /invoices/{id} | All | Invoice detail |
| GET | /invoices/{id}/print | All | Print invoice |
| DELETE | /invoices/{id} | Admin | Cancel invoice |

### Reports *(Admin only)*
| Method | URI | Description |
|---|---|---|
| GET | /reports/daily | Daily summary |
| GET | /reports/daily/print | Printable daily report |
| GET | /reports/monthly | Monthly revenue |
| GET | /reports/monthly/print | Printable monthly report |
| GET | /reports/outstanding | Outstanding balances |
| GET | /reports/outstanding/print | Printable outstanding report |

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AppointmentController.php
│   │   ├── DoctorController.php
│   │   ├── InvoiceController.php
│   │   ├── PatientController.php
│   │   ├── PatientTreatmentPhotoController.php
│   │   ├── ReportController.php
│   │   └── TreatmentController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── Appointment.php
│   ├── Doctor.php
│   ├── Invoice.php
│   ├── InvoiceItem.php
│   ├── Patient.php
│   ├── PatientTreatmentPhoto.php
│   ├── Treatment.php
│   └── User.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_patients_table.php
│   ├── add_barcode_to_patients_table.php
│   ├── create_treatments_table.php
│   ├── create_patient_treatment_photos_table.php
│   ├── create_doctors_table.php
│   ├── add_role_to_users_table.php
│   ├── create_appointments_table.php
│   └── create_invoices_table.php
└── seeders/
    └── DatabaseSeeder.php          ← seeds 3 default users

resources/views/
├── layouts/
│   └── app.blade.php               ← sidebar + top bar layout
├── auth/
│   └── login.blade.php             ← split-screen login
├── welcome.blade.php               ← dashboard
├── patients/
│   ├── index, create, edit, show
├── doctors/
│   ├── index, create, edit, show
├── treatments/
│   ├── index, create, edit, show
├── appointments/
│   ├── index, create, edit, show, history, receipt
├── invoices/
│   ├── index, create, show, print
└── reports/
    ├── daily, monthly, outstanding
    └── print/
        ├── daily, monthly, outstanding

routes/
└── web.php

bootstrap/
└── app.php                         ← RoleMiddleware alias registered here
```

---

## Screenshots Overview

| Page | Description |
|---|---|
| Login | Split-screen with branded left panel and role access badges |
| Dashboard | Greeting banner, 6 stat cards, module cards, quick actions, recent patients |
| Patients | Searchable paginated table with skin type badges |
| Patient Profile | Photo, barcode, medical info, treatment history, progress photos |
| Doctors | Filterable list with status badges |
| Appointments | Daily calendar grouped by doctor, time slot grid booking form |
| Invoices | Multi-line invoice builder with live totals, A4 print layout |
| Daily Report | Stats, appointments, invoices, payment breakdown — printable |
| Monthly Report | Revenue charts (bar + line + trend), top treatments — printable |
| Outstanding | Age-coded balance table with overdue summary — printable |

---

## Key Design Decisions

**No external auth package** — Auth is built from scratch using Laravel's built-in `Auth` facade and session handling. This keeps the codebase simple and avoids Breeze/Jetstream overhead.

**Role middleware** — A single `RoleMiddleware` registered as `role` alias handles all access control. Usage: `->middleware('role:admin,receptionist')`.

**Static routes before wildcards** — All `create` and other static routes are defined before `{model}` wildcard routes to prevent Laravel matching `/doctors/create` as `doctors/{doctor}` with `doctor=create`.

**Booking number resets daily** — The appointment booking number is a simple integer counter (`1, 2, 3…`) that resets each day by querying the max booking number for today's date.

**Barcode stored as SVG** — Patient barcodes are generated once and stored as SVG text in the database, avoiding re-generation on every page load.

**No JavaScript framework** — All interactivity (webcam, NIC parser, time slot picker, invoice live totals) is vanilla JavaScript. No Vue, React, or Alpine.

---

## License

MIT — free to use and modify for personal or commercial clinic projects.

---

*Built with Laravel 13 · Tailwind CSS · Chart.js*
