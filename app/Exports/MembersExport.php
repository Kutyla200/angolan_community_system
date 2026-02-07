<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class MembersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $query;
    
    public function __construct($query)
    {
        $this->query = $query;
    }
    
    /**
     * @return Builder
     */
    public function query()
    {
        return $this->query->with(['skills', 'supportAreas']);
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Registration Number',
            'First Name',
            'Last Name',
            'Gender',
            'Date of Birth',
            'Age',
            'Nationality',
            'Citizenship Status',
            'Province',
            'City',
            'Area',
            'Mobile Number',
            'Email',
            'WhatsApp Number',
            'Preferred Contact',
            'Employment Status',
            'Profession',
            'Field of Study',
            'Willing to Help',
            'Skills',
            'Support Areas',
            'Registered At',
            'IP Address',
        ];
    }
    
    /**
     * @param Member $member
     * @return array
     */
    public function map($member): array
    {
        return [
            $member->registration_number,
            $member->first_name,
            $member->last_name,
            ucfirst($member->gender),
            $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
            $member->age ?? '',
            $member->nationality,
            ucfirst(str_replace('_', ' ', $member->citizenship_status)),
            $member->province,
            $member->city,
            $member->area ?? '',
            $member->mobile_number,
            $member->email ?? '',
            $member->whatsapp_number ?? '',
            ucfirst(str_replace('_', ' ', $member->preferred_contact_method)),
            ucfirst(str_replace('_', ' ', $member->employment_status)),
            $member->profession ?? '',
            $member->field_of_study ?? '',
            $member->willing_to_help ? 'Yes' : 'No',
            $member->skills->pluck('name_en')->implode(', '),
            $member->supportAreas->pluck('name_en')->implode(', '),
            $member->created_at->format('Y-m-d H:i:s'),
            $member->registration_ip ?? '',
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}