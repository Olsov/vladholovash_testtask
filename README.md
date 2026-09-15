# Document Validation

A simple test task for validating documents based on their fields with tenant-specific rules.
PHPUnit is used for testing.

## Requirements

* PHP 8.1+
* Composer
* PHPUnit 10

## Installation

Clone the repository and then run the following commands:

```bash
composer install
```

```bash
composer dump-autoload
```

```bash
php index.php
```

## Running Tests

```bash
./vendor/bin/phpunit tests
```

The tests cover both successful and unsuccessful document validation scenarios.

### Document

`Document` is implemented as a separate model containing:

* ID
* Tenant ID
* Content
* Metadata

### ValidationRule

`ValidationRule` is an interface that defines the `validate` method and/or other methods required for  validation rule.

```php
interface ValidationRule
{
    /**
     * @return string[]
     */
    public function validate(Document $document): array;
}
```

The `validate` method returns string with error messages containing information about what went wrong for each rule, or null when the document passes the rule.

### BaseValidationRule

This is an abstract base class for validation rules. Since the rules have a similar structure, we can create a common class that can be extended by individual rules with the functionality they need.

I also added the interface to the abstract class so that everything related to a validation rule is defined in one place and can then be inherited by the concrete rules.

### Rules

* `MaxDocumentSizeRule` — Maximum document size
* `RequiredMetadataRule` — Required metadata fields
* `ProhibitedWordsRule` — Prohibited words

### TenantRuleProvider

`TenantRuleProvider` is used to allow each tenant to have its own validation rules, or no rules at all.

### DocumentValidator

`DocumentValidator` is used to validate a document without being directly tied to a specific tenant.

### ValidationResult

`ValidationResult` provides the validation result — whether the document is valid and any validation errors.

```php
$result->isValid();
$result->getErrors();
```
