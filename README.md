# Smart Donation and Volunteer Management System for Al-Mukminun Mosque

A comprehensive mosque management platform built with Laravel 8, designed to streamline administration, donations, events, volunteer coordination, and community engagement.

## Features

- **Authentication & User Management** — Register, login, email verification, password reset, role-based access (Admin, Treasurer, Member, Amil)
- **Donation Management** — Record, batch & bulk entry, fund purposes, confirmation/dispute workflow, receipt number generation, donor info encryption
- **Zakat Akad** — Islamic donation contract management with PDF generation
- **Withdrawal Management** — Request, approval/rejection workflow with maker-checker, document uploads
- **Event Management** — Create/manage events, volunteer signup, attendance tracking with bulk operations
- **Volunteer System** — Profiles, skills, transparency page
- **Gamification** — Points, badges, rewards, tier milestones, leaderboard, certificates, and redemption system
- **Reports & Exports** — Financial, donations, events, attendance, and gamification reports in CSV and PDF
- **Notifications** — In-app notification system for key events
- **Settings** — Admin configuration, amil management, registration code system

## Requirements

- PHP ^7.3|^8.0
- Composer
- MySQL or compatible database
- Node.js & NPM (for frontend assets)

## Installation

```bash
git clone https://github.com/mosque2/mosque2.git
cd mosque2
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in `.env`, then:

```bash
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

## Roles

| Role | Description |
|------|-------------|
| **Admin** | Full access — manage donations, events, withdrawals, gamification, settings |
| **Treasurer** | Approve/reject withdrawals, confirm/dispute donations, view reports |
| **Member** | View dashboard, update profile, join events, access gamification |
| **Amil** | Special designation for zakat collection officers |

## License

MIT
