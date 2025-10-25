<?php

namespace App\Http\Validators;

use App\Models\Appointment;
use App\Models\Subscription;
use Carbon\Carbon;

class AppointmentTimeValidator
{
    /**
     * Valide les contraintes horaires pour un rendez-vous
     */
    public static function validateAppointmentTime($userId, $appointmentDate, $appointmentDuration = 1)
    {
        $subscriptions = Subscription::where('learner_id', $userId)
            ->where('status', 'active')
            ->where('type_service', 'Conduite')
            ->with('service')
            ->get();

        if ($subscriptions->isEmpty()) {
            return [
                'valid' => false,
                'message' => 'Aucun abonnement de conduite actif trouvé.'
            ];
        }

        // Vérifier les contraintes pour chaque abonnement
        foreach ($subscriptions as $subscription) {
            $validation = self::validateSubscriptionConstraints($subscription, $appointmentDate, $appointmentDuration);
            if ($validation['valid']) {
                return $validation; // Au moins un abonnement est valide
            }
        }

        return [
            'valid' => false,
            'message' => 'Aucun de vos abonnements ne respecte les contraintes horaires pour ce rendez-vous. Taux horaire journalier ou hebdomadaire dépassé.'
        ];
    }

    /**
     * Valide les contraintes pour un abonnement spécifique
     */
    private static function validateSubscriptionConstraints($subscription, $appointmentDate, $appointmentDuration)
    {
        $date = Carbon::parse($appointmentDate);
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // Déterminer les limites selon le type de pack
        $dailyLimit = $subscription->isAcceleratedPack() ? 4 : 2;
        $weeklyLimit = $subscription->isAcceleratedPack() ? 8 : 4;

        // Vérifier les heures du jour
        $dailyHours = self::getAppointmentHours($subscription->learner_id, $startOfDay, $endOfDay);
        if ($dailyHours + $appointmentDuration > $dailyLimit) {
            return [
                'valid' => false,
            ];
        }

        // Vérifier les heures de la semaine
        $weeklyHours = self::getAppointmentHours($subscription->learner_id, $startOfWeek, $endOfWeek);
        if ($weeklyHours + $appointmentDuration > $weeklyLimit) {
            return [
                'valid' => false,
                'message' => sprintf(
                    'Limite hebdomadaire dépassée. Vous avez déjà %dh cette semaine, limite: %dh pour un pack %s.',
                    $weeklyHours,
                    $weeklyLimit,
                    $subscription->isAcceleratedPack() ? 'accéléré' : 'normal'
                )
            ];
        }

        return [
            'valid' => true,
            'message' => 'Contraintes horaires respectées.',
            'subscription' => $subscription
        ];
    }

    /**
     * Calcule le nombre d'heures de rendez-vous dans une période donnée
     */
    private static function getAppointmentHours($userId, $startDate, $endDate)
    {
        $appointments = Appointment::where('learner_id', $userId)
            ->whereHas('availability', function($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->where('status', '!=', 'cancelled')
            ->get();

        return $appointments->sum(function($appointment) {
            // Supposons que chaque rendez-vous dure 1h par défaut
            // Vous pouvez ajuster selon votre logique métier
            return 1;
        });
    }
}
