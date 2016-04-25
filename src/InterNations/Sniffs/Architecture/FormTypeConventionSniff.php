<?php
namespace InterNations\Sniffs\Architecture;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class FormTypeConventionSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_CLASS];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        if (!preg_match('@/[^/]+Bundle/Form/@', $file->getFilename())) {
            return;
        }

        if ($file->findExtendedClassName($stackPtr) !== 'AbstractType') {
            return;
        }

        $className = $file->getDeclarationName($stackPtr);

        if (!preg_match('/.*FormType$/D', $className)) {
            $file->addError(
                sprintf('Form types are expected to be named "<Something>FormType", "%s" found', $className),
                $stackPtr,
                'Architecture.FormTypeConvention'
            );
        }
    }
}
