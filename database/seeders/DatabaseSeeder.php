<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupSubscription;
use App\Models\UserSubscription;
use App\Models\Service;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Notice;
use App\Models\Connection;
use App\Models\Message;
use App\Models\CmsPage;
use App\Models\Payment;
use App\Models\Announcement;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
        DB::table('group_user')->truncate();
        DB::table('group_subscriptions')->truncate();
        DB::table('user_subscriptions')->truncate();
        DB::table('services')->truncate();
        DB::table('user_services')->truncate();
        DB::table('events')->truncate();
        DB::table('event_registrations')->truncate();
        DB::table('notices')->truncate();
        DB::table('connections')->truncate();
        DB::table('messages')->truncate();
        DB::table('cms_pages')->truncate();
        DB::table('payments')->truncate();
        DB::table('announcements')->truncate();
        DB::table('notifications')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create Super Admin
        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'phone' => '+44 7700 900000',
            'password' => Hash::make('password123'),
            'profession' => 'Platform Administrator',
            'company' => 'Community UK Headquarters',
            'city' => 'London',
            'country' => 'United Kingdom',
            'global_role' => 'super_admin',
            'status' => 'active',
        ]);

        // 2. Create Group Admin
        $groupAdmin = User::create([
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'email' => 'groupadmin@example.com',
            'phone' => '+44 7700 900001',
            'password' => Hash::make('password123'),
            'profession' => 'Community Manager & Legal Advisor',
            'company' => 'London Business Hub',
            'city' => 'London',
            'country' => 'United Kingdom',
            'global_role' => 'group_admin',
            'status' => 'active',
        ]);

        // 3. Create Active Member
        $activeMember = User::create([
            'first_name' => 'Priya',
            'last_name' => 'Patel',
            'email' => 'member@example.com',
            'phone' => '+44 7700 900002',
            'password' => Hash::make('password123'),
            'profession' => 'Chartered Accountant',
            'company' => 'Patel & Co Tax Advisory',
            'city' => 'London',
            'country' => 'United Kingdom',
            'global_role' => 'user',
            'status' => 'active',
        ]);

        // 4. Create Pending Member (For testing approval workflow)
        $pendingMember = User::create([
            'first_name' => 'Amit',
            'last_name' => 'Joshi',
            'email' => 'pendingmember@example.com',
            'phone' => '+44 7700 900003',
            'password' => Hash::make('password123'),
            'profession' => 'Software Engineer',
            'company' => 'TechWave UK',
            'city' => 'Birmingham',
            'country' => 'United Kingdom',
            'global_role' => 'user',
            'status' => 'pending',
        ]);

        // 5. Create Services
        $services = [
            'Accounting & Tax Advice',
            'Legal & Solicitor Services',
            'Property & Estate Agents',
            'Mortgage & Insurance Broking',
            'IT & Web Development',
            'Digital Marketing & SEO',
        ];

        $serviceModels = [];
        foreach ($services as $sName) {
            $serviceModels[] = Service::create(['name' => $sName, 'status' => 'active']);
        }

        // Attach services to active member
        $activeMember->servicesOffered()->attach([$serviceModels[0]->id], ['type' => 'offer']);
        $activeMember->servicesNeeded()->attach([$serviceModels[1]->id], ['type' => 'need']);

        // 6. Create Communities
        $londonGroup = Group::create([
            'name' => 'London Business Community',
            'slug' => 'london-business-community',
            'description' => 'The premier networking ecosystem for business owners, legal advisors, and consultants in London.',
            'purpose' => 'Accelerating B2B collaboration and professional service exchanges.',
            'why_join' => 'Access verified London member listings and monthly networking breakfasts.',
            'who_can_join' => ['London Professionals', 'Founders & Entrepreneurs', 'Consultants'],
            'benefits' => ['Member Directory', 'Event Discounts', 'Service Exchange Board'],
            'community_type' => 'free',
            'city' => 'London',
            'country' => 'United Kingdom',
            'status' => 'active',
        ]);

        $propertyGroup = Group::create([
            'name' => 'UK Property & Real Estate Network',
            'slug' => 'uk-property-real-estate-network',
            'description' => 'Exclusive community for estate agents, mortgage brokers, and property developers across the UK.',
            'purpose' => 'Sharing UK property market leads, deals, and legal compliance guidelines.',
            'why_join' => 'Direct access to high-value property investment opportunities.',
            'who_can_join' => ['Estate Agents', 'Investors', 'Mortgage Advisors'],
            'benefits' => ['Investor Directory', 'Deal Room', 'Quarterly Summits'],
            'community_type' => 'paid',
            'city' => 'London',
            'country' => 'United Kingdom',
            'status' => 'active',
        ]);

        // Subscription plan for paid group
        $plan = GroupSubscription::create([
            'group_id' => $propertyGroup->id,
            'name' => 'Annual Membership',
            'price' => 25.00,
            'currency' => 'GBP',
            'duration' => 1,
            'duration_type' => 'yearly',
            'status' => 'active',
        ]);

        // Attach Group Admin to London Group
        $groupAdmin->groups()->attach($londonGroup->id, [
            'membership_role' => 'group_admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        $groupAdmin->groups()->attach($propertyGroup->id, [
            'membership_role' => 'group_admin',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Attach Active Member to London Group
        $activeMember->groups()->attach($londonGroup->id, [
            'membership_role' => 'member',
            'status' => 'active',
            'approved_by' => $groupAdmin->id,
            'approved_at' => now(),
            'joined_at' => now(),
        ]);

        // Attach Pending Member to London Group (Status = PENDING)
        $pendingMember->groups()->attach($londonGroup->id, [
            'membership_role' => 'member',
            'status' => 'pending',
            'joined_at' => now(),
        ]);

        // 7. Create Events
        $freeEvent = Event::create([
            'group_id' => $londonGroup->id,
            'created_by' => $groupAdmin->id,
            'title' => 'London Business Networking Breakfast 2026',
            'slug' => 'london-business-networking-breakfast-2026',
            'description' => 'Join us for a morning of high-impact networking and keynote talks by UK industry leaders.',
            'event_type' => 'free',
            'price' => 0.00,
            'venue' => 'Hilton London Metropole',
            'city' => 'London',
            'start_at' => now()->addDays(10)->setHour(9)->setMinute(0),
            'capacity' => 100,
            'status' => 'published',
            'country' => 'United Kingdom',
            'currency' => 'GBP',
        ]);

        $paidEvent = Event::create([
            'group_id' => $propertyGroup->id,
            'created_by' => $groupAdmin->id,
            'title' => 'UK Property Growth & Investment Summit 2026',
            'slug' => 'uk-property-growth-investment-summit-2026',
            'description' => 'Annual summit covering UK estate trends, mortgage regulations, and commercial developments.',
            'event_type' => 'paid',
            'price' => 15.00,
            'venue' => 'Excel London Convention Centre',
            'city' => 'London',
            'start_at' => now()->addDays(20)->setHour(10)->setMinute(0),
            'capacity' => 200,
            'status' => 'published',
            'country' => 'United Kingdom',
            'currency' => 'GBP',
        ]);

        // Register active member for free event
        EventRegistration::create([
            'event_id' => $freeEvent->id,
            'user_id' => $activeMember->id,
            'amount' => 0.00,
            'payment_status' => 'completed',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        // 8. Create Payment Record for demo
        Payment::create([
            'user_id' => $activeMember->id,
            'group_id' => $propertyGroup->id,
            'amount' => 25.00,
            'currency' => 'GBP',
            'provider' => 'stripe',
            'transaction_id' => 'TXN_TEST_' . strtoupper(rand(10000, 99999)),
            'status' => 'completed',
            'type' => 'subscription',
        ]);

        // 9. Create Announcement & Notification
        $announcement = Announcement::create([
            'group_id' => $londonGroup->id,
            'created_by' => $groupAdmin->id,
            'title' => 'Welcome to the London Business Portal!',
            'content' => 'We are excited to launch our community directory. Make sure to complete your profile and register for upcoming events!',
            'target_role' => 'all',
            'is_email_sent' => true,
        ]);

        Notification::create([
            'user_id' => $activeMember->id,
            'announcement_id' => $announcement->id,
            'type' => 'announcement',
            'title' => $announcement->title,
            'message' => 'Welcome to the London Business Portal!',
            'link' => route('notifications.index'),
            'is_read' => false,
        ]);

        // 10. CMS Pages
        $cmsPages = [
            ['slug' => 'about', 'title' => 'About Community UK', 'content' => "Community UK is Britain's leading professional networking ecosystem."],
            ['slug' => 'privacy', 'title' => 'Privacy Policy', 'content' => "Your privacy is protected under UK GDPR regulations."],
            ['slug' => 'terms', 'title' => 'Terms & Conditions', 'content' => "Please use the platform respectfully and adhere to community guidelines."],
        ];
        foreach ($cmsPages as $cp) {
            CmsPage::create(array_merge($cp, ['is_published' => true]));
        }
    }
}
