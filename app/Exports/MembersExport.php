<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Member::with(['skills', 'supportAreas'])->get();
    }

    public function headings(): array
    {
        return [
            'Registration Number',
            'First Name',
            'Last Name',
            'Gender',
            'Date of Birth',
            'Nationality',
            'Citizenship Status',
            'Province',
            'City',
            'Area',
            'Mobile Number',
            'Email',
            'Preferred Contact Method',
            'WhatsApp Number',
            'Employment Status',
            'Profession',
            'Field of Study',
            'Willing to Help',
            'Registered At',
            'Skills',
            'Support Areas',
        ];
    }

    public function map($member): array
    {
        return [
            $member->registration_number,
            $member->first_name,
            $member->last_name,
            ucfirst($member->gender),
            $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
            $member->nationality,
            ucfirst(str_replace('_', ' ', $member->citizenship_status)),
            $member->province,
            $member->city,
            $member->area,
            $member->mobile_number,
            $member->email,
            ucfirst($member->preferred_contact_method),
            $member->whatsapp_number,
            ucfirst(str_replace('_', ' ', $member->employment_status)),
            $member->profession,
            $member->field_of_study,
            $member->willing_to_help ? 'Yes' : 'No',
            $member->registered_at->format('Y-m-d H:i'),
            $member->skills->pluck('name_en')->implode(', '),
            $member->supportAreas->pluck('name_en')->implode(', '),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
            
            // Make email column italic
            'L' => ['font' => ['italic' => true]],
            
            // Center align registration number
            'A' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}