<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\CommunitySupportArea;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create skills
        $this->createSkills();
        
        // Create support areas
        $this->createSupportAreas();
        
        // Create admin users
        $this->createAdminUsers();
        
        // Create sample members (for testing)
        $this->createSampleMembers();
    }
    
    private function createSkills()
    {
        $skills = [
            // IT & Technology
            ['name_en' => 'Software Development', 'name_pt' => 'Desenvolvimento de Software', 'category' => 'IT & Technology', 'icon' => 'bi-code-slash'],
            ['name_en' => 'Web Design', 'name_pt' => 'Design Web', 'category' => 'IT & Technology', 'icon' => 'bi-globe'],
            ['name_en' => 'Data Analysis', 'name_pt' => 'Análise de Dados', 'category' => 'IT & Technology', 'icon' => 'bi-graph-up'],
            ['name_en' => 'Network Administration', 'name_pt' => 'Administração de Redes', 'category' => 'IT & Technology', 'icon' => 'bi-hdd-network'],
            ['name_en' => 'Database Management', 'name_pt' => 'Gestão de Base de Dados', 'category' => 'IT & Technology', 'icon' => 'bi-database'],
            ['name_en' => 'Cybersecurity', 'name_pt' => 'Cibersegurança', 'category' => 'IT & Technology', 'icon' => 'bi-shield-check'],
            
            // Construction
            ['name_en' => 'Carpentry', 'name_pt' => 'Carpintaria', 'category' => 'Construction', 'icon' => 'bi-hammer'],
            ['name_en' => 'Electrical Work', 'name_pt' => 'Trabalhos Elétricos', 'category' => 'Construction', 'icon' => 'bi-lightning'],
            ['name_en' => 'Plumbing', 'name_pt' => 'Encanamento', 'category' => 'Construction', 'icon' => 'bi-droplet'],
            ['name_en' => 'Painting', 'name_pt' => 'Pintura', 'category' => 'Construction', 'icon' => 'bi-brush'],
            ['name_en' => 'Masonry', 'name_pt' => 'Alvenaria', 'category' => 'Construction', 'icon' => 'bi-bricks'],
            
            // Health
            ['name_en' => 'Nursing', 'name_pt' => 'Enfermagem', 'category' => 'Health', 'icon' => 'bi-heart-pulse'],
            ['name_en' => 'First Aid', 'name_pt' => 'Primeiros Socorros', 'category' => 'Health', 'icon' => 'bi-ambulance'],
            ['name_en' => 'Medical Translation', 'name_pt' => 'Tradução Médica', 'category' => 'Health', 'icon' => 'bi-translate'],
            ['name_en' => 'Health Education', 'name_pt' => 'Educação para a Saúde', 'category' => 'Health', 'icon' => 'bi-book'],
            
            // Education
            ['name_en' => 'Teaching', 'name_pt' => 'Ensino', 'category' => 'Education', 'icon' => 'bi-mortarboard'],
            ['name_en' => 'Tutoring', 'name_pt' => 'Explicações', 'category' => 'Education', 'icon' => 'bi-person-video'],
            ['name_en' => 'Curriculum Development', 'name_pt' => 'Desenvolvimento Curricular', 'category' => 'Education', 'icon' => 'bi-journal-text'],
            ['name_en' => 'Language Instruction', 'name_pt' => 'Instrução de Línguas', 'category' => 'Education', 'icon' => 'bi-chat-dots'],
            
            // Business
            ['name_en' => 'Accounting', 'name_pt' => 'Contabilidade', 'category' => 'Business', 'icon' => 'bi-calculator'],
            ['name_en' => 'Marketing', 'name_pt' => 'Marketing', 'category' => 'Business', 'icon' => 'bi-megaphone'],
            ['name_en' => 'Business Planning', 'name_pt' => 'Planejamento de Negócios', 'category' => 'Business', 'icon' => 'bi-briefcase'],
            ['name_en' => 'Customer Service', 'name_pt' => 'Serviço ao Cliente', 'category' => 'Business', 'icon' => 'bi-headset'],
            
            // Legal
            ['name_en' => 'Legal Advice', 'name_pt' => 'Aconselhamento Jurídico', 'category' => 'Legal', 'icon' => 'bi-scale'],
            ['name_en' => 'Document Preparation', 'name_pt' => 'Preparação de Documentos', 'category' => 'Legal', 'icon' => 'bi-file-text'],
            ['name_en' => 'Immigration Assistance', 'name_pt' => 'Assistência de Imigração', 'category' => 'Legal', 'icon' => 'bi-passport'],
            
            // Transport
            ['name_en' => 'Driving', 'name_pt' => 'Condução', 'category' => 'Transport', 'icon' => 'bi-truck'],
            ['name_en' => 'Logistics', 'name_pt' => 'Logística', 'category' => 'Transport', 'icon' => 'bi-box-seam'],
            ['name_en' => 'Vehicle Maintenance', 'name_pt' => 'Manutenção de Veículos', 'category' => 'Transport', 'icon' => 'bi-tools'],
            
            // Other
            ['name_en' => 'Cooking', 'name_pt' => 'Culinária', 'category' => 'Other', 'icon' => 'bi-egg-fried'],
            ['name_en' => 'Event Planning', 'name_pt' => 'Planejamento de Eventos', 'category' => 'Other', 'icon' => 'bi-calendar-event'],
            ['name_en' => 'Photography', 'name_pt' => 'Fotografia', 'category' => 'Other', 'icon' => 'bi-camera'],
        ];
        
        foreach ($skills as $index => $skill) {
            Skill::create(array_merge($skill, ['display_order' => $index + 1]));
        }
    }
    
    private function createSupportAreas()
    {
        $supportAreas = [
            [
                'name_en' => 'Job Referrals',
                'name_pt' => 'Referências de Emprego',
                'description_en' => 'Help community members find job opportunities',
                'description_pt' => 'Ajudar membros da comunidade a encontrar oportunidades de emprego',
                'icon' => 'bi-briefcase',
                'display_order' => 1
            ],
            [
                'name_en' => 'Translation',
                'name_pt' => 'Tradução',
                'description_en' => 'Assist with translation between Portuguese and other languages',
                'description_pt' => 'Ajudar com tradução entre Português e outras línguas',
                'icon' => 'bi-translate',
                'display_order' => 2
            ],
            [
                'name_en' => 'Accommodation Assistance',
                'name_pt' => 'Apoio com Alojamento',
                'description_en' => 'Help with finding accommodation or temporary housing',
                'description_pt' => 'Ajudar a encontrar alojamento ou habitação temporária',
                'icon' => 'bi-house',
                'display_order' => 3
            ],
            [
                'name_en' => 'Legal Guidance',
                'name_pt' => 'Orientação Jurídica',
                'description_en' => 'Provide guidance on legal matters and documentation',
                'description_pt' => 'Fornecer orientação sobre questões jurídicas e documentação',
                'icon' => 'bi-scale',
                'display_order' => 4
            ],
            [
                'name_en' => 'Mentorship',
                'name_pt' => 'Mentoria',
                'description_en' => 'Mentor community members in professional development',
                'description_pt' => 'Orientar membros da comunidade no desenvolvimento profissional',
                'icon' => 'bi-people',
                'display_order' => 5
            ],
            [
                'name_en' => 'Emergency Support',
                'name_pt' => 'Apoio de Emergência',
                'description_en' => 'Provide support during emergencies or crises',
                'description_pt' => 'Fornecer apoio durante emergências ou crises',
                'icon' => 'bi-telephone-plus',
                'display_order' => 6
            ],
            [
                'name_en' => 'Cultural Integration',
                'name_pt' => 'Integração Cultural',
                'description_en' => 'Help with cultural adaptation and integration',
                'description_pt' => 'Ajudar com adaptação e integração cultural',
                'icon' => 'bi-globe2',
                'display_order' => 7
            ],
            [
                'name_en' => 'Healthcare Navigation',
                'name_pt' => 'Navegação do Sistema de Saúde',
                'description_en' => 'Assist with understanding and accessing healthcare services',
                'description_pt' => 'Ajudar a compreender e aceder aos serviços de saúde',
                'icon' => 'bi-hospital',
                'display_order' => 8
            ],
        ];
        
        foreach ($supportAreas as $area) {
            CommunitySupportArea::create($area);
        }
    }
    
   private function createAdminUsers()
{
    AdminUser::firstOrCreate(
        ['email' => 'admin@angolancommunity.org'],
        [
            'name' => 'Super Administrator',
            'password' => Hash::make('Password123!'),
            'role' => 'super_admin',
            'phone' => '+27 11 234 5678',
            'is_active' => true,
        ]
    );

    AdminUser::firstOrCreate(
        ['email' => 'coordinator@angolancommunity.org'],
        [
            'name' => 'Province Coordinator',
            'password' => Hash::make('Password123!'),
            'role' => 'coordinator',
            'phone' => '+27 11 234 5679',
            'assigned_province' => 'Gauteng',
            'is_active' => true,
        ]
    );

    AdminUser::firstOrCreate(
        ['email' => 'leader@angolancommunity.org'],
        [
            'name' => 'Community Leader',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'phone' => '+27 11 234 5680',
            'is_active' => true,
        ]
    );
}

    
    private function createSampleMembers()
    {
        // This is for testing - you can remove or modify
        \App\Models\Member::factory(50)->create();
    }
}