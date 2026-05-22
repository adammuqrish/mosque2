# Mosque Test Data Seeding System

This file documents the test data generation mechanism for the mosque management platform.

## Overview

The system generates realistic production-like test data including:
- ~700 users (admins, treasurers, members)
- ~600 volunteer profiles
- ~125 referrals with points
- 120 events
- ~2500 event volunteers with attendance
- 4500 donations
- Point transactions, badges, rewards, and withdrawal requests

## Files Created

| File | Purpose |
|------|---------|
| `app/Console/Commands/GenerateMosqueTestData.php` | CLI command to trigger seeding |
| `database/seeders/RealisticPopulateSeeder.php` | Master seeder with all seeding logic |

Note: Individual factory files in `database/factories/` were created but not used - the seeder uses direct batch inserts for better performance.

## How It Works

### 1. Running the Seeder

Use Laragon Terminal to run:

```bash
php artisan mosque:generate-test-data --seed
```

This calls `RealisticPopulateSeeder` which runs inside a database transaction for atomic execution.

### 2. Seeding Order

The seeder runs in this order (respecting FK dependencies):

1. **seedRoleHierarchyUsers()** - Creates admin, treasurer, and member users
2. **seedVolunteerProfiles()** - Links users to volunteer_profiles table
3. **seedReferralsAndPoints()** - Creates referral codes and initial points
4. **seedEvents()** - Creates 120 events with various categories
5. **seedEventVolunteers()** - Links volunteers to events with attendance
6. **seedDonations()** - Creates 4500 donations linked to users
7. **seedPointTransactions()** - Additional point activities
8. **seedBadgesAndRewards()** - Gamification badges and rewards
9. **seedRewardRedemptions()** - Reward redemptions
10. **seedWithdrawalRequests()** - Treasurer withdrawal requests

### 3. Important Schema Rules

- **donations.user_id** - Required (NOT NULL due to `constrained()`)
- **donations.source** - Must be `'cash'` or `'online'` (enum)
- **donations.category** - Valid: 'zakat', 'sedekah', 'wakaf', 'yasin', 'korban', 'bersuci', 'd娲捐赠', 'maintenance', 'charity'
- **event_volunteer.status** - Must be `'confirmed'`, `'completed'`, or `'absent'`

## How to Add More Data

### Option 1: Modify Existing Counts (Easiest)

The seeder uses hardcoded values. To change amounts:

1. **More users**: Edit line ~66 in seeder (role hierarchy) and line ~91 (loop for members)
2. **More donations**: The loop at line 391 runs 4500 times - change to desired number
3. **More events**: The loop at line ~156 runs 120 times - change to desired number

For example, to create 10,000 donations instead of 4500:

```php
// In seedDonations() method, find:
for ($i = 0; $i < 4500; $i++) {

// Change to:
for ($i = 0; $i < 10000; $i++) {
```

### Option 2: Run with Custom Parameters

Currently the command accepts `--users`, `--events`, `--donations` options but the seeder doesn't use them. To make the seeder use them:

Edit `GenerateMosqueTestData.php` lines 33-35 to pass to seeder, then modify `RealisticPopulateSeeder.php` to accept those values.

### Option 3: Create a New Method

To add new data types:

1. Add new method in `RealisticPopulateSeeder.php`: `protected function seedNewType() { }`
2. Call it in `run()` method: `$this->seedNewType();`
3. Follow the batch insert pattern used in other methods

Example structure for a new seeder method:

```php
protected function seedNewType()
{
    $this->command-> Creating new records...');

    $records = [];
    $startDate = Carbon::now()->subMonths(24);
    
    for ($i = 0; $i < 1000; $i++) {
        $records[] = [
            'field1' => value,
            'field2' => value,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (count($records) >= $this->batchSize) {
            NewModel::insert($records);
            $records = [];
        }
    }

    if (count($records) > 0) {
        NewModel::insert($records);
    }

    $this->command->info('Created new records');
}
```

## Commands Summary

```bash
# Run full seeder (Recommended after fresh database)
php artisan mosque:generate-test-data --seed

# Safe reset (truncates all tables in correct order)
php artisan mosque:generate-test-data --safe-reset

# Validate data after seeding
php artisan mosque:validate-data
```

## Troubleshooting

### Common Errors

1. **"Column 'user_id' cannot be null"** - The donations table requires user_id (edit seeder to always assign a user)

2. **"Data truncated for column 'source'"** - Value not in enum. Valid values are only 'cash' or 'online'

3. **"Integrity constraint violation"** - FK dependency issue. Ensure parent records exist first

4. **PHP errors (undefined variable, etc.)** - Usually typo from editing. Check the variable is defined before use

### Debugging Tips

1. Run with smaller counts first to test
2. Check migration files for exact schema requirements
3. Use `--safe-reset` to clean and start fresh
4. Check Laravel logs in `storage/logs/`

## Database Schema Summary

| Table | Key Dependencies | Notes |
|-------|-----------------|-------|
| users | None | Base table |
| volunteer_profiles | user_id → users | 1:1 with users |
| member_points | user_id → users | 1:1 with users |
| events | created_by → users | Admin-created |
| event_volunteer | user_id, event_id | FK to both |
| donations | user_id → users | Required user_id |
| point_transactions | user_id → users | Points ledger |
| badge_earnings | user_id, badge_id | FK to both |
| reward_redemptions | user_id, reward_id | FK to both |
| withdrawal_requests | treasurer_id → users | Only treasurers |

mysql -u your_user -p mosque < storage/app/backup_withdrawals_20260512_231716.sql