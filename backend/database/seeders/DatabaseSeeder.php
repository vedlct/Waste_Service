<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->guardProduction();

        $now = now();

        $adminId = $this->admin($now);
        $media = $this->media($now, $adminId);
        $this->settings($now, $adminId);
        $pages = $this->pages($now, $adminId, $media);
        $serviceCategories = $this->serviceCategories($now);
        $services = $this->services($now, $pages, $serviceCategories, $media);
        $priceCategories = $this->priceCategories($now, $media);
        $this->serviceItems($now, $priceCategories, $media);
        $this->loadPackages($now);
        $this->extraCharges($now);
        $this->coverageAreas($now);
        $this->faqs($now);
        $this->reviews($now, $services);
    }

    /**
     * The catalogue sections below upsert by slug, so re-seeding a live site would reset
     * every price, service, FAQ and review an admin has edited. Seeding production is only
     * allowed on an empty database, or when explicitly forced for a one-off.
     */
    private function guardProduction(): void
    {
        if (! app()->isProduction() || ! DB::table('users')->exists()) {
            return;
        }

        if (! filter_var(env('ALLOW_PRODUCTION_SEED', false), FILTER_VALIDATE_BOOLEAN)) {
            throw new RuntimeException(
                'Refusing to seed a production database that already has data: it would overwrite '
                .'catalogue edits made in the admin. Set ALLOW_PRODUCTION_SEED=true for a deliberate one-off.'
            );
        }
    }

    /**
     * Creates the first super admin only when the account does not exist yet. An existing
     * account's password is never touched, so re-seeding cannot reset it.
     */
    private function admin($now): int
    {
        $email = 'admin@mrtee.local';

        if (! DB::table('users')->where('email', $email)->exists()) {
            $password = env('SEED_ADMIN_PASSWORD');

            if (blank($password) && app()->isProduction()) {
                throw new RuntimeException('Set SEED_ADMIN_PASSWORD before seeding production; the default password is for local use only.');
            }

            DB::table('users')->insert([
                'name' => 'MR. TEE Admin',
                'email' => $email,
                'password' => Hash::make($password ?: 'password'),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return (int) DB::table('users')->where('email', $email)->value('id');
    }

    private function media($now, int $adminId): array
    {
        $paths = [
            'logo' => '/images/MainLogo.png',
            'footer_logo' => '/images/footerLogo.png',
            'home_hero' => '/images/HeroImage.jpg',
            'house_clearance' => '/images/Garbage.jpg',
            'garden_clearance' => '/images/GardenHero.jpg',
            'flat_clearance' => '/images/FlatWaste.jpg',
            'garage_clearance' => '/images/garage.jpg',
            'furniture_clearance' => '/images/furnitureHero.jpg',
            'builders_waste_removal' => '/images/BuildersWaste.jpg',
            'junk_collection' => '/images/junkHero.jpg',
            'wait_load' => '/images/LoadHero.jpg',
            'office_waste_clearance' => '/images/OfficeWaste.jpg',
            'fly_tipping_clearance' => '/images/flyTipping.jpg',
            'warehouse_clearance' => '/images/warehouse.jpg',
            'hotel_pub_clearance' => '/images/hotelHero.jpg',
            'restaurant_clearance' => '/images/RestaurantWaste.jpg',
            'shop_strip_out_clearance' => '/images/shopHero.jpg',
            'window_cleaning' => '/images/window.jpg',
            'communal_area_cleaning' => '/images/communal.jpg',
            'ground_maintenance' => '/images/groundCleaning.jpg',
            'lawn_mowing' => '/images/Lawn.jpg',
            'hedge_cutting' => '/images/hedge.jpg',
            'man_van' => '/images/option1.jpg',
            'sofas' => '/images/option2.jpg',
            'mattress_bed' => '/images/option3.jpg',
            'furniture' => '/images/option4.jpg',
            'kitchen_appliances' => '/images/option5.jpg',
            'fridge_freezer' => '/images/option6.jpg',
            'electrical_it' => '/images/option7.jpg',
            'garden_items' => '/images/option8.jpg',
            'hazardous_waste' => '/images/option9.jpg',
            'office_items' => '/images/option10.jpg',
            'commercial_items' => '/images/option11.jpg',
            'bins_wheelie_bins' => '/images/option12.jpg',
        ];

        $ids = [];

        foreach ($paths as $key => $path) {
            DB::table('media_assets')->updateOrInsert(
                ['disk' => 'public', 'path' => $path],
                [
                    'uploaded_by' => $adminId,
                    'original_name' => basename($path),
                    'mime_type' => $this->mimeType($path),
                    'alt_text' => Str::headline($key),
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );

            $ids[$key] = (int) DB::table('media_assets')->where('disk', 'public')->where('path', $path)->value('id');
        }

        return $ids;
    }

    private function settings($now, int $adminId): void
    {
        // Setting values are admin-managed and never overwritten on re-seed. The exceptions are
        // defaults the first build got wrong, corrected only while still untouched: the London
        // location, a placeholder example.com email, and 24/7 hours that contradicted the
        // "six days a week" copy on the site.
        foreach ([
            'location' => ['London, United Kingdom', 'Portsmouth, United Kingdom'],
            'email' => ['info@example.com', 'info@wasteservices.com'],
            'opening_hours' => ['Available 24/7', 'Mon–Sat, 7:00am–7:00pm'],
        ] as $key => [$wrong, $right]) {
            DB::table('site_settings')
                ->where('group', 'contact')
                ->where('key', $key)
                ->whereRaw('JSON_UNQUOTE(`value`) = ?', [$wrong])
                ->update(['value' => json_encode($right), 'updated_at' => $now]);
        }

        $settings = [
            ['general', 'site_name', 'MR. TEE Removals', 'string', 'Site name', 'Shown in the browser title and across the website.', true],
            ['contact', 'phone', '020 8226 6477', 'string', 'Primary phone', 'Displayed in the header, footer, and contact page.', true],
            ['contact', 'email', 'info@wasteservices.com', 'email', 'Primary email', 'Receives contact enquiries and booking confirmations.', true],
            ['contact', 'location', 'Portsmouth, United Kingdom', 'string', 'Location', 'Service base shown on the contact page.', true],
            ['contact', 'opening_hours', 'Mon–Sat, 7:00am–7:00pm', 'string', 'Opening hours', 'Free text opening hours summary.', true],
            ['booking', 'saturday_collection_surcharge_pence', 5000, 'integer', 'Saturday collection surcharge', 'Added to bookings collected on a Saturday.', true],
            ['booking', 'pay_on_arrival_callout_fee_pence', 2500, 'integer', 'Pay on arrival callout fee', 'Charged when the customer pays the crew on arrival.', true],
            ['seo', 'default_meta_title', 'MR. TEE Removals', 'string', 'Default SEO title', 'Used when a page has no meta title of its own.', true],
            ['seo', 'default_meta_description', 'Waste collection, rubbish removal, clearance and cleaning services.', 'string', 'Default SEO description', 'Used when a page has no meta description of its own.', true],
            ['seo', 'default_share_image_url', null, 'url', 'Default share image URL', 'Full address of the image shown when a page without its own share image is shared. Copy it from the Media Library; 1200 x 630 pixels works best.', true],
            ['seo', 'google_site_verification', null, 'string', 'Google verification code', 'The content value of the google-site-verification tag from Google Search Console.', true],
            ['seo', 'bing_site_verification', null, 'string', 'Bing verification code', 'The content value of the msvalidate.01 tag from Bing Webmaster Tools.', true],
            ['seo', 'twitter_handle', null, 'string', 'X (Twitter) handle', 'Credited on shared link cards, for example @mrteeremovals.', true],
            ['business', 'street_address', null, 'string', 'Street address', 'Used in the search engine business listing. Leave blank if the business has no public premises.', true],
            ['business', 'locality', 'Portsmouth', 'string', 'Town or city', 'Used in the search engine business listing.', true],
            ['business', 'region', 'Hampshire', 'string', 'County', 'Used in the search engine business listing.', true],
            ['business', 'postal_code', null, 'string', 'Postcode', 'Used in the search engine business listing.', true],
            ['business', 'country_code', 'GB', 'string', 'Country code', 'Two letter country code.', true],
            ['business', 'opening_hours_spec', 'Mo-Sa 07:00-19:00', 'string', 'Opening hours (for search engines)', 'Machine readable, for example Mo-Sa 07:00-19:00 or Mo-Fr 08:00-18:00, Sa 09:00-13:00. The friendly version is in Contact.', true],
            ['business', 'price_range', '££', 'string', 'Price range', 'Shown in some search listings, for example ££.', true],
            ['social', 'facebook_url', null, 'url', 'Facebook URL', 'Full profile URL. Leave blank to hide the link.', true],
            ['social', 'instagram_url', null, 'url', 'Instagram URL', 'Full profile URL. Leave blank to hide the link.', true],
            ['social', 'linkedin_url', null, 'url', 'LinkedIn URL', 'Full profile URL. Leave blank to hide the link.', true],
            ['social', 'x_url', null, 'url', 'X (Twitter) URL', 'Full profile URL. Leave blank to hide the link.', true],
            // Private: these drive outgoing email and must never reach the public API.
            ['notifications', 'office_email', null, 'email', 'Office alert email', 'Where new enquiry, booking, and review alerts are sent. Leave blank to use the primary contact email.', false],
            ['notifications', 'notify_new_enquiry', true, 'boolean', 'Email the office about new enquiries', 'Sent when the contact form is submitted.', false],
            ['notifications', 'notify_new_booking', true, 'boolean', 'Email the office about new bookings', 'Sent when a booking is placed online.', false],
            ['notifications', 'notify_new_review', true, 'boolean', 'Email the office about new reviews', 'Sent when a visitor submits a review for moderation.', false],
            ['notifications', 'send_customer_booking_confirmation', true, 'boolean', 'Email customers a booking confirmation', 'Sends the booking reference and summary to the billing email address.', false],
            ['notifications', 'send_customer_status_updates', true, 'boolean', 'Email customers when a booking is confirmed, scheduled or cancelled', 'The office can still untick the email on each change.', false],
        ];

        foreach ($settings as [$group, $key, $value, $type, $label, $helpText, $isPublic]) {
            $exists = DB::table('site_settings')->where('group', $group)->where('key', $key)->exists();

            if ($exists) {
                // Values and visibility are admin-managed, so re-seeding only refreshes the descriptive columns.
                DB::table('site_settings')
                    ->where('group', $group)
                    ->where('key', $key)
                    ->update([
                        'type' => $type,
                        'label' => $label,
                        'help_text' => $helpText,
                        'updated_at' => $now,
                    ]);

                continue;
            }

            DB::table('site_settings')->insert([
                'group' => $group,
                'key' => $key,
                'type' => $type,
                'value' => json_encode($value),
                'label' => $label,
                'help_text' => $helpText,
                'is_public' => $isPublic,
                'updated_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function pages($now, int $adminId, array $media): array
    {
        $pages = [
            ['home', '/', 'Home', 'home', 'home_hero'],
            ['prices', '/prices', 'Prices & Booking', 'prices', 'man_van'],
            ['checkout', '/checkout', 'Checkout', 'checkout', null],
            ['payment', '/payment', 'Payment', 'payment', null],
            ['faq', '/faq', 'Frequently Asked Questions', 'faq', null],
            ['contact-us', '/contactUs', 'Contact Us', 'contact', null],
            ['areas', '/area', 'Areas We Cover', 'areas', null],
        ];

        foreach ($this->serviceData() as $service) {
            $pages[] = [$service['slug'], $service['route'], $service['name'], 'service', $service['media']];
        }

        $ids = [];

        foreach ($pages as $index => [$slug, $route, $title, $template, $mediaKey]) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $slug],
                [
                    'hero_media_id' => $mediaKey ? ($media[$mediaKey] ?? null) : null,
                    'route_path' => $route,
                    'title' => $title,
                    'navigation_label' => $title,
                    'template' => $template,
                    'meta_title' => $title . ' | MR. TEE Removals',
                    'meta_description' => $template === 'service'
                        ? 'Learn more about ' . strtolower($title) . ' and book a collection online.'
                        : 'MR. TEE Removals website page.',
                    'status' => 'published',
                    'is_indexable' => ! in_array($template, ['checkout', 'payment'], true),
                    'is_followable' => ! in_array($template, ['checkout', 'payment'], true),
                    'sort_order' => $index + 1,
                    'published_at' => $now,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'deleted_at' => null,
                ],
            );

            $ids[$slug] = (int) DB::table('pages')->where('slug', $slug)->value('id');
        }

        return $ids;
    }

    private function serviceCategories($now): array
    {
        foreach ([
            ['rubbish-removal', 'Rubbish Removal'],
            ['commercial-waste', 'Commercial Waste'],
            ['cleaning', 'Cleaning'],
            ['garden-services', 'Garden Services'],
        ] as $index => [$slug, $name]) {
            DB::table('service_categories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }

        return DB::table('service_categories')->pluck('id', 'slug')->map(fn ($id) => (int) $id)->all();
    }

    private function services($now, array $pages, array $categories, array $media): array
    {
        $ids = [];

        foreach ($this->serviceData() as $index => $service) {
            DB::table('services')->updateOrInsert(
                ['slug' => $service['slug']],
                [
                    'service_category_id' => $categories[$service['category']] ?? null,
                    'page_id' => $pages[$service['slug']] ?? null,
                    'hero_media_id' => $media[$service['media']] ?? null,
                    'name' => $service['name'],
                    'short_name' => $service['short'] ?? $service['name'],
                    'route_path' => $service['route'],
                    'headline' => $service['headline'],
                    'summary' => $service['summary'],
                    'description' => $service['description'],
                    'status' => 'published',
                    'is_featured' => $index < 8,
                    'is_bookable' => true,
                    'sort_order' => $index + 1,
                    'published_at' => $now,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'deleted_at' => null,
                ],
            );

            $serviceId = (int) DB::table('services')->where('slug', $service['slug'])->value('id');
            $ids[$service['slug']] = $serviceId;
            $this->serviceBlocks($now, $serviceId, $service);
        }

        return $ids;
    }

    private function serviceBlocks($now, int $serviceId, array $service): void
    {
        DB::table('service_content_blocks')->updateOrInsert(
            ['service_id' => $serviceId, 'block_key' => 'overview'],
            [
                'component' => 'service_overview',
                'eyebrow' => Str::headline($service['category']),
                'heading' => $service['headline'],
                'body' => $service['description'],
                'is_enabled' => true,
                'sort_order' => 1,
                'updated_at' => $now,
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ],
        );

        $blockId = (int) DB::table('service_content_blocks')->where('service_id', $serviceId)->where('block_key', 'overview')->value('id');

        foreach ($service['items'] as $index => $item) {
            DB::table('service_block_items')->updateOrInsert(
                ['service_content_block_id' => $blockId, 'title' => $item],
                [
                    'is_enabled' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }
    }

    private function priceCategories($now, array $media): array
    {
        $categories = [
            ['sofas', 'Sofas', 'Bulky furniture', 'sofas'],
            ['mattress-bed', 'Mattress & Bed', 'Bedroom items', 'mattress_bed'],
            ['furniture', 'Furniture', 'Home clearance', 'furniture'],
            ['kitchen-appliances', 'Kitchen Appliances', 'Large appliances', 'kitchen_appliances'],
            ['fridge-freezer', 'Fridge & Freezer', 'Responsible disposal', 'fridge_freezer'],
            ['electrical-it', 'Electrical & IT', 'Electrical waste', 'electrical_it'],
            ['garden-items', 'Garden Items', 'Outdoor clearance', 'garden_items'],
            ['hazardous-waste', 'Hazardous Waste', 'Specialist handling', 'hazardous_waste'],
            ['office-items', 'Office Items', 'Workplace clearance', 'office_items'],
            ['commercial-items', 'Commercial Items', 'Business waste', 'commercial_items'],
            ['bins-wheelie-bins', 'Bins & Wheelie Bins', 'Everyday waste', 'bins_wheelie_bins'],
        ];

        foreach ($categories as $index => [$slug, $name, $eyebrow, $mediaKey]) {
            DB::table('price_categories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'eyebrow' => $eyebrow,
                    'image_id' => $media[$mediaKey] ?? null,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }

        return DB::table('price_categories')->pluck('id', 'slug')->map(fn ($id) => (int) $id)->all();
    }

    private function serviceItems($now, array $categories, array $media): void
    {
        $items = [
            ['sofas', 'sofa-2seater', '2 Seater Sofa / Chaise Lounge', 'Standard 2 seater, love seat, chaise lounge or wicker sofa.', 7500],
            ['sofas', 'sofa-3piece', '3 Piece Suite', '1 armchair, 1 two-seater sofa, 1 three-seater sofa and 1 footstool.', 22000],
            ['sofas', 'sofa-3seater', '3 Seat Sofa', 'Standard 3 seater, large chaise longue or wicker sofa.', 9500],
            ['sofas', 'armchair', 'Armchair', 'Wooden, fabric, wicker or leather chair.', 5000],
            ['sofas', 'l-shape-corner-sofa', 'L Shape Corner Sofa', '4-seater sofa or corner sofa.', 10000],
            ['sofas', 'sofa-bed', 'Sofa Bed', '2 seater sofa bed or 3 seater sofa bed.', 10000],
            ['mattress-bed', 'mattress-single', 'Single Mattress', 'Standard single size, any material.', 5000],
            ['mattress-bed', 'mattress-double-king', 'Double / King Mattress', 'Double, king or super king size.', 6000],
            ['mattress-bed', 'bed-frame', 'Bed Frame', 'Wooden or metal frame, single to king.', 4000],
            ['mattress-bed', 'divan-base', 'Divan Base', 'Includes storage divan bases.', 4500],
            ['mattress-bed', 'queen-size-mattress', 'Queen Size Mattress', 'Queen size mattress.', 9000],
            ['mattress-bed', 'headboard', 'Headboard', 'Bedroom headboard.', 2500],
            ['furniture', 'wardrobe', 'Wardrobe', 'Single, double or triple door.', 5500],
            ['furniture', 'chest-drawers', 'Chest of Drawers', 'Any size; wood, veneer or flat-pack.', 3000],
            ['furniture', 'dining-table', 'Dining Table', 'Seats 4-8, with or without chairs.', 4500],
            ['furniture', 'bookcase-shelving-unit', 'Bookcase / Shelving Unit', 'Freestanding units of any size.', 2500],
            ['kitchen-appliances', 'washing-machine', 'Washing Machine', 'Freestanding or integrated.', 3500],
            ['kitchen-appliances', 'dishwasher', 'Dishwasher', 'Freestanding or integrated.', 3500],
            ['kitchen-appliances', 'cooker-oven', 'Cooker / Oven', 'Electric or gas, freestanding.', 4000],
            ['kitchen-appliances', 'microwave', 'Microwave', 'Any size microwave oven.', 1500],
            ['fridge-freezer', 'fridge-freezer', 'Fridge Freezer', 'Standard combined fridge freezer.', 9500],
            ['fridge-freezer', 'fridge-only', 'Fridge Only', 'Under-counter or full size.', 7500],
            ['fridge-freezer', 'chest-freezer', 'Chest Freezer', 'Any size chest freezer.', 5000],
            ['fridge-freezer', 'wine-cooler', 'Wine Cooler', 'Freestanding wine fridge.', 3000],
            ['electrical-it', 'television', 'Television', 'Any size, CRT or flat screen.', 2000],
            ['electrical-it', 'computer-monitor', 'Computer / Monitor', 'Desktops, monitors and peripherals.', 1500],
            ['electrical-it', 'printer-scanner', 'Printer / Scanner', 'Home or office printers.', 1500],
            ['electrical-it', 'small-electricals-box', 'Small Electricals (box)', 'Kettles, toasters, cables and similar items.', 2000],
            ['garden-items', 'lawnmower', 'Lawnmower', 'Petrol or electric mower.', 2500],
            ['garden-items', 'garden-shed-dismantled', 'Garden Shed (dismantled)', 'Wooden or metal, flat packed.', 12000],
            ['garden-items', 'garden-furniture-set', 'Garden Furniture Set', 'Table, chairs or bench sets. Arm chair, 2 seater, 3 seater.', 12000],
            ['garden-items', 'green-waste-per-bag', 'Green Waste (per bag)', 'Grass, hedge cuttings and leaves.', 800],
            ['hazardous-waste', 'paint-tins-per-5', 'Paint Tins (per 5)', 'Part-full or empty tins.', 2000],
            ['hazardous-waste', 'gas-canister', 'Gas Canister', 'Camping or BBQ gas bottles.', 2500],
            ['hazardous-waste', 'asbestos-small-item', 'Asbestos (small item)', 'Requires licensed handling.', 8000],
            ['hazardous-waste', 'chemicals-oils-per-container', 'Chemicals / Oils (per container)', 'Household chemicals and oils.', 2000],
            ['office-items', 'office-desk', 'Office Desk', 'Single or double pedestal desk.', 3500],
            ['office-items', 'office-chair', 'Office Chair', 'Swivel or task chair.', 1500],
            ['office-items', 'filing-cabinet', 'Filing Cabinet', '2, 3 or 4 drawer unit.', 2500],
            ['office-items', 'office-partition', 'Office Partition', 'Freestanding screen or partition.', 3000],
            ['commercial-items', 'shop-fittings', 'Shop Fittings', 'Shelving, racking and display units.', 5000],
            ['commercial-items', 'catering-equipment', 'Catering Equipment', 'Commercial kitchen equipment.', 6000],
            ['commercial-items', 'pallets-per-5', 'Pallets (per 5)', 'Wooden or plastic pallets.', 2500],
            ['commercial-items', 'signage-displays', 'Signage / Displays', 'Shop or event signage.', 2000],
            ['bins-wheelie-bins', 'wheelie-bin', 'Wheelie Bin', '140 L, 240 L or 360 L bins.', 5000],
            ['bins-wheelie-bins', 'builders-bag-full', "Builder's Bag (full)", 'Filled rubble or waste bag.', 6500],
            ['bins-wheelie-bins', 'black-sacks-per-5', 'Black Sacks (per 5)', 'General household waste bags.', 1500],
            ['bins-wheelie-bins', 'skip-bag', 'Skip Bag', 'Large-capacity waste bag.', 8000],
        ];

        $categoryMedia = [
            'sofas' => 'sofas',
            'mattress-bed' => 'mattress_bed',
            'furniture' => 'furniture',
            'kitchen-appliances' => 'kitchen_appliances',
            'fridge-freezer' => 'fridge_freezer',
            'electrical-it' => 'electrical_it',
            'garden-items' => 'garden_items',
            'hazardous-waste' => 'hazardous_waste',
            'office-items' => 'office_items',
            'commercial-items' => 'commercial_items',
            'bins-wheelie-bins' => 'bins_wheelie_bins',
        ];

        foreach ($items as $index => [$categorySlug, $slug, $name, $description, $price]) {
            DB::table('service_items')->updateOrInsert(
                ['sku' => strtoupper(str_replace('-', '_', $slug))],
                [
                    'price_category_id' => $categories[$categorySlug],
                    'image_id' => $media[$categoryMedia[$categorySlug]] ?? null,
                    'slug' => $slug,
                    'name' => $name,
                    'description' => $description,
                    'price_pence' => $price,
                    'vat_rate_basis_points' => 2000,
                    'pricing_status' => 'placeholder',
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'deleted_at' => null,
                ],
            );
        }
    }

    private function loadPackages($now): void
    {
        foreach ([
            ['mini-load', 'Mini Load', 10000, 8333, 50, '1.05', 6, 10, 'confirmed', true],
            ['small-load', 'Small Load', 15000, 12500, 125, '2.10', 12, 15, 'confirmed', true],
            ['small-load-plus', 'Small Load +', 19500, 16250, 250, '4.50', 25, 25, 'confirmed', false],
            ['medium-load', 'Medium Load', 29500, 24583, 375, '6.50', 35, 35, 'placeholder', false],
            ['large-load', 'Large Load', 39500, 32917, 750, '9.00', 48, 45, 'placeholder', false],
            ['full-load-tipper', 'Full Load Tipper', 59500, 49583, 1000, '12.00', 65, 60, 'placeholder', false],
        ] as $index => [$slug, $name, $incVat, $exVat, $weight, $volume, $sacks, $minutes, $status, $popular]) {
            DB::table('load_packages')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'price_inc_vat_pence' => $incVat,
                    'price_ex_vat_pence' => $exVat,
                    'vat_rate_basis_points' => 2000,
                    'max_weight_kg' => $weight,
                    'volume_cubic_yards' => $volume,
                    'sack_equivalent' => $sacks,
                    'loading_time_minutes' => $minutes,
                    'pricing_status' => $status,
                    'is_popular' => $popular,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'deleted_at' => null,
                ],
            );
        }
    }

    private function extraCharges($now): void
    {
        foreach ([
            ['saturday-collection', 'Saturday collection surcharge', 'Added when Saturday collection is selected.', 5000, false, 'surcharge', 'active'],
            ['pay-on-arrival-callout-fee', 'Pay-on-arrival callout fee', 'Paid now; the remaining balance is paid after collection.', 2500, false, 'callout_fee', 'active'],
            ['difficult-property-access', 'Difficult property access', 'May apply to upper-floor flats without lifts, restricted parking or long carrying distances.', null, true, 'variable', 'quote_required'],
            ['additional-large-items', 'Additional large items', 'Items not included in the selected package may attract an extra charge; the customer is contacted first.', null, true, 'variable', 'quote_required'],
        ] as $index => [$slug, $name, $description, $amount, $variable, $type, $status]) {
            DB::table('extra_charges')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $description,
                    'amount_pence' => $amount,
                    'is_variable' => $variable,
                    'charge_type' => $type,
                    'pricing_status' => $status,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }
    }

    /**
     * The business is based in Portsmouth. Each area carries its postcode district, which is
     * what the booking coverage check matches a customer's outward code against.
     */
    private function coverageAreas($now): void
    {
        // The first build seeded London boroughs by mistake; remove them where they are left.
        DB::table('coverage_regions')
            ->whereIn('slug', ['central-london', 'north-london', 'south-london', 'east-london', 'west-london', 'greater-london'])
            ->delete();

        $regions = [
            'portsmouth' => ['Portsmouth', [
                ['Portsmouth City Centre', 'PO1'],
                ['Southsea', 'PO5'],
                ['Fratton', 'PO1'],
                ['North End', 'PO2'],
                ['Copnor', 'PO3'],
                ['Hilsea', 'PO3'],
                ['Milton', 'PO4'],
                ['Eastney', 'PO4'],
                ['Cosham', 'PO6'],
                ['Drayton', 'PO6'],
                ['Paulsgrove', 'PO6'],
            ]],
            'havant-waterlooville' => ['Havant & Waterlooville', [
                ['Havant', 'PO9'],
                ['Bedhampton', 'PO9'],
                ['Leigh Park', 'PO9'],
                ['Waterlooville', 'PO7'],
                ['Purbrook', 'PO7'],
                ['Denmead', 'PO7'],
                ['Cowplain', 'PO8'],
                ['Horndean', 'PO8'],
                ['Emsworth', 'PO10'],
                ['Hayling Island', 'PO11'],
            ]],
            'fareham-gosport' => ['Fareham & Gosport', [
                ['Fareham', 'PO16'],
                ['Portchester', 'PO16'],
                ['Stubbington', 'PO14'],
                ['Titchfield', 'PO14'],
                ['Whiteley', 'PO15'],
                ['Gosport', 'PO12'],
                ['Lee-on-the-Solent', 'PO13'],
                ['Wickham', 'PO17'],
            ]],
        ];

        $regionOrder = 0;

        foreach ($regions as $regionSlug => [$name, $areas]) {
            $regionOrder++;

            DB::table('coverage_regions')->updateOrInsert(
                ['slug' => $regionSlug],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $regionOrder,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );

            $regionId = (int) DB::table('coverage_regions')->where('slug', $regionSlug)->value('id');

            foreach ($areas as $index => [$area, $postcodePrefix]) {
                DB::table('coverage_areas')->updateOrInsert(
                    ['slug' => Str::slug($area)],
                    [
                        'coverage_region_id' => $regionId,
                        'name' => $area,
                        'postcode_prefix' => $postcodePrefix,
                        'is_featured' => $index === 0,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                        'updated_at' => $now,
                        'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    ],
                );
            }
        }
    }

    private function faqs($now): void
    {
        foreach ([
            ['What areas do you cover?', 'We cover Portsmouth and the surrounding area, including Southsea, Havant, Waterlooville, Fareham and Gosport. You can visit our Areas Covered page to see whether we operate in your location.'],
            ['How much does rubbish removal cost?', 'The price depends on the amount and type of rubbish, access, and location. Contact our team for a quick quotation.'],
            ['Do you offer same-day collection?', 'Yes, depending on availability. Contact us as early as possible and we will do our best to arrange a convenient collection time.'],
            ['Do you provide garden clearance?', 'Yes. We offer garden clearance and green waste removal services for homes, landlords and businesses.'],
            ['Can I book your service online?', 'Yes. You can choose a collection option online or contact us directly to arrange your service and receive a quotation.'],
        ] as $index => [$question, $answer]) {
            DB::table('faqs')->updateOrInsert(
                ['service_id' => null, 'question' => $question],
                [
                    'answer' => $answer,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }
    }

    private function reviews($now, array $services): void
    {
        foreach ([
            ['Sue Lewis', 5, 'Excellent removal service. I needed to move some stuff in a storage facility.', '2026-01-17 10:00:00'],
            ['Mr Blue Sky', 5, 'Excellent, professional, reliable and friendly team. Mr Tee and his team are great.', '2026-04-17 10:00:00'],
            ['Julie Smith', 4, 'I am glad I decided to go with Mr Tee Removals after a long drive to my new house.', '2025-10-17 10:00:00'],
            ['Julie Smith', 4, 'The team handled the clearance carefully and kept everything straightforward.', '2025-11-17 10:00:00'],
            ['Julie Smith', 3, 'Helpful service and clear communication from the office team.', '2026-07-17 10:00:00'],
            ['Julie Smith', 4, 'Good value and a friendly collection team.', '2026-08-17 10:00:00'],
        ] as $index => [$name, $rating, $body, $publishedAt]) {
            DB::table('reviews')->updateOrInsert(
                ['reviewer_name' => $name, 'body' => $body],
                [
                    'service_id' => $index === 0 ? ($services['house-clearance'] ?? null) : null,
                    'rating' => $rating,
                    'source' => 'website',
                    'status' => 'published',
                    'reviewed_at' => $publishedAt,
                    'published_at' => $publishedAt,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ],
            );
        }
    }

    private function serviceData(): array
    {
        return [
            ['slug' => 'house-clearance', 'name' => 'House Clearance', 'route' => '/houseClearance', 'category' => 'rubbish-removal', 'media' => 'house_clearance', 'headline' => 'House clearance and rubbish removal in Portsmouth', 'summary' => 'Save time, heavy lifting and trips to the local tip. Our experienced team clears single items, rooms and complete properties with care.', 'description' => 'Our experienced team clears single items, rooms and complete properties with care.', 'items' => ['Single items & rooms', 'Furniture and appliances', 'Full house clearance', 'Garages & outbuildings']],
            ['slug' => 'garden-clearance', 'name' => 'Garden Clearance', 'route' => '/gardenClearance', 'category' => 'rubbish-removal', 'media' => 'garden_clearance', 'headline' => 'Garden clearance in Portsmouth', 'summary' => 'Clear the waste. Enjoy the garden again.', 'description' => 'We remove garden waste efficiently and without fuss, leaving you with a cleaner outdoor space to enjoy.', 'items' => ['Green waste & planting', 'Structures & boundaries', 'Landscaping materials', 'Outdoor features']],
            ['slug' => 'flat-clearance', 'name' => 'Flat Clearance', 'route' => '/flatClearance', 'category' => 'rubbish-removal', 'media' => 'flat_clearance', 'headline' => 'Flat clearance and rubbish removal in Portsmouth', 'summary' => 'A practical clearance service for flats, maisonettes and duplex properties.', 'description' => 'Our local team plans around the property access and handles the lifting, carrying and removal from start to finish.', 'items' => ['Careful access', 'Bulky items', 'Every clearance size']],
            ['slug' => 'garage-clearance', 'name' => 'Garage Clearance', 'route' => '/garageClearance', 'category' => 'rubbish-removal', 'media' => 'garage_clearance', 'headline' => 'Garage clearance and junk removal in Portsmouth', 'summary' => 'Turn an overcrowded garage back into useful space.', 'description' => 'Clear accumulated furniture, equipment and rubbish from garages and sheds with help from our removal team.', 'items' => ['Single bulky items', 'Complete clear-outs', 'Garage and shed collections']],
            ['slug' => 'furniture-clearance', 'name' => 'Furniture Removal & Disposal', 'route' => '/furnitureClearance', 'category' => 'rubbish-removal', 'media' => 'furniture_clearance', 'headline' => 'Furniture disposal in Portsmouth', 'summary' => 'Clear promises. Reliable collections.', 'description' => 'Every booking is supported by practical collection choices, responsible recycling and an experienced team.', 'items' => ['3 Piece Suites', 'Bathroom Furniture', 'Dining Room Tables', 'Office Furniture', 'Wooden Beds']],
            ['slug' => 'builders-waste-removal', 'name' => 'Builders Waste Removal', 'route' => '/buildersWasteRemoval', 'category' => 'commercial-waste', 'media' => 'builders_waste_removal', 'headline' => 'Builders waste removal in Portsmouth', 'summary' => 'A dependable collection service for renovation debris and building waste.', 'description' => 'Keeping rubble and renovation waste under control improves site safety and productivity.', 'items' => ['Bathroom Fixtures', 'Kitchens & Appliances', 'Timber and Wood', 'Plasterboard', 'Rubble and Brickwork']],
            ['slug' => 'junk-collection', 'name' => 'Junk Collection', 'route' => '/junkCollection', 'category' => 'rubbish-removal', 'media' => 'junk_collection', 'headline' => 'Junk collection in Portsmouth', 'summary' => 'Reliable collections for unwanted household junk.', 'description' => 'We remove junk efficiently and without fuss, leaving you with a cleaner space to enjoy.', 'items' => ['Flexible booking options', 'Responsible recycling', 'Experienced collection team']],
            ['slug' => 'wait-load', 'name' => 'Wait & Load', 'route' => '/waitLoad', 'category' => 'commercial-waste', 'media' => 'wait_load', 'headline' => 'Wait and load collection in Portsmouth', 'summary' => 'A practical option when waste is ready to load quickly.', 'description' => 'Our team arrives, loads the waste, and moves on without the need for a skip left on site.', 'items' => ['Flexible load sizes', 'Fast loading visits', 'Responsible waste handling']],
            ['slug' => 'office-waste-clearance', 'name' => 'Office Waste Clearance', 'route' => '/officeWasteClearance', 'category' => 'commercial-waste', 'media' => 'office_waste_clearance', 'headline' => 'Office waste clearance in Portsmouth', 'summary' => 'Clear furniture, equipment and workplace waste with minimal disruption.', 'description' => 'We handle everything from single unwanted items and room clearances to complete offices and storage areas.', 'items' => ['Furniture & equipment', 'Rooms & storage areas', 'Responsible removal']],
            ['slug' => 'fly-tipping-clearance', 'name' => 'Fly Tipping Clearance', 'route' => '/flyTippingClearance', 'category' => 'commercial-waste', 'media' => 'fly_tipping_clearance', 'headline' => 'Fly-tipping clearance in Portsmouth', 'summary' => 'Remove dumped waste. Restore the area quickly.', 'description' => 'We remove dumped waste efficiently, helping restore the affected area without adding more hassle.', 'items' => ['Assess the location', 'Send the right collection team', 'Remove it safely', 'Handle it responsibly']],
            ['slug' => 'warehouse-clearance', 'name' => 'Warehouse Rubbish Clearance', 'route' => '/warehouseClearance', 'category' => 'commercial-waste', 'media' => 'warehouse_clearance', 'headline' => 'Warehouse clearance in Portsmouth', 'summary' => 'Reliable collections for warehouse rubbish and business waste.', 'description' => 'We make warehouse clearance efficient and straightforward with practical collection choices.', 'items' => ['Flexible booking options', 'Business waste clearance', 'Responsible disposal']],
            ['slug' => 'hotel-pub-clearance', 'name' => 'Hotel & Pub Clearance', 'route' => '/hotelPubClearance', 'category' => 'commercial-waste', 'media' => 'hotel_pub_clearance', 'headline' => 'Hotel & pub waste clearance in Portsmouth', 'summary' => 'Clearance support for refurbishing rooms, bars and hospitality spaces.', 'description' => 'Typically used when refurbishing single rooms or whole parts of a building.', 'items' => ['Refurb rooms cleared', 'Bars cleared', 'Beds & wardrobes', 'Builders waste & rubble removal']],
            ['slug' => 'restaurant-clearance', 'name' => 'Restaurant Clearance', 'route' => '/restaurantClearance', 'category' => 'commercial-waste', 'media' => 'restaurant_clearance', 'headline' => 'Restaurant waste clearance in Portsmouth', 'summary' => 'Five-star clearance support for dining furniture, kitchen fittings and refurbishment waste.', 'description' => 'From one unwanted item to a complete restaurant strip-out, our team manages lifting and clearance around your schedule.', 'items' => ['Dining & customer areas', 'Kitchen & storage', 'Fixtures & interiors', 'Refurbishment material']],
            ['slug' => 'shop-strip-out-clearance', 'name' => 'Shop Strip Out & Clearance', 'route' => '/shopStripOutClearance', 'category' => 'commercial-waste', 'media' => 'shop_strip_out_clearance', 'headline' => 'Strip out and shop clearance in Portsmouth', 'summary' => 'A practical service for retail strip-outs and shop clearances.', 'description' => 'We make strip out and shop clearance efficient and straightforward.', 'items' => ['Flexible booking options', 'Shop fittings', 'Refurbishment waste']],
            ['slug' => 'window-cleaning', 'name' => 'Window Cleaning', 'route' => '/windowCleaning', 'category' => 'cleaning', 'media' => 'window_cleaning', 'headline' => 'Window cleaning in Portsmouth', 'summary' => 'Professional cleaning for brighter glass, cleaner frames and well-presented properties.', 'description' => 'Our trained and vetted local cleaners care for windows, frames and sills with close attention to the finish.', 'items' => ['Interior and exterior glass', 'Frames and window sills', 'Residential and managed properties']],
            ['slug' => 'communal-area-cleaning', 'name' => 'Communal Area Cleaning', 'route' => '/communalAreaCleaning', 'category' => 'cleaning', 'media' => 'communal_area_cleaning', 'headline' => 'Communal area cleaning in Portsmouth', 'summary' => 'Reliable cleaning that keeps shared spaces presentable and welcoming.', 'description' => 'Our team keeps high-traffic communal spaces clean, presentable and easier to manage.', 'items' => ['Entrance halls and corridors', 'Stairwells and landings', 'Hard-floor mopping and polishing', 'Kitchens and refuse areas']],
            ['slug' => 'ground-maintenance', 'name' => 'Ground Maintenance', 'route' => '/groundMaintainance', 'category' => 'cleaning', 'media' => 'ground_maintenance', 'headline' => 'Grounds maintenance services in Portsmouth', 'summary' => 'Dependable maintenance that keeps grounds neat, safe and presentable.', 'description' => 'From a simple tidy-up to clearance, landscaping and ongoing care, we tailor maintenance around each site.', 'items' => ['Site clearance', 'Hedge cutting', 'Leaf clearance', 'Litter collection', 'Grass cutting']],
            ['slug' => 'lawn-mowing', 'name' => 'Lawn Mowing', 'route' => '/lawnMowing', 'category' => 'garden-services', 'media' => 'lawn_mowing', 'headline' => 'Lawn mowing in Portsmouth', 'summary' => 'Reliable lawn mowing that keeps outdoor spaces tidy and professionally maintained.', 'description' => 'Choose weekly or fortnightly visits on a reliable schedule.', 'items' => ['Small domestic lawns', 'Large residential gardens', 'Commercial grounds contracts', 'Grass-cutting collection']],
            ['slug' => 'hedge-cutting', 'name' => 'Hedge Cutting', 'route' => '/hedgeCutting', 'category' => 'garden-services', 'media' => 'hedge_cutting', 'headline' => 'Hedge cutting in Portsmouth', 'summary' => 'Professional hedge cutting delivered to a consistently high standard.', 'description' => 'We shape and maintain hedges, clear the trimmings and leave surrounding pavements and drives tidy.', 'items' => ['Hedge trimming and shaping', 'Height reduction', 'Topiary maintenance', 'Complete hedge removal']],
        ];
    }

    private function mimeType(string $path): string
    {
        return match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'jfif', 'jpeg', 'jpg' => 'image/jpeg',
            default => 'application/octet-stream',
        };
    }
}
