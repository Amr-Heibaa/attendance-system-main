<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public function log(
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $targetName = null,
        ?string $description = null,
        ?array $meta = null
    ): void {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'target_name' => $targetName,
            'description' => $description,
            'meta' => $meta,
        ]);
    }
}