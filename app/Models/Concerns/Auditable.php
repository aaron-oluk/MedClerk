<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn (Model $model) => $model->writeAuditLog('created'));
        static::updated(fn (Model $model) => $model->writeAuditLog('updated'));
        static::deleted(fn (Model $model) => $model->writeAuditLog('deleted'));
    }

    protected function writeAuditLog(string $action): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'institution_id' => $this->auditInstitutionId(),
            'auditable_type' => static::class,
            'auditable_id' => $this->getKey(),
            'action' => $action,
            'changes' => $action === 'deleted' ? null : $this->getChanges(),
            'ip_address' => request()?->ip(),
        ]);
    }

    protected function auditInstitutionId(): ?int
    {
        return null;
    }
}
