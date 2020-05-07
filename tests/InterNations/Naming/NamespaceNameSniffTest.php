<?php
namespace InterNations\Sniffs\Tests\Naming;

use InterNations\Sniffs\Tests\AbstractTestCase;

class NamespaceNameSniffTest extends AbstractTestCase
{
    public function testWrongNamespaceName(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/WrongNamespaceName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Namespace of class "Fixtures\\WrongNamespaceName\\WrongNamespaceName" does not match file "Fixtures/NamespaceName/WrongNamespaceName.php"',
            'InterNations.Naming.NamespaceName.InvalidNamespaceName',
            5
        );
    }

    public function testWrongClassName(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/WrongClassName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Namespace of class "Fixtures\\NamespaceName\\ClassName" does not match file "Fixtures/NamespaceName/WrongClassName.php"',
            'InterNations.Naming.NamespaceName.InvalidNamespaceName',
            5
        );
    }

    public function testWrongInterfaceName(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/WrongInterfaceName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Namespace of interface "Fixtures\\NamespaceName\\InterfaceName" does not match file "Fixtures/NamespaceName/WrongInterfaceName.php"',
            'InterNations.Naming.NamespaceName.InvalidNamespaceName',
            5
        );
    }

    public function testWrongTraitName(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/WrongTraitName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Namespace of trait "Fixtures\\NamespaceName\\TraitName" does not match file "Fixtures/NamespaceName/WrongTraitName.php"',
            'InterNations.Naming.NamespaceName.InvalidNamespaceName',
            5
        );
    }

    public function testAllGood(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/CorrectClassName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testPsr4Good(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/src/Foo/CorrectClassName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testPsr4Bad(): void
    {
        $file = __DIR__ . '/Fixtures/NamespaceName/src/Foo/IncorrectClassName.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/NamespaceNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
