<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'service_id', 'start_date', 'end_date',
        'status', 'mode','amount','transaction_id','type_service','hour','gearbox'
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'contrat_id');
    }


    /**
     * Désactive la souscription
     */
    public function deactivate()
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }

    /**
     * Vérifie si la souscription est active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Vérifie si la souscription est inactive/annulée
     */
    public function isInactive()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Détermine si c'est un pack accéléré
     */
    public function isAcceleratedPack()
    {
        // Vérifier si le service contient "accéléré" ou "accéléré" dans le titre
        return $this->service && (
            stripos($this->service->title, 'accéléré') !== false ||
            stripos($this->service->title, 'Accéléré') !== false
        );
    }

    /**
     * Détermine si c'est un pack normal
     */
    public function isNormalPack()
    {
        return !$this->isAcceleratedPack();
    }
}
