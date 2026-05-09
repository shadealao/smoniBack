<?php

namespace App\Exports;

use App\Models\Withdraw;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class WithdrawsExport implements FromView
{
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Withdraw::select('invoice_code','created_at','ammount', 'payed')->where('monitor_id',$this->id)->orderBy('created_at','desc') ->get();
    // }

    // public function headings(): array
    // {
    //     // Les en-têtes de vos colonnes dans le fichier Excel
    //     return [
    //         'Transaction ID',
    //         'Créé le',
    //         'Montant',
    //         'Statut',
    //     ];
    // }

     public function view(): View
    {
        $withdraws = Withdraw::select('invoice_code','created_at','ammount', 'payed')->where('monitor_id',$this->id)->orderBy('created_at','desc') ->get();

        return view('export.transaction',compact('withdraws'));
    }

    
     /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
}