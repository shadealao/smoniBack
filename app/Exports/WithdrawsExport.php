<?php

namespace App\Exports;

use App\Models\Withdraw;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithdrawsExport implements FromCollection, WithHeadings
{
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Withdraw::select('invoice_code','created_at','ammount', 'payed')->where('monitor_id',$this->id)->orderBy('created_at','desc') ->get();
    }

    public function headings(): array
    {
        // Les en-têtes de vos colonnes dans le fichier Excel
        return [
            'Transaction ID',
            'Créé le',
            'Montant',
            'Statut',
        ];
    }
}