
# Guest Session Tracking - Setup Guide

## 📋 Installation Steps

### 1. Register the Service Provider

Add to `config/app.php`:

```php
'providers' => [
    // ... other providers
    App\Providers\GuestSessionServiceProvider::class,
],
```

### 2. Register the Middleware

In `app/Http/Kernel.php`, add to the `$middlewareGroups['web']` array:

```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\TrackGuestSession::class,
    ],
];
```

Or register as route middleware in `$middlewareAliases`:

```php
protected $middlewareAliases = [
    // ... other aliases
    'track.guest' => \App\Http\Middleware\TrackGuestSession::class,
];
```

### 3. Create Helper File

Create `app/Helpers/GuestSessionHelper.php` with the helper class provided.

### 4. Autoload Helpers (Optional)

In `composer.json`, add:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Helpers/GuestSessionHelper.php"
    ]
},
```

Then run: `composer dump-autoload`

### 5. Schedule Cleanup Command (Optional)

In `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Clean up expired sessions daily
    $schedule->command('guest-sessions:cleanup')->daily();
}
```

### 6. Configure Cookies for Production

In `.env`, set:

```env
SESSION_SECURE_COOKIE=true  # Use HTTPS only
SESSION_SAME_SITE=lax
```

---

## 🚀 Usage Examples

### 1. Access Current Session in Controllers

```php
use App\Helpers\GuestSessionHelper;

class ProductController extends Controller
{
    public function show($id)
    {
        $guestSession = GuestSessionHelper::current();
        
        // Or get directly from request
        $guestSession = request()->attributes->get('guest_session');
        
        // Your logic here
    }
}
```

### 2. Add Items to Cart

```php
use App\Services\GuestSessionService;

class CartController extends Controller
{
    protected $guestSessionService;
    
    public function __construct(GuestSessionService $service)
    {
        $this->guestSessionService = $service;
    }
    
    public function addToCart(Request $request)
    {
        $guestSession = GuestSessionHelper::current();
        
        $this->guestSessionService->addToCart($guestSession, [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);
        
        return response()->json(['success' => true]);
    }
}
```

### 3. Save Favorites

```php
public function addFavorite($productId)
{
    $guestSession = GuestSessionHelper::current();
    
    $this->guestSessionService->addToFavorites($guestSession, $productId);
    
    return back()->with('success', 'Added to favorites');
}
```

### 4. Access Preferences in Blade

```php
// In your controller
public function index()
{
    $cart = GuestSessionHelper::preferences('cart', []);
    $favorites = GuestSessionHelper::preferences('favorites', []);
    
    return view('products.index', compact('cart', 'favorites'));
}
```

### 5. Update Custom Preferences

```php
use App\Services\GuestSessionService;

public function savePreferences(Request $request, GuestSessionService $service)
{
    $guestSession = GuestSessionHelper::current();
    
    $service->updatePreferences($guestSession, [
        'theme' => $request->theme,
        'language' => $request->language,
        'notifications' => $request->notifications,
    ]);
    
    return response()->json(['success' => true]);
}
```

### 6. Get Session Info

```php
$guestSession = GuestSessionHelper::current();

if ($guestSession) {
    echo "IP: " . $guestSession->ip_address;
    echo "Location: " . $guestSession->city . ", " . $guestSession->country;
    echo "Device: " . $guestSession->device_id;
    echo "Last Active: " . $guestSession->last_activity_at->diffForHumans();
}
```

---

## 🔧 Configuration Options

### Customize Session Duration

In `GuestSessionService.php`:

```php
protected $sessionDuration = 24; // Change to desired hours
```

### Skip Routes

In `TrackGuestSession` middleware, modify `$skipRoutes`:

```php
$skipRoutes = [
    'admin/*',
    'api/*',
    '_debugbar/*',
    'telescope/*',
    'horizon/*',
];
```

### Skip Authenticated Users

Uncomment in `TrackGuestSession` middleware:

```php
if (auth()->check()) {
    return $next($request);
}
```

---

## 📊 Monitoring & Analytics

### View Active Sessions

```php
use App\Models\GuestSession;

// Get active sessions count
$activeSessions = GuestSession::active()->count();

// Get sessions by country
$sessionsByCountry = GuestSession::active()
    ->selectRaw('country, COUNT(*) as count')
    ->groupBy('country')
    ->get();

// Get recent activity
$recentActivity = GuestSession::active()
    ->orderBy('last_activity_at', 'desc')
    ->take(10)
    ->get();
```

### Dashboard Example

```php
public function dashboard()
{
    $stats = [
        'total_active' => GuestSession::active()->count(),
        'total_today' => GuestSession::whereDate('created_at', today())->count(),
        'by_country' => GuestSession::active()
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->take(10)
            ->get(),
    ];
    
    return view('admin.guest-sessions.dashboard', compact('stats'));
}
```

---

## 🔐 Security Considerations

1. **IP Privacy**: Consider GDPR compliance when storing IP addresses
2. **Data Retention**: Implement cleanup policies (use scheduled command)
3. **Cookie Security**: Use HTTPS in production (`secure` flag)
4. **Rate Limiting**: Add rate limiting to prevent abuse
5. **XSS Protection**: Use `httpOnly` flag on cookies (already implemented)

---

## 🐛 Troubleshooting

### Sessions Not Being Created

1. Check middleware is registered in `Kernel.php`
2. Verify database migration has run
3. Check logs: `storage/logs/laravel.log`
4. Ensure cookies are enabled in browser

### Location Data Not Working

1. Check if IP is public (won't work for localhost)
2. Verify internet connection for API calls
3. Check ip-api.com rate limits (45 requests/minute free tier)
4. Consider alternative services: ipstack.com, ipgeolocation.io

### Performance Issues

1. Add database indexes (already included in migration)
2. Use queue jobs for location API calls
3. Cache location data
4. Implement session cleanup regularly

---

## 🔄 Migration to Authenticated Users

When a guest registers/logs in, transfer their session data:

```php
public function register(Request $request)
{
    // Register user logic...
    
    $guestSession = GuestSessionHelper::current();
    
    if ($guestSession) {
        // Transfer cart
        $cart = $guestSession->preferences['cart'] ?? [];
        // Save to user's cart...
        
        // Mark session as inactive
        $guestSession->update(['status' => 'inactive']);
    }
    
    // Continue with login...
}
```

---

## 📚 Additional Features to Consider

1. **Session Merging**: Merge multiple sessions from same device
2. **Anonymous Analytics**: Track page views, clicks without PII
3. **A/B Testing**: Store experiment variants in preferences
4. **Personalization**: Store viewed products, search history
5. **Recovery**: Allow users to recover abandoned carts