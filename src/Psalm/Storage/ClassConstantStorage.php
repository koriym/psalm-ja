<?php

declare(strict_types=1);

namespace Psalm\Storage;

use Psalm\CodeLocation;
use Psalm\Internal\Analyzer\ClassLikeAnalyzer;
use Psalm\Internal\Scanner\UnresolvedConstantComponent;
use Psalm\Type\Union;

use function array_values;
use function property_exists;

/**
 * @psalm-suppress PossiblyUnusedProperty
 * @psalm-immutable
 */
final class ClassConstantStorage
{
    /** @psalm-suppress MutableDependency Mutable by design */
    use CustomMetadataTrait;
    use ImmutableNonCloneableTrait;

    /**
     * @param ClassLikeAnalyzer::VISIBILITY_* $visibility
     * @param list<AttributeStorage> $attributes
     * @param array<int, string> $suppressed_issues
     */
    public function __construct(
        /**
         * The type from an annotation, or the inferred type if no annotation exists.
         */
        public ?Union $type,
        /**
         * The type inferred from the value.
         */
        public ?Union $inferred_type,
        public int $visibility,
        public ?CodeLocation $location,
        public ?CodeLocation $type_location = null,
        public ?CodeLocation $stmt_location = null,
        public bool $deprecated = false,
        public bool $final = false,
        public ?UnresolvedConstantComponent $unresolved_node = null,
        public array $attributes = [],
        public array $suppressed_issues = [],
        public ?string $description = null,
    ) {
    }

    /**
     * Used in the Language Server
     */
    public function getHoverMarkdown(string $const): string
    {
        $visibility_text = match ($this->visibility) {
            ClassLikeAnalyzer::VISIBILITY_PRIVATE => 'private',
            ClassLikeAnalyzer::VISIBILITY_PROTECTED => 'protected',
            default => 'public',
        };

        $value = '';
        if ($this->type) {
            $types = $this->type->getAtomicTypes();
            $type = array_values($types)[0];
            if (property_exists($type, 'value')) {
                /** @psalm-suppress UndefinedPropertyFetch */
                $value = " = {$type->value};";
            }
        }


        return "$visibility_text const $const$value";
    }
}
