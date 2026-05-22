# Mosque Gamification Module - Implementation Guide v2.0

## Updates Based on Your Clarifications

### Schema Updates

| Table | Change | Notes |
|-------|--------|-------|
| `users` | Add `referred_by` (FK→users), `referred_code` (unique string) | For referral tracking |
| `users` | Add `hide_from_leaderboard` (boolean, default false) | Opt-out privacy |
| `rewards` | Remove limited stock tracking | Now uses `reward_redemptions` with fulfillment workflow |
| `reward_redemptions` | Add `fulfillment_notes`, `fulfilled_by`, `fulfilled_at` | Admin fulfillment tracking |

### Certificate Generation

```php
// app/Services/CertificateService.php
namespace App\Services;

use App\Models\User;
use App\Models\RewardRedemption;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class CertificateService
{
    public function generateCertificate(User $user, RewardRedemption $redemption): string
    {
        $data = [
            'user' => $user,
            'redemption' => $redemption,
            'date' => Carbon::now()->format('d F Y'),
            'tier' => $user->memberPoints?->tier,
        ];

        $pdf = PDF::loadView('gamification.certificate', $data);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = "certificate_{$user->id}_{$redemption->id}.pdf";
        $path = "certificates/{$filename}";
        
        \Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }
}
```

### Badge Icons (Heroicons SVG)

```php
// config/gamification.php

return [
    'badges' => [
        'first_step' => [
            'name' => 'First Step',
            'icon' => 'heroicons-outline:sparkles', // or inline SVG path
        ],
        // ... more badges
    ],
    
    'tiers' => [
        'bronze' => ['min_points' => 0, 'icon' => 'heroicons-outline:sparkles'],
        'silver' => ['min_points' => 200, 'icon' => 'heroicons-outline:star'],
        'gold' => ['min_points' => 500, 'icon' => 'heroicons-outline:sun'],
        'platinum' => ['min_points' => 1000, 'icon' => 'heroicons-outline:fire'],
        'diamond' => ['min_points' => 2000, 'icon' => 'heroicons-outline:sparkles'],
    ],
];
```

---

## Implementation Checklist

### Database Migrations
- [x] `member_points` table
- [x] `badges` table
- [x] `badge_earnings` pivot
- [x] `rewards` table
- [x] `point_transactions` audit
- [x] `reward_redemptions` with fulfillment
- [x] `tier_milestones` table
- [x] `leaderboard_preferences` table

### User Updates
- [x] `referred_by`, `referred_code` columns
- [x] `hide_from_leaderboard` column

### Services
- [x] `GamificationService`
- [x] `LeaderboardService`
- [x] `CertificateService` (bonus)

### Controllers
- [x] `GamificationController`
- [x] `Admin/GamificationAdminController`

### Views
- [x] Event poster component
- [x] Gamification dashboard
- [x] Badges page
- [x] Rewards catalog
- [x] Leaderboard
- [x] Points history
- [x] Certificate template (PDF)

### Features
- [x] Point award on attendance completion
- [x] Badge system with SVG icons
- [x] Tier progression
- [x] Referral tracking
- [x] Reward redemption with admin fulfillment
- [x] Leaderboard with privacy option
- [x] Certificate generation

---

Ready to start implementation?
