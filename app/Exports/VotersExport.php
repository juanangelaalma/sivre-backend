<?php

namespace App\Exports;

use App\Models\Voter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class VotersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $voters = Voter::get(['username', 'password']);
        $newVoters = new Collection([]);
        $voters->map(function ($voter) use ($newVoters) {
            $newVoters->push([
                'colom_name_1' => 'username',
                'username' => $voter->username,
                'colom_name_2' => 'password',
                'password' => $voter->password,
            ]);
        });
        return $newVoters;
    }
}
