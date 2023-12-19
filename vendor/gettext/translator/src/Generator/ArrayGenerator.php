<?php
declare(strict_types = 1);

namespace Gettext\Generator;

use Gettext\Headers;
use Gettext\Translation;
use Gettext\Translations;

final class ArrayGenerator extends Generator
{
    /**
     * @private
     */
    const PRETTY_INDENT = '    ';

    /**
     * @var bool
     */
    private $includeEmpty;

    /**
     * @var bool
     */
    private $strictTypes;

    /**
     * @var bool
     */
    private $pretty;

    /**
     * Constructs a new ArrayGenerator
     * @param array|null $options
     *
     * bool includeEmpty Controls whether empty translations should be included (default: false)
     */
    public function __construct(?array $options = null)
    {
        $this->includeEmpty = (bool) ($options['includeEmpty'] ?? false);
        $this->strictTypes = (bool) ($options['strictTypes'] ?? false);
        $this->pretty = (bool) ($options['pretty'] ?? false);
    }

    public function generateString(Translations $translations): string
    {
        $array = $this->generateArray($translations);
        $result = '<?php';
        if ($this->pretty) {
            $result .= $this->strictTypes ? "\n\ndeclare(strict_types=1);\n\n" : "\n\n";
        } else {
            $result .= $this->strictTypes ? ' declare(strict_types=1); ' : ' ';
        }

        return $result . 'return ' . ($this->pretty ? self::prettyExport($array) : (var_export($array, true) . ';'));
    }

    public function generateArray(Translations $translations): array
    {
        $pluralForm = $translations->getHeaders()->getPluralForm();
        $pluralSize = is_array($pluralForm) ? ($pluralForm[0] - 1) : null;
        $messages = [];

        foreach ($translations as $translation) {
            if ((!$this->includeEmpty && !$translation->getTranslation()) || $translation->isDisabled()) {
                continue;
            }

            $context = $translation->getContext() ?: '';
            $original = $translation->getOriginal();

            if (!isset($messages[$context])) {
                $messages[$context] = [];
            }

            if (self::hasPluralTranslations($translation)) {
                $messages[$context][$original] = $translation->getPluralTranslations($pluralSize);
                array_unshift($messages[$context][$original], $translation->getTranslation());
            } else {
                $messages[$context][$original] = $translation->getTranslation();
            }
        }

        return [
            'domain' => $translations->getDomain(),
            'plural-forms' => $translations->getHeaders()->get(Headers::HEADER_PLURAL),
            'messages' => $messages,
        ];
    }

    private static function hasPluralTranslations(Translation $translation): bool
    {
        return implode('', $translation->getPluralTranslations()) !== '';
    }

    private static function prettyExport(array &$array): string
    {
        return self::prettyExportArray($array, 0) . ";\n";
    }

    private static function prettyExportArray(array &$array, int $depth): string
    {
        if ($array === []) {
            return '[]';
        }
        $result = '[';
        $isList = self::isList($array);
        foreach ($array as $key => $value) {
            $result .= "\n" . str_repeat(self::PRETTY_INDENT, $depth + 1);
            if (!$isList) {
                $result .= var_export($key, true) . ' => ';
            }
            if (is_array($value)) {
                $result .= self::prettyExportArray($value, $depth + 1);
            } else {
                $result .= self::prettyExportScalar($value);
            }
            $result .= ',';
        }
        return $result . "\n" . str_repeat(self::PRETTY_INDENT, $depth) . ']';
    }

    private static function prettyExportScalar($value): string
    {
        return $value === null ? 'null' : var_export($value, true);
    }

    private static function isList(array &$value): bool
    {
        if ($value === []) {
            return true;
        }
        if (function_exists('array_is_list')) {
            return \array_is_list($value);
        }

        return array_keys($value) === range(0, count($value) - 1);
    }
}
