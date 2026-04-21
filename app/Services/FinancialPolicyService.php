<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\FinancialPolicy;

class FinancialPolicyService
{
    public function getPenaltyForAttendance(AttendanceRecord $record): ?array
    {
        // غياب
        if ($record->status === 'absent') {
            $policy = FinancialPolicy::getApplicablePolicy('absence');

            if ($policy) {
                return $this->formatPenalty($policy);
            }
        }

        // تأخير
        if (($record->late_minutes ?? 0) > 0) {
            $policy = FinancialPolicy::getApplicablePolicy('late', $record->late_minutes);

            if ($policy) {
                return $this->formatPenalty($policy);
            }
        }

        // خروج مبكر
        if (($record->early_leave_minutes ?? 0) > 0) {
            $policy = FinancialPolicy::getApplicablePolicy('early_leave', $record->early_leave_minutes);

            if ($policy) {
                return $this->formatPenalty($policy);
            }
        }

        return null;
    }

    private function formatPenalty(FinancialPolicy $policy): array
    {
        return [
            'policy_id' => $policy->id,
            'name' => $policy->name,
            'type' => $policy->type,
            'penalty_type' => $policy->penalty_type,
            'penalty_value' => $policy->penalty_value,
            'label' => $this->makeLabel($policy),
        ];
    }

    private function makeLabel(FinancialPolicy $policy): string
    {
        if ($policy->penalty_type === 'warning') {
            return 'إنذار';
        }

        if ($policy->penalty_type === 'fixed') {
            return 'خصم ثابت: ' . $policy->penalty_value;
        }

        if ($policy->penalty_type === 'percent') {
            return 'خصم بنسبة: ' . $policy->penalty_value . '%';
        }

        return $policy->name;
    }
}