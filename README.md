# group-project-03-
HealthFirst

A PHP (MVC architecture) based web platform for tracking personal health, connecting with trainers/doctors, and managing medical records — built for patients, trainers, doctors, and lab staff.

Features:

#Patient
- **Daily Health Log** — log steps, water intake, sleep hours, workout status, and medication status
- **Health Score Calculator** — auto-calculates BMI and a 0–100 health score (sleep, hydration, activity, meds, BMI, blood pressure, blood sugar) with a personalized comment
- **Health History** — view past logs, searchable by date, and downloadable as a PDF report
- **Reminders** — set custom reminders for medicine, water, exercise, and sleep
- **Progress Summary** — dashboard shows average health score with a status message
- **Trainer Booking & Chat** — book a trainer slot, chat during the appointment window, and view assigned workout suggestions/notes
- **Doctor Appointments & Consultation** — book doctor appointments and chat-based consultations
- **Symptom Checker** — basic symptom-based health guidance
- **Vaccination Tracker**
- **Blood Donation** module
- **Emergency / Ambulance Request**
- **Healthcare Facility Finder**
- **Medical Reports** — upload/view lab & medical reports
- **Support Chat** with staff/admin

#Trainer
- Manage available time slots
- View/manage appointments and client list
- View a client's health history & reports before consulting
- Assign workout plans and send notes/feedback
- In-app chat with patients during active sessions

#Doctor
- View today's/ upcoming appointments
- Consultation chat with patients

#Staff/Lab
- Review submitted samples/medical reports

Tech Stack
- **Backend:** PHP (custom MVC framework — no external framework)
- **Database:** MySQL / MariaDB
- **PDF Generation:** [FPDF](http://www.fpdf.org/) (bundled in `/fpdf`)
- **Frontend:** HTML, CSS, JavaScript (server-rendered views)

Project Structure
```
healthchecker/
├── app/
│   ├── bootstrap.php        # App entry: session, config, DB, autoloader
│   ├── config/               # DB & app configuration
│   ├── core/                 # Base Controller, Model, View classes
│   ├── controllers/          # Business logic (one per feature)
│   ├── models/                # DB queries (one per feature)
│   └── views/                 # HTML templates, grouped by feature
├── includes/                  # Helper functions (e.g. crypto helper)
├── fpdf/                       # Third-party PDF library
├── Images/, Uploads/           # User uploads (profile photos, reports, samples)
└── *.php                       # Front controllers (e.g. dailylog.php, history.php)
```


User Roles
`patient`, `trainer`, `doctor`, `staff` — access is role-restricted via `Controller::requireAuth($allowedRoles)`.

Notes
- Health Score is calculated from: sleep hours, steps, water intake, workout status, medication adherence, BMI range, blood pressure, and blood sugar level.
- Health history can be filtered by date and exported to PDF.
