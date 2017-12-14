<?php
namespace InterNations\Sniffs\Architecture;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class FormTypeConventionSniff implements Sniff
{
    public function register()
    {
        return [T_CLASS];
    }

    public function process(File $file, $stackPtr)
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
                'FormTypeConvention'
            );
        }
    }
}
