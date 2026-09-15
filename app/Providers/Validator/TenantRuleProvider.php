<?php
namespace App\Providers\Validator;

use App\Interfaces\Validator\ValidationRule;

class TenantRuleProvider
{

    private array $rulesByTenant = [];

    /**
     * @param int $tenantId
     * @param ValidationRule[] $rules
     */
    public function setRulesForTenant(int $tenantId, array $rules): void
    {
        $this->rulesByTenant[$tenantId] = $rules;
    }

    /**
     * @param int $tenantId
     * @return ValidationRule[]
     */
    public function getRulesForTenant(int $tenantId): array
    {
        return $this->rulesByTenant[$tenantId] ?? [];
    }
}