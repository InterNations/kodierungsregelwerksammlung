<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_DocBlockTypesSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testInvalidDocBlocks()
    {
        $file = __DIR__ . '/Fixtures/DocBlockTypes/InvalidDocBlocks.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/DocBlockTypesSniff'], [$file]);

        $this->assertReportCount(26, 0, $errors, $file);
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
            'Found "@var bool", expected "@var boolean"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@var int", expected "@var integer"',
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
            'Found "@param bool", expected "@param boolean"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return bool", expected "@return boolean"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@param int", expected "@param integer"',
            'InterNations.Syntax.DocBlockTypes.ShortDocCommentTypes',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found "@return int", expected "@return integer"',
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
            'Found "@param int|null", expected "@param integer|null"',
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

    public function testLegalDocBlocks()
    {
        $file = __DIR__ . '/Fixtures/DocBlockTypes/LegalDocBlocks.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/DocBlockTypesSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
