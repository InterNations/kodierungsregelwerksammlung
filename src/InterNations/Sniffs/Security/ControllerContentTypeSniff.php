<?php
namespace InterNations\Sniffs\Security;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Exception;
use InterNations\Sniffs\Util;
use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;

class ControllerContentTypeSniff implements CodeSnifferSniff
{
    private $docParser;

    public function __construct()
    {
        AnnotationRegistry::registerLoader('class_exists');
        $this->docParser = new DocParser();
        $this->docParser->addNamespace(Configuration::class);
        $this->docParser->setIgnoredAnnotationNames(
            (static function () {
                return self::$globalIgnoredNames;
            })->bindTo(null, AnnotationReader::class)()
        );
    }

    public function register(): array
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr): void
    {
        if (!Util::isController($file)) {
            return;
        }

        $endPtr = $file->findPrevious(
            [T_WHITESPACE, T_PUBLIC, T_STATIC, T_PRIVATE, T_ABSTRACT, T_FINAL, T_PROTECTED],
            $stackPtr - 1,
            null,
            true
        );

        if (!$endPtr || $file->getTokens()[$endPtr]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            //printf("No doc block found for %s\n",$file->getDeclarationName($stackPtr));
            return;
        }

        $docPtr = $file->findPrevious(T_DOC_COMMENT_OPEN_TAG, $endPtr - 1);

        $template = null;
        $route = null;

        foreach ($this->parseAnnotation($file->getTokensAsString($docPtr, $endPtr - $docPtr)) as $annotation) {
            if ($annotation instanceof Configuration\Template) {
                $template = $annotation;
            } elseif ($annotation instanceof Configuration\Route) {
                $route = $annotation;
            }
        }

        $templateFormat = 'html';

        if ($template) {
            $templateFormat = pathinfo(substr($template->getTemplate(), 0, -5), PATHINFO_EXTENSION) ?: $templateFormat;
        }


        $defaultFormat = $route ? $route->getDefaults()['_format'] ?? null : null;
        $formatRequirement = $route ? $route->getRequirements()['_format'] ?? null : null;

        if ($defaultFormat === null && $formatRequirement === null) {
            $defaultFormat = $formatRequirement = 'html';
        }


        if ($templateFormat !== $defaultFormat) {
            $file->addError(
                'Extension ".%s.twig" in @Template annotation does not match @Route default format "%s" in %s()',
                $stackPtr,
                'TemplateDefaultFormat',
                [$templateFormat, $defaultFormat, $file->getDeclarationName($stackPtr)]
            );
        }

        if ($defaultFormat !== $formatRequirement) {
            $file->addError(
                '@Route default format "%s" does not match @Route format requirement "%s" in %s()',
                $stackPtr,
                'TemplateFormatRequirement',
                [$defaultFormat, $formatRequirement, $file->getDeclarationName($stackPtr)]
            );
        }
    }

    private function parseAnnotation(string $docComment)
    {
        try {
            return $this->docParser->parse($docComment);
        } catch (Exception $e) {
            return [];
        }
    }
}
