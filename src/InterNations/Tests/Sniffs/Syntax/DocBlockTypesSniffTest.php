<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class DocBlockTypesSniffTest extends AbstractTestCase
{
    public function testInvalidDocBlocks(): void
    {
        $file = __DIR__ . '/Fixtures/DocBlockTypes/InvalidDocBlocks.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/DocBlockTypesSniff'], [$file]);

        $this->assertReportCount(27, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var void", expected "@var null"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var binary", expected "@var string"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var boolean", expected "@var bool"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var integer", expected "@var int"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var this", expected "@var self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var $this", expected "@var self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var real", expected "@var float"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param void", expected "@param null"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return void", expected "@return null"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param binary", expected "@param string"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return binary", expected "@return string"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param double", expected "@param float"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return double", expected "@return float"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param real", expected "@param float"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return real", expected "@return float"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param boolean", expected "@param bool"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return boolean", expected "@return bool"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param integer", expected "@param int"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return integer", expected "@return int"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param $this", expected "@param self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return $this", expected "@return self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param this", expected "@param self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return this", expected "@return self"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param integer|null", expected "@param int|null"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return void|integer", expected "@return null|integer"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
    }

    public function testLegalDocBlocks(): void
    {
        $file = __DIR__ . '/Fixtures/DocBlockTypes/LegalDocBlocks.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/DocBlockTypesSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
