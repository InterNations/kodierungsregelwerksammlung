<?php
class FunctionNames
{
    public function join()
    {
        $newString = join(',', ['foo', 'bar']);

        $pn = new PaamayimNekudotayim();
        $newString = $pn->join('a', 'b');
        $newString = $pn
            ->join('a', 'b');

        $newString = $pn->
            join('a', 'b'); join(',', ['foo', 'bar']);join(',', ['foo', 'bar']);

        sha1('foo');
    }

    public function sizeof()
    {
        $count = sizeof(['foo', 'bar']);
    }

    private function fputs()
    {
        $fp = tmpfile();
        fputs($fp, '');
        fclose($fp);
    }

    public function chop()
    {
        chop('string');
    }

    public function is_real()
    {
        $float = 123;
        if (is_real($float)) {
            echo "Is float";
        } else {
            echo "Not float, it is";
        }
    }

    protected function strchr()
    {
        strchr('This is a string', 'is');
    }

    private function doubleval()
    {
        echo doubleval(123);
    }

    public function key_exists()
    {
        if (key_exists('key', ['foo', 'bar'])) {
            echo "key exists";
        }
    }

    protected function is_double()
    {
        $double = 123.45;
        if (is_double($double)) {
            echo "Is double";
        } else {
            echo "Not double, it is";
        }
    }

    public function ini_alter()
    {
        ini_alter('display_errors', 1);
    }

    public function is_long()
    {
        is_long(123);
    }

    public function is_integer()
    {
        is_integer(123);
    }

    public function is_real()
    {
        is_real(123);
    }

    public function pos()
    {
        pos(123);
    }

    protected function tPaamayimNekudotayim()
    {
        PaamayimNekudotayim::sizeof([1, 2, 3]);
    }
}

class PaamayimNekudotayim
{
    public static function sizeof($var)
    {
        return count($var);
    }

    public function join($a, $b)
    {
        return $a . $b;
    }
}
