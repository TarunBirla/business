<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupSubscription;
use App\Models\UserSubscription;
use App\Models\Service;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Notice;
use App\Models\Connection;
use App\Models\ContactRequest;
use App\Models\Message;
use App\Models\CmsPage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@community.uk'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '+44 7700 900000',
                'password' => bcrypt('password'),
                'profession' => 'Platform Administrator',
                'company' => 'Community UK Ecosystem',
                'city' => 'London',
                'country' => 'United Kingdom',
                'global_role' => 'super_admin',
                'status' => 'active',
            ]
        );

        // 2. Create Services
        $services = [
            'Accounting & Tax Advice',
            'Legal & Solicitor Services',
            'Property & Estate Agents',
            'Mortgage & Insurance Broking',
            'IT & Web Development',
            'Digital Marketing & SEO',
            'Recruitment & HR Consulting',
            'Photography & Media Production',
            'Private Tutoring & Education',
            'Business Strategy Consulting',
        ];

        $serviceModels = [];
        foreach ($services as $serviceName) {
            $serviceModels[] = Service::firstOrCreate(['name' => $serviceName], ['status' => 'active']);
        }

        // 3. Create Communities
        $groupsData = [
            [
                'name' => 'Gujarati Community UK',
                'slug' => 'gujarati-community-uk',
                'description' => 'The premier networking ecosystem for the Gujarati diaspora across the United Kingdom. Connecting professionals, business owners, students, and families.',
                'purpose' => 'To support Gujarati professionals and businesses in the UK through meaningful networking, professional service exchanges, and community cultural events.',
                'why_join' => 'Connect with over 1,500+ Gujarati professionals across London, Birmingham, Leicester, and Manchester. Discover reliable services offered by community members.',
                'who_can_join' => ['Gujarati individuals & families in the UK', 'Professionals & Entrepreneurs', 'Students & Young Professionals'],
                'benefits' => ['Access to verified member directory', 'Free entry to quarterly networking breakfasts', 'Direct professional connection requests', 'Community noticeboard'],
                'community_type' => 'paid',
                'price' => 20.00,
                'city' => 'London',
            ],
            [
                'name' => 'Marathi Community UK',
                'slug' => 'marathi-community-uk',
                'description' => 'A vibrant UK-wide community bringing together Maharashtrian professionals, tech leaders, and families living across Britain.',
                'purpose' => 'Promoting professional development, mentorship, business collaboration, and cultural heritage among Marathi people in the UK.',
                'why_join' => 'Build strong professional bonds, exchange business services, and participate in regional tech and business seminars.',
                'who_can_join' => ['Marathi diaspora in the UK', 'Tech Professionals & Engineers', 'Business Owners'],
                'benefits' => ['Member directory', 'Professional networking', 'Cultural & business events'],
                'community_type' => 'free',
                'price' => 0.00,
                'city' => 'Birmingham',
            ],
            [
                'name' => 'Madhya Pradesh Community UK',
                'slug' => 'madhya-pradesh-community-uk',
                'description' => 'Dedicated networking community for people from Madhya Pradesh residing, working, and studying in the UK.',
                'purpose' => 'Fostering unity, mutual professional assistance, career guidance for newcomers, and business networking.',
                'why_join' => 'Find trusted advice, connect with fellow MP professionals, and stay informed on UK community activities.',
                'who_can_join' => ['People from Madhya Pradesh in UK', 'UK Professionals', 'University Students'],
                'benefits' => ['Community directory', 'Service exchange board', 'Mentorship programs'],
                'community_type' => 'free',
                'price' => 0.00,
                'city' => 'Leicester',
            ],
            [
                'name' => 'Punjabi Community UK',
                'slug' => 'punjabi-community-uk',
                'description' => 'Empowering Punjabi entrepreneurs, professionals, and community leaders across the United Kingdom.',
                'purpose' => 'Creating growth opportunities, trade partnerships, legal & financial support networks for the Punjabi community.',
                'why_join' => 'Access exclusive business networks, high-impact networking dinners, and peer recommendation systems.',
                'who_can_join' => ['Punjabi professionals & business owners', 'UK Families & Youth'],
                'benefits' => ['Verified member listings', 'Business referral exchange', 'Annual gala & networking night'],
                'community_type' => 'paid',
                'price' => 15.00,
                'city' => 'Manchester',
            ],
            [
                'name' => 'Indian Community UK',
                'slug' => 'indian-community-uk',
                'description' => 'An inclusive umbrella community platform for the wider Indian diaspora in the United Kingdom.',
                'purpose' => 'Uniting professionals from all Indian states to collaborate, network, and exchange services seamlessly.',
                'why_join' => 'Connect across all professional sectors—finance, medicine, IT, law, real estate, and trade.',
                'who_can_join' => ['All Indian origin residents in the UK'],
                'benefits' => ['National member database', 'Multi-sector networking', 'Regular webinars & meetups'],
                'community_type' => 'free',
                'price' => 0.00,
                'city' => 'London',
            ],
            [
                'name' => 'London Business Community',
                'slug' => 'london-business-community',
                'description' => 'An elite professional networking club for business founders, executives, and senior consultants in Greater London.',
                'purpose' => 'Accelerating business partnerships, investor connections, and B2B client acquisition.',
                'why_join' => 'Direct access to high-value business leads, peer advisory roundtables, and executive events.',
                'who_can_join' => ['Founders, C-suite Executives, & Consultants in London'],
                'benefits' => ['Executive directory', 'VIP Event Invites', 'B2B Service Matching'],
                'community_type' => 'paid',
                'price' => 50.00,
                'city' => 'London',
            ],
        ];

        $groupModels = [];
        foreach ($groupsData as $gData) {
            $group = Group::firstOrCreate(
                ['slug' => $gData['slug']],
                [
                    'name' => $gData['name'],
                    'description' => $gData['description'],
                    'purpose' => $gData['purpose'],
                    'why_join' => $gData['why_join'],
                    'who_can_join' => $gData['who_can_join'],
                    'benefits' => $gData['benefits'],
                    'community_type' => $gData['community_type'],
                    'city' => $gData['city'],
                    'country' => 'United Kingdom',
                    'status' => 'active',
                ]
            );

            if ($gData['community_type'] === 'paid') {
                GroupSubscription::firstOrCreate(
                    ['group_id' => $group->id],
                    [
                        'name' => 'Annual Membership',
                        'price' => $gData['price'],
                        'currency' => 'GBP',
                        'duration' => 1,
                        'duration_type' => 'yearly',
                        'status' => 'active',
                    ]
                );
            }

            $groupModels[] = $group;
        }

        // 4. Create Sample Group Admins & Members
        $sampleMembers = [
            ['Rajesh', 'Patel', 'rajesh.patel@example.com', 'Solicitor', 'Patel & Co Legal', 'London'],
            ['Priya', 'Shah', 'priya.shah@example.com', 'Chartered Accountant', 'Apex Tax Advisory', 'London'],
            ['Amit', 'Joshi', 'amit.joshi@example.com', 'Software Engineer', 'TechWave UK', 'Birmingham'],
            ['Sneha', 'Deshmukh', 'sneha.d@example.com', 'Estate Agent', 'Grand Property UK', 'Birmingham'],
            ['Vikram', 'Sharma', 'vikram.s@example.com', 'Financial Consultant', 'Sharma Capital', 'Leicester'],
            ['Ananya', 'Verma', 'ananya.v@example.com', 'Marketing Director', 'BrandVista UK', 'Leicester'],
            ['Gurpreet', 'Singh', 'gurpreet.s@example.com', 'Mortgage Broker', 'Singh Mortgages', 'Manchester'],
            ['Harleen', 'Kaur', 'harleen.k@example.com', 'Recruitment Specialist', 'TalentFirst UK', 'Manchester'],
        ];

        $usersCreated = [];
        foreach ($sampleMembers as $idx => $m) {
            $u = User::firstOrCreate(
                ['email' => $m[2]],
                [
                    'first_name' => $m[0],
                    'last_name' => $m[1],
                    'phone' => '+44 7700 90000' . ($idx + 1),
                    'password' => bcrypt('password'),
                    'profession' => $m[3],
                    'company' => $m[4],
                    'city' => $m[5],
                    'country' => 'United Kingdom',
                    'description' => "Experienced {$m[3]} based in {$m[5]}, United Kingdom. Passionate about community networking and professional collaboration.",
                    'privacy_settings' => [
                        'show_email' => false,
                        'show_phone' => false,
                        'allow_connections' => true,
                        'allow_contact_requests' => true,
                    ],
                    'global_role' => 'user',
                    'status' => 'active',
                ]
            );

            // Assign services
            if (!$u->servicesOffered()->where('service_id', $serviceModels[$idx % count($serviceModels)]->id)->exists()) {
                $u->servicesOffered()->attach([$serviceModels[$idx % count($serviceModels)]->id], ['type' => 'offer']);
            }
            if (!$u->servicesNeeded()->where('service_id', $serviceModels[($idx + 2) % count($serviceModels)]->id)->exists()) {
                $u->servicesNeeded()->attach([$serviceModels[($idx + 2) % count($serviceModels)]->id], ['type' => 'need']);
            }

            // Attach user to groups
            $targetGroup = $groupModels[$idx % count($groupModels)];
            $isGroupAdmin = ($idx < 3);

            if (!$u->isMemberOf($targetGroup->id)) {
                $u->groups()->attach($targetGroup->id, [
                    'membership_role' => $isGroupAdmin ? 'group_admin' : 'member',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }

            // Also attach to Indian Community UK
            if ($targetGroup->id !== $groupModels[4]->id && !$u->isMemberOf($groupModels[4]->id)) {
                $u->groups()->attach($groupModels[4]->id, [
                    'membership_role' => 'member',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }

            // Create active user subscription if paid
            if ($targetGroup->community_type === 'paid') {
                $plan = $targetGroup->activeSubscriptionPlan;
                UserSubscription::firstOrCreate(
                    ['user_id' => $u->id, 'group_id' => $targetGroup->id],
                    [
                        'subscription_id' => $plan?->id,
                        'start_date' => now(),
                        'expiry_date' => now()->addYear(),
                        'status' => 'active',
                    ]
                );
            }

            $usersCreated[] = $u;
        }

        // 5. Create Events
        $eventsData = [
            [
                'group_id' => $groupModels[0]->id,
                'created_by' => $usersCreated[0]->id,
                'title' => 'Gujarati Community UK Business Networking Dinner',
                'slug' => 'gujarati-community-uk-business-dinner',
                'description' => 'Join us for an exclusive evening of high-impact networking, keynote talks by leading Gujarati entrepreneurs, and a three-course dinner.',
                'event_type' => 'paid',
                'price' => 25.00,
                'venue' => 'Hilton London Metropole',
                'city' => 'London',
                'start_at' => now()->addDays(14)->setHour(18)->setMinute(30),
                'capacity' => 120,
            ],
            [
                'group_id' => $groupModels[1]->id,
                'created_by' => $usersCreated[2]->id,
                'title' => 'UK Marathi Tech & Innovation Summit',
                'slug' => 'uk-marathi-tech-summit',
                'description' => 'A gathering of Marathi software architects, tech founders, and IT consultants sharing insights on AI, cloud engineering, and career progression.',
                'event_type' => 'free',
                'price' => 0.00,
                'venue' => 'Birmingham International Convention Centre',
                'city' => 'Birmingham',
                'start_at' => now()->addDays(20)->setHour(14)->setMinute(0),
                'capacity' => 80,
            ],
            [
                'group_id' => $groupModels[3]->id,
                'created_by' => $usersCreated[6]->id,
                'title' => 'Punjabi Professional Gala & Networking Night',
                'slug' => 'punjabi-professional-gala',
                'description' => 'Celebrate business success and connect with solicitors, property developers, and business owners across Greater Manchester.',
                'event_type' => 'paid',
                'price' => 20.00,
                'venue' => 'Manchester Central Convention Complex',
                'city' => 'Manchester',
                'start_at' => now()->addDays(28)->setHour(19)->setMinute(0),
                'capacity' => 150,
            ],
        ];

        foreach ($eventsData as $eData) {
            $evt = Event::firstOrCreate(
                ['slug' => $eData['slug']],
                array_merge($eData, [
                    'country' => 'United Kingdom',
                    'currency' => 'GBP',
                    'status' => 'published',
                ])
            );

            // Register first 3 members to the event
            foreach (array_slice($usersCreated, 0, 3) as $u) {
                EventRegistration::firstOrCreate(
                    ['event_id' => $evt->id, 'user_id' => $u->id],
                    [
                        'amount' => $evt->price,
                        'payment_status' => 'completed',
                        'registration_status' => 'confirmed',
                        'registered_at' => now(),
                    ]
                );
            }
        }

        // 6. Create Group Notices
        Notice::firstOrCreate(
            ['group_id' => $groupModels[0]->id, 'title' => 'Welcome to Gujarati Community UK Official Portal!'],
            [
                'created_by' => $usersCreated[0]->id,
                'content' => 'We are thrilled to launch our new professional networking portal. Please complete your member profile, list your services offered, and start connecting with fellow members.',
                'priority' => 'high',
                'published_at' => now(),
                'status' => 'published',
            ]
        );

        Notice::firstOrCreate(
            ['group_id' => $groupModels[1]->id, 'title' => 'Quarterly Tech Roundtable Registration Open'],
            [
                'created_by' => $usersCreated[2]->id,
                'content' => 'Registrations are now open for our upcoming Birmingham Tech Summit. All members are invited to register early.',
                'priority' => 'medium',
                'published_at' => now(),
                'status' => 'published',
            ]
        );

        // 7. Create Connections
        Connection::firstOrCreate(
            ['sender_id' => $usersCreated[0]->id, 'receiver_id' => $usersCreated[1]->id, 'group_id' => $groupModels[0]->id],
            [
                'status' => 'accepted',
                'accepted_at' => now()->subDays(2),
            ]
        );

        Connection::firstOrCreate(
            ['sender_id' => $usersCreated[2]->id, 'receiver_id' => $usersCreated[3]->id, 'group_id' => $groupModels[1]->id],
            [
                'status' => 'pending',
            ]
        );

        // 8. Create Sample Messages
        Message::firstOrCreate(
            ['group_id' => $groupModels[0]->id, 'sender_id' => $usersCreated[0]->id, 'receiver_id' => $usersCreated[1]->id, 'message' => 'Hello Priya! Welcome to the Gujarati Community UK networking portal.'],
            ['is_read' => true]
        );
        Message::firstOrCreate(
            ['group_id' => $groupModels[0]->id, 'sender_id' => $usersCreated[1]->id, 'receiver_id' => $usersCreated[0]->id, 'message' => 'Hi Rajesh! Thank you, looking forward to exchanging services and attending community dinners.'],
            ['is_read' => true]
        );

        // 8. Create CMS Pages
        $cmsPages = [
            [
                'slug' => 'about',
                'title' => 'About Community UK',
                'content' => "Community UK is the United Kingdom's leading professional community networking ecosystem.\n\nOur mission is to empower individuals who share a common heritage, region, cultural background, or professional identity to connect, exchange services, support each other, and build lasting relationships.\n\nWhether you are a professional, business founder, student, or community member, Community UK gives you a trusted digital environment to discover opportunities.",
                'meta_title' => 'About Us - Community UK Platform',
                'meta_description' => 'Learn about Community UK professional networking ecosystem.',
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'content' => "Have questions or need help setting up a community for your group?\n\nEmail: support@community.uk\nAddress: 100 Bishopsgate, London EC2N 4AG, United Kingdom\nPhone: +44 20 7946 0900",
                'meta_title' => 'Contact Us - Community UK',
                'meta_description' => 'Get in touch with the Community UK team.',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy & GDPR Compliance',
                'content' => "Your privacy is paramount to us.\n\n1. Contact Information Protection: Email addresses and phone numbers are never publicly disclosed by default. Contact details are shared only when you explicitly approve a contact request.\n2. Data Rights: UK GDPR grants you the right to request a full export of your personal data or request total account deletion at any time.\n3. Payment Security: Credit/debit card transactions are processed securely via Stripe. We never store credit card numbers on our servers.",
                'meta_title' => 'Privacy Policy & GDPR - Community UK',
                'meta_description' => 'Read our UK GDPR privacy policy and data security commitments.',
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms & Conditions',
                'content' => "By using Community UK, you agree to treat all community members with respect, maintain professional integrity in service exchanges, and abide by group community rules.",
                'meta_title' => 'Terms & Conditions - Community UK',
                'meta_description' => 'Terms and conditions for Community UK.',
            ],
            [
                'slug' => 'faq',
                'title' => 'Frequently Asked Questions',
                'content' => "Q: How do shareable public community links work?\nA: Group Admins can share public URLs (such as /join/gujarati-community-uk) on WhatsApp, LinkedIn, or posters. Visitors can view community info, events, and benefits before registering.\n\nQ: Is my phone number visible to everyone?\nA: No! By default, phone numbers and emails are hidden. Other members must send a contact request which you can accept or decline.\n\nQ: How do paid memberships work?\nA: Super Admins can set annual subscription prices (e.g. £20/year GBP). Payments are securely processed via Stripe.",
                'meta_title' => 'FAQ - Community UK',
                'meta_description' => 'Frequently asked questions about Community UK.',
            ],
        ];

        foreach ($cmsPages as $page) {
            CmsPage::firstOrCreate(['slug' => $page['slug']], array_merge($page, ['is_published' => true]));
        }
    }
}
