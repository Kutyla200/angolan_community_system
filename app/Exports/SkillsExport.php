<?php

namespace App\Exports;

use App\Models\Skill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SkillsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Skill::withCount('members')->get();
    }

    public function headings(): array
    {
        return [
            'Skill Name (EN)',
            'Skill Name (PT)',
            'Category',
            'Total Members',
            'Active',
        ];
    }

    public function map($skill): array
    {
        return [
            $skill->name_en,
            $skill->name_pt,
            $skill->category,
            $skill->members_count,
            $skill->is_active ? 'Yes' : 'No',
        ];
    }
}