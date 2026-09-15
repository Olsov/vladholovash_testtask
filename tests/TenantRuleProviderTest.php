<?php
namespace Tests;

use App\Providers\Validator\TenantRuleProvider;
use App\Validator\Rules\MaxDocumentSizeRule;
use App\Validator\Rules\RequiredMetadataRule;
use PHPUnit\Framework\TestCase;

class TenantRuleProviderTest extends TestCase
{
    public function testReturnsRulesConfiguredForTenant(): void
    {
        $provider = new TenantRuleProvider();

        $rules = [
            new MaxDocumentSizeRule(100),
            new RequiredMetadataRule(['author']),
        ];

        $provider->setRulesForTenant(123, $rules);

        self::assertSame(
            $rules,
            $provider->getRulesForTenant(123)
        );
    }


    public function testReturnsEmptyRulesForUnknownTenant(): void
    {
        $provider = new TenantRuleProvider();

        self::assertSame(
            [],
            $provider->getRulesForTenant(999)
        );
    }


}