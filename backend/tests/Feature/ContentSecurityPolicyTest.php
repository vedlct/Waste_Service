<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\CoverageArea;
use App\Models\CoverageRegion;
use App\Models\ExtraCharge;
use App\Models\Faq;
use App\Models\LoadPackage;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\PriceCategory;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\ServiceContentBlock;
use App\Models\ServiceItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * No browser runs in this environment, so the policy is checked against the rendered HTML
 * of every admin page instead: anything the policy would block fails here first.
 */
class ContentSecurityPolicyTest extends TestCase
{
    public function test_every_admin_page_renders_and_obeys_the_content_security_policy(): void
    {
        $superAdmin = User::query()->create([
            'name' => 'CSP Admin',
            'email' => 'csp-admin-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $booking = Booking::query()->create([
            'reference' => 'MT-TEST-CSP1',
            'status' => 'submitted',
            'payment_status' => 'unpaid',
            'payment_option' => 'pay_on_arrival',
            'collection_date' => now()->addDays(2)->toDateString(),
            'total_pence' => 5000,
        ]);
        $booking->addresses()->create([
            'type' => 'billing', 'first_name' => 'Csp', 'last_name' => 'Check', 'email' => 'csp@example.com',
            'phone' => '02012345678', 'address_line_1' => '1 Test Road', 'city' => 'Portsmouth', 'postcode' => 'PO1 3AX',
        ]);

        $enquiry = ContactEnquiry::query()->create([
            'name' => 'Csp Check', 'email' => 'csp@example.com', 'phone' => '02012345678',
            'message' => 'Checking the policy.', 'status' => 'new', 'source' => 'website',
        ]);

        $block = ServiceContentBlock::query()->has('items')->with(['service', 'items'])->firstOrFail();

        $parameters = [
            'media' => MediaAsset::query()->firstOrFail(),
            'service_category' => ServiceCategory::query()->firstOrFail(),
            'service' => $block->service,
            'block' => $block,
            'item' => $block->items->first(),
            'price_category' => PriceCategory::query()->firstOrFail(),
            'service_item' => ServiceItem::query()->firstOrFail(),
            'load_package' => LoadPackage::query()->firstOrFail(),
            'extra_charge' => ExtraCharge::query()->firstOrFail(),
            'faq' => Faq::query()->firstOrFail(),
            'review' => Review::query()->firstOrFail(),
            'coverage_region' => CoverageRegion::query()->firstOrFail(),
            'coverage_area' => CoverageArea::query()->firstOrFail(),
            'booking' => $booking,
            'enquiry' => $enquiry,
            'user' => $superAdmin,
            'page' => Page::query()->firstOrFail(),
            'group' => 'general',
        ];

        $checked = 0;

        foreach (Route::getRoutes() as $route) {
            $name = (string) $route->getName();

            if (! str_starts_with($name, 'admin.')
                || ! in_array('GET', $route->methods(), true)
                || str_ends_with($name, '.data')
                || str_ends_with($name, '.options')
                || str_ends_with($name, '.export')) {
                continue;
            }

            $url = route($name, array_intersect_key($parameters, array_flip($route->parameterNames())));
            $response = $this->actingAs($superAdmin)->get($url);

            $response->assertOk();
            $this->assertPageObeysPolicy($response, $name);
            $checked++;
        }

        $this->assertGreaterThan(40, $checked, 'Expected to check every admin page.');

        // The sign-in screen is served to guests under the same policy.
        auth()->logout();
        $this->assertPageObeysPolicy($this->get(route('login'))->assertOk(), 'login');
    }

    public function test_json_responses_do_not_carry_the_policy(): void
    {
        $this->getJson(route('api.v1.health'))
            ->assertOk()
            ->assertHeaderMissing('Content-Security-Policy');
    }

    public function test_the_policy_can_run_in_report_only_mode(): void
    {
        config(['security.csp.report_only' => true]);

        $this->get(route('login'))
            ->assertOk()
            ->assertHeaderMissing('Content-Security-Policy')
            ->assertHeader('Content-Security-Policy-Report-Only');
    }

    public function test_the_policy_can_be_switched_off(): void
    {
        config(['security.csp.enabled' => false]);

        $this->get(route('login'))
            ->assertOk()
            ->assertHeaderMissing('Content-Security-Policy');
    }

    public function test_each_request_gets_a_fresh_nonce(): void
    {
        $first = $this->get(route('login'))->headers->get('Content-Security-Policy');
        $second = $this->get(route('login'))->headers->get('Content-Security-Policy');

        $this->assertNotSame($this->nonce($first), $this->nonce($second));
    }

    private function assertPageObeysPolicy(TestResponse $response, string $page): void
    {
        $policy = (string) $response->headers->get('Content-Security-Policy');
        $this->assertNotSame('', $policy, "{$page} has no Content-Security-Policy header.");

        $nonce = $this->nonce($policy);
        $html = (string) $response->getContent();

        $this->assertStringContainsString("frame-ancestors 'self'", $policy);
        $this->assertStringContainsString("object-src 'none'", $policy);

        preg_match_all('/<script\b([^>]*)>/i', $html, $scripts);

        foreach ($scripts[1] as $attributes) {
            if (preg_match('/\bsrc="([^"]+)"/i', $attributes, $src)) {
                $this->assertAllowedSource($src[1], config('security.csp.script_hosts'), $page, 'script');
            } else {
                $this->assertStringContainsString("nonce=\"{$nonce}\"", $attributes, "{$page} has an inline script without the nonce.");
            }
        }

        preg_match_all('/<link\b[^>]*rel="stylesheet"[^>]*>/i', $html, $links);

        foreach ($links[0] as $link) {
            preg_match('/\bhref="([^"]+)"/i', $link, $href);
            $this->assertAllowedSource($href[1] ?? '', config('security.csp.style_hosts'), $page, 'stylesheet');
        }

        // Inline event handlers and javascript: URLs are never allowed under the policy.
        $this->assertDoesNotMatchRegularExpression('/<[^>]+\son[a-z]+\s*=/i', $html, "{$page} has an inline event handler.");
        $this->assertStringNotContainsStringIgnoringCase('href="javascript:', $html, "{$page} has a javascript: link.");
    }

    /**
     * @param array<int, string> $hosts
     */
    private function assertAllowedSource(string $url, array $hosts, string $page, string $kind): void
    {
        if (! preg_match('#^https?://#i', $url)) {
            return;
        }

        $allowed = collect($hosts)->contains(fn (string $host): bool => str_starts_with($url, $host.'/'));

        $this->assertTrue($allowed, "{$page} loads a {$kind} from a host the policy blocks: {$url}");
    }

    private function nonce(?string $policy): string
    {
        $this->assertSame(1, preg_match("/'nonce-([^']+)'/", (string) $policy, $matches), 'The policy has no nonce.');

        return $matches[1];
    }
}
